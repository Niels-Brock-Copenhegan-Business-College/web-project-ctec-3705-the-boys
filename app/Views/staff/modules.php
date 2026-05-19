<?php
// Variables from StaffController::modules()
$staff   = $staff   ?? [];
$modules = $modules ?? [];

// Group by year of study for display
$byYear = [];
foreach ($modules as $m) {
    $byYear[(int)$m['year_of_study']][] = $m;
}
ksort($byYear);
$pageTitle = 'My Modules';
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
            <a href="<?= base_url('/staff') ?>" class="text-muted text-decoration-none small">
                &larr; Dashboard
            </a>
            <h1 class="h3 fw-semibold mb-0 mt-1">My Modules</h1>
        </div>
        <span class="staff-stat-card px-3 py-2 text-center" style="min-width:90px;">
            <div class="staff-stat-number" style="font-size:1.4rem;"><?= count($modules) ?></div>
            <div class="staff-stat-label">Total</div>
        </span>
    </div>

    <?php if (empty($modules)): ?>
        <div class="staff-section-card">
            <div class="staff-empty-state py-5">
                <p class="text-muted mb-0">You are not assigned to any modules yet.</p>
                <p class="text-muted small mt-1">Contact an administrator to be assigned.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($byYear as $year => $yearModules): ?>
            <h2 class="h6 text-uppercase text-muted fw-semibold mb-2" style="letter-spacing:.07em;">
                Year <?= (int)$year ?>
            </h2>
            <div class="staff-section-card mb-4">
                <?php foreach ($yearModules as $m): ?>
                    <!-- Each row links with ?from=modules so back button returns here -->
                    <a href="<?= base_url('/staff/modules/' . (int)$m['id']) ?>"
                       class="staff-module-row text-decoration-none"
                       aria-label="View <?= htmlspecialchars($m['title'], ENT_QUOTES) ?>">
                        <div class="staff-module-row__body">
                            <div class="staff-module-row__title">
                                <?= htmlspecialchars($m['title'], ENT_QUOTES) ?>
                            </div>
                            <div class="staff-module-row__desc">
                                <?= htmlspecialchars(mb_strimwidth($m['description'] ?? '', 0, 90, '…'), ENT_QUOTES) ?>
                            </div>
                        </div>
                        <div class="staff-module-row__meta">
                            <span class="staff-arrow" aria-hidden="true">&rarr;</span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Auto-dismiss flash alerts after 4 seconds
    document.querySelectorAll('.auto-dismiss').forEach(function(el) {
        setTimeout(function() {
            el.style.transition = 'opacity .5s';
            el.style.opacity = '0';
            setTimeout(function() { el.remove(); }, 500);
        }, 4000);
    });
</script>
</body>
</html>