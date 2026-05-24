<?php
$prog = $prog ?? ['id' => 0, 'title' => '', 'level' => '', 'description' => ''];
$pageTitle = htmlspecialchars($prog['title'], ENT_QUOTES);
include __DIR__ . '/../layout/header.php';
?>

<section class="py-5">
  <div class="container">
    <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary mt-2 mb-2">← Back</a>

    <div class="row g-4 align-items-start">
      <!-- Left: Image + Description -->
      <div class="col-lg-6">
        <?php
          $img = $prog['image_url'] ?? '';
          if (!empty($img)) {
            $src = preg_match('#^https?://#i', $img) ? $img : base_url('/' . ltrim($img, '/'));
          }
        ?>
        <?php if (!empty($img) && !empty($src)): ?>
          <img src="<?= htmlspecialchars($src, ENT_QUOTES) ?>"
               class="img-fluid rounded mb-4" alt="<?= htmlspecialchars($prog['title'], ENT_QUOTES) ?> programme image">
        <?php else: ?>
          <div class="d-flex align-items-center justify-content-center rounded mb-4" style="width:100%;height:220px;background-color:#f8f9fa;border:1px dashed #dee2e6;color:#6c757d;">
            No image assigned yet
          </div>
        <?php endif; ?>

        <span class="badge <?= $prog['level'] === 'Undergraduate' ? 'bg-info' : 'bg-warning text-dark' ?> mb-2">
          <?= htmlspecialchars($prog['level'], ENT_QUOTES) ?>
        </span>
        <h1 class="mt-2 mb-1"><?= htmlspecialchars($prog['title'], ENT_QUOTES) ?></h1>
        <?php
          $progLeader = $prog['programme_leader'] ?? $prog['program_leader'] ?? $prog['leader'] ?? $prog['programme_leader_name'] ?? $prog['program_leader_name'] ?? '';
        ?>
        <div class="mb-3 text-muted" style="font-size:.95rem;">
          <strong>Programme leader:</strong>
          <?php if (!empty($progLeader)): ?>
            <?= htmlspecialchars($progLeader, ENT_QUOTES) ?>
          <?php else: ?>
            <span class="text-muted">No programme leader assigned yet</span>
          <?php endif; ?>
        </div>
        <p class="lead mb-3"><?= htmlspecialchars($prog['description'], ENT_QUOTES) ?></p>
        <a href="<?= base_url('/interest/register/' . $prog['id']) ?>" class="btn btn-primary btn-lg mt-2">Register Interest</a>
      </div>

      <!-- Right: Modules by Year -->
      <div class="col-lg-6">
      <?php if (!empty($modulesByYear)): ?>
        <h2 class="mb-3">Modules by Year</h2>
        <div class="accordion" id="modulesAccordion">
          <?php foreach ($modulesByYear as $year => $modules): ?>
            <div class="accordion-item">
              <h3 class="accordion-header" id="heading-year<?= $year ?>">
                <button class="accordion-button <?= $year > 1 ? 'collapsed' : '' ?>" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapse-year<?= $year ?>"
                        aria-expanded="<?= $year === 1 ? 'true' : 'false' ?>"
                        aria-controls="collapse-year<?= $year ?>">
                  Year <?= (int)$year ?>
                </button>
              </h3>
              <div id="collapse-year<?= $year ?>" class="accordion-collapse collapse <?= $year == 1 ? 'show' : '' ?>">
                <div class="accordion-body">
                  <div class="accordion accordion-flush" id="modulesYear<?= (int)$year ?>">
                    <?php foreach ($modules as $index => $m): ?>
                      <?php
                        $moduleId = 'year' . (int)$year . '-module-' . ($m['id'] ?? $index);
                        $moduleHeadingId = 'heading-' . $moduleId;
                        $moduleCollapseId = 'collapse-' . $moduleId;
                      ?>
                      <div class="accordion-item border rounded mb-3 overflow-hidden">
                        <h4 class="accordion-header" id="<?= $moduleHeadingId ?>">
                          <button class="accordion-button collapsed fw-semibold" type="button"
                                  data-bs-toggle="collapse" data-bs-target="#<?= $moduleCollapseId ?>"
                                  aria-expanded="false" aria-controls="<?= $moduleCollapseId ?>">
                            <?= htmlspecialchars($m['title'], ENT_QUOTES) ?>
                          </button>
                        </h4>
                        <div id="<?= $moduleCollapseId ?>" class="accordion-collapse collapse" aria-labelledby="<?= $moduleHeadingId ?>" data-bs-parent="#modulesYear<?= (int)$year ?>">
                          <div class="accordion-body bg-body-tertiary">
                            <div class="row g-3 align-items-start">
                            <?php
                              // prefer the `photo` column used by admin, fall back to other names
                              $mImg = $m['photo'] ?? $m['image_url'] ?? $m['image'] ?? '';
                              if (!empty($mImg)) {
                                // if stored as just filename (admin uses uploads/), mirror admin behaviour
                                $mSrc = preg_match('#^https?://#i', $mImg) ? $mImg : (strpos($mImg, 'uploads/') === 0 ? base_url('/' . ltrim($mImg, '/')) : base_url('/uploads/' . ltrim($mImg, '/')));
                              }
                              $moduleLeader = $m['module_leader'] ?? $m['leader'] ?? $m['leader_name'] ?? $m['module_leader_name'] ?? '';
                            ?>
                              <div class="col-12 mb-3">
                                <?php if (!empty($mImg) && !empty($mSrc)): ?>
                                  <figure class="mb-0 w-100 rounded-top overflow-hidden" style="height:220px;">
                                    <img src="<?= htmlspecialchars($mSrc, ENT_QUOTES) ?>" alt="<?= htmlspecialchars($m['title'] ?? 'Module', ENT_QUOTES) ?> image" class="w-100 h-100" style="object-fit:cover; display:block;">
                                  </figure>
                                <?php else: ?>
                                  <div class="d-flex justify-content-center align-items-center rounded-top bg-light" style="height:220px;">
                                    <span class="text-muted">No Image</span>
                                  </div>
                                <?php endif; ?>
                              </div>
                              <div class="col-12">
                                <h5 class="h6 fw-bold mb-2"><?= htmlspecialchars($m['title'], ENT_QUOTES) ?></h5>
                                <p class="mb-2 text-muted"><?= htmlspecialchars($m['description'] ?? '', ENT_QUOTES) ?></p>
                                <div class="text-muted" style="font-size:.9rem;">
                                  <strong>Module leader:</strong>
                                  <?php if (!empty($moduleLeader)): ?>
                                    <?= htmlspecialchars($moduleLeader, ENT_QUOTES) ?>
                                  <?php else: ?>
                                    <span class="text-muted">No module leader assigned yet</span>
                                  <?php endif; ?>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php if (!empty($staff)): ?>
