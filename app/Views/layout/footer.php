</main>

<!-- ── Footer ───────────────────────────────────────────────────── -->
<footer class="uni-footer" id="contact" role="contentinfo">
  <div class="uni-footer__top">
    <div class="container">
      <div class="uni-footer__grid">

        <!-- Brand col -->
        <div class="uni-footer__brand">
          <a href="<?= base_url('/') ?>" class="uni-footer__logo" aria-label="UniHub home">
            <span class="uni-footer__mark">U</span>
            <span class="uni-footer__name">UniHub</span>
          </a>
          <p class="uni-footer__desc">Shaping the next generation of thinkers, innovators, and leaders through world-class education.</p>
          <div class="uni-footer__social">
            <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
            <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
          </div>
        </div>

        <!-- Study -->
        <div class="uni-footer__col">
          <div class="uni-footer__col-head">Study with us</div>
          <ul>
            <li><a href="<?= base_url('/?level=Undergraduate') ?>">Undergraduate programmes</a></li>
            <li><a href="<?= base_url('/?level=Postgraduate') ?>">Postgraduate programmes</a></li>
            <li><a href="#open-days">Open days</a></li>
            <li><a href="<?= base_url('/') ?>">How to apply</a></li>
            <li><a href="<?= base_url('/') ?>">Fees &amp; funding</a></li>
            <li><a href="<?= base_url('/') ?>">Scholarships</a></li>
          </ul>
        </div>

        <!-- University -->
        <div class="uni-footer__col">
          <div class="uni-footer__col-head">The university</div>
          <ul>
            <li><a href="#why-unihub">About UniHub</a></li>
            <li><a href="#">Faculties &amp; schools</a></li>
            <li><a href="#">Research</a></li>
            <li><a href="#">Campus life</a></li>
            <li><a href="#">News &amp; events</a></li>
            <li><a href="#">Careers</a></li>
          </ul>
        </div>

        <!-- Contact -->
        <div class="uni-footer__col">
          <div class="uni-footer__col-head">Get in touch</div>
          <ul class="uni-footer__contact">
            <li><i class="bi bi-geo-alt"></i> 1 University Avenue, London, EC1A 1BB</li>
            <li><i class="bi bi-telephone"></i> <a href="tel:+442012345678">+44 (0)20 1234 5678</a></li>
            <li><i class="bi bi-envelope"></i> <a href="mailto:admissions@unihub.ac.uk">admissions@unihub.ac.uk</a></li>
            <li><i class="bi bi-clock"></i> Mon–Fri, 9am–5pm</li>
          </ul>
        </div>

      </div>
    </div>
  </div>

  <div class="uni-footer__bottom">
    <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
      <span>&copy; <?= date('Y') ?> UniHub University. All rights reserved.</span>
      <div class="uni-footer__legal">
        <a href="#">Privacy policy</a>
        <a href="#">Cookie policy</a>
        <a href="#">Accessibility</a>
        <a href="#">Terms of use</a>
      </div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('/js/main.js') ?>"></script>
<script>
// ── Sticky nav scroll behaviour ──────────────────────────────────
(function () {
  const header = document.getElementById('uni-header');
  if (!header) return;
  let lastY = 0;
  const onScroll = () => {
    const y = window.scrollY;
    header.classList.toggle('scrolled', y > 60);
    header.classList.toggle('hidden', y > lastY + 10 && y > 200);
    header.classList.toggle('visible', y < lastY - 5);
    lastY = y;
  };
  window.addEventListener('scroll', onScroll, { passive: true });
})();

// ── Megamenu ────────────────────────────────────────────────────
(function () {
  document.querySelectorAll('.uni-nav__has-drop').forEach(item => {
    const btn = item.querySelector('.uni-nav__drop-btn');
    const open = () => { item.classList.add('open'); btn?.setAttribute('aria-expanded','true'); };
    const close = () => { item.classList.remove('open'); btn?.setAttribute('aria-expanded','false'); };
    item.addEventListener('mouseenter', open);
    item.addEventListener('mouseleave', close);
    btn?.addEventListener('click', () => item.classList.contains('open') ? close() : open());
  });
})();

// ── Mobile menu ─────────────────────────────────────────────────
(function () {
  const burger  = document.getElementById('navBurger');
  const menu    = document.getElementById('mobileMenu');
  const overlay = document.getElementById('mobileOverlay');
  if (!burger || !menu) return;
  const open  = () => { menu.classList.add('open'); overlay.classList.add('open'); burger.classList.add('open'); burger.setAttribute('aria-expanded','true'); document.body.style.overflow='hidden'; };
  const close = () => { menu.classList.remove('open'); overlay.classList.remove('open'); burger.classList.remove('open'); burger.setAttribute('aria-expanded','false'); document.body.style.overflow=''; };
  burger.addEventListener('click', () => menu.classList.contains('open') ? close() : open());
  overlay.addEventListener('click', close);
  menu.querySelectorAll('a').forEach(a => a.addEventListener('click', close));
})();

// ── Counter animation ────────────────────────────────────────────
(function () {
  const counters = document.querySelectorAll('[data-count]');
  if (!counters.length) return;
  const run = (el) => {
    const target = +el.dataset.count;
    const suffix = el.dataset.suffix || '';
    let current = 0;
    const step = Math.ceil(target / 60);
    const tick = () => {
      current = Math.min(current + step, target);
      el.textContent = current.toLocaleString() + suffix;
      if (current < target) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
  };
  const io = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { run(e.target); io.unobserve(e.target); } });
  }, { threshold: 0.5 });
  counters.forEach(c => io.observe(c));
})();
</script>