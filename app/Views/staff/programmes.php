<?php
// Variables from StaffController::programmes()
$staff      = $staff      ?? [];
$programmes = $programmes ?? [];
$pageTitle  = 'My Programmes';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES) ?> | Staff Portal | UniHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('/css/custom.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/css/staff.css') ?>">
</head>
<body class="staff-body">
<a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>

<?php include __DIR__ . '/partials/navbar.php'; ?>

<main id="main-content" class="staff-main">
<div class="container py-4">

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <a href="<?= base_url('/staff') ?>" class="text-muted text-decoration-none small">&larr; Dashboard</a>
            <h1 class="h3 fw-semibold mb-0 mt-1">My Programmes</h1>
        </div>
        <span class="staff-stat-card px-3 py-2 text-center" style="min-width:90px;">
            <div class="staff-stat-number" style="font-size:1.4rem;"><?= count($programmes) ?></div>
            <div class="staff-stat-label">Total</div>
        </span>
    </div>

    <?php if (empty($programmes)): ?>
        <div class="staff-section-card">
            <div class="staff-empty-state py-5">
                <p class="text-muted mb-0">You are not linked to any programmes yet.</p>
                <p class="text-muted small mt-1">Contact an administrator to be assigned.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($programmes as $p): ?>
                <div class="col-md-6">
                    <?php include __DIR__ . '/partials/programme-card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
