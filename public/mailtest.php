<?php
/**
 * mail_test.php — drop in your project ROOT (same level as public/)
 * Visit: http://localhost:8080/mail_test.php  (or wherever your app runs)
 * DELETE this file after testing.
 */
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

$cfg = require __DIR__ . '/../config/mail.php';

// ── Change this to YOUR email address ──
$testTo = 'chitraranjanyadav2058@gmail.com';

$errors = [];
$success = false;

try {
    $mail = new PHPMailer(true); // true = throw exceptions
    $mail->SMTPDebug  = 2;       // verbose output
    $mail->Debugoutput = function($str, $level) {
        echo htmlspecialchars("[SMTP] $str") . "<br>\n";
        flush();
    };

    $mail->isSMTP();
    $mail->Host       = $cfg['host'];
    $mail->Port       = (int)$cfg['port'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $cfg['username'];
    $mail->Password   = $cfg['password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    $mail->setFrom($cfg['from_email'], $cfg['from_name']);
    $mail->addAddress($testTo);

    $mail->Subject = 'UniHub mail test — ' . date('H:i:s');
    $mail->Body    = "If you see this, PHPMailer is working correctly.\n\nSent at: " . date('Y-m-d H:i:s');
    $mail->isHTML(false);

    $mail->send();
    $success = true;

} catch (MailException $e) {
    $errors[] = 'MailException: ' . $e->getMessage();
} catch (\Throwable $e) {
    $errors[] = get_class($e) . ': ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head><title>Mail test</title>
<style>body{font-family:monospace;padding:2rem;} .ok{color:green} .err{color:red} pre{background:#f4f4f4;padding:1rem;border-radius:6px;}</style>
</head>
<body>
<h2>UniHub — PHPMailer test</h2>
<p>Sending to: <strong><?= htmlspecialchars($testTo) ?></strong></p>
<hr>
<?php if ($success): ?>
  <p class="ok">✅ Email sent successfully! Check your inbox.</p>
<?php else: ?>
  <p class="err">❌ Failed to send.</p>
  <?php foreach ($errors as $e): ?>
    <pre><?= htmlspecialchars($e) ?></pre>
  <?php endforeach; ?>
<?php endif; ?>
</body>
</html>