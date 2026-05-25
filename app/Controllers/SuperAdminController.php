<?php

namespace App\Controllers;

use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\SuperAdminModel;

class SuperAdminController
{
    public function __construct(
        private \PDO $pdo,
        private PhpRenderer $renderer,
        private array $mailConfig = []
    ) {}

    public function loginForm(Request $req, Response $res): Response
    {
        if (!empty($_SESSION['superadmin_id'])) {
            return $res->withHeader('Location', base_url('/superadmin'))->withStatus(302);
        }
        $flash = $_SESSION['login_flash'] ?? [];
        unset($_SESSION['login_flash']);
        return $this->renderer->render($res, 'superadmin/login.php', [
            'error' => null,
            'flash' => $flash,
            'oldUser' => '',
        ]);
    }

    public function login(Request $req, Response $res): Response
    {
        $d = $req->getParsedBody();
        $user = trim((string) ($d['username'] ?? ''));
        $pass = (string) ($d['password'] ?? '');

        $sam = new SuperAdminModel($this->pdo);
        $sa = $sam->verifyLogin($user, $pass);
        if ($sa) {
            session_regenerate_id(true);
            $_SESSION['superadmin_id'] = $sa['id'];
            $_SESSION['superadmin_name'] = $sa['name'] ?? $sa['username'];
            return $res->withHeader('Location', base_url('/superadmin'))->withStatus(302);
        }

        return $this->renderer->render($res, 'superadmin/login.php', [
            'error' => 'Incorrect username or password.',
            'flash' => [],
            'oldUser' => htmlspecialchars($user, ENT_QUOTES),
        ]);
    }

    public function logout(Request $req, Response $res): Response
    {
        session_destroy();
        return $res->withHeader('Location', base_url('/superadmin/login'))->withStatus(302);
    }

    public function dashboard(Request $req, Response $res): Response
    {
        // Simple dashboard: list admins for management
        $stmt = $this->pdo->query('SELECT id, username, password_hash, is_active FROM admins ORDER BY id ASC');
        $admins = $stmt->fetchAll();
        return $this->renderer->render($res, 'superadmin/dashboard.php', [
            'admins' => $admins,
            'flash' => $_SESSION['flash'] ?? [],
        ]);
    }

    public function showCreateAdminForm(Request $req, Response $res): Response
    {
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $this->renderer->render($res, 'superadmin/create_admin.php', [
            'flash' => $flash,
            'errors' => [],
            'old' => [],
        ]);
    }

    public function createAdminSubmit(Request $req, Response $res): Response
    {
        $d = $req->getParsedBody();
        $username = trim((string) ($d['username'] ?? ''));
        $email = strtolower(trim((string) ($d['email'] ?? '')));
        $name = trim((string) ($d['name'] ?? ''));

        $errors = [];
        if ($username === '') $errors[] = 'Username is required.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';

        if (!empty($errors)) {
            return $this->renderer->render($res, 'superadmin/create_admin.php', [
                'flash' => [],
                'errors' => $errors,
                'old' => ['username' => $username, 'email' => $email, 'name' => $name],
            ]);
        }

        // Create admin with empty password_hash (admin will set password via emailed link)
        $stmt = $this->pdo->prepare('INSERT INTO admins (username, password_hash, is_active) VALUES (?, ?, 1)');
        $stmt->execute([$username, '']);
        $adminId = (int) $this->pdo->lastInsertId();

        // Create invite token
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $expiresAt = (new \DateTimeImmutable('+2 hours'))->format('Y-m-d H:i:s');
        $createBy = (int) ($_SESSION['superadmin_id'] ?? 0);
        $stmt = $this->pdo->prepare('INSERT INTO admin_password_resets (admin_id, token_hash, created_by, expires_at) VALUES (?, ?, ?, ?)');
        $stmt->execute([$adminId, $tokenHash, $createBy, $expiresAt]);

        // Send invite email (best-effort)
        try {
            $this->sendAdminInviteEmail($email, $username, $name, $token, $expiresAt);
            $_SESSION['flash']['success'] = 'Admin created and invite email sent.';
        } catch (\Throwable $e) {
            $_SESSION['flash']['error'] = 'Admin created but unable to send invite email.';
        }

        return $res->withHeader('Location', base_url('/superadmin'))->withStatus(302);
    }

