<?php
$admins = $admins ?? [];
$flash = $flash ?? [];
$totalAdmins = count($admins);
$activeAdmins = count(array_filter($admins, fn($a) => !empty($a['is_active'])));
$blockedAdmins = $totalAdmins - $activeAdmins;
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Superadmin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(13,110,253,.14), transparent 30%),
                radial-gradient(circle at top right, rgba(25,135,84,.12), transparent 25%),
                linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
            font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
        }
        .hero-shell {
            background: rgba(255,255,255,.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,.7);
        }
        .metric-card {
            border: 0;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
        }
        .admin-table {
            border-collapse: separate;
            border-spacing: 0 12px;
        }
        .admin-table thead th {
            border: 0;
            color: #64748b;
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .08em;
        }
        .admin-table tbody tr {
            background: #fff;
            box-shadow: 0 8px 24px rgba(15,23,42,.05);
        }
        .admin-table tbody td {
            vertical-align: middle;
            border-top: 0;
            border-bottom: 0;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
    </style>
</head>
<body>
<div class="container py-4 py-lg-5">
    <div class="hero-shell rounded-4 shadow-sm p-4 p-lg-5 mb-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
            <div>
                <div class="text-uppercase text-primary fw-semibold small mb-2">Superadmin Control Center</div>
                <h1 class="display-6 fw-bold mb-2">Admin management dashboard</h1>
                <p class="text-muted mb-0">Create, block, and permanently remove admin accounts from one place.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?= base_url('/superadmin/logs') ?>" class="btn btn-outline-dark">View logs</a>
                <a href="<?= base_url('/superadmin/admins/create') ?>" class="btn btn-primary">Create admin</a>
                <a href="<?= base_url('/superadmin/logout') ?>" class="btn btn-outline-secondary">Logout</a>
            </div>
        </div>
    </div>

    <?php if (!empty($flash['success'])): ?>
        <div class="alert alert-success shadow-sm border-0"><?= htmlspecialchars($flash['success'], ENT_QUOTES) ?></div>
    <?php endif; ?>
    <?php if (!empty($flash['error'])): ?>
        <div class="alert alert-danger shadow-sm border-0"><?= htmlspecialchars($flash['error'], ENT_QUOTES) ?></div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card metric-card h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-semibold mb-2">Total admins</div>
                    <div class="display-6 fw-bold mb-0"><?= (int) $totalAdmins ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card metric-card h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-semibold mb-2">Active</div>
                    <div class="display-6 fw-bold text-success mb-0"><?= (int) $activeAdmins ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card metric-card h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-semibold mb-2">Blocked</div>
                    <div class="display-6 fw-bold text-danger mb-0"><?= (int) $blockedAdmins ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card metric-card border-0 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold">Admins</h5>
                <small class="text-muted">Use the actions on the right to manage access.</small>
            </div>
            <span class="badge text-bg-light border text-secondary"><?= (int) $totalAdmins ?> total</span>
        </div>
        <div class="card-body p-4 pt-2">
            <div class="table-responsive">
                <table class="table admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:80px;">ID</th>
                            <th>Username</th>
                            <th>Status</th>
                            <th>Password</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($admins as $a): ?>
                        <tr>
                            <td class="fw-semibold text-secondary">#<?= (int) $a['id'] ?></td>
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($a['username'], ENT_QUOTES) ?></div>
                                <div class="text-muted small">Admin account</div>
                            </td>
                            <td>
                                <?php if (!empty($a['is_active'])): ?>
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2">Active</span>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-3 py-2">Blocked</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($a['password_hash'])): ?>
                                    <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">Set</span>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-3 py-2">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2 flex-wrap justify-content-end">
                                    <form method="POST" action="<?= base_url('/superadmin/admins/' . (int) $a['id'] . '/block-toggle') ?>">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm <?= !empty($a['is_active']) ? 'btn-outline-warning' : 'btn-outline-success' ?>">
                                            <?= !empty($a['is_active']) ? 'Block' : 'Unblock' ?>
                                        </button>
                                    </form>
                                    <form method="POST" action="<?= base_url('/superadmin/admins/' . (int) $a['id'] . '/delete') ?>" onsubmit="return confirm('Delete this admin permanently? This cannot be undone.');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
