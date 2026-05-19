<?php
namespace App\Controllers;

use App\Models\StaffModel;
use App\Models\ModuleModel;
use App\Models\ProgrammeModel;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class StaffController
{
    public function __construct(
        private StaffModel $staffModel,
        private ModuleModel $moduleModel,
        private ProgrammeModel $programmeModel,
        private PhpRenderer $renderer
    ) {}

    private function flash(string $key, string $msg): void { $_SESSION['flash'][$key] = $msg; }
    private function getFlash(): array { $f = $_SESSION['flash'] ?? []; unset($_SESSION['flash']); return $f; }
    private function clean(mixed $v): string { return htmlspecialchars(trim((string)$v), ENT_QUOTES, 'UTF-8'); }

    // ── Admin: staff management ───────────────────────────────────

    public function index(Request $req, Response $res): Response
    {
        return $this->renderer->render($res, 'admin/staff/list.php', [
            'staff' => $this->staffModel->getAll(),
            'flash' => $this->getFlash(),
        ]);
    }

    public function create(Request $req, Response $res): Response
    {
        return $this->renderer->render($res, 'admin/staff/form.php', ['staff' => null]);
    }

    public function store(Request $req, Response $res): Response
    {
        $d = $req->getParsedBody();
        $errors = [];
        $username        = $this->clean($d['username'] ?? '');
        $email           = $this->clean($d['email'] ?? '');
        $fullName        = $this->clean($d['full_name'] ?? '');
        $password        = $d['password'] ?? '';
        $confirmPassword = $d['confirm_password'] ?? '';

        if (!$username || strlen($username) < 3)           $errors['username'] = 'Username must be at least 3 characters.';
        if ($this->staffModel->findByUsername($username))  $errors['username'] = 'Username already exists.';
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Valid email is required.';
        if (!$fullName)                                    $errors['full_name'] = 'Full name is required.';
        if (!$password || strlen($password) < 6)           $errors['password'] = 'Password must be at least 6 characters.';
        if ($password !== $confirmPassword)                $errors['confirm_password'] = 'Passwords do not match.';

        if (!empty($errors)) {
            return $this->renderer->render($res, 'admin/staff/form.php', ['staff' => $d, 'errors' => $errors]);
        }

        $this->staffModel->create([
            'username'  => $username,
            'email'     => $email,
            'full_name' => $fullName,
            'password'  => $password,
            'is_active' => 1,
        ], $_SESSION['admin_id']);

        $this->flash('success', 'Staff member created successfully.');
        return $res->withHeader('Location', base_url('/admin/staff'))->withStatus(302);
    }

    public function show(Request $req, Response $res, array $args): Response
    {
        $staffId = (int) $args['id'];
        $staff   = $this->staffModel->findById($staffId);
        if (!$staff) return $res->withStatus(404);

        return $this->renderer->render($res, 'admin/staff/detail.php', [
            'staff'                => $staff,
            'assignedModules'      => $this->staffModel->getAssignedModules($staffId),
            'assignedProgrammes'   => $this->staffModel->getAssignedProgrammes($staffId),
            'unassignedModules'    => $this->moduleModel->getUnassignedForStaff($staffId),
            'unassignedProgrammes' => $this->staffModel->getUnassignedProgrammes($staffId),
            'programmesList'       => $this->programmeModel->getAll(),
            'flash'                => $this->getFlash(),
        ]);
    }

    public function assignModule(Request $req, Response $res, array $args): Response
    {
        $staffId = (int) $args['id'];
        if (!$this->staffModel->findById($staffId)) return $res->withStatus(404);
        $d = $req->getParsedBody();
        if (!empty($d['module_id'])) $this->staffModel->assignModule($staffId, (int) $d['module_id']);
        $this->flash('success', 'Module assigned successfully.');
        return $res->withHeader('Location', base_url('/admin/staff/' . $staffId))->withStatus(302);
    }

    public function assignProgramme(Request $req, Response $res, array $args): Response
    {
        $staffId = (int) $args['id'];
        if (!$this->staffModel->findById($staffId)) return $res->withStatus(404);
        $d = $req->getParsedBody();
        if (!empty($d['programme_id'])) $this->staffModel->assignProgramme($staffId, (int) $d['programme_id']);
        $this->flash('success', 'Programme assigned successfully.');
        return $res->withHeader('Location', base_url('/admin/staff/' . $staffId))->withStatus(302);
    }

    public function unassignModule(Request $req, Response $res, array $args): Response
    {
        $staffId = (int) $args['id'];
        if (!$this->staffModel->findById($staffId)) return $res->withStatus(404);
        $d = $req->getParsedBody();
        if (!empty($d['module_id'])) {
            $this->staffModel->unassignModule($staffId, (int)$d['module_id']);
            $this->flash('success', 'Module unassigned successfully.');
        }
        return $res->withHeader('Location', base_url('/admin/staff/' . $staffId))->withStatus(302);
    }

    public function unassignProgramme(Request $req, Response $res, array $args): Response
    {
        $staffId = (int) $args['id'];
        if (!$this->staffModel->findById($staffId)) return $res->withStatus(404);
        $d = $req->getParsedBody();
        if (!empty($d['programme_id'])) {
            $this->staffModel->unassignProgramme($staffId, (int)$d['programme_id']);
            $this->flash('success', 'Programme unassigned successfully.');
        }
        return $res->withHeader('Location', base_url('/admin/staff/' . $staffId))->withStatus(302);
    }

    public function edit(Request $req, Response $res, array $args): Response
    {
        $staff = $this->staffModel->findById((int)$args['id']);
        if (!$staff) return $res->withStatus(404);
        return $this->renderer->render($res, 'admin/staff/form.php', ['staff' => $staff]);
    }

    public function update(Request $req, Response $res, array $args): Response
    {
        $staffId = (int)$args['id'];
        $staff   = $this->staffModel->findById($staffId);
        if (!$staff) return $res->withStatus(404);

        $d               = $req->getParsedBody();
        $errors          = [];
        $email           = $this->clean($d['email'] ?? '');
        $fullName        = $this->clean($d['full_name'] ?? '');
        $password        = $d['password'] ?? '';
        $confirmPassword = $d['confirm_password'] ?? '';

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email']    = 'Valid email is required.';
        if (!$fullName)                                             $errors['full_name'] = 'Full name is required.';
        if ($password && strlen($password) < 6)                    $errors['password']  = 'Password must be at least 6 characters.';
        if ($password && $password !== $confirmPassword)            $errors['confirm_password'] = 'Passwords do not match.';

        if (!empty($errors)) {
            return $this->renderer->render($res, 'admin/staff/form.php', [
                'staff'  => array_merge($staff, $d),
                'errors' => $errors,
            ]);
        }

        $updateData = [
            'email'     => $email,
            'full_name' => $fullName,
            'is_active' => isset($d['is_active']) ? 1 : 0,
        ];
        if ($password) $updateData['password'] = $password;

        $this->staffModel->update($staffId, $updateData);
        $this->flash('success', 'Staff member updated successfully.');
        return $res->withHeader('Location', base_url('/admin/staff'))->withStatus(302);
    }

    public function delete(Request $req, Response $res, array $args): Response
    {
        $this->staffModel->delete((int)$args['id']);
        $this->flash('success', 'Staff member deleted.');
        return $res->withHeader('Location', base_url('/admin/staff'))->withStatus(302);
    }

    // ── Staff portal ──────────────────────────────────────────────

    /**
     * Staff dashboard — modules by year, profile card, programmes summary
     */
    public function dashboard(Request $req, Response $res): Response
    {
        $staffId    = (int) $_SESSION['staff_id'];
        $modules    = $this->staffModel->getAssignedModules($staffId);
        $programmes = $this->staffModel->getAssignedProgrammes($staffId);

        return $this->renderer->render($res, 'staff/dashboard.php', [
            'staff'      => $this->staffModel->findById($staffId),
            'modules'    => $modules,
            'programmes' => $programmes,
        ]);
    }

    /**
     * My Modules list page — all modules assigned to the logged-in staff member
     */
    public function modules(Request $req, Response $res): Response
    {
        $staffId = (int) $_SESSION['staff_id'];
        $modules = $this->staffModel->getAssignedModules($staffId);

        return $this->renderer->render($res, 'staff/modules.php', [
            'staff'   => $this->staffModel->findById($staffId),
            'modules' => $modules,
        ]);
    }

    /**
     * Module detail — only accessible to assigned staff.
     * Redirects to dashboard with flash if not assigned.
     * Supports ?from=dashboard to set the back button destination.
     */
    public function moduleDetail(Request $req, Response $res, array $args): Response
    {
        $staffId  = (int) $_SESSION['staff_id'];
        $moduleId = (int) $args['id'];

        // Access control — redirect instead of raw 403
        $assigned   = $this->staffModel->getAssignedModules($staffId);
        $isAssigned = !empty(array_filter($assigned, fn($m) => (int)$m['id'] === $moduleId));

        if (!$isAssigned) {
            $this->flash('error', 'You are not assigned to that module.');
            return $res->withHeader('Location', base_url('/staff'))->withStatus(302);
        }

        $module = $this->staffModel->getModuleDetail($moduleId);
        if (!$module) return $res->withStatus(404);

        // Smart back button — dashboard cards pass ?from=dashboard
        $from      = $req->getQueryParams()['from'] ?? 'modules';
        $backUrl   = $from === 'dashboard' ? base_url('/staff') : base_url('/staff/modules');
        $backLabel = $from === 'dashboard' ? '← Back to dashboard' : '← Back to my modules';

        return $this->renderer->render($res, 'staff/module-detail.php', [
            'staff'     => $this->staffModel->findById($staffId),
            'module'    => $module,
            'backUrl'   => $backUrl,
            'backLabel' => $backLabel,
        ]);
    }

    /**
     * My Programmes list page
     */
    public function programmes(Request $req, Response $res): Response
    {
        $staffId    = (int) $_SESSION['staff_id'];
        $programmes = $this->staffModel->getAssignedProgrammes($staffId);

        return $this->renderer->render($res, 'staff/programmes.php', [
            'staff'      => $this->staffModel->findById($staffId),
            'programmes' => $programmes,
        ]);
    }

    /**
     * Programme detail — only accessible to assigned staff.
     * Redirects to dashboard with flash if not assigned.
     * Supports ?from=dashboard to set the back button destination.
     */
    public function programmeDetail(Request $req, Response $res, array $args): Response
    {
        $staffId     = (int) $_SESSION['staff_id'];
        $programmeId = (int) $args['id'];

        // Access control — redirect instead of raw 403
        $assigned   = $this->staffModel->getAssignedProgrammes($staffId);
        $isAssigned = !empty(array_filter($assigned, fn($p) => (int)$p['id'] === $programmeId));

        if (!$isAssigned) {
            $this->flash('error', 'You are not linked to that programme.');
            return $res->withHeader('Location', base_url('/staff'))->withStatus(302);
        }

        $programme = $this->staffModel->getProgrammeDetail($programmeId);
        if (!$programme) return $res->withStatus(404);

        // Smart back button — dashboard cards pass ?from=dashboard
        $from      = $req->getQueryParams()['from'] ?? 'programmes';
        $backUrl   = $from === 'dashboard' ? base_url('/staff') : base_url('/staff/programmes');
        $backLabel = $from === 'dashboard' ? '← Back to dashboard' : '← Back to my programmes';

        return $this->renderer->render($res, 'staff/programme-detail.php', [
            'staff'     => $this->staffModel->findById($staffId),
            'programme' => $programme,
            'backUrl'   => $backUrl,
            'backLabel' => $backLabel,
        ]);
    }

    /**
     * Edit own profile — full_name and email only.
     * Updates $_SESSION['staff_name'] immediately so navbar reflects the change.
     */
    public function editProfile(Request $req, Response $res): Response
    {
        $staffId = (int) $_SESSION['staff_id'];
        return $this->renderer->render($res, 'staff/edit-profile.php', [
            'staff' => $this->staffModel->findById($staffId),
            'flash' => $this->getFlash(),
        ]);
    }

    public function updateProfile(Request $req, Response $res): Response
    {
        $staffId  = (int) $_SESSION['staff_id'];
        $d        = $req->getParsedBody();
        $fullName = $this->clean($d['full_name'] ?? '');
        $email    = $this->clean($d['email'] ?? '');
        $errors   = [];

        if (!$fullName)                                            $errors['full_name'] = 'Full name is required.';
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email']     = 'A valid email address is required.';

        if (!empty($errors)) {
            return $this->renderer->render($res, 'staff/edit-profile.php', [
                'staff'  => array_merge($this->staffModel->findById($staffId), $d),
                'errors' => $errors,
            ]);
        }

        $this->staffModel->update($staffId, ['full_name' => $fullName, 'email' => $email]);

        // Update session so navbar shows the new name immediately without re-login
        $_SESSION['staff_name'] = $fullName;

        $this->flash('success', 'Profile updated successfully.');
        return $res->withHeader('Location', base_url('/staff/profile/edit'))->withStatus(302);
    }

    /**
     * Change own password — verifies current password before saving new one
     */
    public function changePasswordForm(Request $req, Response $res): Response
    {
        return $this->renderer->render($res, 'staff/change-password.php', [
            'staff' => $this->staffModel->findById((int) $_SESSION['staff_id']),
            'flash' => $this->getFlash(),
        ]);
    }

    public function changePassword(Request $req, Response $res): Response
    {
        $staffId = (int) $_SESSION['staff_id'];
        $staff   = $this->staffModel->findById($staffId);
        $d       = $req->getParsedBody();
        $current = $d['current_password']  ?? '';
        $new     = $d['new_password']      ?? '';
        $confirm = $d['confirm_password']  ?? '';
        $errors  = [];

        if (!password_verify($current, $staff['password_hash'])) $errors['current_password'] = 'Current password is incorrect.';
        if (strlen($new) < 6)                                    $errors['new_password']      = 'New password must be at least 6 characters.';
        if ($new !== $confirm)                                   $errors['confirm_password']  = 'Passwords do not match.';

        if (!empty($errors)) {
            return $this->renderer->render($res, 'staff/change-password.php', [
                'staff'  => $staff,
                'errors' => $errors,
            ]);
        }

        $this->staffModel->update($staffId, ['password' => $new]);
        $this->flash('success', 'Password changed successfully.');
        return $res->withHeader('Location', base_url('/staff/change-password'))->withStatus(302);
    }
}