<?php
// ── staff/module-detail.php ──────────────────────────────────────
// Individual module detail page for the staff portal.
// Layout mirrors programme-detail.php exactly:
//   - Blurred hero image + dark overlay + stats
//   - col-lg-7: staff on this module (grouped like modules-by-year)
//   - col-lg-5: programmes using this module + quick facts
// ─────────────────────────────────────────────────────────────────
$staff    = $staff    ?? [];
$module   = $module   ?? ['title'=>'','year_of_study'=>1,'description'=>'','photo'=>null,'staff'=>[],'programmes'=>[]];
$iTeach   = $iTeach   ?? true;
$pageTitle = htmlspecialchars($module['title'] ?? 'Module Detail', ENT_QUOTES);

$hasPhoto = !empty($module['photo']);
$photoUrl = $hasPhoto ? base_url('/uploads/' . htmlspecialchars($module['photo'], ENT_QUOTES)) : '';
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
        /* ── Hero — identical approach to programme-detail ──────── */
        .mod-hero {
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            padding: 2.5rem;
            background: linear-gradient(135deg, #003366 0%, #00509e 100%);
            color: #fff;
            margin-bottom: 1.75rem;
        }
        .mod-hero--with-image {
            background-size: cover;
            background-position: center;
        }
        .mod-hero__overlay {
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(0,30,70,.88), rgba(0,60,130,.80));
        }
        .mod-hero__content { position: relative; z-index: 1; }

        .mod-hero__badges { display: flex; align-items: center; gap: .6rem; flex-wrap: wrap; margin-bottom: .9rem; }
        .mod-hero__badge {
            display: inline-block; padding: .25rem .75rem; border-radius: 20px;
            font-size: .72rem; font-weight: 600; letter-spacing: .04em;
        }
        .mod-hero__badge--year   { background: rgba(255,255,255,.18); color: #fff; }
        .mod-hero__badge--taught { background: rgba(34,197,94,.22);   color: #86efac; }
        .mod-hero__badge--view   { background: rgba(251,191,36,.2);   color: #fde68a; }

        .mod-hero__title { font-size: 1.7rem; font-weight: 700; line-height: 1.2; margin-bottom: .75rem; }
        .mod-hero__desc  { font-size: .9rem; opacity: .85; line-height: 1.65; max-width: 640px; margin-bottom: 1.25rem; }

        .mod-hero__stats { display: flex; gap: 2rem; flex-wrap: wrap; }
        .mod-hero__stat-n { font-size: 1.8rem; font-weight: 700; line-height: 1; }
        .mod-hero__stat-l { font-size: .68rem; opacity: .72; text-transform: uppercase; letter-spacing: .07em; margin-top: .1rem; }

        /* ── Staff person avatar photo support ──────────────────── */
        .staff-person-avatar--photo { background: none !important; padding: 0 !important; overflow: hidden; }
        .staff-person-avatar--photo img { width: 100%; height: 100%; object-fit: cover; }
    </style>
</head>
<body class="staff-body">
<a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>

<?php include __DIR__ . '/partials/navbar.php'; ?>

<main id="main-content" class="staff-main">
<div class="container py-4">

    <!-- Back button — controller sets $backUrl / $backLabel based on ?from= -->
    <a href="<?= htmlspecialchars($backUrl ?? base_url('/staff/modules'), ENT_QUOTES) ?>"
       class="btn btn-sm btn-outline-secondary mb-4">
        <?= htmlspecialchars($backLabel ?? '← Back to my modules', ENT_QUOTES) ?>
    </a>

    <!-- Not-assigned notice — shown when viewing via a programme, not directly assigned -->
    <?php if (!($iTeach ?? true)): ?>
        <div class="alert alert-info d-flex align-items-start gap-3 mb-4" role="note">
            <i class="bi bi-info-circle-fill fs-5 flex-shrink-0 mt-1" aria-hidden="true"></i>
            <div>
                <strong>You are not assigned to this module.</strong><br>
                <span class="small">You can view it because it is part of one of your programmes.
                <?php if (!empty($module['leader_name'])): ?>
                    The assigned module leader is <strong><?= htmlspecialchars($module['leader_name'], ENT_QUOTES) ?></strong>.
                <?php endif; ?>
                </span>
            </div>
        </div>
    <?php endif; ?>

    <!-- ── Hero ─────────────────────────────────────────────────── -->
    <div class="mod-hero<?= $hasPhoto ? ' mod-hero--with-image' : '' ?>"
         <?= $hasPhoto ? 'style="background-image:url(' . htmlspecialchars($photoUrl, ENT_QUOTES) . ');"' : '' ?>>

        <?php if ($hasPhoto): ?><div class="mod-hero__overlay"></div><?php endif; ?>

        <div class="mod-hero__content">

            <!-- Badges: Year + taught/view-only status -->
            <div class="mod-hero__badges">
                <span class="mod-hero__badge mod-hero__badge--year">
                    <i class="bi bi-calendar3 me-1"></i>Year <?= (int)($module['year_of_study'] ?? 1) ?>
                </span>
                <?php if ($iTeach): ?>
                    <span class="mod-hero__badge mod-hero__badge--taught">
                        <i class="bi bi-person-check me-1"></i>You teach this
                    </span>
                <?php else: ?>
                    <span class="mod-hero__badge mod-hero__badge--view">
                        <i class="bi bi-eye me-1"></i>View only
                    </span>
                <?php endif; ?>
            </div>

            <!-- Title + description -->
            <h1 class="mod-hero__title"><?= htmlspecialchars($module['title'] ?? '', ENT_QUOTES) ?></h1>
            <p class="mod-hero__desc"><?= htmlspecialchars($module['description'] ?? '', ENT_QUOTES) ?></p>

            <!-- Stats strip -->
            <div class="mod-hero__stats">
                <div>
                    <div class="mod-hero__stat-n"><?= count($module['staff']) ?></div>
                    <div class="mod-hero__stat-l">Team members</div>
                </div>
                <div>
                    <div class="mod-hero__stat-n"><?= count($module['programmes']) ?></div>
                    <div class="mod-hero__stat-l">Programme<?= count($module['programmes']) !== 1 ? 's' : '' ?></div>
                </div>
            </div>

        </div>
    </div>
    <!-- ── /Hero ─────────────────────────────────────────────────── -->

    <div class="row g-4">

        <!-- ── LEFT col (lg-7): Staff on this module ────────────── -->
        <div class="col-lg-7">
            <div class="staff-section-card">
                <div class="staff-section-header">
                    <h2 class="staff-section-title">
                        <i class="bi bi-people me-1 text-primary"></i>Staff on this module
                    </h2>
                    <span class="badge bg-secondary rounded-pill"><?= count($module['staff']) ?></span>
                </div>

                <?php if (empty($module['staff'])): ?>
                    <div class="staff-empty-state">
                        <p class="text-muted mb-0">No staff assigned to this module yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($module['staff'] as $s): ?>
                        <div class="staff-person-row">
                            <!-- Avatar -->
                            <div class="staff-person-avatar <?= !empty($s['photo']) ? 'staff-person-avatar--photo' : '' ?>"
                                 aria-hidden="true">
                                <?php if (!empty($s['photo'])): ?>
                                    <img src="<?= base_url('/uploads/staff/' . htmlspecialchars($s['photo'], ENT_QUOTES)) ?>"
                                         alt="<?= htmlspecialchars($s['full_name'], ENT_QUOTES) ?>">
                                <?php else: ?>
                                    <?= mb_strtoupper(mb_substr($s['full_name'] ?? 'S', 0, 1)) ?>
                                <?php endif; ?>
                            </div>
                            <!-- Name + email -->
                            <div class="flex-grow-1" style="min-width:0;">
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
                            <!-- Role badge -->
                            <span class="staff-role-badge staff-role-<?= htmlspecialchars($s['staff_role'] ?? 'instructor', ENT_QUOTES) ?> flex-shrink-0">
                                <?= ucfirst(htmlspecialchars($s['staff_role'] ?? 'instructor', ENT_QUOTES)) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <!-- ── /LEFT col ─────────────────────────────────────────── -->

        <!-- ── RIGHT col (lg-5): Programmes + Quick facts ──────── -->
        <div class="col-lg-5">

            <!-- Programmes using this module -->
            <div class="staff-section-card">
                <div class="staff-section-header">
                    <h2 class="staff-section-title">
                        <i class="bi bi-journals me-1 text-primary"></i>Programmes using this
                    </h2>
                    <span class="badge bg-secondary rounded-pill"><?= count($module['programmes']) ?></span>
                </div>

                <?php if (empty($module['programmes'])): ?>
                    <div class="staff-empty-state">
                        <p class="text-muted mb-0">This module is not linked to any programme yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($module['programmes'] as $p): ?>
                        <div class="staff-prog-item">
                            <div style="min-width:0;">
                                <div class="staff-prog-item__title">
                                    <?= htmlspecialchars($p['title'], ENT_QUOTES) ?>
                                </div>
                                <span class="staff-level-badge staff-level-<?= $p['level'] === 'Undergraduate' ? 'ug' : 'pg' ?>">
                                    <?= htmlspecialchars($p['level'], ENT_QUOTES) ?>
                                </span>
                            </div>
                            <span class="flex-shrink-0">
                                <?php if ($p['is_published']): ?>
                                    <span class="staff-badge staff-badge--leader">Published</span>
                                <?php else: ?>
                                    <span class="staff-badge staff-badge--contributor">Draft</span>
                                <?php endif; ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Quick facts — mirrors programme-detail's quick facts card -->
            <div class="staff-section-card mt-4">
                <div class="staff-section-header">
                    <h2 class="staff-section-title">
                        <i class="bi bi-info-circle me-1 text-primary"></i>Quick facts
                    </h2>
                </div>
                <dl class="staff-profile-dl p-3">
                    <dt>Year of study</dt>
                    <dd>Year <?= (int)($module['year_of_study'] ?? 1) ?></dd>
                    <dt>Shared across</dt>
                    <dd><?= count($module['programmes']) ?> programme<?= count($module['programmes']) !== 1 ? 's' : '' ?></dd>
                    <dt>Total staff</dt>
                    <dd><?= count($module['staff']) ?> member<?= count($module['staff']) !== 1 ? 's' : '' ?></dd>
                    <dt>You teach this</dt>
                    <dd><?= $iTeach ? '<span class="text-success fw-semibold">Yes</span>' : '<span class="text-muted">No — view only</span>' ?></dd>
                </dl>
            </div>

        </div>
        <!-- ── /RIGHT col ────────────────────────────────────────── -->

    </div>

</div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>