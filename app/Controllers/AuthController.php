<?php

namespace App\Controllers;

use App\Models\StaffModel;
use App\Models\InterestModel;
use App\Models\AdminModel;
use PHPMailer\PHPMailer\PHPMailer;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function base_url;
use function app_log;

class AuthController
{
    public function __construct(
        private \PDO $pdo,
        private PhpRenderer $renderer,
        private array $mailConfig = []
    ) {}

    private function flash(string $key, string $msg): void { $_SESSION['login_flash'][$key] = $msg; }

    private function tableHasColumns(string $table, array $columns): bool
    {
        static $cache = [];
        $database = (string) ($this->pdo->query('SELECT DATABASE()')->fetchColumn() ?: '');
        if ($database === '') {
            return false;
        }

        $cacheKey = $database . '|' . $table . '|' . implode(',', $columns);
        if (array_key_exists($cacheKey, $cache)) {
            return $cache[$cacheKey];
        }

        $placeholders = implode(',', array_fill(0, count($columns), '?'));
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(DISTINCT column_name) FROM information_schema.columns WHERE table_schema = ? AND table_name = ? AND column_name IN ($placeholders)"
        );
        $stmt->execute(array_merge([$database, $table], $columns));

        return $cache[$cacheKey] = ((int) $stmt->fetchColumn()) === count($columns);
    }
 
    // ─── UNIFIED LOGIN FORM ──────────────────────────────────────
 
    public function unifiedLoginForm(Request $req, Response $res): Response
    {
        if (!empty($_SESSION['admin_id'])) {
            return $res->withHeader('Location', base_url('/admin'))->withStatus(302);
        }
        if (!empty($_SESSION['staff_id'])) {
            return $res->withHeader('Location', base_url('/staff'))->withStatus(302);
        }
 
        $flash = $_SESSION['login_flash'] ?? [];
        unset($_SESSION['login_flash']);
 
        return $this->renderer->render($res, 'login.php', [
            'error'   => null,
            'flash'   => $flash,
            'oldUser' => '',
        ]);
    }
 
    // ─── UNIFIED LOGIN POST ──────────────────────────────────────
 
    public function unifiedLogin(Request $req, Response $res): Response
    {
        $d    = $req->getParsedBody();
        $user = trim($d['username'] ?? '');
        $pass = $d['password']     ?? '';
        $path = (string) $req->getUri()->getPath();
        $adminActiveEnabled = $this->tableHasColumns('admins', ['is_active']);

        $stmt = $this->pdo->prepare('SELECT * FROM admins WHERE username = ?');
        $stmt->execute([$user]);
        $admin = $stmt->fetch();

        if ($admin) {
            if ($adminActiveEnabled && isset($admin['is_active']) && (int) $admin['is_active'] === 0) {
                return $this->renderer->render($res, 'login.php', [
                    'error'   => 'This admin account is blocked. Please contact the super admin.',
                    'flash'   => [],
                    'oldUser' => htmlspecialchars($user, ENT_QUOTES),
                ]);
            }

            if (!empty($admin['locked_until'])) {
                $lockedUntil = new \DateTime($admin['locked_until']);
                $now = new \DateTime();
                if ($now < $lockedUntil) {
                    $diff = $now->diff($lockedUntil);
                    $remaining = ($diff->h * 60) + $diff->i + ($diff->s > 0 ? 1 : 0);
                    return $this->renderer->render($res, 'login.php', [
                        'error'   => "Too many failed attempts. Please try again in {$remaining} minute(s).",
                        'flash'   => [],
                        'oldUser' => htmlspecialchars($user, ENT_QUOTES),
                    ]);
                }

                $this->pdo->prepare('UPDATE admins SET login_attempts = 0, locked_until = NULL WHERE id = ?')
                          ->execute([$admin['id']]);
                $admin['login_attempts'] = 0;
                $admin['locked_until'] = null;
            }

            if (password_verify($pass, $admin['password_hash'])) {
                $this->pdo->prepare('UPDATE admins SET login_attempts = 0, locked_until = NULL WHERE id = ?')
                          ->execute([$admin['id']]);
                session_regenerate_id(true);
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'] ?? $user;
                $_SESSION['admin_avatar'] = $admin['avatar'] ?? null;
                return $res->withHeader('Location', base_url('/admin'))->withStatus(302);
            }

            $attempts = (int) ($admin['login_attempts'] ?? 0) + 1;
            if ($attempts >= 5) {
                $this->pdo->prepare(
                    'UPDATE admins SET login_attempts = login_attempts + 1, locked_until = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE id = ?'
                )->execute([$admin['id']]);

                \app_log('warning', 'Failed login attempt', [
                    'area' => str_replace('/login', '', $path) ?: 'unified',
                    'username' => $user,
                    'admin_id' => (int) $admin['id'],
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
                    'method' => $req->getMethod(),
                    'path' => $path,
                ]);

                return $this->renderer->render($res, 'login.php', [
                    'error'   => 'Too many failed attempts. Your account has been locked for 15 minutes.',
                    'flash'   => [],
                    'oldUser' => htmlspecialchars($user, ENT_QUOTES),
                ]);
            }

            $this->pdo->prepare('UPDATE admins SET login_attempts = login_attempts + 1 WHERE id = ?')
                      ->execute([$admin['id']]);

            \app_log('warning', 'Failed login attempt', [
                'area' => str_replace('/login', '', $path) ?: 'unified',
                'username' => $user,
                'admin_id' => (int) $admin['id'],
                'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
                'method' => $req->getMethod(),
                'path' => $path,
            ]);

            $left = 5 - $attempts;
            return $this->renderer->render($res, 'login.php', [
                'error'   => "Incorrect username or password. {$left} attempt(s) remaining before lockout.",
                'flash'   => [],
                'oldUser' => htmlspecialchars($user, ENT_QUOTES),
            ]);
        }

        $staffModel = new StaffModel($this->pdo);
        $staffRow = $staffModel->findByUsername($user);

        if ($staffRow) {
            if (!empty($staffRow['locked_until'])) {
                $lockedUntil = new \DateTime($staffRow['locked_until']);
                $now = new \DateTime();
                if ($now < $lockedUntil) {
                    \app_log('warning', 'Failed login attempt', [
                        'area' => str_replace('/login', '', $path) ?: 'unified',
                        'username' => $user,
                        'staff_id' => (int) $staffRow['id'],
                        'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
                        'method' => $req->getMethod(),
                        'path' => $path,
                    ]);

                    $diff = $now->diff($lockedUntil);
                    $remaining = ($diff->h * 60) + $diff->i + ($diff->s > 0 ? 1 : 0);
                    return $this->renderer->render($res, 'login.php', [
                        'error'   => "Too many failed attempts. Please try again in {$remaining} minute(s).",
                        'flash'   => [],
                        'oldUser' => htmlspecialchars($user, ENT_QUOTES),
                    ]);
                }

                $staffModel->resetLoginAttempts((int) $staffRow['id']);
                $staffRow['login_attempts'] = 0;
                $staffRow['locked_until'] = null;
            }

            if (password_verify($pass, $staffRow['password_hash'])) {
                $staffModel->resetLoginAttempts((int) $staffRow['id']);
                session_regenerate_id(true);
                $_SESSION['staff_id'] = $staffRow['id'];
                $_SESSION['staff_name'] = $staffRow['full_name'];
                $_SESSION['staff_role'] = $staffRow['role'];
                return $res->withHeader('Location', base_url('/staff'))->withStatus(302);
            }

            $attempts = (int) ($staffRow['login_attempts'] ?? 0) + 1;
            \app_log('warning', 'Failed login attempt', [
                'area' => str_replace('/login', '', $path) ?: 'unified',
                'username' => $user,
                'staff_id' => (int) $staffRow['id'],
                'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
                'method' => $req->getMethod(),
                'path' => $path,
            ]);

            if ($attempts >= 5) {
                $staffModel->lockAccount((int) $staffRow['id'], 15);
                return $this->renderer->render($res, 'login.php', [
                    'error'   => 'Too many failed attempts. Your account has been locked for 15 minutes.',
                    'flash'   => [],
                    'oldUser' => htmlspecialchars($user, ENT_QUOTES),
                ]);
            }

            $staffModel->incrementLoginAttempts((int) $staffRow['id']);
            $left = 5 - $attempts;
            return $this->renderer->render($res, 'login.php', [
                'error'   => "Incorrect username or password. {$left} attempt(s) remaining before lockout.",
                'flash'   => [],
                'oldUser' => htmlspecialchars($user, ENT_QUOTES),
            ]);
        }

        \app_log('warning', 'Failed login attempt', [
            'area' => str_replace('/login', '', $path) ?: 'unified',
            'username' => $user,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            'method' => $req->getMethod(),
            'path' => $path,
        ]);

        return $this->renderer->render($res, 'login.php', [
            'error'   => 'Incorrect username or password.',
            'flash'   => [],
            'oldUser' => htmlspecialchars($user, ENT_QUOTES),
        ]);
    }
    // ─── FORGOT PASSWORD (student email lookup) ──────────────────

    public function forgotForm(Request $req, Response $res): Response
    {
        $flash = $_SESSION['login_flash'] ?? [];
        unset($_SESSION['login_flash']);
        return $this->renderer->render($res, 'forgot.php', [
            'flash'  => $flash,
            'error'  => null,
            'sent'   => false,
        ]);
    }

    public function forgotSubmit(Request $req, Response $res): Response
    {
        $d     = $req->getParsedBody();
        $email = strtolower(trim($d['email'] ?? ''));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->renderer->render($res, 'forgot.php', [
                'flash' => [],
                'error' => 'Please enter a valid email address.',
                'sent'  => false,
            ]);
        }

        // Look up all interest registrations for this email
        $interestModel = new InterestModel($this->pdo);
        $registrations = $interestModel->findByEmail($email);

        // Always show success — don't reveal whether email exists
        // But if registrations exist, send a summary email
        if (!empty($registrations)) {
            $this->sendInterestSummary($email, $registrations);
        }

        return $this->renderer->render($res, 'forgot.php', [
            'flash' => [],
            'error' => null,
            'sent'  => true,
        ]);
    }

    private function sendInterestSummary(string $email, array $registrations): void
    {
        try {
            $cfg    = $this->mailConfig;
            $mailer = new PHPMailer(true);
            $mailer->isSMTP();
            $mailer->Host       = (string)($cfg['host'] ?? '');
            $mailer->Port       = (int)($cfg['port'] ?? 587);
            $mailer->SMTPAuth   = true;
            $mailer->Username   = (string)($cfg['username'] ?? '');
            $mailer->Password   = (string)($cfg['password'] ?? '');
            $enc = strtolower((string)($cfg['encryption'] ?? 'tls'));
            $mailer->SMTPSecure = $enc === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mailer->setFrom((string)($cfg['from_email'] ?? $cfg['username'] ?? ''), (string)($cfg['from_name'] ?? 'UniHub'));
            $mailer->addAddress($email);
            $mailer->isHTML(false);
            $mailer->Subject = 'Your UniHub interest registrations';

            $lines = ["Hi,\n\nHere's a summary of your registered interests on UniHub:\n"];
            foreach ($registrations as $r) {
                $lines[] = '• ' . ($r['programme_title'] ?? 'Programme') . ' — registered ' . date('j F Y', strtotime($r['registered_at']));
                if (!empty($r['withdraw_token'])) {
                    $lines[] = '  Withdraw: ' . base_url('/interest/withdraw/' . $r['withdraw_token']);
                }
            }
            $lines[] = "\nTo explore more programmes, visit: " . base_url('/');
            $lines[] = "\nRegards,\nThe UniHub Team";

            $mailer->Body = implode("\n", $lines);
            $mailer->send();
        } catch (\Throwable) {
            // Silently swallow — don't reveal mail errors to the user
        }
    }

    // ─── LEGACY REDIRECTS ────────────────────────────────────────

    public function loginForm(Request $req, Response $res): Response
    {
        if (!empty($_SESSION['admin_id'])) {
            return $res->withHeader('Location', base_url('/admin'))->withStatus(302);
        }
        return $res->withHeader('Location', base_url('/login'))->withStatus(302);
    }

    public function login(Request $req, Response $res): Response
    {
        return $this->unifiedLogin($req, $res);
    }

    public function staffLoginForm(Request $req, Response $res): Response
    {
        if (!empty($_SESSION['staff_id'])) {
            return $res->withHeader('Location', base_url('/staff'))->withStatus(302);
        }
        return $res->withHeader('Location', base_url('/login'))->withStatus(302);
    }

    public function staffLogin(Request $req, Response $res): Response
    {
        return $this->unifiedLogin($req, $res);
    }

    // ─── LOGOUT ──────────────────────────────────────────────────

    public function logout(Request $req, Response $res): Response
    {
        session_destroy();
        return $res->withHeader('Location', base_url('/login'))->withStatus(302);
    }

    public function staffLogout(Request $req, Response $res): Response
    {
        session_destroy();
        return $res->withHeader('Location', base_url('/login'))->withStatus(302);
    }

    public function adminProfileForm(Request $req, Response $res): Response
    {
        $adminId = (int) ($_SESSION['admin_id'] ?? 0);
        if (!$adminId) {
            return $res->withHeader('Location', base_url('/admin/login'))->withStatus(302);
        }

        $admin = (new AdminModel($this->pdo))->findById($adminId);
        if (!$admin) {
            return $res->withHeader('Location', base_url('/admin/login'))->withStatus(302);
        }

        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);

        return $this->renderer->render($res, 'admin/profile.php', [
            'admin' => $admin,
            'flash' => $flash,
        ]);
    }

    public function adminProfileUpdate(Request $req, Response $res): Response
    {
        $adminId = (int) ($_SESSION['admin_id'] ?? 0);
        if (!$adminId) {
            return $res->withHeader('Location', base_url('/admin/login'))->withStatus(302);
        }

        $adminModel = new AdminModel($this->pdo);
        $admin = $adminModel->findById($adminId);
        if (!$admin) {
            return $res->withHeader('Location', base_url('/admin/login'))->withStatus(302);
        }

        $files = $req->getUploadedFiles();
        $avatar = $files['avatar'] ?? null;

        if (!$avatar || $avatar->getError() !== UPLOAD_ERR_OK) {
            $_SESSION['flash']['error'] = 'Please choose an image to upload.';
            return $res->withHeader('Location', base_url('/admin/profile'))->withStatus(302);
        }

        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ];
        $clientType = strtolower((string) $avatar->getClientMediaType());
        $extension = $allowedTypes[$clientType] ?? null;

        if (!$extension) {
            $_SESSION['flash']['error'] = 'Only JPG, PNG, WEBP, or GIF images are allowed.';
            return $res->withHeader('Location', base_url('/admin/profile'))->withStatus(302);
        }

        $uploadDir = __DIR__ . '/../../public/uploads/admins/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $newFileName = 'admin_' . $adminId . '_' . time() . '.' . $extension;
        $destination = $uploadDir . $newFileName;

        try {
            $avatar->moveTo($destination);
        } catch (\Throwable $e) {
            $_SESSION['flash']['error'] = 'Unable to upload profile picture.';
            return $res->withHeader('Location', base_url('/admin/profile'))->withStatus(302);
        }

        $oldAvatar = (string) ($admin['avatar'] ?? '');
        if ($oldAvatar !== '') {
            $oldPath = __DIR__ . '/../../public/' . ltrim($oldAvatar, '/');
            if (is_file($oldPath) && strpos(realpath($oldPath) ?: '', realpath($uploadDir) ?: '') === 0) {
                @unlink($oldPath);
            }
        }

        $relativePath = 'uploads/admins/' . $newFileName;
        $adminModel->updateAvatar($adminId, $relativePath);
        $_SESSION['admin_avatar'] = $relativePath;
        $_SESSION['flash']['success'] = 'Profile picture updated.';

        return $res->withHeader('Location', base_url('/admin/profile'))->withStatus(302);
    }

    public function adminSendStaffResetLink(Request $req, Response $res, array $args): Response
    {
        $staffModel = new StaffModel($this->pdo);
        $staffId = (int) ($args['id'] ?? 0);
        $staff = $staffModel->findById($staffId);

        if (!$staff) {
            $_SESSION['flash']['error'] = 'Staff member not found.';
            return $res->withHeader('Location', base_url('/admin/staff'))->withStatus(302);
        }

        $reset = $staffModel->createPasswordResetToken($staffId, (int) ($_SESSION['admin_id'] ?? 0));

        try {
            $this->sendStaffResetEmail($staff, $reset['token'], $reset['expires_at']);
            $_SESSION['flash']['success'] = 'Password reset link sent to the staff email address.';
        } catch (\Throwable) {
            $staffModel->deletePasswordResetToken($reset['token']);
            $_SESSION['flash']['error'] = 'Unable to send reset email. Please check SMTP settings and try again.';
        }

        return $res->withHeader('Location', base_url('/admin/staff/' . $staffId))->withStatus(302);
    }

    public function staffResetForm(Request $req, Response $res, array $args): Response
    {
        $staffModel = new StaffModel($this->pdo);
        $token = (string) ($args['token'] ?? '');
        $reset = $staffModel->findPasswordResetToken($token);

        return $this->renderer->render($res, 'staff/reset-password.php', [
            'token' => $token,
            'reset' => $reset,
            'flash' => [],
            'error' => null,
            'success' => false,
        ]);
    }

    public function staffResetSubmit(Request $req, Response $res, array $args): Response
    {
        $staffModel = new StaffModel($this->pdo);
        $token = (string) ($args['token'] ?? '');
        $reset = $staffModel->findPasswordResetToken($token);

        if (!$reset) {
            return $this->renderer->render($res, 'staff/reset-password.php', [
                'token' => $token,
                'reset' => null,
                'flash' => [],
                'error' => 'This reset link is invalid or has expired.',
                'success' => false,
            ]);
        }

        $data = $req->getParsedBody();
        $password = (string) ($data['password'] ?? '');
        $confirm = (string) ($data['confirm_password'] ?? '');
        $error = null;

        if (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters long.';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match.';
        }

        if ($error !== null) {
            return $this->renderer->render($res, 'staff/reset-password.php', [
                'token' => $token,
                'reset' => $reset,
                'flash' => [],
                'error' => $error,
                'success' => false,
            ]);
        }

        if (!$staffModel->resetPasswordWithToken($token, $password)) {
            return $this->renderer->render($res, 'staff/reset-password.php', [
                'token' => $token,
                'reset' => null,
                'flash' => [],
                'error' => 'This reset link is invalid or has expired.',
                'success' => false,
            ]);
        }

        return $this->renderer->render($res, 'staff/reset-password.php', [
            'token' => $token,
            'reset' => $reset,
            'flash' => [],
            'error' => null,
            'success' => true,
        ]);
    }

    // ─── ADMIN SET PASSWORD + SECRET CODE (from invite link) ─────────

    public function adminSetPasswordForm(Request $req, Response $res, array $args): Response
    {
        $token = (string) ($args['token'] ?? '');
        $stmt = $this->pdo->prepare(
            'SELECT apr.id AS reset_id, apr.admin_id, apr.expires_at, apr.used_at, a.username
             FROM admin_password_resets apr
             JOIN admins a ON a.id = apr.admin_id
             WHERE apr.token_hash = ?
             LIMIT 1'
        );
        $stmt->execute([hash('sha256', $token)]);
        $reset = $stmt->fetch();

        if (!$reset || !empty($reset['used_at']) || strtotime($reset['expires_at']) < time()) {
            return $this->renderer->render($res, 'admin/set-password.php', [
                'token' => $token,
                'reset' => null,
                'error' => 'This link is invalid or has expired.',
                'success' => false,
            ]);
        }

        return $this->renderer->render($res, 'admin/set-password.php', [
            'token' => $token,
            'reset' => $reset,
            'error' => null,
            'success' => false,
        ]);
    }

    public function adminSetPasswordSubmit(Request $req, Response $res, array $args): Response
    {
        $token = (string) ($args['token'] ?? '');
        $data = $req->getParsedBody();
        $password = (string) ($data['password'] ?? '');
        $confirm = (string) ($data['confirm_password'] ?? '');
        $secret = (string) ($data['secret_code'] ?? '');
        $secret_confirm = (string) ($data['confirm_secret_code'] ?? '');

        $stmt = $this->pdo->prepare(
            'SELECT id, admin_id FROM admin_password_resets WHERE token_hash = ? AND used_at IS NULL AND expires_at > NOW() LIMIT 1 FOR UPDATE'
        );
        $this->pdo->beginTransaction();
        try {
            $stmt->execute([hash('sha256', $token)]);
            $reset = $stmt->fetch();
            if (!$reset) {
                $this->pdo->rollBack();
                return $this->renderer->render($res, 'admin/set-password.php', [
                    'token' => $token,
                    'reset' => null,
                    'error' => 'This link is invalid or has expired.',
                    'success' => false,
                ]);
            }

            $error = null;
            if (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters long.';
            } elseif ($password !== $confirm) {
                $error = 'Passwords do not match.';
            } elseif (strlen($secret) < 4) {
                $error = 'Secret code must be at least 4 characters.';
            } elseif ($secret !== $secret_confirm) {
                $error = 'Secret codes do not match.';
            }

            if ($error !== null) {
                $this->pdo->rollBack();
                return $this->renderer->render($res, 'admin/set-password.php', [
                    'token' => $token,
                    'reset' => $reset,
                    'error' => $error,
                    'success' => false,
                ]);
            }

            $update = $this->pdo->prepare('UPDATE admins SET password_hash = ?, secret_code_hash = ?, secret_code_set_at = NOW() WHERE id = ?');
            $update->execute([password_hash($password, PASSWORD_BCRYPT), password_hash($secret, PASSWORD_BCRYPT), (int) $reset['admin_id']]);

            $markUsed = $this->pdo->prepare('UPDATE admin_password_resets SET used_at = NOW() WHERE id = ?');
            $markUsed->execute([(int) $reset['id']]);

            $this->pdo->commit();
            return $this->renderer->render($res, 'admin/set-password.php', [
                'token' => $token,
                'reset' => $reset,
                'error' => null,
                'success' => true,
            ]);
        } catch (\Throwable $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            return $this->renderer->render($res, 'admin/set-password.php', [
                'token' => $token,
                'reset' => null,
                'error' => 'An unexpected error occurred.',
                'success' => false,
            ]);
        }
    }

    private function sendStaffResetEmail(array $staff, string $token, string $expiresAt): void
    {
        $cfg = $this->mailConfig;
        $mailer = new PHPMailer(true);
        $mailer->isSMTP();
        $mailer->Host = (string) ($cfg['host'] ?? '');
        $mailer->Port = (int) ($cfg['port'] ?? 587);
        $mailer->SMTPAuth = true;
        $mailer->Username = (string) ($cfg['username'] ?? '');
        $mailer->Password = (string) ($cfg['password'] ?? '');
        $enc = strtolower((string) ($cfg['encryption'] ?? 'tls'));
        $mailer->SMTPSecure = $enc === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mailer->setFrom((string) ($cfg['from_email'] ?? $cfg['username'] ?? ''), (string) ($cfg['from_name'] ?? 'UniHub'));
        $mailer->addAddress((string) ($staff['email'] ?? ''));
        $mailer->isHTML(true);
        $mailer->Subject = 'Reset your UniHub staff password';

// reset URL construction with proper base URL and token encoding
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $resetUrl = $scheme . '://' . $host . base_url('/staff/reset-password/' . rawurlencode($token));

        $staffName = htmlspecialchars((string) ($staff['full_name'] ?? 'Staff member'), ENT_QUOTES);
        $expiresLabel = date('j F Y, g:i A', strtotime($expiresAt));

        $mailer->Body = '
            <div style="font-family:Arial,sans-serif;line-height:1.6;color:#1f2937">
                <h2 style="margin:0 0 12px;color:#0f172a">Password reset request</h2>
                <p>Hello ' . $staffName . ',</p>
                <p>An administrator requested a password reset for your UniHub staff account.</p>
                <p style="margin:24px 0;">
                    <a href="' . htmlspecialchars($resetUrl, ENT_QUOTES) . '" style="display:inline-block;background:#0d6efd;color:#fff;text-decoration:none;padding:12px 18px;border-radius:8px;">Reset password</a>
                </p>
                <p>This link expires at ' . htmlspecialchars($expiresLabel, ENT_QUOTES) . '.</p>
                <p>If you did not expect this email, you can safely ignore it.</p>
            </div>
        ';
        $mailer->AltBody = "Hello {$staffName},\n\nAn administrator requested a password reset for your UniHub staff account.\n\nReset link: {$resetUrl}\nExpires at: {$expiresLabel}\n\nIf you did not expect this email, you can ignore it.";
        $mailer->send();
    }
}
