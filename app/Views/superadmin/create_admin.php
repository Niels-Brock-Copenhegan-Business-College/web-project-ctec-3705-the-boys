<?php
$flash = $flash ?? [];
$errors = $errors ?? [];
$old = $old ?? [];
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Create Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>body{padding:30px;font-family:Inter,system-ui,Roboto,sans-serif}</style>
</head>
<body>
<div class="container"><div class="row justify-content-center"><div class="col-12 col-md-6">
    <h3 class="mb-3">Create admin account</h3>

    <?php if (!empty($flash['success'])): ?><div class="alert alert-success auto-dismiss"><?= htmlspecialchars($flash['success'], ENT_QUOTES) ?></div><?php endif; ?>
    <?php if (!empty($flash['error'])): ?><div class="alert alert-danger"><?= htmlspecialchars($flash['error'], ENT_QUOTES) ?></div><?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
            <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e, ENT_QUOTES) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= base_url('/superadmin/admins') ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label">Full name</label>
            <input name="name" class="form-control" value="<?= htmlspecialchars($old['name'] ?? '', ENT_QUOTES) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input name="username" class="form-control" value="<?= htmlspecialchars($old['username'] ?? '', ENT_QUOTES) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input name="email" type="email" class="form-control" value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES) ?>" required>
        </div>
        <button class="btn btn-primary">Create & send invite</button>
        <a href="<?= base_url('/superadmin') ?>" class="btn btn-link">Cancel</a>
    </form>

</div></div></div>
<script>
    window.APP_BASE_URL = '<?= rtrim(base_url(), "/") ?>';
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('/js/main.js') ?>"></script>
</body>
</html>
