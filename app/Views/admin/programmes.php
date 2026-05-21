<?php
$programmes = $programmes ?? [];
$pageTitle = 'Programmes';
include __DIR__ . '/header.php';

$totalProgrammes    = count($programmes);
$publishedProgrammes = 0;
foreach ($programmes as $p) {
  if (!empty($p['is_published'])) {
    $publishedProgrammes++;
  }
}
$draftProgrammes = $totalProgrammes - $publishedProgrammes;

$levels = [];
foreach ($programmes as $programme) {
  $level = trim((string) ($programme['level'] ?? ''));
  if ($level !== '' && !in_array($level, $levels, true)) {
    $levels[] = $level;
  }
}
sort($levels);
?>

<!-- Hero -->
<div class="mb-4 prog-hero">
  <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
    <div>
      <p class="text-uppercase text-muted small mb-2">Programmes Overview</p>
      <h1 class="h2 mb-0">Programmes</h1>
    </div>
    <a href="<?= base_url('/admin/programmes/create') ?>" class="btn btn-light text-dark fw-semibold">
      + New Programme
    </a>
  </div>

  <!-- Stat cards -->
  <div class="prog-stats">
    <span class="stat-badge">
      <strong><?= $totalProgrammes ?></strong> Total Programmes
    </span>
    <span class="stat-badge">
      <strong><?= $publishedProgrammes ?></strong> Published
    </span>
    <span class="stat-badge">
      <strong><?= $draftProgrammes ?></strong> Drafts
    </span>
  </div>
</div>

<!-- Search (outside hero) -->
<div class="row mb-3">
  <div class="col-md-6 col-lg-5">
    <div class="input-group">
      <span class="input-group-text bg-white border-end-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="text-muted" viewBox="0 0 16 16">
          <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.099zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
        </svg>
      </span>
      <input
        type="text"
        id="programmeSearch"
        class="form-control border-start-0 ps-0"
        placeholder="Search by title…"
        autocomplete="off"
        aria-label="Search programmes"
      >
    </div>
  </div>
  <div class="col-md-4 col-lg-3 mt-3 mt-md-0">
    <select id="programmeLevelFilter" class="form-select" aria-label="Filter programmes by level">
      <option value="">All levels</option>
      <?php foreach ($levels as $level): ?>
        <option value="<?= htmlspecialchars($level, ENT_QUOTES) ?>"><?= htmlspecialchars($level, ENT_QUOTES) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
</div>

<!-- Flash messages -->
<?php if (!empty($flash['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert" aria-live="polite">
    <?= htmlspecialchars($flash['success'], ENT_QUOTES) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<!-- Table -->
<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="programmesTable">
        <thead class="table-light">
          <tr>
            <th class="ps-4">Title</th>
            <th>Level</th>
            <th>Status</th>
            <th class="text-end pe-4">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($programmes as $p): ?>
            <tr id="prog-row-<?= $p['id'] ?>" class="programme-row" data-level="<?= htmlspecialchars(strtolower(trim((string) ($p['level'] ?? ''))), ENT_QUOTES) ?>">
              <td class="ps-4">
                <a href="<?= base_url('/admin/programmes/' . $p['id']) ?>" class="text-decoration-none text-dark fw-semibold">
                  <?= htmlspecialchars($p['title'], ENT_QUOTES) ?>
                </a>
                <div class="text-muted small"><?= htmlspecialchars($p['short_description'] ?? '', ENT_QUOTES) ?></div>
              </td>
              <td><?= htmlspecialchars($p['level'], ENT_QUOTES) ?></td>
              <td>
                <select class="form-select form-select-sm status-select" data-id="<?= $p['id'] ?>">
                  <option value="publish" <?= $p['is_published'] ? 'selected' : '' ?>>Published</option>
                  <option value="draft"   <?= !$p['is_published'] ? 'selected' : '' ?>>Draft</option>
                </select>
              </td>
              <td class="text-end pe-4">
                <div class="btn-group">
                  <a href="<?= base_url('/admin/programmes/' . $p['id']) ?>" class="btn btn-sm btn-outline-secondary">View</a>
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle</span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><a class="dropdown-item" href="<?= base_url('/admin/interests/' . $p['id']) ?>">Interests</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('/admin/programmes/' . $p['id'] . '/edit') ?>">Edit</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                      <button type="button" 
                              class="dropdown-item text-danger delete-btn" 
                              data-id="<?= htmlspecialchars($p['id'], ENT_QUOTES) ?>"
                              data-type="programme"
                              data-delete-url="<?= base_url('/admin/programmes/' . $p['id'] . '/delete') ?>"
                              data-title="<?= htmlspecialchars($p['title'], ENT_QUOTES) ?>">
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
    <div id="noProgrammeResults" class="alert alert-info m-3 d-none">No matching programmes found.</div>
  </div>
</div>

<script id="admin-programmes-js"
        src="<?= base_url('/js/admin-programmes.js') ?>"
        data-publish-url-base="<?= base_url('/admin/programmes') ?>"></script>

<?php include __DIR__ . '/footer.php'; ?>