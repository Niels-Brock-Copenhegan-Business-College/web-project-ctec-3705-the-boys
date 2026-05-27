<?php
$staff = $staff ?? null;
$pageTitle = $staff ? 'Edit Staff' : 'Add Staff';
$action = $staff ? base_url('/admin/staff/' . $staff['id']) : base_url('/admin/staff');
include __DIR__ . '/../header.php';

$errors = $errors ?? [];
$photoSrc = !empty($staff['photo']) ? base_url('/uploads/staff/' . ltrim($staff['photo'], '/')) : base_url('/uploads/admin-avatar.png');
?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="row g-0 align-items-stretch">
          <div class="col-md-5 bg-primary bg-gradient text-white p-4 d-flex flex-column justify-content-center">
            <h2 class="h4 fw-bold mb-2"><?= $staff ? 'Edit Staff' : 'Create New Staff' ?></h2>
            <p class="mb-3 opacity-75">Manage staff account details and login access.</p>
            <div class="rounded-3 border border-2 border-white/20 bg-dark bg-opacity-10 p-3 shadow-sm">
              <div class="small text-white-50 mb-2">Account Preview</div>
              <div class="d-flex align-items-center gap-3 mb-3">
                <img src="<?= htmlspecialchars($photoSrc, ENT_QUOTES) ?>" alt="Staff photo" class="rounded-circle border border-2 border-white shadow-sm" style="width:72px; height:72px; object-fit:cover;">
                <div class="small text-white-50">
                  <div class="fw-semibold text-white mb-1"><?= htmlspecialchars($staff['full_name'] ?? 'New Staff Member', ENT_QUOTES) ?></div>
                  <div><?= $staff && !empty($staff['photo']) ? 'Current profile picture' : 'No profile picture yet' ?></div>
                </div>
              </div>
              <div class="fw-semibold mb-1"><?= htmlspecialchars($staff['full_name'] ?? 'New Staff Member', ENT_QUOTES) ?></div>
              <div class="text-white-50 mb-3"><?= htmlspecialchars($staff['username'] ?? 'username', ENT_QUOTES) ?></div>
              <div class="d-flex flex-wrap gap-2">
                <span class="badge bg-light text-dark">Staff</span>
                <span class="badge bg-light text-dark"><?= $staff ? 'Editing' : 'New' ?></span>
                <span class="badge bg-light text-dark"><?= (!empty($staff) && !empty($staff['is_active'])) ? 'Active' : 'Access pending' ?></span>
              </div>
            </div>
          </div>

          <div class="col-md-7 p-4 bg-white">
            <?php if (!empty($errors)): ?>
              <div class="alert alert-danger">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                  <?php foreach ($errors as $field => $msg): ?>
                    <li><?= htmlspecialchars($msg, ENT_QUOTES) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>

            <form method="POST" action="<?= $action ?>" class="row g-3" id="staff-form">
              <?= csrf_field() ?>

              <div class="col-12">
                <label for="username" class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-lg shadow-sm <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
                       id="username" name="username" value="<?= htmlspecialchars($staff['username'] ?? $_POST['username'] ?? '', ENT_QUOTES) ?>"
                       <?= $staff ? 'readonly' : '' ?> required>
                <?php if (isset($errors['username'])): ?>
                  <div class="invalid-feedback"><?= htmlspecialchars($errors['username'], ENT_QUOTES) ?></div>
                <?php endif; ?>
              </div>

              <div class="col-12 mt-2">
                <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>"
                       id="full_name" name="full_name" value="<?= htmlspecialchars($staff['full_name'] ?? $_POST['full_name'] ?? '', ENT_QUOTES) ?>" required>
                <?php if (isset($errors['full_name'])): ?>
                  <div class="invalid-feedback"><?= htmlspecialchars($errors['full_name'], ENT_QUOTES) ?></div>
                <?php endif; ?>
              </div>

              <div class="col-12 mt-2">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                       id="email" name="email" value="<?= htmlspecialchars($staff['email'] ?? $_POST['email'] ?? '', ENT_QUOTES) ?>" required>
                <?php if (isset($errors['email'])): ?>
                  <div class="invalid-feedback"><?= htmlspecialchars($errors['email'], ENT_QUOTES) ?></div>
                <?php endif; ?>
              </div>

              <div class="col-12 mt-2">
                <label for="password" class="form-label">
                  Password <?= !$staff ? '<span class="text-danger">*</span>' : '<small class="text-muted">(leave blank to keep current)</small>' ?>
                </label>
                <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                       id="password" name="password" <?= !$staff ? 'required' : '' ?>>
                <?php if (isset($errors['password'])): ?>
                  <div class="invalid-feedback"><?= htmlspecialchars($errors['password'], ENT_QUOTES) ?></div>
                <?php endif; ?>
              </div>

              <div class="col-12 mt-2">
                <label for="confirm_password" class="form-label">
                  Confirm Password <?= !$staff ? '<span class="text-danger">*</span>' : '' ?>
                </label>
                <input type="password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>"
                       id="confirm_password" name="confirm_password" <?= !$staff ? 'required' : '' ?>>
                <?php if (isset($errors['confirm_password'])): ?>
                  <div class="invalid-feedback"><?= htmlspecialchars($errors['confirm_password'], ENT_QUOTES) ?></div>
                <?php endif; ?>
              </div>

              <div class="col-12 mt-2">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                         <?= (empty($staff) || $staff['is_active'] ?? false) ? 'checked' : '' ?>>
                  <label class="form-check-label" for="is_active">Active (staff can log in)</label>
                </div>
              </div>

              <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                <a href="<?= base_url('/admin/staff') ?>" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                  <?= $staff ? 'Update Staff' : 'Create Staff' ?>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
