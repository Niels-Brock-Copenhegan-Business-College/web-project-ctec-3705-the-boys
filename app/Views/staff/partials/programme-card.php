<?php
// Expects $p — programme array with module_count, interest_count, team_count, my_module_count
$p    = $p ?? [];
if (empty($p)) return;
$isUg = ($p['level'] ?? '') === 'Undergraduate';
$detailUrl = base_url('/staff/programmes/' . (int)$p['id']);
?>
<a href="<?= $detailUrl ?>" class="staff-prog-card-link"
   aria-label="View <?= htmlspecialchars($p['title'], ENT_QUOTES) ?>">
<article class="staff-section-card h-100 staff-prog-card">

    <!-- Image or placeholder -->
    <div class="staff-prog-card__image-wrap">
        <?php if (!empty($p['image_url'])): ?>
            <img src="<?= htmlspecialchars($p['image_url'], ENT_QUOTES) ?>"
                 alt="<?= htmlspecialchars($p['title'], ENT_QUOTES) ?>"
                 class="staff-prog-card__image">
        <?php else: ?>
            <div class="staff-prog-card__image-placeholder staff-prog-card__image-placeholder--<?= $isUg ? 'ug' : 'pg' ?>">
                <span class="staff-prog-card__image-initial" aria-hidden="true">
                    <?= mb_strtoupper(mb_substr($p['title'], 0, 1)) ?>
                </span>
            </div>
        <?php endif; ?>
        <span class="staff-prog-card__level-overlay staff-level-<?= $isUg ? 'ug' : 'pg' ?>">
            <?= htmlspecialchars($p['level'], ENT_QUOTES) ?>
        </span>
        <?php if (!$p['is_published']): ?>
            <span class="staff-prog-card__draft-overlay">Draft</span>
        <?php endif; ?>
    </div>

    <div class="p-3">
        <h3 class="staff-prog-card__title">
            <?= htmlspecialchars($p['title'], ENT_QUOTES) ?>
        </h3>
        <?php if (!empty($p['description'])): ?>
            <p class="staff-prog-card__desc">
                <?= htmlspecialchars(mb_strimwidth($p['description'], 0, 100, '…'), ENT_QUOTES) ?>
            </p>
        <?php endif; ?>

        <!-- Stats -->
        <div class="staff-prog-card__stats">
            <div class="staff-prog-card__stat">
                <span class="staff-prog-card__stat-n"><?= (int)($p['module_count'] ?? 0) ?></span>
                <span class="staff-prog-card__stat-l">Modules</span>
            </div>
            <div class="staff-prog-card__stat">
                <span class="staff-prog-card__stat-n"><?= (int)($p['interest_count'] ?? 0) ?></span>
                <span class="staff-prog-card__stat-l">Interested</span>
            </div>
            <div class="staff-prog-card__stat">
                <span class="staff-prog-card__stat-n"><?= (int)($p['team_count'] ?? 0) ?></span>
                <span class="staff-prog-card__stat-l">Team</span>
            </div>
            <div class="staff-prog-card__stat staff-prog-card__stat--highlight">
                <span class="staff-prog-card__stat-n"><?= (int)($p['my_module_count'] ?? 0) ?></span>
                <span class="staff-prog-card__stat-l">I teach</span>
            </div>
        </div>
    </div>

</article>
</a>
