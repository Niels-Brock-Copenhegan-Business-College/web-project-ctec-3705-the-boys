<?php
// Variables from StaffController::dashboard()
$staff           = $staff           ?? [];
$modules         = $modules         ?? [];
$programmes      = $programmes      ?? [];
$totalInterest   = $totalInterest   ?? 0;
$recentInterests = $recentInterests ?? [];
$draftProgrammes = $draftProgrammes ?? [];
$flash           = $flash           ?? [];

// Profile completeness
$hasPhoto = !empty($staff['photo']);
$hasBio   = !empty($staff['bio']);
$profileComplete = $hasPhoto && $hasBio;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Staff Portal | UniHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('/css/custom.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/css/staff.css') ?>">
</head>
<body class="staff-body">
<a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>

<?php include __DIR__ . '/partials/navbar.php'; ?>

<main id="main-content" class="staff-main">
<div class="container py-4">

    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 fw-semibold mb-0">
                Welcome back, <?= htmlspecialchars($staff['full_name'] ?? 'Staff', ENT_QUOTES) ?>
            </h1>
            <p class="text-muted small mb-0 mt-1">
                <?= date('l, j F Y') ?> &nbsp;&middot;&nbsp;
                <span class="staff-role-badge staff-role-<?= htmlspecialchars($staff['role'] ?? 'instructor', ENT_QUOTES) ?>">
                    <?= ucfirst(htmlspecialchars($staff['role'] ?? 'instructor', ENT_QUOTES)) ?>
                </span>
            </p>
        </div>
    </div>

    <!-- Stat cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="staff-stat-card">
                <div class="staff-stat-number"><?= count($modules) ?></div>
                <div class="staff-stat-label">Modules assigned</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="staff-stat-card staff-stat-card--accent">
                <div class="staff-stat-number"><?= count($programmes) ?></div>
                <div class="staff-stat-label">Programmes linked</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="staff-stat-card">
                <div class="staff-stat-number"><?= $totalInterest ?></div>
                <div class="staff-stat-label">Students interested</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="staff-stat-card <?= count($draftProgrammes) > 0 ? 'staff-stat-card--warn' : '' ?>">
                <div class="staff-stat-number"><?= count($draftProgrammes) ?></div>
                <div class="staff-stat-label">Unpublished programmes</div>
            </div>
        </div>
    </div>

    <!-- Draft warning -->
    <?php if (!empty($draftProgrammes)): ?>
        <div class="alert alert-warning d-flex align-items-start gap-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0 mt-1" aria-hidden="true"></i>
            <div>
                <strong>
                    <?= count($draftProgrammes) === 1 ? '1 programme is' : count($draftProgrammes) . ' programmes are' ?>
                    not visible to students.
                </strong>
                <div class="small mt-1">
                    <?php foreach ($draftProgrammes as $dp): ?>
                        <a href="<?= base_url('/staff/programmes/' . (int)$dp['id']) ?>" class="me-2">
                            <?= htmlspecialchars($dp['title'], ENT_QUOTES) ?>
                        </a>
                    <?php endforeach; ?>
                    — contact an admin to publish.
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Profile completeness nudge -->
    <?php if (!$profileComplete): ?>
        <div class="alert alert-info d-flex align-items-start gap-3 mb-4" role="note">
            <i class="bi bi-person-circle fs-5 flex-shrink-0 mt-1" aria-hidden="true"></i>
            <div>
                <strong>Complete your profile</strong> — students see your info on programme pages.
                <div class="small mt-1">
                    <?php if (!$hasBio): ?><span class="me-2">⚠ No bio added.</span><?php endif; ?>
                    <?php if (!$hasPhoto): ?><span>⚠ No profile photo.</span><?php endif; ?>
                </div>
                <a href="<?= base_url('/staff/profile/edit') ?>" class="btn btn-sm btn-outline-primary mt-2">
                    <i class="bi bi-pencil me-1"></i>Edit profile
                </a>
            </div>
        </div>
    <?php endif; ?>

    <div class="row g-4">

        <!-- Modules -->
        <div class="col-lg-7">
            <div class="staff-section-card">
                <div class="staff-section-header">
                    <h2 class="staff-section-title">My Modules</h2>
                    <span class="badge bg-secondary rounded-pill"><?= count($modules) ?></span>
                </div>

                <?php if (empty($modules)): ?>
                    <div class="staff-empty-state">
                        <p class="text-muted mb-0">No modules assigned yet. Contact an administrator.</p>
                    </div>
                <?php else: ?>
                    <?php
                    $byYear = [];
                    foreach ($modules as $m) {
                        $byYear[(int)$m['year_of_study']][] = $m;
                    }
                    ksort($byYear);
                    ?>
                    <?php foreach ($byYear as $year => $yearModules): ?>
                        <div class="staff-year-group">
                            <div class="staff-year-label">Year <?= $year ?></div>
                            <?php foreach ($yearModules as $m): ?>
                                <a href="<?= base_url('/staff/modules/' . (int)$m['id']) ?>?from=dashboard"
                                   class="staff-module-row"
                                   aria-label="View details for <?= htmlspecialchars($m['title'], ENT_QUOTES) ?>">
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
        </div>

        <!-- Right column -->
        <div class="col-lg-5">

            <!-- Profile -->
            <div class="staff-section-card mb-4">
                <div class="staff-section-header">
                    <h2 class="staff-section-title">My Profile</h2>
                </div>
                <div class="staff-profile-row">
                    <div class="staff-profile-avatar" aria-hidden="true">
                        <?php if (!empty($staff['photo'])): ?>
                            <img src="<?= base_url('/uploads/staff/' . htmlspecialchars($staff['photo'], ENT_QUOTES)) ?>"
                                 alt="Profile photo"
                                 style="width:100%;height:100%;object-fit:cover;">
                        <?php else: ?>
                            <?= mb_strtoupper(mb_substr($staff['full_name'] ?? 'S', 0, 1)) ?>
                        <?php endif; ?>
                    </div>
                    <div>
                        <div class="fw-semibold"><?= htmlspecialchars($staff['full_name'] ?? '', ENT_QUOTES) ?></div>
                        <div class="text-muted small"><?= htmlspecialchars($staff['email'] ?? '', ENT_QUOTES) ?></div>
                        <div class="mt-1">
                            <span class="staff-role-badge staff-role-<?= htmlspecialchars($staff['role'] ?? 'instructor', ENT_QUOTES) ?>">
                                <?= ucfirst(htmlspecialchars($staff['role'] ?? 'instructor', ENT_QUOTES)) ?>
                            </span>
                        </div>
                    </div>
                </div>
                <dl class="staff-profile-dl">
                    <dt>Username</dt>
                    <dd><?= htmlspecialchars($staff['username'] ?? '', ENT_QUOTES) ?></dd>
                    <dt>Member since</dt>
                    <dd><?= date('j F Y', strtotime($staff['created_at'] ?? 'now')) ?></dd>
                </dl>
            </div>

            <!-- Recent interest registrations -->
            <?php if (!empty($recentInterests)): ?>
            <div class="staff-section-card mb-4">
                <div class="staff-section-header">
                    <h2 class="staff-section-title">
                        <i class="bi bi-bell me-1 text-primary"></i>Recent interest
                    </h2>
                    <span class="badge bg-secondary rounded-pill"><?= count($recentInterests) ?></span>
                </div>
              <ul class="list-unstyled mb-0" style="padding:0 .25rem;">
    <?php foreach ($recentInterests as $r): ?>
        <li style="display:flex;align-items:center;gap:.75rem;
                   padding:.7rem .5rem;
                   border-bottom:1px solid #f1f5f9;min-width:0;">

            <!-- Avatar -->
            <div style="width:34px;height:34px;border-radius:50%;
                        background:linear-gradient(135deg,#dbeafe,#bfdbfe);
                        display:flex;align-items:center;justify-content:center;
                        font-size:.75rem;font-weight:700;color:#1d4ed8;flex-shrink:0;">
                <?= mb_strtoupper(mb_substr($r['first_name'], 0, 1)) ?>
            </div>

            <!-- Name + programme -->
            <div style="flex:1;min-width:0;">
                <div style="font-size:.84rem;font-weight:600;
                            overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    <?= htmlspecialchars($r['first_name'] . ' ' . $r['last_name'], ENT_QUOTES) ?>
                </div>
                <div style="font-size:.74rem;color:#64748b;
                            overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    <?= htmlspecialchars($r['programme_title'], ENT_QUOTES) ?>
                </div>
            </div>

            <!-- Date — pulled in from right edge -->
            <div style="font-size:.72rem;color:#94a3b8;flex-shrink:0;
                        white-space:nowrap;padding-right:.25rem;">
                <?= date('j M Y', strtotime($r['registered_at'])) ?>
            </div>

        </li>
    <?php endforeach; ?>
</ul>

<a href="<?= base_url('/staff/interests') ?>" class="staff-view-all staff-view-all--center">See all</a>
            </div>
            <?php endif; ?>

            <!-- Programmes summary -->
            <div class="staff-section-card">
                <div class="staff-section-header">
                    <h2 class="staff-section-title">My Programmes</h2>
                    <a href="<?= base_url('/staff/programmes') ?>" class="staff-view-all">View all &rarr;</a>
                </div>
                <?php if (empty($programmes)): ?>
                    <div class="staff-empty-state">
                        <p class="text-muted mb-0">No programmes linked yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($programmes as $p): ?>
                        <a href="<?= base_url('/staff/programmes/' . (int)$p['id']) ?>" class="staff-programme-row text-decoration-none">
                            <div>
                                <div class="staff-programme-row__title">
                                    <?= htmlspecialchars($p['title'], ENT_QUOTES) ?>
                                </div>
                                <span class="staff-level-badge staff-level-<?= $p['level'] === 'Undergraduate' ? 'ug' : 'pg' ?>">
                                    <?= htmlspecialchars($p['level'], ENT_QUOTES) ?>
                                </span>
                            </div>
                            <span class="staff-arrow">&rarr;</span>
                        </a>
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