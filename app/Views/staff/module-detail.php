<?php
// Variables from StaffController::moduleDetail()
$staff  = $staff  ?? [];
$module = $module ?? ['title'=>'','year_of_study'=>1,'description'=>'','staff'=>[],'programmes'=>[]];
$pageTitle = htmlspecialchars($module['title'] ?? 'Module Detail', ENT_QUOTES);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?> | Staff Portal | UniHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('/css/custom.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/css/staff.css') ?>">
</head>
<body class="staff-body">
<a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>

<?php include __DIR__ . '/partials/navbar.php'; ?>

<main id="main-content" class="staff-main">
<div class="container py-4">

    <a href="<?= base_url('/staff') ?>" class="btn btn-sm btn-outline-secondary mb-4">
        &larr; Back to dashboard
    </a>

    <!-- Hero -->
    <div class="staff-detail-hero mb-4">
        <div class="staff-detail-hero__year">
            Year <?= (int)($module['year_of_study'] ?? 1) ?>
        </div>
        <h1 class="staff-detail-hero__title">
            <?= htmlspecialchars($module['title'] ?? '', ENT_QUOTES) ?>
        </h1>
        <p class="staff-detail-hero__desc">
            <?= htmlspecialchars($module['description'] ?? '', ENT_QUOTES) ?>
        </p>
    </div>

    <?php if (!empty($module['photo'])): ?>
        <div class="mb-4">
            <img src="<?= base_url('/uploads/' . htmlspecialchars($module['photo'], ENT_QUOTES)) ?>"
                 alt="<?= htmlspecialchars($module['title'] ?? '', ENT_QUOTES) ?> photo"
                 class="staff-module-image img-fluid rounded-3">
        </div>
    <?php endif; ?>

    <div class="row g-4">

        <!-- Staff on this module -->
        <div class="col-lg-6">
            <div class="staff-section-card">
                <div class="staff-section-header">
                    <h2 class="staff-section-title">Staff on this module</h2>
                    <span class="badge bg-secondary rounded-pill"><?= count($module['staff']) ?></span>
                </div>
                <?php if (empty($module['staff'])): ?>
                    <div class="staff-empty-state">
                        <p class="text-muted mb-0">No staff assigned to this module yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($module['staff'] as $s): ?>
                        <div class="staff-person-row">
                            <div class="staff-person-avatar" aria-hidden="true">
                                <?= mb_strtoupper(mb_substr($s['full_name'] ?? 'S', 0, 1)) ?>
                            </div>
                            <div class="flex-grow-1">
                                <div class="staff-person-name">
                                    <?= htmlspecialchars($s['full_name'], ENT_QUOTES) ?>
                                    <?php if ((int)$s['id'] === (int)($_SESSION['staff_id'] ?? 0)): ?>
                                        <span class="staff-badge staff-badge--contributor ms-1">You</span>
                                    <?php endif; ?>
                                </div>
                                <div class="staff-person-email">
                                    <?= htmlspecialchars($s['email'], ENT_QUOTES) ?>
                                </div>
                            </div>
                            <span class="staff-role-badge staff-role-<?= htmlspecialchars($s['staff_role'] ?? 'instructor', ENT_QUOTES) ?>">
                                <?= ucfirst(htmlspecialchars($s['staff_role'] ?? 'instructor', ENT_QUOTES)) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Programmes using this module -->
        <div class="col-lg-6">
            <div class="staff-section-card">
                <div class="staff-section-header">
                    <h2 class="staff-section-title">Programmes using this module</h2>
                    <span class="badge bg-secondary rounded-pill"><?= count($module['programmes']) ?></span>
                </div>
                <?php if (empty($module['programmes'])): ?>
                    <div class="staff-empty-state">
                        <p class="text-muted mb-0">This module is not linked to any programme yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($module['programmes'] as $p): ?>
                        <div class="staff-prog-item">
                            <div>
                                <div class="staff-prog-item__title">
                                    <?= htmlspecialchars($p['title'], ENT_QUOTES) ?>
                                </div>
                                <span class="staff-level-badge staff-level-<?= $p['level'] === 'Undergraduate' ? 'ug' : 'pg' ?>">
                                    <?= htmlspecialchars($p['level'], ENT_QUOTES) ?>
                                </span>
                            </div>
                            <?php if ($p['is_published']): ?>
                                <span class="staff-badge staff-badge--leader">Published</span>
                            <?php else: ?>
                                <span class="staff-badge staff-badge--contributor">Draft</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Quick facts -->
            <div class="staff-section-card mt-4">
                <div class="staff-section-header">
                    <h2 class="staff-section-title">Quick facts</h2>
                </div>
                <dl class="staff-profile-dl p-3">
                    <dt>Year of study</dt>
                    <dd>Year <?= (int)($module['year_of_study'] ?? 1) ?></dd>
                    <dt>Shared across</dt>
                    <dd><?= count($module['programmes']) ?> programme<?= count($module['programmes']) !== 1 ? 's' : '' ?></dd>
                    <dt>Total staff</dt>
                    <dd><?= count($module['staff']) ?> member<?= count($module['staff']) !== 1 ? 's' : '' ?></dd>
                </dl>
            </div>
        </div>

    </div>
</div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
