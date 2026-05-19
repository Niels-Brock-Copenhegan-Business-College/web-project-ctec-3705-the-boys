<?php
// Variables from StaffController::programmeDetail()
$staff     = $staff     ?? [];
$programme = $programme ?? ['title'=>'','level'=>'Undergraduate','description'=>'','image_url'=>null,'is_published'=>0,'staff'=>[],'modulesByYear'=>[],'interest_count'=>0];
$isUg      = ($programme['level'] ?? '') === 'Undergraduate';
$pageTitle = htmlspecialchars($programme['title'] ?? 'Programme', ENT_QUOTES);
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

    <a href="<?= base_url('/staff/programmes') ?>" class="btn btn-sm btn-outline-secondary mb-4">
        &larr; Back to my programmes
    </a>

    <!-- Hero -->
    <div class="staff-detail-hero mb-4"
         style="<?= !empty($programme['image_url']) ? 'background-image:linear-gradient(135deg,rgba(0,30,70,.82),rgba(0,60,130,.75)),url(' . htmlspecialchars($programme['image_url'], ENT_QUOTES) . ');background-size:cover;background-position:center;' : '' ?>">
        <div class="staff-detail-hero__year">
            <span class="staff-level-badge" style="background:rgba(255,255,255,.18);color:#fff;">
                <?= htmlspecialchars($programme['level'] ?? '', ENT_QUOTES) ?>
            </span>
            <?php if (!$programme['is_published']): ?>
                &nbsp;&middot;&nbsp;
                <span class="staff-badge" style="background:rgba(255,200,0,.25);color:#ffe066;">
                    Draft — not visible to students
                </span>
            <?php endif; ?>
        </div>
        <h1 class="staff-detail-hero__title">
            <?= htmlspecialchars($programme['title'] ?? '', ENT_QUOTES) ?>
        </h1>
        <p class="staff-detail-hero__desc">
            <?= htmlspecialchars($programme['description'] ?? '', ENT_QUOTES) ?>
        </p>
        <!-- Stats in hero -->
        <div class="d-flex gap-4 mt-3 flex-wrap">
            <div class="text-white">
                <div style="font-size:1.6rem;font-weight:700;line-height:1;">
                    <?= array_sum(array_map('count', $programme['modulesByYear'])) ?>
                </div>
                <div style="font-size:.72rem;opacity:.75;text-transform:uppercase;letter-spacing:.06em;">Modules</div>
            </div>
            <div class="text-white">
                <div style="font-size:1.6rem;font-weight:700;line-height:1;"><?= count($programme['staff']) ?></div>
                <div style="font-size:.72rem;opacity:.75;text-transform:uppercase;letter-spacing:.06em;">Team members</div>
            </div>
            <div class="text-white">
                <div style="font-size:1.6rem;font-weight:700;line-height:1;"><?= (int)$programme['interest_count'] ?></div>
                <div style="font-size:.72rem;opacity:.75;text-transform:uppercase;letter-spacing:.06em;">Students interested</div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <!-- Modules by year -->
        <div class="col-lg-7">
            <div class="staff-section-card">
                <div class="staff-section-header">
                    <h2 class="staff-section-title">Modules</h2>
                    <span class="badge bg-secondary rounded-pill">
                        <?= array_sum(array_map('count', $programme['modulesByYear'])) ?>
                    </span>
                </div>
                <?php if (empty($programme['modulesByYear'])): ?>
                    <div class="staff-empty-state">
                        <p class="text-muted mb-0">No modules assigned yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($programme['modulesByYear'] as $year => $mods): ?>
                        <div class="staff-year-group">
                            <div class="staff-year-label">Year <?= (int)$year ?></div>
                            <?php foreach ($mods as $m): ?>
                                <a href="<?= base_url('/staff/modules/' . (int)$m['id']) ?>"
                                   class="staff-module-row"
                                   aria-label="View <?= htmlspecialchars($m['title'], ENT_QUOTES) ?>">
                                    <div class="staff-module-row__body">
                                        <div class="staff-module-row__title">
                                            <?= htmlspecialchars($m['title'], ENT_QUOTES) ?>
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
        </div>

        <!-- Team -->
        <div class="col-lg-5">
            <div class="staff-section-card">
                <div class="staff-section-header">
                    <h2 class="staff-section-title">Programme team</h2>
                    <span class="badge bg-secondary rounded-pill"><?= count($programme['staff']) ?></span>
                </div>
                <?php if (empty($programme['staff'])): ?>
                    <div class="staff-empty-state">
                        <p class="text-muted mb-0">No staff assigned yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($programme['staff'] as $s): ?>
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

    </div>
</div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
