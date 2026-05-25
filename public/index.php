<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use App\Controllers\ProgrammeController;
use App\Controllers\InterestController;
use App\Controllers\AuthController;
use App\Controllers\SuperAdminController;
use App\Controllers\ModuleController;
use App\Controllers\StaffController;
use App\Models\ProgrammeModel;
use App\Models\SuperAdminModel;
use App\Models\ModuleModel;
use App\Models\InterestModel;
use App\Models\StaffModel;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Helpers/logging.php';
require __DIR__ . '/../app/Helpers/csrf.php';


if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        if ($basePath === '/' || $basePath === '.' || $basePath === '\\') {
            $basePath = '';
        }
        if ($path === '' || $path === '/') {
            return $basePath !== '' ? $basePath : '/';
        }
        return rtrim($basePath, '/') . '/' . ltrim($path, '/');
    }
}

// Session
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Strict');
session_start();

// Database
$dbConfig   = require __DIR__ . '/../config/database.php';
$mailConfig = require __DIR__ . '/../config/mail.php';
$dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset=utf8mb4";
$pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass'], [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

// make PDO available to helper logger
$GLOBALS['app_pdo'] = $pdo;

// Renderer
$renderer = new PhpRenderer(__DIR__ . '/../app/Views');

// Models
$progModel     = new ProgrammeModel($pdo);
$moduleModel   = new ModuleModel($pdo);
$interestModel = new InterestModel($pdo);
$staffModel    = new StaffModel($pdo);
$superAdminModel = new SuperAdminModel($pdo);

// Controllers
$progCtrl     = new ProgrammeController($pdo, $progModel, $renderer, $staffModel, $moduleModel, $interestModel);
$interestCtrl = new InterestController($interestModel, $progModel, $renderer, $mailConfig);
$authCtrl     = new AuthController($pdo, $renderer, $mailConfig);
$superAdminCtrl = new SuperAdminController($pdo, $renderer, $mailConfig);
$moduleCtrl   = new ModuleController($pdo, $moduleModel, $progModel, $renderer);
$staffCtrl = new StaffController($staffModel, $moduleModel, $progModel, $renderer, $interestModel);

$app = AppFactory::create();
$app->add(new App\Middleware\CsrfMiddleware());
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
if ($scriptName !== '/' && $scriptName !== '.') {
    $app->setBasePath($scriptName);
}
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);

// Admin auth middleware
$adminAuth = function ($request, $handler) {
    if (empty($_SESSION['admin_id'])) {
        return (new \Slim\Psr7\Response())
            ->withHeader('Location', base_url('/admin/login'))
            ->withStatus(302);
    }
    return $handler->handle($request);
};

// Superadmin auth middleware
$superAdminAuth = function ($request, $handler) {
    if (empty($_SESSION['superadmin_id'])) {
        return (new \Slim\Psr7\Response())
            ->withHeader('Location', base_url('/superadmin/login'))
            ->withStatus(302);
    }
    return $handler->handle($request);
};

// Staff auth middleware
$staffAuth = function ($request, $handler) {
    if (empty($_SESSION['staff_id'])) {
        return (new \Slim\Psr7\Response())
            ->withHeader('Location', base_url('/staff/login'))
            ->withStatus(302);
    }
    return $handler->handle($request);
};

// ── Public routes ───────────────────────────────────────────────
$app->get('/', [$progCtrl, 'home']);
$app->get('/programmes/{id:[0-9]+}', [$progCtrl, 'detail']);
$app->get('/interest/register/{id:[0-9]+}', [$interestCtrl, 'showForm']);
$app->post('/interest', [$interestCtrl, 'register']);
$app->get('/interest/withdraw/{token}', [$interestCtrl, 'withdraw']);
$app->get('/my-interests',            [$interestCtrl, 'myInterestsForm']);
$app->post('/my-interests',           [$interestCtrl, 'myInterestsLookup']);
$app->post('/my-interests/withdraw',  [$interestCtrl, 'myInterestsWithdraw']);

