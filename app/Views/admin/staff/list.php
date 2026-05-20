<?php
$pageTitle = 'Staff Management';
include __DIR__ . '/../header.php';

$staff = $staff ?? [];

$totalStaff = is_array($staff) ? count($staff) : 0;
$activeStaff = 0;
foreach ($staff as $member) {
  if (!empty($member['is_active'])) {
    $activeStaff++;
  }
}
$inactiveStaff = $totalStaff - $activeStaff;
?>

<div class="prog-hero">
  <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
    <div>
      <p class="text-uppercase text-muted small mb-2">Staff Overview</p>
      <h1 class="h2 mb-0">Staff Management</h1>
      <p class="mb-0 mt-2 text-white-50">Manage staff accounts, roles, and password resets from one place.</p>
    </div>
    <a href="<?= base_url('/admin/staff/create') ?>" class="btn btn-light text-dark fw-semibold">
      + Add New Staff
    </a>
  </div>

  <div class="prog-stats">
    <span class="stat-badge">
      <strong><?= $totalStaff ?></strong> Total Staff
    </span>
    <span class="stat-badge">
      <strong><?= $activeStaff ?></strong> Active Staff
    </span>
    <span class="stat-badge">
      <strong><?= $inactiveStaff ?></strong> Inactive Staff
    </span>
  </div>
</div>

<?php if (!empty($flash['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
    <?= htmlspecialchars($flash['success']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<?php if (!empty($flash['error'])): ?>
  <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
    <?= htmlspecialchars($flash['error']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <?php if (empty($staff)): ?>
      <div class="p-4">
        <div class="alert alert-info mb-0">No staff members found.</div>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="ps-4">Full Name</th>
              <th>Role</th>
              <th>Status</th>
              <th>Created</th>
              <th class="text-end pe-4">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($staff as $s): ?>
              <?php
                $role = strtolower((string) ($s['role'] ?? 'staff'));
                $roleBadge = match ($role) {
                  'coordinator' => 'text-bg-success',
                  'admin' => 'text-bg-danger',
                  default => 'text-bg-primary',
                };
                $statusBadge = !empty($s['is_active']) ? 'text-bg-success' : 'text-bg-secondary';
              ?>
              <tr>
                <td class="ps-4">
                  <div class="fw-semibold">
                    <a href="<?= base_url('/admin/staff/' . $s['id']) ?>" class="text-decoration-none text-dark">
                      <?= htmlspecialchars($s['full_name']) ?>
                    </a>
                  </div>
                  <div class="text-muted small"><?= htmlspecialchars($s['email'] ?? '') ?></div>
                </td>
                <td>
                  <span class="badge rounded-pill <?= $roleBadge ?>"><?= htmlspecialchars(ucfirst($role)) ?></span>
                </td>
                <td>
                  <span class="badge rounded-pill <?= $statusBadge ?>">
                    <?= !empty($s['is_active']) ? 'Active' : 'Inactive' ?>
                  </span>
                </td>
                <td>
                  <div class="small text-muted"><?= date('d M Y', strtotime($s['created_at'])) ?></div>
                </td>
                <td class="text-end pe-4">
                  <div class="btn-group">
                    <a href="<?= base_url('/admin/staff/' . $s['id']) ?>" class="btn btn-sm btn-outline-secondary">View</a>
                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                      <span class="visually-hidden">Toggle dropdown</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                      <li><a class="dropdown-item" href="<?= base_url('/admin/staff/' . $s['id'] . '/edit') ?>">Edit staff</a></li>
                      <li>
                        <form method="POST" action="<?= base_url('/admin/staff/' . $s['id'] . '/send-password-reset') ?>" class="m-0">
                          <button type="submit" class="dropdown-item" onclick="return confirm('Send a password reset link to this staff member?');">Send reset link</button>
                        </form>
                      </li>
                      <li><hr class="dropdown-divider"></li>
                      <li>
                        <form method="POST" action="<?= base_url('/admin/staff/' . $s['id'] . '/delete') ?>" class="m-0" onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                          <button type="submit" class="dropdown-item text-danger">Delete</button>
                        </form>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>