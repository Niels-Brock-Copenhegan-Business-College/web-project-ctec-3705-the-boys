<?php
$prog = $prog ?? ['id' => 0, 'title' => ''];
$success = $success ?? false;
$errors = $errors ?? [];
$pageTitle = 'Register Interest';
include __DIR__ . '/../layout/header.php';
?>

<section class="py-5">
  <div class="container" style="max-width:560px">
 
    <a href="<?= base_url('/programmes/' . (int)$prog['id']) ?>"
       class="d-inline-flex align-items-center gap-1 text-decoration-none text-muted mb-4"
       style="font-size:.85rem; font-weight:500;">
      <i class="bi bi-arrow-left"></i> Back to programme
    </a>

    <h1 class="mb-1">Register Your Interest</h1>
    <p class="text-muted mb-4">Programme: <strong><?= htmlspecialchars($prog['title'] ?? '', ENT_QUOTES) ?></strong></p>

    <?php if ($success): ?>
      <div class="alert alert-success alert-dismissible d-flex align-items-start gap-3 auto-dismiss"
           role="alert" aria-live="polite" id="successAlert">
        <i class="bi bi-check-circle-fill fs-5 flex-shrink-0 mt-1"></i>
        <div>
          <strong>You're registered!</strong><br>
          <span class="small">
            Thank you for your interest in <strong><?= htmlspecialchars($prog['title'] ?? '', ENT_QUOTES) ?></strong>.
            A confirmation has been sent to your email address.
          </span>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <script>
        setTimeout(() => {
          const el = document.getElementById('successAlert');
          if (!el) return;
          el.style.transition = 'opacity .6s';
          el.style.opacity = '0';
          setTimeout(() => el.remove(), 600);
        }, 5000);
      </script>
    <?php else: ?>
      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" role="alert" aria-live="polite">
          <ul class="mb-0">
            <?php foreach ($errors as $e): ?>
              <li><?= htmlspecialchars($e, ENT_QUOTES) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="POST" action="<?= base_url('/interest') ?>" novalidate id="interestForm">
        <input type="hidden" name="programme_id" value="<?= (int)$prog['id'] ?>">

        <div class="mb-3">
          <label for="first_name" class="form-label">First Name</label>
          <input id="first_name" type="text" name="first_name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="last_name" class="form-label">Last Name</label>
          <input id="last_name" type="text" name="last_name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input id="email" type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register Interest</button>
      </form>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>