// ── Static info pages ───────────────────────────────────────
$app->get('/how-to-apply',     function ($req, $res) use ($renderer) {
    return $renderer->render($res, 'student/how-to-apply.php', []);
});
$app->get('/fees-and-funding', function ($req, $res) use ($renderer) {
    return $renderer->render($res, 'student/fees-and-funding.php', []);
});
$app->get('/scholarships',     function ($req, $res) use ($renderer) {
    return $renderer->render($res, 'student/scholarships.php', []);
});
$app->get('/campus-life',      function ($req, $res) use ($renderer) {
    return $renderer->render($res, 'student/campus-life.php', []);
});
$app->get('/privacy-policy',   function ($req, $res) use ($renderer) {
    return $renderer->render($res, 'student/privacy-policy.php', []);
});
$app->get('/cookie-policy',    function ($req, $res) use ($renderer) {
    return $renderer->render($res, 'student/cookie-policy.php', []);
});
$app->get('/accessibility',    function ($req, $res) use ($renderer) {
    return $renderer->render($res, 'student/accessibility.php', []);
});
$app->get('/terms-of-use',     function ($req, $res) use ($renderer) {
    return $renderer->render($res, 'student/terms-of-use.php', []);
});

// ── Auth routes ─────────────────────────────────────────────────
$app->get('/login',          [$authCtrl, 'unifiedLoginForm']);
$app->post('/login',         [$authCtrl, 'unifiedLogin']);
$app->get('/admin/login',    [$authCtrl, 'loginForm']);
$app->post('/admin/login',   [$authCtrl, 'login']);
$app->get('/admin/logout', [$authCtrl, 'logout']);
$app->get('/admin/profile', [$authCtrl, 'adminProfileForm']);
$app->post('/admin/profile', [$authCtrl, 'adminProfileUpdate']);
$app->get('/staff/login',  [$authCtrl, 'staffLoginForm']);
$app->post('/staff/login', [$authCtrl, 'staffLogin']);
$app->get('/staff/logout', [$authCtrl, 'staffLogout']);
$app->get('/staff/reset-password/{token:[a-f0-9]{64}}', [$authCtrl, 'staffResetForm']);
$app->post('/staff/reset-password/{token:[a-f0-9]{64}}', [$authCtrl, 'staffResetSubmit']);

// Admin invite / set password routes
$app->get('/admin/set-password/{token:[a-f0-9]{64}}', [$authCtrl, 'adminSetPasswordForm']);
$app->post('/admin/set-password/{token:[a-f0-9]{64}}', [$authCtrl, 'adminSetPasswordSubmit']);

// Superadmin auth
$app->get('/superadmin/login',  [$superAdminCtrl, 'loginForm']);
$app->post('/superadmin/login', [$superAdminCtrl, 'login']);
$app->get('/superadmin/logout', [$superAdminCtrl, 'logout']);

