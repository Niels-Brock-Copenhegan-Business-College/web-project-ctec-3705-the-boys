<?php
$prog = $prog ?? ['id' => 0, 'title' => '', 'level' => '', 'description' => ''];
$pageTitle = htmlspecialchars($prog['title'], ENT_QUOTES);
include __DIR__ . '/../layout/header.php';
?>

<section class="py-5">
  <div class="container">
    <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary mt-2 mb-2">← Back</a>
    <div class="row">
      <div class="col-lg-8">
        <?php if (!empty($prog['image_url'])): ?>
          <?php
            $img = $prog['image_url'];
            $src = preg_match('#^https?://#i', $img) ? $img : base_url('/' . ltrim($img, '/'));
          ?>
          <img src="<?= htmlspecialchars($src, ENT_QUOTES) ?>"
               class="img-fluid rounded mb-4" alt="<?= htmlspecialchars($prog['title'], ENT_QUOTES) ?> programme image">
        <?php endif; ?>
        <span class="badge <?= $prog['level'] === 'Undergraduate' ? 'bg-info' : 'bg-warning text-dark' ?> mb-2">
          <?= htmlspecialchars($prog['level'], ENT_QUOTES) ?>
        </span>
        <h1><?= htmlspecialchars($prog['title'], ENT_QUOTES) ?></h1>
        <p class="lead"><?= htmlspecialchars($prog['description'], ENT_QUOTES) ?></p>
        <a href="<?= base_url('/interest/register/' . $prog['id']) ?>" class="btn btn-primary btn-lg mt-2">Register Interest</a>
      </div>
    </div>

    <?php if (!empty($modulesByYear)): ?>
      <h2 class="mt-5 mb-4">Modules by year</h2>
      <div class="accordion" id="modulesAccordion">

        <?php foreach ($modulesByYear as $year => $modules): ?>
          <div class="accordion-item mb-2 border rounded-3 overflow-hidden">
            <h3 class="accordion-header" id="heading-year<?= $year ?>">
              <button class="accordion-button <?= $year > 1 ? 'collapsed' : '' ?> fw-semibold"
                      type="button"
                      data-bs-toggle="collapse"
                      data-bs-target="#collapse-year<?= $year ?>"
                      aria-expanded="<?= $year === 1 ? 'true' : 'false' ?>"
                      aria-controls="collapse-year<?= $year ?>">
                Year <?= (int)$year ?>
                <span class="badge bg-secondary ms-2 fw-normal" style="font-size:.72rem;">
                  <?= count($modules) ?> module<?= count($modules) !== 1 ? 's' : '' ?>
                </span>
              </button>
            </h3>

            <div id="collapse-year<?= $year ?>"
                 class="accordion-collapse collapse <?= $year == 1 ? 'show' : '' ?>"
                 aria-labelledby="heading-year<?= $year ?>">
              <div class="accordion-body p-0">

                <?php foreach ($modules as $index => $m): ?>
                  <?php
                    $mid      = 'mod-' . (int)($m['id'] ?? $index) . '-yr' . (int)$year;
                    $hasPhoto = !empty($m['photo']);
                    $initials = mb_strtoupper(mb_substr($m['title'], 0, 2));
                    $colours  = ['#003366','#1d4ed8','#0f6e56','#633806','#791f1f','#533ab7'];
                    $bg       = $colours[($m['id'] ?? $index) % count($colours)];
                    $shared   = $m['shared_programmes'] ?? [];
                    $isLast   = $index === array_key_last($modules);
                  ?>
                  <div class="<?= $isLast ? '' : 'border-bottom' ?>">

                    <!-- Module header row (always visible, click to expand) -->
                    <div class="d-flex align-items-center gap-3 px-3 py-3"
                         style="cursor:pointer;"
                         data-bs-toggle="collapse"
                         data-bs-target="#<?= $mid ?>"
                         aria-expanded="false"
                         aria-controls="<?= $mid ?>"
                         role="button">

                      <!-- Thumbnail or initial tile -->
                      <?php if ($hasPhoto): ?>
                        <img src="<?= base_url('/uploads/' . htmlspecialchars($m['photo'], ENT_QUOTES)) ?>"
                             alt=""
                             aria-hidden="true"
                             style="width:3rem;height:3rem;border-radius:.5rem;object-fit:cover;flex-shrink:0;">
                      <?php else: ?>
                        <div aria-hidden="true"
                             style="width:3rem;height:3rem;border-radius:.5rem;background:<?= $bg ?>;
                                    color:#fff;font-size:.85rem;font-weight:700;
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                          <?= htmlspecialchars($initials, ENT_QUOTES) ?>
                        </div>
                      <?php endif; ?>

                      <!-- Title + shared badges -->
                      <div class="flex-grow-1" style="min-width:0;">
                        <div class="fw-semibold" style="font-size:.95rem;">
                          <?= htmlspecialchars($m['title'], ENT_QUOTES) ?>
                        </div>
                        <?php if (!empty($shared)): ?>
                          <div class="d-flex flex-wrap gap-1 mt-1">
                            <?php foreach ($shared as $sp): ?>
                              <a href="<?= base_url('/programmes/' . (int)$sp['id']) ?>"
                                 class="badge text-decoration-none"
                                 style="background:#dbeafe;color:#1e40af;font-size:.68rem;font-weight:500;"
                                 title="Also in <?= htmlspecialchars($sp['title'], ENT_QUOTES) ?>, Year <?= (int)$sp['shared_year'] ?>">
                                Also in: <?= htmlspecialchars($sp['title'], ENT_QUOTES) ?>
                              </a>
                            <?php endforeach; ?>
                          </div>
                        <?php endif; ?>
                      </div>

                      <!-- Chevron -->
                      <i class="bi bi-chevron-down text-muted"
                         style="font-size:.8rem;flex-shrink:0;"
                         aria-hidden="true"></i>
                    </div>

                    <!-- Expanded body -->
                    <div id="<?= $mid ?>" class="collapse">
                      <div class="px-3 pb-4 pt-1" style="padding-left:calc(3rem + 1.75rem + .5rem) !important;">

                        <?php if ($hasPhoto): ?>
                          <img src="<?= base_url('/uploads/' . htmlspecialchars($m['photo'], ENT_QUOTES)) ?>"
                               alt="<?= htmlspecialchars($m['title'], ENT_QUOTES) ?> module image"
                               class="img-fluid rounded-3 mb-3"
                               style="max-height:220px;object-fit:cover;width:100%;">
                        <?php endif; ?>

                        <p class="text-muted mb-2" style="font-size:.9rem;line-height:1.7;">
                          <?= htmlspecialchars($m['description'], ENT_QUOTES) ?>
                        </p>

                        <?php if (!empty($shared)): ?>
                          <p class="mb-0" style="font-size:.8rem;color:#6b7280;">
                            <strong>Shared with:</strong>
                            <?php foreach ($shared as $i => $sp): ?>
                              <a href="<?= base_url('/programmes/' . (int)$sp['id']) ?>"
                                 class="text-decoration-none" style="color:#1d4ed8;">
                                <?= htmlspecialchars($sp['title'], ENT_QUOTES) ?>
                                (Year <?= (int)$sp['shared_year'] ?>)
                              </a><?= $i < count($shared) - 1 ? ', ' : '' ?>
                            <?php endforeach; ?>
                          </p>
                        <?php endif; ?>

                      </div>
                    </div>

                  </div>
                <?php endforeach; ?>

              </div>
            </div>
          </div>
        <?php endforeach; ?>

      </div>
    <?php endif; ?>
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