    public function toggleAdminBlock(Request $req, Response $res, array $args): Response
    {
        $adminId = (int) ($args['id'] ?? 0);
        if ($adminId <= 0) {
            $_SESSION['flash']['error'] = 'Invalid admin selected.';
            return $res->withHeader('Location', base_url('/superadmin'))->withStatus(302);
        }

        $stmt = $this->pdo->prepare('SELECT id, username, is_active FROM admins WHERE id = ?');
        $stmt->execute([$adminId]);
        $admin = $stmt->fetch();

        if (!$admin) {
            $_SESSION['flash']['error'] = 'Admin not found.';
            return $res->withHeader('Location', base_url('/superadmin'))->withStatus(302);
        }

        $newStatus = empty($admin['is_active']) ? 1 : 0;
        $stmt = $this->pdo->prepare('UPDATE admins SET is_active = ? WHERE id = ?');
        $stmt->execute([$newStatus, $adminId]);

        \app_log('warning', 'Super admin changed admin active status', [
            'superadmin_id' => (int) ($_SESSION['superadmin_id'] ?? 0),
            'admin_id' => $adminId,
            'username' => $admin['username'] ?? null,
            'new_status' => $newStatus,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);

        $_SESSION['flash']['success'] = $newStatus ? 'Admin unblocked successfully.' : 'Admin blocked successfully.';
        return $res->withHeader('Location', base_url('/superadmin'))->withStatus(302);
    }

    public function hardDeleteAdmin(Request $req, Response $res, array $args): Response
    {
        $adminId = (int) ($args['id'] ?? 0);
        if ($adminId <= 0) {
            $_SESSION['flash']['error'] = 'Invalid admin selected.';
            return $res->withHeader('Location', base_url('/superadmin'))->withStatus(302);
        }

        $stmt = $this->pdo->prepare('SELECT id, username FROM admins WHERE id = ?');
        $stmt->execute([$adminId]);
        $admin = $stmt->fetch();

        if (!$admin) {
            $_SESSION['flash']['error'] = 'Admin not found.';
            return $res->withHeader('Location', base_url('/superadmin'))->withStatus(302);
        }

        $this->pdo->beginTransaction();
        try {
            $this->pdo->prepare('DELETE FROM admin_password_resets WHERE admin_id = ?')->execute([$adminId]);
            $this->pdo->prepare('DELETE FROM admins WHERE id = ?')->execute([$adminId]);
            $this->pdo->commit();

            \app_log('warning', 'Super admin hard deleted admin', [
                'superadmin_id' => (int) ($_SESSION['superadmin_id'] ?? 0),
                'admin_id' => $adminId,
                'username' => $admin['username'] ?? null,
                'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            ]);

            $_SESSION['flash']['success'] = 'Admin deleted permanently.';
        } catch (\Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            $_SESSION['flash']['error'] = 'Unable to delete admin.';
        }

        return $res->withHeader('Location', base_url('/superadmin'))->withStatus(302);
    }

    public function logs(Request $req, Response $res): Response
    {
        $entries = [];
        $pdo = $GLOBALS['app_pdo'] ?? null;
        if ($pdo instanceof \PDO) {
            try {
                $stmt = $pdo->query('SELECT id, created_at AS time, level, message, context FROM audit_logs ORDER BY id DESC LIMIT 1000');
                $rows = $stmt->fetchAll();
                foreach ($rows as $r) {
                    $r['context'] = $r['context'] ? json_decode($r['context'], true) : [];
                    $entries[] = $r;
                }
            } catch (\PDOException $e) {
                $sqlState = $e->getCode();
                if ($sqlState === '42S02' || strpos($e->getMessage(), '1146') !== false) {
                    // Table missing — try to create it (best-effort)
                    try {
                        $create = "CREATE TABLE IF NOT EXISTS `audit_logs` (
                            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                            `created_at` DATETIME NOT NULL,
                            `level` VARCHAR(20) NOT NULL,
                            `message` TEXT NOT NULL,
                            `context` JSON DEFAULT NULL,
                            `ip` VARCHAR(45) DEFAULT NULL,
                            `created_by` INT DEFAULT NULL
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                        $pdo->exec($create);
                        // No entries yet after creating
                        $entries = [];
                    } catch (\Throwable $e2) {
                        $_SESSION['flash']['error'] = 'Log table is missing and could not be created automatically. Please run the migration or check DB permissions.';
                    }
                } else {
                    $_SESSION['flash']['error'] = 'Unable to read audit logs: ' . $e->getMessage();
                }
            }
        }

        return $this->renderer->render($res, 'superadmin/logs.php', [
            'entries' => $entries,
        ]);
    }

    public function deleteLog(Request $req, Response $res): Response
    {
        $data = $req->getParsedBody();
        $id = isset($data['id']) ? (int) $data['id'] : null;

        if ($id === null) {
            $_SESSION['flash']['error'] = 'Unable to delete log entry.';
            return $res->withHeader('Location', base_url('/superadmin/logs'))->withStatus(302);
        }

        $pdo = $GLOBALS['app_pdo'] ?? null;
        if ($pdo instanceof \PDO) {
            try {
                $stmt = $pdo->prepare('DELETE FROM audit_logs WHERE id = ?');
                $stmt->execute([$id]);
                $_SESSION['flash']['success'] = 'Log entry deleted.';
            } catch (\PDOException $e) {
                $_SESSION['flash']['error'] = 'Unable to delete log entry: ' . $e->getMessage();
            }
            return $res->withHeader('Location', base_url('/superadmin/logs'))->withStatus(302);
        }

        $_SESSION['flash']['error'] = 'DB not available; cannot delete log entries.';
        return $res->withHeader('Location', base_url('/superadmin/logs'))->withStatus(302);
    }

    private function sendAdminInviteEmail(string $email, string $username, string $name, string $token, string $expiresAt): void
    {
        $cfg = $this->mailConfig;
        $mailer = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mailer->isSMTP();
        $mailer->Host = (string) ($cfg['host'] ?? '');
        $mailer->Port = (int) ($cfg['port'] ?? 587);
        $mailer->SMTPAuth = true;
        $mailer->Username = (string) ($cfg['username'] ?? '');
        $mailer->Password = (string) ($cfg['password'] ?? '');
        $enc = strtolower((string) ($cfg['encryption'] ?? 'tls'));
        $mailer->SMTPSecure = $enc === 'ssl' ? \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS : \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mailer->setFrom((string) ($cfg['from_email'] ?? $cfg['username'] ?? ''), (string) ($cfg['from_name'] ?? 'UniHub'));
        $mailer->addAddress($email);
        $mailer->isHTML(true);
        $mailer->Subject = 'Set up your admin account on UniHub';

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $inviteUrl = $scheme . '://' . $host . base_url('/admin/set-password/' . rawurlencode($token));

        $recipient = htmlspecialchars($name ?: $username, ENT_QUOTES);
        $expiresLabel = date('j F Y, g:i A', strtotime($expiresAt));

        $mailer->Body = "<div style='font-family:Arial,sans-serif;line-height:1.6;color:#111;'>\n"
            . "<h2>Welcome to UniHub</h2>\n"
            . "<p>Hello {$recipient},</p>\n"
            . "<p>A super administrator created an admin account for you (username: <strong>" . htmlspecialchars($username, ENT_QUOTES) . "</strong>).</p>\n"
            . "<p><a href='" . htmlspecialchars($inviteUrl, ENT_QUOTES) . "' style='display:inline-block;padding:12px 16px;background:#0d6efd;color:#fff;border-radius:8px;text-decoration:none;'>Set your password & secret code</a></p>\n"
            . "<p>This link expires at {$expiresLabel}.</p>\n"
            . "</div>";
        $mailer->AltBody = "Set up your admin account: {$inviteUrl}\nExpires at: {$expiresLabel}";
        $mailer->send();
    }
}
