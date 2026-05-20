<?php
$pageTitle = 'Explore Our Programmes';
include __DIR__ . '/../layout/header.php';
$ug  = array_filter($programmes ?? [], fn($p) => $p['level'] === 'Undergraduate');
$pg  = array_filter($programmes ?? [], fn($p) => $p['level'] === 'Postgraduate');
?>

<!-- ══ HERO ══════════════════════════════════════════════════════ -->
<section class="uh-hero" aria-label="Welcome to UniHub">
  <div class="uh-hero__bg" aria-hidden="true">
    <div class="uh-hero__blob uh-hero__blob--1"></div>
    <div class="uh-hero__blob uh-hero__blob--2"></div>
    <div class="uh-hero__grid"></div>
  </div>
  <div class="container uh-hero__inner">
    <div class="uh-hero__content">
      <div class="uh-hero__eyebrow">
        <span class="uh-hero__dot"></span> Applications open for 2026 entry
      </div>
      <h1 class="uh-hero__title">
        Shape your future<br>
        <em>at UniHub</em>
      </h1>
      <p class="uh-hero__sub">
        Discover world-class undergraduate and postgraduate programmes designed to launch outstanding careers. Find yours today.
      </p>

      <!-- Search -->
      <form method="GET" action="<?= base_url('/') ?>" class="uh-search" role="search" aria-label="Search programmes">
        <div class="uh-search__wrap">
          <i class="bi bi-search uh-search__icon" aria-hidden="true"></i>
          <input type="search" name="search" id="heroSearch"
                 class="uh-search__input"
                 placeholder="Search programmes, subjects…"
                 value="<?= htmlspecialchars($search ?? '', ENT_QUOTES) ?>"
                 autocomplete="off">
          <select name="level" class="uh-search__select" aria-label="Filter by level">
            <option value="">All levels</option>
            <option value="Undergraduate" <?= ($level ?? '') === 'Undergraduate' ? 'selected' : '' ?>>Undergraduate</option>
            <option value="Postgraduate"  <?= ($level ?? '') === 'Postgraduate'  ? 'selected' : '' ?>>Postgraduate</option>
          </select>
          <button type="submit" class="uh-search__btn">Search</button>
        </div>
      </form>

      <!-- Quick filters -->
      <div class="uh-hero__tags" aria-label="Quick filters">
        <a href="<?= base_url('/?level=Undergraduate') ?>" class="uh-tag">Undergraduate</a>
        <a href="<?= base_url('/?level=Postgraduate') ?>"  class="uh-tag">Postgraduate</a>
        <a href="<?= base_url('/?search=computer') ?>"     class="uh-tag">Computer Science</a>
        <a href="<?= base_url('/?search=business') ?>"     class="uh-tag">Business</a>
        <a href="<?= base_url('/?search=engineering') ?>"  class="uh-tag">Engineering</a>
      </div>
    </div>

    <!-- Hero stats -->
    <div class="uh-hero__stats" aria-label="University stats">
      <div class="uh-hero__stat">
        <span class="uh-hero__stat-n" data-count="<?= count($programmes ?? []) ?>" data-suffix="+"><?= count($programmes ?? []) ?>+</span>
        <span class="uh-hero__stat-l">Programmes</span>
      </div>
      <div class="uh-hero__stat-div"></div>
      <div class="uh-hero__stat">
        <span class="uh-hero__stat-n" data-count="12000" data-suffix="+">12,000+</span>
        <span class="uh-hero__stat-l">Students</span>
      </div>
      <div class="uh-hero__stat-div"></div>
      <div class="uh-hero__stat">
        <span class="uh-hero__stat-n" data-count="96" data-suffix="%">96%</span>
        <span class="uh-hero__stat-l">Graduate employment</span>
      </div>
      <div class="uh-hero__stat-div"></div>
      <div class="uh-hero__stat">
        <span class="uh-hero__stat-n" data-count="Top" data-suffix="">Top 50</span>
        <span class="uh-hero__stat-l">UK University ranking</span>
      </div>
    </div>
  </div>

  <!-- Scroll indicator -->
  <a href="#programmes" class="uh-hero__scroll" aria-label="Scroll to programmes">
    <i class="bi bi-chevron-down"></i>
  </a>
</section>

