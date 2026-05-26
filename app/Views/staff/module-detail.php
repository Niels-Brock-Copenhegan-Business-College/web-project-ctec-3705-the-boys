<?php
// ── staff/module-detail.php ──────────────────────────────────────
// Individual staff module detail page.
// This view shows module metadata, assigned staff, linked programmes, and quick facts.
// It is intentionally self-contained so the staff portal can render without a separate layout wrapper.
// ─────────────────────────────────────────────────────────────────

$staff = $staff ?? [];
$module = $module ?? [
    'title' => '',
    'year_of_study' => 1,
    'description' => '',
    'photo' => null,
    'staff' => [],
    'programmes' => [],
];
$iTeach = $iTeach ?? true;

// Page title uses the module title when available.
$pageTitle = htmlspecialchars($module['title'] ?? 'Module Detail', ENT_QUOTES);

// Precompute photo state and URL to avoid repeated logic in the template.
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
</head>
<body class="staff-body">

<a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>

<?php include __DIR__ . '/partials/navbar.php'; ?>

<main id="main-content" class="staff-main">
    <div class="container py-4">

        <!-- Back control uses a fallback URL when none is supplied. -->
        <a
            href="<?= htmlspecialchars($backUrl ?? base_url('/staff/modules'), ENT_QUOTES) ?>"
            class="btn btn-sm btn-outline-secondary mb-4"
        >
            <?= htmlspecialchars($backLabel ?? '← Back to my modules', ENT_QUOTES) ?>
        </a>

        <!-- Informational notice when viewing without assignment. -->
        <?php if (!($iTeach ?? true)): ?>
            <div class="alert alert-info d-flex align-items-start gap-3 mb-4" role="note">
                <i class="bi bi-info-circle-fill fs-5 flex-shrink-0 mt-1" aria-hidden="true"></i>
                <div>
                    <strong>You are not assigned to this module.</strong><br>
                    <span class="small">
                        You can view it because it is part of one of your programmes.
                        <?php if (!empty($module['leader_name'])): ?>
                            The assigned module leader is <strong><?= htmlspecialchars($module['leader_name'], ENT_QUOTES) ?></strong>.
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        <?php endif; ?>

        <!-- Hero section displays module title, description, and summary stats. -->
        <div
            class="mod-hero<?= $hasPhoto ? ' mod-hero--with-image' : '' ?>"
            <?= $hasPhoto ? 'style="background-image:url(' . htmlspecialchars($photoUrl, ENT_QUOTES) . ');"' : '' ?>
        >
            <?php if ($hasPhoto): ?>
                <div class="mod-hero__overlay"></div>
            <?php endif; ?>

            <div class="mod-hero__content">
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

                <h1 class="mod-hero__title"><?= htmlspecialchars($module['title'] ?? '', ENT_QUOTES) ?></h1>

                <p class="mod-hero__desc" style="text-align:justify;">
                    <?= htmlspecialchars($module['description'] ?? '', ENT_QUOTES) ?>
                </p>

                <div class="mod-hero__stats">
                    <div>
                        <div class="mod-hero__stat-n"><?= count($module['staff']) ?></div>
                        <div class="mod-hero__stat-l">Team members</div>
                    </div>
                    <div>
                        <div class="mod-hero__stat-n"><?= count($module['programmes']) ?></div>
                        <div class="mod-hero__stat-l">
                            Programme<?= count($module['programmes']) !== 1 ? 's' : '' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            <!-- Left column: staff assigned to this module. -->
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
                                <!-- Staff avatar or initial fallback. -->
                                <div
                                    class="staff-person-avatar <?= !empty($s['photo']) ? 'staff-person-avatar--photo' : '' ?>"
                                    aria-hidden="true"
                                >
                                    <?php if (!empty($s['photo'])): ?>
                                        <img
                                            src="<?= base_url('/uploads/staff/' . htmlspecialchars($s['photo'], ENT_QUOTES)) ?>"
                                            alt="<?= htmlspecialchars($s['full_name'], ENT_QUOTES) ?>"
                                        >
                                    <?php else: ?>
                                        <?= mb_strtoupper(mb_substr($s['full_name'] ?? 'S', 0, 1)) ?>
                                    <?php endif; ?>
                                </div>

                                <!-- Staff name + email details. -->
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

                                <!-- Role badge for this staff member. -->
                                <span class="staff-role-badge staff-role-<?= htmlspecialchars($s['staff_role'] ?? 'instructor', ENT_QUOTES) ?> flex-shrink-0">
                                    <?= ucfirst(htmlspecialchars($s['staff_role'] ?? 'instructor', ENT_QUOTES)) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right column: linked programmes and quick facts. -->
            <div class="col-lg-5">

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
                                    <?php if (!empty($p['is_published'])): ?>
                                        <span class="staff-badge staff-badge--leader">Published</span>
                                    <?php else: ?>
                                        <span class="staff-badge staff-badge--contributor">Draft</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="staff-section-card mt-4">
                    <div class="staff-section-header">
                        <h2 class="staff-section-title">
                            <i class="bi bi-info-circle me-1 text-primary"></i>Quick facts
                        </h2>
                    </div>

                    <dl class="staff-profile-dl p-3">
                        <dt>Year of study</dt>
                        <dd>Year <?= (int)($module['year_of_study'] ?? 1) ?></dd>

                        <dt>Credits</dt>
                        <dd><?= (int)($module['credits'] ?? 20) ?> credits</dd>

                        <dt>Shared across</dt>
                        <dd><?= count($module['programmes']) ?> programme<?= count($module['programmes']) !== 1 ? 's' : '' ?></dd>

                        <dt>Total staff</dt>
                        <dd><?= count($module['staff']) ?> member<?= count($module['staff']) !== 1 ? 's' : '' ?></dd>

                        <dt>You teach this</dt>
                        <dd>
                            <?= $iTeach
                                ? '<span class="text-success fw-semibold">Yes</span>'
                                : '<span class="text-muted">No — view only</span>' ?>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
