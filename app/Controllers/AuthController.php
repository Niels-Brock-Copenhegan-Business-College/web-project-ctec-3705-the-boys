<?php
namespace App\Controllers;

use App\Models\StaffModel;
use App\Models\InterestModel;
use PHPMailer\PHPMailer\PHPMailer;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    public function __construct(
        private \PDO $pdo,
        private PhpRenderer $renderer,
        private array $mailConfig = []
    ) {}

    private function flash(string $key, string $msg): void { $_SESSION['login_flash'][$key] = $msg; }

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
    // No role selector — try admin first, then staff. Whoever matches gets in.

    public function unifiedLogin(Request $req, Response $res): Response
    {
        $d    = $req->getParsedBody();
        $user = trim($d['username'] ?? '');
        $pass = $d['password']     ?? '';

        // 1. Try admin
        $stmt = $this->pdo->prepare('SELECT * FROM admins WHERE username = ?');
        $stmt->execute([$user]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($pass, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            return $res->withHeader('Location', base_url('/admin'))->withStatus(302);
        }

        // 2. Try staff
        $staffModel = new StaffModel($this->pdo);
        $staff      = $staffModel->verifyLogin($user, $pass);

        if ($staff) {
            session_regenerate_id(true);
            $_SESSION['staff_id']   = $staff['id'];
            $_SESSION['staff_name'] = $staff['full_name'];
            $_SESSION['staff_role'] = $staff['role'];
            return $res->withHeader('Location', base_url('/staff'))->withStatus(302);
        }

        // 3. No match
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
}