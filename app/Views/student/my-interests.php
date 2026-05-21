<?php
/**
 * student/my-interests.php
 * 
 * Two states:
 *  1. Email lookup form (default)
 *  2. Registration list — shown after valid email submitted
 *
 * Variables injected by AuthController:
 *   $error        string|null
 *   $registrations array|null   — null = form not yet submitted
 *   $email        string        — echo'd back into form on error
 *   $withdrawn    bool          — true if a withdrawal just completed
 */
$pageTitle      = 'My Interests';
$error          = $error          ?? null;
$registrations  = $registrations  ?? null;
$email          = $email          ?? '';
$withdrawn      = $withdrawn      ?? false;
include __DIR__ . '/../layout/header.php';
?>

<section class="mi-page py-5" aria-labelledby="mi-heading">
  <div class="container" style="max-width: 680px;">

    <!-- Page heading -->
    <div class="mi-head mb-4">
      <a href="<?= base_url('/') ?>" class="mi-back">
        <i class="bi bi-arrow-left" aria-hidden="true"></i> Back to programmes
      </a>
      <div class="mi-head__icon" aria-hidden="true">
        <i class="bi bi-bookmark-heart"></i>
      </div>
      <h1 class="mi-head__title" id="mi-heading">My registered interests</h1>
      <p class="mi-head__sub">
        Enter your email address to view and manage the programmes you've registered interest in.
      </p>
    </div>

    <!-- ── Withdrawal confirmation ─────────────────────────── -->
    <?php if ($withdrawn): ?>
      <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert" aria-live="polite">
        <i class="bi bi-check-circle-fill fs-5" aria-hidden="true"></i>
        <div>
          <strong>Interest withdrawn successfully.</strong><br>
          <span class="small">A confirmation has been sent to your email address.</span>
        </div>
      </div>
    <?php endif; ?>

    <!-- ── Email lookup form ───────────────────────────────── -->
    <div class="mi-card mb-4">
      <form method="POST" action="<?= base_url('/my-interests') ?>" novalidate aria-label="Look up your interests by email">

        <?php if ($error): ?>
          <div class="alert alert-danger d-flex align-items-center gap-2 mb-3"
               role="alert" id="mi-error" aria-live="polite">
            <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i>
            <span><?= htmlspecialchars($error, ENT_QUOTES) ?></span>
          </div>
        <?php endif; ?>

        <label class="form-label fw-semibold" for="mi-email">Email address</label>
        <div class="input-group">
          <span class="input-group-text" aria-hidden="true">
            <i class="bi bi-envelope"></i>
          </span>
          <input
            type="email"
            id="mi-email"
            name="email"
            class="form-control <?= $error ? 'is-invalid' : '' ?>"
            value="<?= htmlspecialchars($email, ENT_QUOTES) ?>"
            placeholder="you@example.com"
            autocomplete="email"
            required
            <?= $error ? 'aria-describedby="mi-error"' : '' ?>
          >
          <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-search me-1" aria-hidden="true"></i> Find my interests
          </button>
        </div>
        <p class="form-text mt-2">
          <i class="bi bi-shield-lock me-1" aria-hidden="true"></i>
          We only use this to look up your registrations — no account required.
        </p>

      </form>
    </div>

    <!-- ── Results ─────────────────────────────────────────── -->
    <?php if ($registrations !== null): ?>

      <?php if (empty($registrations)): ?>

        <div class="mi-empty" role="status">
          <i class="bi bi-inbox mi-empty__icon" aria-hidden="true"></i>
          <p class="mi-empty__title">No registrations found</p>
          <p class="mi-empty__sub">
            We couldn't find any interest registrations for
            <strong><?= htmlspecialchars($email, ENT_QUOTES) ?></strong>.
            Double-check your email or
            <a href="<?= base_url('/') ?>">explore our programmes</a>.
          </p>
        </div>

      <?php else: ?>

        <div class="mi-results">
          <div class="mi-results__header">
            <span class="mi-results__count">
              <i class="bi bi-bookmark-check me-1" aria-hidden="true"></i>
              <?= count($registrations) ?> registration<?= count($registrations) !== 1 ? 's' : '' ?>
              found for <strong><?= htmlspecialchars($email, ENT_QUOTES) ?></strong>
            </span>
          </div>

          <ul class="mi-list" aria-label="Your registered interests">
            <?php foreach ($registrations as $r): ?>
              <li class="mi-item">

                <!-- Programme info -->
                <div class="mi-item__info">
                  <span class="mi-item__badge mi-item__badge--<?= $r['level'] === 'Undergraduate' ? 'ug' : 'pg' ?>">
                    <?= htmlspecialchars($r['level'], ENT_QUOTES) ?>
                  </span>
                  <h2 class="mi-item__title">
                    <a href="<?= base_url('/programmes/' . (int)$r['programme_id']) ?>">
                      <?= htmlspecialchars($r['programme_title'], ENT_QUOTES) ?>
                    </a>
                  </h2>
                  <p class="mi-item__date">
                    <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
                    Registered <?= date('j F Y', strtotime($r['registered_at'])) ?>
                  </p>
                </div>

                <!-- Withdraw action -->
                <form method="POST"
                      action="<?= base_url('/my-interests/withdraw') ?>"
                      class="mi-item__action"
                      aria-label="Withdraw interest in <?= htmlspecialchars($r['programme_title'], ENT_QUOTES) ?>">
                  <input type="hidden" name="token"          value="<?= htmlspecialchars($r['withdraw_token'], ENT_QUOTES) ?>">
                  <input type="hidden" name="redirect_email" value="<?= htmlspecialchars($email, ENT_QUOTES) ?>">
                  <button type="submit"
                          class="btn btn-outline-danger btn-sm mi-withdraw-btn"
                          onclick="return confirm('Remove your interest in <?= addslashes(htmlspecialchars($r['programme_title'], ENT_QUOTES)) ?>?')">
                    <i class="bi bi-x-circle me-1" aria-hidden="true"></i> Withdraw
                  </button>
                </form>

              </li>
            <?php endforeach; ?>
          </ul>

          <p class="mi-hint mt-3">
            <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
            Withdrawing removes you from the mailing list for that programme only.
          </p>
        </div>

      <?php endif; ?>

    <?php endif; ?>

  </div>