<section class="py-5 bg-light" aria-labelledby="staff-heading">
  <div class="container">
    <h2 id="staff-heading" class="h4 fw-semibold mb-4">Programme team</h2>
    <div class="row g-4">
      <?php foreach ($staff as $s): ?>
        <div class="col-sm-6 col-lg-4">
          <div class="d-flex flex-column p-4 bg-white rounded-3 border h-100" style="gap:.75rem;">
            <!-- Photo + name row -->
            <div class="d-flex align-items-center gap-3">
              <?php if (!empty($s['photo'])): ?>
                <img src="<?= base_url('/uploads/staff/' . htmlspecialchars($s['photo'], ENT_QUOTES)) ?>"
                     alt="<?= htmlspecialchars($s['full_name'], ENT_QUOTES) ?>"
                     style="width:3.2rem;height:3.2rem;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0;flex-shrink:0;">
              <?php else: ?>
                <div style="width:3.2rem;height:3.2rem;border-radius:50%;background:linear-gradient(135deg,#003366,#00509e);
                            color:#fff;font-size:1.1rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;"
                     aria-hidden="true">
                  <?= mb_strtoupper(mb_substr($s['full_name'], 0, 1)) ?>
                </div>
              <?php endif; ?>
              <div>
                <div class="fw-semibold text-dark" style="font-size:.92rem;">
                  <?= htmlspecialchars($s['full_name'], ENT_QUOTES) ?>
                </div>
                <span class="badge mt-1" style="background:#dbeafe;color:#1d4ed8;font-size:.7rem;font-weight:600;">
                  <?= ucfirst(htmlspecialchars($s['staff_role'] ?? 'instructor', ENT_QUOTES)) ?>
                </span>
              </div>
            </div>
            <!-- Bio -->
            <?php if (!empty($s['bio'])): ?>
              <p class="text-muted mb-0" style="font-size:.82rem;line-height:1.6;">
                <?= htmlspecialchars($s['bio'], ENT_QUOTES) ?>
              </p>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/../layout/footer.php'; ?>