<!-- ══ PROGRAMMES ════════════════════════════════════════════════ -->
<section class="uh-programmes" id="programmes" aria-label="Programme listings">
  <div class="container">

    <!-- Section header -->
    <div class="uh-section-head">
      <div>
        <div class="uh-section-head__eyebrow">Our courses</div>
        <h2 class="uh-section-head__title">
          <?php if (!empty($search) || !empty($level)): ?>
            Search results
          <?php else: ?>
            Explore all programmes
          <?php endif; ?>
        </h2>
      </div>
      <?php if (!empty($search) || !empty($level)): ?>
        <a href="<?= base_url('/') ?>" class="uh-clear-btn">
          <i class="bi bi-x-circle me-1"></i>Clear filters
        </a>
      <?php else: ?>
        <div class="uh-level-pills" role="group" aria-label="Filter by level">
          <button class="uh-level-pill active" data-filter="all">All <span><?= count($programmes ?? []) ?></span></button>
          <button class="uh-level-pill" data-filter="Undergraduate">Undergraduate <span><?= count($ug) ?></span></button>
          <button class="uh-level-pill" data-filter="Postgraduate">Postgraduate <span><?= count($pg) ?></span></button>
        </div>
      <?php endif; ?>
    </div>

    <!-- Cards grid -->
    <?php if (empty($programmes)): ?>
      <div class="uh-empty">
        <i class="bi bi-search uh-empty__icon"></i>
        <h3 class="uh-empty__title">No programmes found</h3>
        <p class="uh-empty__sub">Try adjusting your search or <a href="<?= base_url('/') ?>">view all programmes</a>.</p>
      </div>
    <?php else: ?>
      <div class="uh-grid" id="progGrid">
        <?php foreach ($programmes as $p):
          $img = $p['image_url'] ?? '';
          $src = $img ? (preg_match('#^https?://#i', $img) ? $img : base_url('/' . ltrim($img, '/'))) : null;
          $isUG = $p['level'] === 'Undergraduate';
        ?>
          <article class="uh-card" data-level="<?= htmlspecialchars($p['level'], ENT_QUOTES) ?>">
            <a href="<?= base_url('/programmes/' . (int)$p['id']) ?>" class="uh-card__link" tabindex="-1" aria-hidden="true">
              <div class="uh-card__img-wrap">
                <?php if ($src): ?>
                  <img src="<?= htmlspecialchars($src, ENT_QUOTES) ?>"
                       alt="<?= htmlspecialchars($p['title'], ENT_QUOTES) ?>"
                       class="uh-card__img" loading="lazy">
                <?php else: ?>
                  <div class="uh-card__img-placeholder">
                    <i class="bi bi-mortarboard"></i>
                  </div>
                <?php endif; ?>
                <div class="uh-card__img-overlay"></div>
              </div>
            </a>
            <div class="uh-card__body">
              <span class="uh-card__badge uh-card__badge--<?= $isUG ? 'ug' : 'pg' ?>">
                <?= $isUG ? 'UG' : 'PG' ?> · <?= htmlspecialchars($p['level'], ENT_QUOTES) ?>
              </span>
              <h3 class="uh-card__title">
                <a href="<?= base_url('/programmes/' . (int)$p['id']) ?>">
                  <?= htmlspecialchars($p['title'], ENT_QUOTES) ?>
                </a>
              </h3>
              <p class="uh-card__desc">
                <?= htmlspecialchars(mb_substr($p['description'] ?? '', 0, 110), ENT_QUOTES) ?>…
              </p>
              <div class="uh-card__footer">
                <a href="<?= base_url('/programmes/' . (int)$p['id']) ?>" class="uh-card__cta">
                  View programme <i class="bi bi-arrow-right"></i>
                </a>
                <a href="<?= base_url('/interest/register/' . (int)$p['id']) ?>" class="uh-card__interest">
                  <i class="bi bi-heart"></i>
                </a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- ══ WHY UNIHUB ════════════════════════════════════════════════ -->
<section class="uh-why" id="why-unihub" aria-label="Why choose UniHub">
  <div class="container">
    <div class="uh-section-head uh-section-head--center">
      <div>
        <div class="uh-section-head__eyebrow">Why UniHub</div>
        <h2 class="uh-section-head__title">Built for your success</h2>
      </div>
    </div>
    <div class="uh-why__grid">
      <div class="uh-why__card">
        <div class="uh-why__icon"><i class="bi bi-trophy"></i></div>
        <h3>World-class faculty</h3>
        <p>Learn from leading academics and industry professionals who are active in their fields, bringing real-world insight to every lecture.</p>
      </div>
      <div class="uh-why__card">
        <div class="uh-why__icon"><i class="bi bi-globe2"></i></div>
        <h3>Global community</h3>
        <p>Join students from over 90 countries. UniHub's diverse campus creates an environment that prepares you for international careers.</p>
      </div>
      <div class="uh-why__card">
        <div class="uh-why__icon"><i class="bi bi-laptop"></i></div>
        <h3>Modern facilities</h3>
        <p>State-of-the-art labs, libraries, and collaboration spaces give you everything you need to learn, create, and innovate.</p>
      </div>
      <div class="uh-why__card">
        <div class="uh-why__icon"><i class="bi bi-briefcase"></i></div>
        <h3>Career-ready graduates</h3>
        <p>With a 96% graduate employment rate, our dedicated careers service, industry placements, and alumni network open doors from day one.</p>
      </div>
    </div>
  </div>
</section>

