<?php
$modules = $modules ?? [];
$pageTitle = 'Modules';
include __DIR__ . '/header.php';

$totalModules = count($modules);
$withDescription = 0;
foreach ($modules as $module) {
  if (trim((string) ($module['description'] ?? '')) !== '') {
    $withDescription++;
  }
}
$withoutDescription = $totalModules - $withDescription;
?>
<!-- Hero -->
<div class="mb-4 prog-hero">
  <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
    <div>
      <p class="text-uppercase text-muted small mb-2">Modules Overview</p>
      <h1 class="h2 mb-0">Modules</h1>
    </div>
    <a href="<?= base_url('/admin/modules/create') ?>" class="btn btn-light text-dark fw-semibold">
      + New Module
    </a>
  </div>

  <div class="prog-stats">
    <span class="stat-badge">
      <strong><?= $totalModules ?></strong> Total Modules
    </span>
    
  </div>
</div>

<div class="row mb-3">
  <div class="col-md-6 col-lg-5">
    <div class="input-group">
      <span class="input-group-text bg-white border-end-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="text-muted" viewBox="0 0 16 16">
          <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.099zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
        </svg>
      </span>
      <input type="text" id="moduleSearch" class="form-control border-start-0 ps-0" placeholder="Search by title..." autocomplete="off" aria-label="Search modules">
    </div>
  </div>
</div>
<?php if (!empty($flash['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert" aria-live="polite">
    <?= htmlspecialchars($flash['success'], ENT_QUOTES) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>
<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="modulesTable">
        <thead class="table-light">
          <tr>
            <th class="ps-4">Title</th>
            <th>Description</th>
            <th class="text-end pe-4">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($modules as $m): ?>
            <tr class="module-row">
              <td class="ps-4">
                <a href="<?= base_url('/admin/modules/' . $m['id']) ?>" class="prog-link text-decoration-none text-dark fw-semibold">
                  <?= htmlspecialchars($m['title'], ENT_QUOTES) ?>
                </a>
              </td>
              <td>
                <div class="text-muted small"><?= htmlspecialchars(substr($m['description'] ?? '', 0, 80) . (strlen($m['description'] ?? '') > 80 ? '...' : ''), ENT_QUOTES) ?></div>
              </td>
              <td class="text-end pe-4">
                <div class="btn-group">
                  <a href="<?= base_url('/admin/modules/' . $m['id']) ?>" class="btn btn-sm btn-outline-secondary">View</a>
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle</span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><a class="dropdown-item" href="<?= base_url('/admin/modules/' . $m['id'] . '/edit') ?>">Edit</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                      <button type="button" 
                              class="dropdown-item text-danger delete-btn" 
                              data-id="<?= htmlspecialchars($m['id'], ENT_QUOTES) ?>"
                              data-type="module"
                              data-delete-url="<?= base_url('/admin/modules/' . $m['id'] . '/delete') ?>"
                              data-title="<?= htmlspecialchars($m['title'], ENT_QUOTES) ?>">
                        Delete
                      </button>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div id="noModuleResults" class="alert alert-info m-3 d-none">No matching modules found.</div>
  </div>
</div>
<script src="<?= base_url('/js/admin-modules.js') ?>"></script>
<?php include __DIR__ . '/footer.php'; ?>