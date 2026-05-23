<?php
$pageTitle      = 'My Interests';
$error          = $error          ?? null;
$registrations  = $registrations  ?? null;
$email          = $email          ?? '';
$withdrawn      = $withdrawn      ?? false;
include __DIR__ . '/../layout/header.php';
?>

<section class="mi-page py-5" aria-labelledby="mi-heading">
  <div class="container" style="max-width: 680px;">

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

    <!-- ── Withdrawal confirmation (auto-dismisses after 5s) ── -->
    <?php if ($withdrawn): ?>
      <div class="alert alert-success alert-dismissible d-flex align-items-start gap-3 mb-4"
           role="alert" aria-live="polite" id="withdrawAlert">
        <i class="bi bi-check-circle-fill fs-5 flex-shrink-0 mt-1" aria-hidden="true"></i>
        <div>
          <strong>Interest withdrawn successfully.</strong><br>
          <span class="small">A confirmation has been sent to your email address.</span>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <script>
        setTimeout(() => {
          const el = document.getElementById('withdrawAlert');
          if (!el) return;
          el.style.transition = 'opacity .6s';
          el.style.opacity = '0';
          setTimeout(() => el.remove(), 600);
        }, 5000);
      </script>
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
                <div class="mi-item__info">
                  <span class="mi-item__badge mi-item__badge--<?= ($r['level'] ?? '') === 'Undergraduate' ? 'ug' : 'pg' ?>">
                    <?= htmlspecialchars($r['level'] ?? '', ENT_QUOTES) ?>
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

<?php include __DIR__ . '/../layout/footer.php'; ?>