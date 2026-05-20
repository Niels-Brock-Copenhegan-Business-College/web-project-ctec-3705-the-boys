<?php
$token = $token ?? '';
$reset = $reset ?? null;
$error = $error ?? null;
$success = $success ?? false;
$pageTitle = 'Reset Password';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES) ?> | UniHub</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
<main class="container py-5" style="max-width: 760px;">
  <div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
      <div class="card shadow-sm border-0 overflow-hidden">
        <div class="row g-0">
          <div class="col-md-5 bg-primary text-white p-4 d-flex flex-column justify-content-between">
            <div>
              <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white text-primary fw-bold mb-3" style="width:3rem;height:3rem;">U</div>
              <h1 class="h4 mb-2">Staff password reset</h1>
              <p class="mb-0 text-white-50">Set a new password for your UniHub staff account.</p>
            </div>
            <div class="small text-white-50 mt-4">UniHub Staff Portal</div>
          </div>
          <div class="col-md-7 p-4 p-md-5 bg-white">
            <?php if ($success): ?>
              <div class="text-center py-3">
                <div class="rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center mb-3" style="width:4rem;height:4rem;">
                  <i class="bi bi-check-lg fs-3"></i>
                </div>
                <h2 class="h4 mb-2">Password updated</h2>
                <p class="text-muted mb-4">Your password has been reset successfully. You can now sign in with the new password.</p>
                <a href="<?= base_url('/staff/login') ?>" class="btn btn-primary">Go to staff login</a>
              </div>
            <?php elseif (!$reset): ?>
              <div class="alert alert-danger mb-4" role="alert">
                This reset link is invalid or has expired.
              </div>
              <a href="<?= base_url('/staff/login') ?>" class="btn btn-primary">Back to staff login</a>
            <?php else: ?>
              <div class="mb-4">
                <h2 class="h4 mb-2">Create a new password</h2>
                <p class="text-muted mb-0">Account: <strong><?= htmlspecialchars($reset['full_name'] ?? 'Staff member', ENT_QUOTES) ?></strong></p>
              </div>

              <?php if ($error): ?>
                <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error, ENT_QUOTES) ?></div>
              <?php endif; ?>

              <form method="POST" action="<?= base_url('/staff/reset-password/' . rawurlencode($token)) ?>">
                <div class="mb-3">
                  <label for="password" class="form-label">New password</label>
                  <input type="password" id="password" name="password" class="form-control" minlength="6" required autofocus>
                </div>
                <div class="mb-3">
                  <label for="confirm_password" class="form-label">Confirm password</label>
                  <input type="password" id="confirm_password" name="confirm_password" class="form-control" minlength="6" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Update password</button>
              </form>

              <div class="alert alert-info mt-4 mb-0 small">
                This link expires automatically and can only be used once.
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>