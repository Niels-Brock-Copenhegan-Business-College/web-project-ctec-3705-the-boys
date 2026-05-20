<?php
// Variables from StaffController::programmeInterests()
$staff      = $staff     ?? [];
$programme  = $programme ?? ['id' => 0, 'title' => '', 'level' => 'Undergraduate'];
$interests  = $interests ?? [];
$flash      = $flash     ?? [];
$pageTitle  = htmlspecialchars(($programme['title'] ?? 'Programme') . ' — Interested Students', ENT_QUOTES);
$count      = count($interests);

// Group by month for the timeline feel
$byMonth = [];
foreach ($interests as $row) {
    $month = date('F Y', strtotime($row['registered_at'] ?? 'now'));
    $byMonth[$month][] = $row;
}
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
        /* ── Page-level styles ──────────────────────────────────── */
        .pi-hero {
            background: linear-gradient(135deg, #003366 0%, #00509e 100%);
            border-radius: 18px;
            padding: 2rem 2rem 1.75rem;
            color: #fff;
            margin-bottom: 1.75rem;
        }
        .pi-hero__back   { font-size: .82rem; opacity: .75; margin-bottom: .6rem; }
        .pi-hero__label  { font-size: .72rem; text-transform: uppercase; letter-spacing: .1em; opacity: .65; margin-bottom: .25rem; }
        .pi-hero__title  { font-size: 1.6rem; font-weight: 700; line-height: 1.2; margin-bottom: .6rem; }
        .pi-hero__level  {
            display: inline-block; padding: .22rem .75rem; border-radius: 20px;
            font-size: .72rem; font-weight: 600; letter-spacing: .04em;
            background: rgba(255,255,255,.15); color: #fff;
        }
        .pi-hero__stats {
            display: flex; gap: 2rem; flex-wrap: wrap; margin-top: 1.25rem;
            padding-top: 1.25rem; border-top: 1px solid rgba(255,255,255,.15);
        }
        .pi-stat-n { font-size: 2rem; font-weight: 700; line-height: 1; }
        .pi-stat-l { font-size: .68rem; opacity: .7; text-transform: uppercase; letter-spacing: .08em; margin-top: .15rem; }

        /* ── Search bar ─────────────────────────────────────────── */
        .pi-search-wrap { position: relative; }
        .pi-search-wrap .bi { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        .pi-search { padding-left: 2.4rem; border-radius: 10px; border: 1px solid #e2e8f0; }
        .pi-search:focus { border-color: #00509e; box-shadow: 0 0 0 3px rgba(0,80,158,.12); }

        /* ── Student card ───────────────────────────────────────── */
        .pi-student-card {
            display: flex; align-items: center; gap: 1rem;
            padding: .9rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            transition: background .12s;
        }
        .pi-student-card:last-child { border-bottom: none; }
        .pi-student-card:hover { background: #f8fafc; }

        .pi-avatar {
            width: 2.75rem; height: 2.75rem; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, #003366, #00509e);
            color: #fff; font-size: .95rem; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
        }
        .pi-student-name  { font-size: .92rem; font-weight: 600; color: #111827; }
        .pi-student-email {
            font-size: .78rem; color: #6b7280;
            display: flex; align-items: center; gap: .35rem;
        }
        .pi-student-date  { font-size: .75rem; color: #94a3b8; white-space: nowrap; margin-left: auto; text-align: right; }

        /* ── Month group header ─────────────────────────────────── */
        .pi-month-header {
            font-size: .7rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .09em; color: #94a3b8;
            padding: .6rem 1.25rem .4rem;
            background: #f8fafc; border-bottom: 1px solid #f1f5f9;
        }

        /* ── Empty state ────────────────────────────────────────── */
        .pi-empty {
            padding: 3.5rem 2rem; text-align: center; color: #94a3b8;
        }
        .pi-empty__icon { font-size: 2.5rem; margin-bottom: .75rem; opacity: .5; }
        .pi-empty__text { font-size: .95rem; }

        /* ── Hidden rows (search) ───────────────────────────────── */
        .pi-student-card.pi-hidden { display: none; }
        .pi-month-group.pi-all-hidden .pi-month-header { display: none; }

        /* ── Summary bar ────────────────────────────────────────── */
        .pi-summary-bar {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: .75rem;
            padding: .9rem 1.25rem;
            background: #f8fafc; border-bottom: 1px solid #f1f5f9;
            border-radius: 14px 14px 0 0;
        }
        .pi-summary-bar__count { font-size: .85rem; color: #64748b; }
        .pi-summary-bar__count strong { color: #111827; }
    </style>
</head>
<body class="staff-body">
<a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>

<?php include __DIR__ . '/partials/navbar.php'; ?>

<main id="main-content" class="staff-main">
<div class="container py-4" style="max-width: 860px;">

    <!-- Hero -->
    <div class="pi-hero">
        <div class="pi-hero__back">
            <a href="<?= base_url('/staff/programmes/' . (int)$programme['id']) ?>"
               class="text-white text-decoration-none opacity-75">
                <i class="bi bi-arrow-left me-1"></i>Back to programme
            </a>
        </div>
        <div class="pi-hero__label">Interested students</div>
        <h1 class="pi-hero__title"><?= htmlspecialchars($programme['title'] ?? '', ENT_QUOTES) ?></h1>
        <span class="pi-hero__level">
            <i class="bi bi-mortarboard me-1"></i>
            <?= htmlspecialchars($programme['level'] ?? '', ENT_QUOTES) ?>
        </span>
        <div class="pi-hero__stats">
            <div>
                <div class="pi-stat-n"><?= $count ?></div>
                <div class="pi-stat-l">Total registered</div>
            </div>
            <?php if ($count > 0):
                $newest = $interests[0]['registered_at'] ?? null; // findByProgramme orders DESC
            ?>
            <div>
                <div class="pi-stat-n" style="font-size:1.1rem;padding-top:.45rem;">
                    <?= $newest ? date('j M Y', strtotime($newest)) : '—' ?>
                </div>
                <div class="pi-stat-l">Most recent sign-up</div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Flash -->
    <?php if (!empty($flash['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <?= htmlspecialchars($flash['error'], ENT_QUOTES) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Main card -->
    <div class="staff-section-card p-0 overflow-hidden">

        <!-- Summary bar + search -->
        <div class="pi-summary-bar">
            <span class="pi-summary-bar__count">
                <strong id="pi-visible-count"><?= $count ?></strong> student<?= $count !== 1 ? 's' : '' ?> registered interest
            </span>
            <?php if ($count > 0): ?>
                <div class="pi-search-wrap" style="width:260px;">
                    <i class="bi bi-search"></i>
                    <input type="search"
                           id="pi-search"
                           class="form-control form-control-sm pi-search"
                           placeholder="Search by name or email…"
                           aria-label="Search students">
                </div>
            <?php endif; ?>
        </div>

        <?php if ($count === 0): ?>
            <div class="pi-empty">
                <div class="pi-empty__icon"><i class="bi bi-person-x"></i></div>
                <div class="pi-empty__text">No students have registered interest in this programme yet.</div>
            </div>
        <?php else: ?>
            <?php foreach ($byMonth as $month => $rows): ?>
                <div class="pi-month-group" data-month="<?= htmlspecialchars($month, ENT_QUOTES) ?>">
                    <div class="pi-month-header">
                        <i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars($month, ENT_QUOTES) ?>
                        <span class="ms-2 fw-normal opacity-75">(<?= count($rows) ?>)</span>
                    </div>
                    <?php foreach ($rows as $student): ?>
                        <?php
                        $initials = mb_strtoupper(
                            mb_substr($student['first_name'] ?? '?', 0, 1) .
                            mb_substr($student['last_name']  ?? '',  0, 1)
                        );
                        $fullName = trim(
                            htmlspecialchars($student['first_name'] ?? '', ENT_QUOTES) . ' ' .
                            htmlspecialchars($student['last_name']  ?? '', ENT_QUOTES)
                        );
                        $email = htmlspecialchars($student['email'] ?? '', ENT_QUOTES);
                        $date  = $student['registered_at']
                            ? date('j M Y, H:i', strtotime($student['registered_at']))
                            : '—';
                        ?>
                        <div class="pi-student-card"
                             data-name="<?= strtolower($fullName) ?>"
                             data-email="<?= strtolower($email) ?>">
                            <div class="pi-avatar" aria-hidden="true"><?= $initials ?></div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="pi-student-name"><?= $fullName ?></div>
                                <div class="pi-student-email">
                                    <i class="bi bi-envelope"></i>
                                    <span><?= $email ?></span>
                                </div>
                            </div>
                            <div class="pi-student-date">
                                <i class="bi bi-clock me-1 opacity-50"></i><?= $date ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

            <!-- No-results message (hidden by default) -->
            <div id="pi-no-results" class="pi-empty" style="display:none;">
                <div class="pi-empty__icon"><i class="bi bi-search"></i></div>
                <div class="pi-empty__text">No students match your search.</div>
            </div>
        <?php endif; ?>

    </div><!-- /.staff-section-card -->

</div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    const searchInput  = document.getElementById('pi-search');
    const visibleCount = document.getElementById('pi-visible-count');
    const noResults    = document.getElementById('pi-no-results');
    if (!searchInput) return;

    searchInput.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        const cards = document.querySelectorAll('.pi-student-card');
        let shown = 0;

        cards.forEach(card => {
            const name  = card.dataset.name  || '';
            const email = card.dataset.email || '';
            const match = !q || name.includes(q) || email.includes(q);
            card.classList.toggle('pi-hidden', !match);
            if (match) shown++;
        });

        // Hide month headers if all their cards are hidden
        document.querySelectorAll('.pi-month-group').forEach(group => {
            const visibleInGroup = group.querySelectorAll('.pi-student-card:not(.pi-hidden)').length;
            group.classList.toggle('pi-all-hidden', visibleInGroup === 0);
        });

        visibleCount.textContent = shown;
        if (noResults) noResults.style.display = shown === 0 && q ? 'block' : 'none';
    });
})();
</script>
</body>
</html>