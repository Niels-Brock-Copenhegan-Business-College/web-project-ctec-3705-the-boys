<?php
// Variables from StaffController::moduleDetail()
$staff  = $staff  ?? [];
$module = $module ?? ['title'=>'','year_of_study'=>1,'description'=>'','photo'=>null,'staff'=>[],'programmes'=>[]];
$pageTitle = htmlspecialchars($module['title'] ?? 'Module Detail', ENT_QUOTES);

$hasPhoto   = !empty($module['photo']);
$photoUrl   = $hasPhoto ? base_url('/uploads/' . htmlspecialchars($module['photo'], ENT_QUOTES)) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?> | Staff Portal | UniHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('/css/custom.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/css/staff.css') ?>">
    <style>
        /* ── Module hero — mirrors programme-detail style ──────────── */
        .mod-hero {
            border-radius: 18px;
            overflow: hidden;
            position: relative;
            background: linear-gradient(135deg, #003366 0%, #00509e 100%);
            color: #fff;
            margin-bottom: 1.75rem;
        }
        /* When a photo exists the image sits below the text block */
        .mod-hero__text {
            padding: 2rem 2rem 1.5rem;
            position: relative;
            z-index: 1;
        }
        .mod-hero__year {
            display: inline-flex; align-items: center; gap: .4rem;
            background: rgba(255,255,255,.15); border-radius: 20px;
            padding: .25rem .85rem; font-size: .75rem; font-weight: 600;
            letter-spacing: .06em; text-transform: uppercase; margin-bottom: .85rem;
        }
        .mod-hero__title {
            font-size: 1.75rem; font-weight: 700; line-height: 1.2; margin-bottom: .75rem;
        }
        .mod-hero__desc {
            font-size: .92rem; line-height: 1.7; opacity: .88; max-width: 680px;
            margin-bottom: 0;
        }
        /* Photo banner — sits directly below description inside hero */
        .mod-hero__image-wrap {
            width: 100%;
            max-height: 340px;
            overflow: hidden;
            display: block;
        }
        .mod-hero__image-wrap img {
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
        }
        /* No-photo: just a subtle bottom padding */
        .mod-hero--no-photo .mod-hero__text { padding-bottom: 2rem; }

        /* ── Person row photo support ──────────────────────────────── */
        .staff-person-avatar--photo {
            background: none !important;
            padding: 0 !important;
            overflow: hidden;
        }
        .staff-person-avatar--photo img {
            width: 100%; height: 100%; object-fit: cover;
        }
    </style>
</head>
<body class="staff-body">
<a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>

<?php include __DIR__ . '/partials/navbar.php'; ?>

<main id="main-content" class="staff-main">
<div class="container py-4">

    <!-- Back button — uses smart back URL from controller -->
    <a href="<?= htmlspecialchars($backUrl ?? base_url('/staff'), ENT_QUOTES) ?>"
       class="btn btn-sm btn-outline-secondary mb-4">
        <?= htmlspecialchars($backLabel ?? '← Back', ENT_QUOTES) ?>
    </a>

    <!-- ── Hero: text block + image inside same card ── -->
    <div class="mod-hero <?= $hasPhoto ? '' : 'mod-hero--no-photo' ?> mb-4">

        <!-- Text content always on top -->
        <div class="mod-hero__text">
            <div class="mod-hero__year">
                <i class="bi bi-calendar3"></i>
                Year <?= (int)($module['year_of_study'] ?? 1) ?>
            </div>
            <h1 class="mod-hero__title">
                <?= htmlspecialchars($module['title'] ?? '', ENT_QUOTES) ?>
            </h1>
            <p class="mod-hero__desc">
                <?= htmlspecialchars($module['description'] ?? '', ENT_QUOTES) ?>
            </p>
        </div>

        <!-- Photo sits immediately after description, inside the hero card -->
        <?php if ($hasPhoto): ?>
            <div class="mod-hero__image-wrap">
                <img src="<?= $photoUrl ?>"
                     alt="<?= htmlspecialchars($module['title'] ?? '', ENT_QUOTES) ?>">
            </div>
        <?php endif; ?>

    </div>

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
                            <div class="staff-person-avatar <?= !empty($s['photo']) ? 'staff-person-avatar--photo' : '' ?>"
                                 aria-hidden="true">
                                <?php if (!empty($s['photo'])): ?>
                                    <img src="<?= base_url('/uploads/staff/' . htmlspecialchars($s['photo'], ENT_QUOTES)) ?>"
                                         alt="<?= htmlspecialchars($s['full_name'], ENT_QUOTES) ?>">
                                <?php else: ?>
                                    <?= mb_strtoupper(mb_substr($s['full_name'] ?? 'S', 0, 1)) ?>
                                <?php endif; ?>
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
