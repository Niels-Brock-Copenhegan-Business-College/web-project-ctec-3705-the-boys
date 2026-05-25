<?php
namespace App\Controllers;

use App\Models\StaffModel;
use App\Models\ModuleModel;
use App\Models\ProgrammeModel;
use App\Models\InterestModel;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class StaffController
{
    public function __construct(
        private StaffModel $staffModel,
        private ModuleModel $moduleModel,
        private ProgrammeModel $programmeModel,
        private PhpRenderer $renderer,
        private InterestModel $interestModel
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
        $staffId = (int) $args['id'];
        $this->staffModel->delete($staffId);
        $this->flash('success', 'Staff member deleted.');
        return $res->withHeader('Location', base_url('/admin/staff'))->withStatus(302);
    }

    /**
     * Verify admin secret code for destructive action
     */
    public function verifySecretCode(Request $req, Response $res): Response
    {
        $adminId = (int)($_SESSION['admin_id'] ?? 0);
        if (!$adminId) {
            $res->getBody()->write(json_encode(['success' => false, 'message' => 'Unauthorized']));
            return $res->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $d = $req->getParsedBody();
        $secretCode = trim((string)($d['secret_code'] ?? ''));

        if (empty($secretCode)) {
            $res->getBody()->write(json_encode(['success' => false, 'message' => 'Secret code required']));
            return $res->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $adminModel = new \App\Models\AdminModel($this->pdo);
        if ($adminModel->verifySecretCode($adminId, $secretCode)) {
            $res->getBody()->write(json_encode(['success' => true]));
            return $res->withHeader('Content-Type', 'application/json');
        }

        \app_log('warning', 'Wrong admin secret code for staff delete or assignment', [
            'admin_id' => $adminId,
            'staff_id' => (int) ($args['id'] ?? 0),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);

        $res->getBody()->write(json_encode(['success' => false, 'message' => 'Invalid secret code']));
        return $res->withStatus(403)->withHeader('Content-Type', 'application/json');
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

        // ── Total interest count across all assigned programmes ──
        $totalInterest = array_sum(array_column($programmes, 'interest_count'));

        // ── Recent interests (last 7 days) per programme ─────────
        $recentInterests = [];
        foreach ($programmes as $p) {
            $stmt = $this->staffModel->getPdo()->prepare(
                'SELECT ir.first_name, ir.last_name, ir.registered_at, p.title AS programme_title
                 FROM interest_registrations ir
                 JOIN programmes p ON p.id = ir.programme_id
                 WHERE ir.programme_id = ?
                 AND ir.registered_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                 ORDER BY ir.registered_at DESC LIMIT 5'
            );
            $stmt->execute([(int)$p['id']]);
            foreach ($stmt->fetchAll() as $row) {
                $recentInterests[] = $row;
            }
        }
        usort($recentInterests, fn($a, $b) => strtotime($b['registered_at']) - strtotime($a['registered_at']));
        $recentInterests = array_slice($recentInterests, 0, 5);

        // ── Draft programmes warning ──────────────────────────────
        $draftProgrammes = array_filter($programmes, fn($p) => !(int)$p['is_published']);

        // ── Module coverage per programme ─────────────────────────
        // my_module_count already in getAssignedProgrammes query

        return $this->renderer->render($res, 'staff/dashboard.php', [
            'staff'           => $this->staffModel->findById($staffId),
            'modules'         => $modules,
            'programmes'      => $programmes,
            'totalInterest'   => $totalInterest,
            'recentInterests' => $recentInterests,
            'draftProgrammes' => array_values($draftProgrammes),
            'flash'           => $this->getFlash(),
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
     * Interest registrations for a programme — read-only view for assigned staff.
     */
    public function programmeInterests(Request $req, Response $res, array $args): Response
    {
        $staffId     = (int) $_SESSION['staff_id'];
        $programmeId = (int) $args['id'];

        // Access control — must be assigned to this programme
        $assigned   = $this->staffModel->getAssignedProgrammes($staffId);
        $isAssigned = !empty(array_filter($assigned, fn($p) => (int)$p['id'] === $programmeId));

        if (!$isAssigned) {
            $this->flash('error', 'You are not linked to that programme.');
            return $res->withHeader('Location', base_url('/staff'))->withStatus(302);
        }

        $programme = $this->programmeModel->findById($programmeId);
        if (!$programme) return $res->withStatus(404);

        $interests = $this->interestModel->findByProgramme($programmeId);

        return $this->renderer->render($res, 'staff/programme-interests.php', [
            'staff'      => $this->staffModel->findById($staffId),
            'programme'  => $programme,
            'interests'  => $interests,
            'flash'      => $this->getFlash(),
        ]);
    }

    /**
     * Edit own profile — full_name and photo only.
     * Username and email are read-only (admin-managed).
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
        $staff    = $this->staffModel->findById($staffId);
        $d        = $req->getParsedBody();
        $fullName = $this->clean($d['full_name'] ?? '');
        $bio      = trim($d['bio'] ?? '');
        $errors   = [];

        if (!$fullName) $errors['full_name'] = 'Full name is required.';

        // Handle photo upload
        $uploadedFiles = $req->getUploadedFiles();
        $photoFile     = $uploadedFiles['photo'] ?? null;
        $newPhoto      = $staff['photo'] ?? null;

        if ($photoFile && $photoFile->getError() === UPLOAD_ERR_OK) {
            $allowed   = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $mediaType = $photoFile->getClientMediaType();
            if (!in_array($mediaType, $allowed, true)) {
                $errors['photo'] = 'Only JPG, PNG, WEBP or GIF images are allowed.';
            } elseif ($photoFile->getSize() > 3 * 1024 * 1024) {
                $errors['photo'] = 'Photo must be under 3 MB.';
            } else {
                $ext      = pathinfo($photoFile->getClientFilename(), PATHINFO_EXTENSION);
                $filename = 'staff_' . $staffId . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
                $uploadDir = __DIR__ . '/../../public/uploads/staff/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $photoFile->moveTo($uploadDir . $filename);
                // Delete old photo if exists
                if (!empty($staff['photo'])) {
                    $old = $uploadDir . $staff['photo'];
                    if (file_exists($old)) @unlink($old);
                }
                $newPhoto = $filename;
            }
        }

        if (!empty($errors)) {
            return $this->renderer->render($res, 'staff/edit-profile.php', [
                'staff'  => array_merge($staff, $d),
                'errors' => $errors,
                'flash'  => [],
            ]);
        }

        $this->staffModel->update($staffId, ['full_name' => $fullName, 'photo' => $newPhoto, 'bio' => $bio]);
        $_SESSION['staff_name'] = $fullName;

        $this->flash('success', 'Profile updated successfully.');
        return $res->withHeader('Location', base_url('/staff/profile/edit'))->withStatus(302);
    }

    /**
     * All interests — shows every registration across all programmes
     * assigned to the logged-in staff member.
     * Security: JOIN on staff_programmes means staff can never see
     * registrations outside their own assigned programmes.
     */
    public function interests(Request $req, Response $res): Response
    {
        $staffId = (int) $_SESSION['staff_id'];

        // Fetch all registrations filtered to this staff's programmes only.
        // The JOIN on staff_programmes is the security boundary.
        $stmt = $this->staffModel->getPdo()->prepare("
            SELECT
                ir.id,
                ir.first_name,
                ir.last_name,
                ir.email,
                ir.registered_at,
                p.id    AS programme_id,
                p.title AS programme_title,
                p.level AS programme_level
            FROM interest_registrations ir
            JOIN programmes p
                ON ir.programme_id = p.id
            JOIN staff_programmes sp
                ON sp.programme_id = ir.programme_id
               AND sp.staff_id = ?
            ORDER BY p.title ASC, ir.registered_at DESC
        ");
        $stmt->execute([$staffId]);
        $registrations = $stmt->fetchAll();

        // Group by programme name for the view
        $grouped = [];
        foreach ($registrations as $r) {
            $grouped[$r['programme_title']][] = $r;
        }

        return $this->renderer->render($res, 'staff/interests.php', [
            'staff'         => $this->staffModel->findById($staffId),
            'registrations' => $registrations,
            'grouped'       => $grouped,
            'flash'         => $this->getFlash(),
        ]);
    }

}