<!-- ══ OPEN DAYS ═════════════════════════════════════════════════ -->
<section class="uh-opendays" id="open-days" aria-label="Open days">
  <div class="container">
    <div class="uh-opendays__inner">
      <div class="uh-opendays__content">
        <div class="uh-section-head__eyebrow" style="color:rgba(255,255,255,.6)">Come visit us</div>
        <h2 class="uh-opendays__title">Experience UniHub<br>in person</h2>
        <p class="uh-opendays__sub">Tour our campus, meet current students, talk to programme leaders, and get a real feel for life at UniHub. Open days are free and open to everyone.</p>
        <div class="uh-opendays__dates">
          <div class="uh-openday__item">
            <div class="uh-openday__cal"><span class="uh-openday__day">14</span><span class="uh-openday__mon">Jun</span></div>
            <div><div class="uh-openday__name">Summer Open Day</div><div class="uh-openday__meta">10:00 – 16:00 · Main Campus</div></div>
          </div>
          <div class="uh-openday__item">
            <div class="uh-openday__cal"><span class="uh-openday__day">19</span><span class="uh-openday__mon">Jul</span></div>
            <div><div class="uh-openday__name">Postgraduate Information Day</div><div class="uh-openday__meta">11:00 – 15:00 · Main Campus</div></div>
          </div>
          <div class="uh-openday__item">
            <div class="uh-openday__cal"><span class="uh-openday__day">06</span><span class="uh-openday__mon">Sep</span></div>
            <div><div class="uh-openday__name">Autumn Open Day</div><div class="uh-openday__meta">10:00 – 16:00 · All Campuses</div></div>
          </div>
        </div>
        <a href="<?= base_url('/') ?>" class="uh-opendays__btn">Reserve your place</a>
      </div>
      <div class="uh-opendays__image" aria-hidden="true">
        <div class="uh-opendays__img-inner">
          <div class="uh-opendays__img-card uh-opendays__img-card--1">
            <i class="bi bi-people-fill"></i>
            <span>1,200+<br><small>visitors last year</small></span>
          </div>
          <div class="uh-opendays__img-card uh-opendays__img-card--2">
            <i class="bi bi-star-fill"></i>
            <span>4.9/5<br><small>visitor rating</small></span>
          </div>
          <div class="uh-opendays__img-placeholder">
            <i class="bi bi-building"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══ TESTIMONIAL STRIP ══════════════════════════════════════════ -->
<section class="uh-testimonials" aria-label="Student testimonials">
  <div class="container">
    <div class="uh-section-head uh-section-head--center">
      <div>
        <div class="uh-section-head__eyebrow">Student voices</div>
        <h2 class="uh-section-head__title">Hear from our community</h2>
      </div>
    </div>
    <div class="uh-testi__grid">
      <blockquote class="uh-testi__card">
        <p>"The lecturers genuinely care about your progress. I got a graduate job three months before finishing my degree."</p>
        <footer><strong>Aisha Rahman</strong> · BSc Computer Science, 2025</footer>
      </blockquote>
      <blockquote class="uh-testi__card uh-testi__card--accent">
        <p>"UniHub's industry links are exceptional. My placement year turned into a full-time offer — I never had to apply anywhere else."</p>
        <footer><strong>James O'Sullivan</strong> · BSc Business Management, 2024</footer>
      </blockquote>
      <blockquote class="uh-testi__card">
        <p>"Coming from abroad, I was nervous. The community here made me feel at home instantly. Best decision I've ever made."</p>
        <footer><strong>Priya Nair</strong> · MSc Data Science, 2025</footer>
      </blockquote>
    </div>
  </div>
</section>

<!-- ══ CTA BANNER ═════════════════════════════════════════════════ -->
<section class="uh-cta-banner" aria-label="Apply now">
  <div class="container">
    <div class="uh-cta-banner__inner">
      <div>
        <h2 class="uh-cta-banner__title">Ready to take the next step?</h2>
        <p class="uh-cta-banner__sub">Browse our programmes, register your interest, and start your journey with UniHub today.</p>
      </div>
      <div class="uh-cta-banner__actions">
        <a href="<?= base_url('/') ?>" class="uh-cta-banner__primary">Explore programmes</a>
        <a href="#open-days" class="uh-cta-banner__secondary">Book an open day</a>
      </div>
    </div>
  </div>
</section>

<script>
// ── Client-side level filter pills ──────────────────────────────
(function () {
  const pills = document.querySelectorAll('.uh-level-pill');
  const cards = document.querySelectorAll('.uh-card');
  if (!pills.length) return;
  pills.forEach(pill => {
    pill.addEventListener('click', () => {
      pills.forEach(p => p.classList.remove('active'));
      pill.classList.add('active');
      const f = pill.dataset.filter;
      cards.forEach(c => {
        c.style.display = (f === 'all' || c.dataset.level === f) ? '' : 'none';
      });
    });
  });
})();
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>