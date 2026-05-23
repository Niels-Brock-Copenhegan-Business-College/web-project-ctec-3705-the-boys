<?php
$admins = $admins ?? [];
$flash = $flash ?? [];
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Superadmin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>body{padding:24px;font-family:Inter,system-ui,Roboto,sans-serif}</style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Superadmin dashboard</h3>
        <div>
            <a href="<?= base_url('/superadmin/logs') ?>" class="btn btn-outline-dark">View logs</a>
            <a href="<?= base_url('/superadmin/admins/create') ?>" class="btn btn-primary">Create admin</a>
            <a href="<?= base_url('/superadmin/logout') ?>" class="btn btn-outline-secondary">Logout</a>
        </div>
    </div>

    <?php if (!empty($flash['success'])): ?><div class="alert alert-success"><?= htmlspecialchars($flash['success'], ENT_QUOTES) ?></div><?php endif; ?>
    <?php if (!empty($flash['error'])): ?><div class="alert alert-danger"><?= htmlspecialchars($flash['error'], ENT_QUOTES) ?></div><?php endif; ?>

    <div class="card"><div class="card-body">
        <h5 class="card-title">Admins</h5>
        <table class="table table-sm">
            <thead><tr><th>ID</th><th>Username</th><th>Password set?</th></tr></thead>
            <tbody>
            <?php foreach ($admins as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['id'], ENT_QUOTES) ?></td>
                    <td><?= htmlspecialchars($a['username'], ENT_QUOTES) ?></td>
                    <td><?= $a['password_hash'] ? 'Yes' : 'No' ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div></div>
</div>
</body>
</html>