// ── Admin routes (protected) ─────────────────────────────────────
$app->group('/admin', function ($group) use ($progCtrl, $moduleCtrl, $interestCtrl, $staffCtrl, $authCtrl) {
    $group->get('',                                          [$progCtrl,     'adminDashboard']);
    // Programmes
    $group->get('/programmes',                               [$progCtrl,     'adminIndex']);
    $group->get('/programmes/create',                        [$progCtrl,     'create']);
    $group->post('/programmes',                              [$progCtrl,     'store']);
    $group->get('/programmes/{id:[0-9]+}',                   [$progCtrl,     'adminShow']);
    $group->get('/programmes/{id:[0-9]+}/edit',              [$progCtrl,     'edit']);
    $group->post('/programmes/{id:[0-9]+}',                  [$progCtrl,     'update']);
    $group->post('/programmes/{id:[0-9]+}/delete',           [$progCtrl,     'destroy']);
    $group->post('/programmes/{id:[0-9]+}/publish',          [$progCtrl,     'togglePublish']);
    $group->post('/programmes/{id:[0-9]+}/assign-module',    [$progCtrl,     'assignModule']);
    $group->post('/programmes/{id:[0-9]+}/unassign-module',  [$progCtrl,     'unassignModule']);
    // Modules
    $group->get('/modules',                                  [$moduleCtrl,   'adminIndex']);
    $group->get('/modules/create',                           [$moduleCtrl,   'create']);
    $group->post('/modules',                                 [$moduleCtrl,   'store']);
    $group->get('/modules/{id:[0-9]+}',                      [$moduleCtrl,   'adminShow']);
    $group->get('/modules/{id:[0-9]+}/edit',                 [$moduleCtrl,   'edit']);
    $group->post('/modules/{id:[0-9]+}',                     [$moduleCtrl,   'update']);
    $group->post('/modules/{id:[0-9]+}/delete',              [$moduleCtrl,   'destroy']);
    // Interests
    $group->get('/interests',                                [$interestCtrl, 'adminAll']);
    $group->get('/interests/{pid:[0-9]+}',                   [$interestCtrl, 'adminList']);
    $group->get('/interests/{pid:[0-9]+}/export',            [$interestCtrl, 'exportCsv']);
    $group->post('/interests/send-programme',                [$interestCtrl, 'sendProgrammeMail']);
    $group->post('/interests/{id:[0-9]+}/send-mail',         [$interestCtrl, 'sendSingleMail']);
    $group->post('/interests/{id:[0-9]+}/delete',            [$interestCtrl, 'adminDelete']);
    // Staff management
    $group->get('/staff',                                    [$staffCtrl,    'index']);
    $group->get('/staff/create',                             [$staffCtrl,    'create']);
    $group->post('/staff',                                   [$staffCtrl,    'store']);
    $group->get('/staff/{id:[0-9]+}',                        [$staffCtrl,    'show']);
    $group->post('/staff/{id:[0-9]+}/assign-module',         [$staffCtrl,    'assignModule']);
    $group->post('/staff/{id:[0-9]+}/assign-programme',      [$staffCtrl,    'assignProgramme']);
    $group->post('/staff/{id:[0-9]+}/unassign-module',       [$staffCtrl,    'unassignModule']);
    $group->post('/staff/{id:[0-9]+}/unassign-programme',    [$staffCtrl,    'unassignProgramme']);
    $group->post('/staff/{id:[0-9]+}/send-password-reset',   [$authCtrl,     'adminSendStaffResetLink']);
    $group->get('/staff/{id:[0-9]+}/edit',                   [$staffCtrl,    'edit']);
    $group->post('/staff/{id:[0-9]+}',                       [$staffCtrl,    'update']);
    $group->post('/staff/{id:[0-9]+}/delete',                [$staffCtrl,    'delete']);
    // Authorization verification
    $group->post('/verify-secret-code',                      [$moduleCtrl,   'verifySecretCode']);
})->add($adminAuth);

// Superadmin routes (protected)
$app->group('/superadmin', function ($group) use ($superAdminCtrl) {
    $group->get('', [$superAdminCtrl, 'dashboard']);
    $group->get('/admins/create', [$superAdminCtrl, 'showCreateAdminForm']);
    $group->post('/admins', [$superAdminCtrl, 'createAdminSubmit']);
    $group->get('/logs', [$superAdminCtrl, 'logs']);
    $group->post('/logs/delete', [$superAdminCtrl, 'deleteLog']);
})->add($superAdminAuth);

// ── Staff routes (protected) ────────────────────────────────────
$app->group('/staff', function ($group) use ($staffCtrl) {
    $group->get('',                        [$staffCtrl, 'dashboard']);
    $group->get('/modules',                [$staffCtrl, 'modules']);
    $group->get('/modules/{id:[0-9]+}',    [$staffCtrl, 'moduleDetail']);
    $group->get('/programmes',             [$staffCtrl, 'programmes']);
    $group->get('/programmes/{id:[0-9]+}', [$staffCtrl, 'programmeDetail']);
    $group->get('/programmes/{id:[0-9]+}/interests', [$staffCtrl, 'programmeInterests']);
    $group->get('/interests',                        [$staffCtrl, 'interests']); // all registrations across assigned programmes
    $group->get('/profile/edit',           [$staffCtrl, 'editProfile']);
    $group->post('/profile/edit',          [$staffCtrl, 'updateProfile']);
})->add($staffAuth);

$app->run();