</section>

<style>
/* ── My interests page ───────────────────────────────────────── */
.mi-back {
  display: inline-flex; align-items: center; gap: .4rem;
  font-size: .83rem; color: #64748b; text-decoration: none;
  margin-bottom: 1.25rem; font-weight: 500;
}
.mi-back:hover { color: #0052cc; }

.mi-head__icon {
  width: 3.2rem; height: 3.2rem; border-radius: 12px;
  background: linear-gradient(135deg, #eff6ff, #dbeafe);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.4rem; color: #0052cc; margin-bottom: 1rem;
}
.mi-head__title { font-size: 1.65rem; font-weight: 700; color: #0d1b2a; margin: 0 0 .4rem; }
.mi-head__sub   { font-size: .9rem; color: #64748b; margin: 0; line-height: 1.6; }

.mi-card {
  background: #fff; border-radius: 16px;
  border: 1px solid #e0e7ef;
  box-shadow: 0 2px 16px rgba(0,20,70,.05);
  padding: 1.5rem 1.75rem;
}

/* Empty state */
.mi-empty {
  text-align: center; padding: 3rem 1rem;
}
.mi-empty__icon  { font-size: 3rem; color: #94a3b8; display: block; margin-bottom: 1rem; }
.mi-empty__title { font-size: 1.1rem; font-weight: 600; color: #374151; margin-bottom: .4rem; }
.mi-empty__sub   { font-size: .88rem; color: #64748b; line-height: 1.6; }

/* Results header */
.mi-results__header {
  margin-bottom: 1rem;
}
.mi-results__count {
  font-size: .88rem; color: #64748b;
}

/* List */
.mi-list {
  list-style: none; margin: 0; padding: 0;
  display: flex; flex-direction: column; gap: .75rem;
}
.mi-item {
  background: #fff; border: 1px solid #e0e7ef;
  border-radius: 14px; padding: 1.1rem 1.25rem;
  display: flex; align-items: center; justify-content: space-between;
  gap: 1rem; box-shadow: 0 1px 6px rgba(0,20,70,.04);
  transition: box-shadow .15s;
}
.mi-item:hover { box-shadow: 0 4px 18px rgba(0,20,70,.09); }
.mi-item__info { flex: 1; min-width: 0; }

.mi-item__badge {
  display: inline-block; font-size: .7rem; font-weight: 700;
  padding: 2px 9px; border-radius: 20px;
  letter-spacing: .04em; text-transform: uppercase;
  margin-bottom: .4rem;
}
.mi-item__badge--ug { background: #eff6ff; color: #1d4ed8; }
.mi-item__badge--pg { background: #faf5ff; color: #7c3aed; }

.mi-item__title {
  font-size: 1rem; font-weight: 600; color: #0d1b2a; margin: 0 0 .25rem;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.mi-item__title a { color: inherit; text-decoration: none; }
.mi-item__title a:hover { color: #0052cc; text-decoration: underline; }

.mi-item__date { font-size: .78rem; color: #94a3b8; margin: 0; }

.mi-item__action { flex-shrink: 0; }
.mi-withdraw-btn { white-space: nowrap; border-radius: 8px; font-size: .82rem; }

.mi-hint { font-size: .8rem; color: #94a3b8; }

/* Mobile */
@media (max-width: 576px) {
  .mi-item { flex-direction: column; align-items: flex-start; }
  .mi-item__action { align-self: flex-start; }
  .mi-item__title { white-space: normal; }
}
</style>

<?php include __DIR__ . '/../layout/footer.php'; ?>