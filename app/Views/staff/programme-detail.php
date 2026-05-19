<?php
// Variables from StaffController::programmeDetail()
$staff     = $staff     ?? [];
$programme = $programme ?? ['title'=>'','level'=>'Undergraduate','description'=>'','image_url'=>null,'is_published'=>0,'staff'=>[],'modulesByYear'=>[],'interest_count'=>0];
$isUg      = ($programme['level'] ?? '') === 'Undergraduate';
$pageTitle = htmlspecialchars($programme['title'] ?? 'Programme', ENT_QUOTES);

// ── FIX: normalise image_url through base_url() so it resolves correctly ──
// DB stores 'uploads/programmes/...' (no leading slash on most rows).
// Without base_url() the CSS background-image URL is relative and breaks
// on nested routes like /staff/programmes/3.
$heroImageUrl = '';
if (!empty($programme['image_url'])) {
    $heroImageUrl = base_url('/' . ltrim($programme['image_url'], '/'));
}

$totalModules = array_sum(array_map('count', $programme['modulesByYear']));
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
        /* ── Extra polish ──────────────────────────────────── */
        .prog-hero {
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            padding: 2.5rem;
            background: linear-gradient(135deg, #003366 0%, #00509e 100%);
            color: #fff;
            margin-bottom: 1.75rem;
        }
        .prog-hero--with-image {
            background-size: cover;
            background-position: center;
        }
        .prog-hero__overlay {
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(0,30,70,.88), rgba(0,60,130,.80));
        }
        .prog-hero__content { position: relative; z-index: 1; }
        .prog-hero__level {
            display: flex; align-items: center; gap: .6rem; margin-bottom: .9rem; flex-wrap: wrap;
        }
        .prog-hero__badge {
            display: inline-block; padding: .25rem .75rem; border-radius: 20px;
            font-size: .72rem; font-weight: 600; letter-spacing: .04em;
        }
        .prog-hero__badge--level { background: rgba(255,255,255,.18); color: #fff; }
        .prog-hero__badge--draft { background: rgba(255,200,0,.22); color: #ffe066; }
        .prog-hero__badge--pub   { background: rgba(34,197,94,.22);  color: #86efac; }
        .prog-hero__title { font-size: 1.7rem; font-weight: 700; line-height: 1.2; margin-bottom: .75rem; }
        .prog-hero__desc  { font-size: .9rem; opacity: .85; line-height: 1.65; max-width: 640px; margin-bottom: 1.25rem; }
        .prog-hero__stats { display: flex; gap: 2rem; flex-wrap: wrap; }
        .prog-hero__stat-n { font-size: 1.8rem; font-weight: 700; line-height: 1; }
        .prog-hero__stat-l { font-size: .68rem; opacity: .72; text-transform: uppercase; letter-spacing: .07em; margin-top: .1rem; }

        .module-row-thumb {
            width: 40px; height: 40px; border-radius: 8px;
            object-fit: cover; flex-shrink: 0;
        }
        .module-row-thumb-placeholder {
            width: 40px; height: 40px; border-radius: 8px; flex-shrink: 0;
            background: linear-gradient(135deg,#e0e7ef,#c7d4e8);
            display: flex; align-items: center; justify-content: center;
            font-size: .8rem; font-weight: 700; color: #64748b;
        }
        .staff-module-row { display: flex; align-items: center; gap: .75rem; }
    </style>
</head>
<body class="staff-body">
<a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>

<?php include __DIR__ . '/partials/navbar.php'; ?>

<main id="main-content" class="staff-main">
<div class="container py-4">

    <!-- Back button -->
    <a href="<?= htmlspecialchars($backUrl ?? base_url('/staff/programmes'), ENT_QUOTES) ?>"
       class="btn btn-sm btn-outline-secondary mb-4">
        <?= htmlspecialchars($backLabel ?? '← Back to my programmes', ENT_QUOTES) ?>
    </a>

    <!-- ── Hero ──────────────────────────────────────────────────── -->
    <div class="prog-hero<?= $heroImageUrl ? ' prog-hero--with-image' : '' ?>"
         <?= $heroImageUrl ? 'style="background-image:url(' . htmlspecialchars($heroImageUrl, ENT_QUOTES) . ');"' : '' ?>>
        <?php if ($heroImageUrl): ?><div class="prog-hero__overlay"></div><?php endif; ?>
        <div class="prog-hero__content">
            <div class="prog-hero__level">
                <span class="prog-hero__badge prog-hero__badge--level">
                    <i class="bi bi-mortarboard me-1"></i>
                    <?= htmlspecialchars($programme['level'] ?? '', ENT_QUOTES) ?>
                </span>
                <?php if ($programme['is_published']): ?>
                    <span class="prog-hero__badge prog-hero__badge--pub">
                        <i class="bi bi-eye me-1"></i>Published
                    </span>
                <?php else: ?>
                    <span class="prog-hero__badge prog-hero__badge--draft">
                        <i class="bi bi-pencil me-1"></i>Draft — not visible to students
                    </span>
                <?php endif; ?>
            </div>
            <h1 class="prog-hero__title"><?= htmlspecialchars($programme['title'] ?? '', ENT_QUOTES) ?></h1>
            <p class="prog-hero__desc"><?= htmlspecialchars($programme['description'] ?? '', ENT_QUOTES) ?></p>
            <div class="prog-hero__stats">
                <div>
                    <div class="prog-hero__stat-n"><?= $totalModules ?></div>
                    <div class="prog-hero__stat-l">Modules</div>
                </div>
                <div>
                    <div class="prog-hero__stat-n"><?= count($programme['staff']) ?></div>
                    <div class="prog-hero__stat-l">Team members</div>
                </div>
                <div>
                    <div class="prog-hero__stat-n"><?= (int)$programme['interest_count'] ?></div>
                    <div class="prog-hero__stat-l">Students interested</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <!-- ── Modules by year ───────────────────────────────────── -->
        <div class="col-lg-7">
            <div class="staff-section-card">
                <div class="staff-section-header">
                    <h2 class="staff-section-title">
                        <i class="bi bi-journals me-1 text-primary"></i> Modules
                    </h2>
                    <span class="badge bg-secondary rounded-pill"><?= $totalModules ?></span>
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
                                <?php
                                // Thumbnail for this module (if it has a photo)
                                $thumbUrl = '';
                                if (!empty($m['photo'])) {
                                    $thumbUrl = base_url('/uploads/' . htmlspecialchars($m['photo'], ENT_QUOTES));
                                }
                                $initial = mb_strtoupper(mb_substr($m['title'], 0, 1));
                                ?>
                                <a href="<?= base_url('/staff/modules/' . (int)$m['id']) ?>"
                                   class="staff-module-row"
                                   aria-label="View <?= htmlspecialchars($m['title'], ENT_QUOTES) ?>">
                                    <!-- Thumbnail -->
                                    <?php if ($thumbUrl): ?>
                                        <img src="<?= $thumbUrl ?>"
                                             alt=""
                                             class="module-row-thumb"
                                             onerror="this.outerHTML='<div class=\'module-row-thumb-placeholder\'><?= $initial ?></div>'">
                                    <?php else: ?>
                                        <div class="module-row-thumb-placeholder"><?= $initial ?></div>
                                    <?php endif; ?>
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

        <!-- ── Team ─────────────────────────────────────────────── -->
        <div class="col-lg-5">
            <div class="staff-section-card">
                <div class="staff-section-header">
                    <h2 class="staff-section-title">
                        <i class="bi bi-people me-1 text-primary"></i> Programme team
                    </h2>
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

            <!-- Quick facts -->
            <div class="staff-section-card mt-4">
                <div class="staff-section-header">
                    <h2 class="staff-section-title"><i class="bi bi-info-circle me-1 text-primary"></i> Quick facts</h2>
                </div>
                <dl class="staff-profile-dl p-3">
                    <dt>Programme level</dt>
                    <dd><?= htmlspecialchars($programme['level'] ?? '', ENT_QUOTES) ?></dd>
                    <dt>Total modules</dt>
                    <dd><?= $totalModules ?> across <?= count($programme['modulesByYear']) ?> year<?= count($programme['modulesByYear']) !== 1 ? 's' : '' ?></dd>
                    <dt>Team size</dt>
                    <dd><?= count($programme['staff']) ?> staff member<?= count($programme['staff']) !== 1 ? 's' : '' ?></dd>
                    <dt>Student interest</dt>
                    <dd><?= (int)$programme['interest_count'] ?> registered</dd>
                    <dt>Visibility</dt>
                    <dd><?= $programme['is_published'] ? '<span class="text-success fw-semibold">Published</span>' : '<span class="text-warning fw-semibold">Draft</span>' ?></dd>
                </dl>
            </div>
        </div>

    </div>
</div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>