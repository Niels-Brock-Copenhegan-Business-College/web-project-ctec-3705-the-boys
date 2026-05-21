<?php
$token = $token ?? '';
$reset = $reset ?? null;
$error = $error ?? null;
$success = $success ?? false;
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Set password & secret code</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>body{padding:30px;font-family:Inter,system-ui,Roboto,sans-serif}</style>
</head>
<body>
<div class="container"><div class="row justify-content-center"><div class="col-12 col-md-6">
    <h3 class="mb-3">Set your admin password & secret code</h3>

    <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success">Your password and secret code have been set. You can now <a href="<?= base_url('/admin/login') ?>">sign in</a>.</div><?php endif; ?>

    <?php if (!$success): ?>
    <form method="POST" action="<?= base_url('/admin/set-password/' . rawurlencode($token)) ?>">
        <div class="mb-3">
            <label class="form-label">New password</label>
            <input name="password" type="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm password</label>
            <input name="confirm_password" type="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Secret code (PIN)</label>
            <input name="secret_code" class="form-control" required placeholder="4+ characters">
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm secret code</label>
            <input name="confirm_secret_code" class="form-control" required>
        </div>
        <button class="btn btn-primary">Set password & code</button>
    </form>
    <?php endif; ?>

</div></div></div>
</body>
</html>
