<?php
// ── staff/interests.php ──────────────────────────────────────────
// Shows ALL interest registrations across every programme assigned
// to the currently logged-in staff member.
// Security: the controller JOIN on staff_programmes ensures a staff
// member can ONLY ever see students from their own programmes.
// ─────────────────────────────────────────────────────────────────
$staff         = $staff         ?? [];
$registrations = $registrations ?? [];
$grouped       = $grouped       ?? [];
$flash         = $flash         ?? [];
$totalCount    = count($registrations);
$progCount     = count($grouped);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Interest Registrations | Staff Portal | UniHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('/css/custom.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/css/staff.css') ?>">
</head>
<body class="staff-body">
<a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>

<?php include __DIR__ . '/partials/navbar.php'; ?>

<main id="main-content" class="staff-main">
<div class="container py-4" style="max-width:960px;">

    <!-- ── Page header ──────────────────────────────────────────── -->
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <a href="<?= base_url('/staff') ?>"
               class="text-muted text-decoration-none small d-inline-flex align-items-center gap-1 mb-2">
                <i class="bi bi-arrow-left"></i> Back to dashboard
            </a>
            <h1 class="h4 fw-bold mb-1" style="font-family:'DM Serif Display',serif;color:#0a1f3d;">
                Interest Registrations
            </h1>
            <p class="text-muted small mb-0">
                Students who registered interest in your assigned programmes
            </p>
        </div>
    </div>

    <!-- ── Flash errors ─────────────────────────────────────────── -->
    <?php if (!empty($flash['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <?= htmlspecialchars($flash['error'], ENT_QUOTES) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($totalCount === 0): ?>

        <!-- ── Empty state ──────────────────────────────────────── -->
        <div class="staff-section-card text-center py-5">
            <i class="bi bi-inbox" style="font-size:2.5rem;color:#94a3b8;display:block;margin-bottom:1rem;"></i>
            <p class="fw-semibold mb-1" style="color:#0a1f3d;">No registrations yet</p>
            <p class="small text-muted mb-0">
                No students have registered interest in your programmes yet.
            </p>
        </div>

    <?php else: ?>

        <!-- ── Summary strip ────────────────────────────────────── -->
        <div class="si-summary mb-4">
            <div class="si-summary__stat">
                <span class="si-summary__n"><?= $totalCount ?></span>
                <span class="si-summary__l">Total registrations</span>
            </div>
            <div class="si-summary__divider"></div>
            <div class="si-summary__stat">
                <span class="si-summary__n"><?= $progCount ?></span>
                <span class="si-summary__l">Programme<?= $progCount !== 1 ? 's' : '' ?></span>
            </div>
        </div>

        <!-- ── Search bar ───────────────────────────────────────── -->
        <div class="si-search-wrap mb-4">
            <i class="bi bi-search si-search-icon"></i>
            <input type="search"
                   id="si-search"
                   class="form-control si-search"
                   placeholder="Search by name, email or programme…"
                   aria-label="Search registrations">
        </div>

        <!-- ── Grouped by programme ─────────────────────────────── -->
        <?php foreach ($grouped as $programmeName => $students):
            $progLevel = $students[0]['programme_level'] ?? 'Undergraduate';
            $isUg      = $progLevel === 'Undergraduate';
            $progId    = $students[0]['programme_id']    ?? 0;
        ?>
        <div class="si-group mb-4" data-group>

            <!-- Programme header -->
            <div class="si-group__header">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="si-group__title">
                        <?= htmlspecialchars($programmeName, ENT_QUOTES) ?>
                    </span>
                    <span class="si-level-badge si-level-badge--<?= $isUg ? 'ug' : 'pg' ?>">
                        <?= htmlspecialchars($progLevel, ENT_QUOTES) ?>
                    </span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="si-group__count">
                        <span class="si-group__count-n"><?= count($students) ?></span>
                        student<?= count($students) !== 1 ? 's' : '' ?>
                    </span>
                    <a href="<?= base_url('/staff/programmes/' . (int)$progId . '/interests') ?>"
                       class="si-view-btn" title="View full page for this programme">
                        <i class="bi bi-arrow-up-right-square"></i>
                    </a>
                </div>
            </div>

            <!-- Students list -->
            <div class="si-group__body">
                <?php foreach ($students as $s):
                    $initial  = mb_strtoupper(mb_substr($s['first_name'] ?? '?', 0, 1));
                    $fullName = htmlspecialchars(trim(($s['first_name'] ?? '') . ' ' . ($s['last_name'] ?? '')), ENT_QUOTES);
                    $email    = htmlspecialchars($s['email'] ?? '', ENT_QUOTES);
                    $date     = $s['registered_at'] ? date('j M Y', strtotime($s['registered_at'])) : '—';
                    $time     = $s['registered_at'] ? date('g:i a', strtotime($s['registered_at'])) : '';
                ?>
                <div class="si-student"
                     data-name="<?= strtolower(strip_tags($fullName)) ?>"
                     data-email="<?= strtolower($email) ?>"
                     data-programme="<?= strtolower(htmlspecialchars($programmeName, ENT_QUOTES)) ?>">

                    <!-- Avatar -->
                    <div class="si-avatar" aria-hidden="true"><?= $initial ?></div>

                    <!-- Name + email -->
                    <div class="si-student__info">
                        <div class="si-student__name"><?= $fullName ?></div>
                        <a href="mailto:<?= $email ?>" class="si-student__email">
                            <i class="bi bi-envelope"></i><?= $email ?>
                        </a>
                    </div>

                    <!-- Date -->
                    <div class="si-student__date">
                        <div><?= $date ?></div>
                        <div class="si-student__time"><?= $time ?></div>
                    </div>

                </div>
                <?php endforeach; ?>
            </div>

        </div>
        <?php endforeach; ?>

        <!-- No search results message -->
        <div id="si-no-results" class="staff-section-card text-center py-5" style="display:none;">
            <i class="bi bi-search" style="font-size:2rem;color:#94a3b8;display:block;margin-bottom:.75rem;"></i>
            <p class="text-muted mb-0">No students match your search.</p>
        </div>

    <?php endif; ?>

</div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    const searchInput = document.getElementById('si-search');
    if (!searchInput) return;

    searchInput.addEventListener('input', function () {
        const q      = this.value.trim().toLowerCase();
        const rows   = document.querySelectorAll('.si-student');
        const groups = document.querySelectorAll('[data-group]');
        let totalShown = 0;

        rows.forEach(row => {
            const name  = row.dataset.name  || '';
            const email = row.dataset.email || '';
            const prog  = row.dataset.programme || '';
            const match = !q || name.includes(q) || email.includes(q) || prog.includes(q);
            row.style.display = match ? '' : 'none';
            if (match) totalShown++;
        });

        // Hide entire programme group if all its students are hidden
        groups.forEach(group => {
            const visible = group.querySelectorAll('.si-student:not([style*="display: none"])').length;
            group.style.display = visible === 0 ? 'none' : '';
        });

        const noResults = document.getElementById('si-no-results');
        if (noResults) noResults.style.display = totalShown === 0 && q ? 'block' : 'none';
    });
})();
</script>
</body>
</html>