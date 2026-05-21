<?php
$error = $error ?? null;
$flash = $flash ?? [];
$oldUser = $oldUser ?? '';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Superadmin Sign in</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>body{background:#f5f7fb;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,sans-serif;padding:40px}</style>
</head>
<body>
<div class="container"><div class="row justify-content-center"><div class="col-12 col-md-6">
    <h3 class="mb-3">Superadmin sign in</h3>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES) ?></div>
    <?php endif; ?>
    <?php if (!empty($flash['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($flash['success'], ENT_QUOTES) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= base_url('/superadmin/login') ?>">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input name="username" class="form-control" value="<?= htmlspecialchars($oldUser, ENT_QUOTES) ?>" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-primary">Sign in</button>
    </form>

    <p class="mt-3"><a href="<?= base_url('/') ?>">Back to site</a></p>
</div></div></div>
</body>
</html>
