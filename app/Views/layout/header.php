<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- ── Page title: falls back to "UniHub" if $pageTitle not set ── -->
  <title><?= htmlspecialchars($pageTitle ?? 'UniHub', ENT_QUOTES) ?> | UniHub University</title>

  <!-- ── Google Fonts: DM Serif Display (headings) + Plus Jakarta Sans (body) ── -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- ── Bootstrap 5 CSS ── -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

  <!-- ── Bootstrap Icons ── -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- ── Custom styles ── -->
  <link rel="stylesheet" href="<?= base_url('css/custom.css') ?>">
</head>
<body>

<!-- ── Accessibility: skip to main content for keyboard/screen-reader users ── -->
<a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>


<!-- ═══════════════════════════════════════════════════════════════
     TOP UTILITY BAR
     Shows phone and email contact info only.
     Hidden on mobile via CSS (max-width: 767px).
════════════════════════════════════════════════════════════════ -->
<div class="uni-topbar">
  <div class="container d-flex justify-content-between align-items-center">

    <!-- Contact details on the left -->
    <div class="uni-topbar__left">
      <a href="#"><i class="bi bi-telephone me-1"></i>+44 (0)20 1234 5678</a>
      <span class="uni-topbar__sep">|</span>
      <a href="mailto:admissions@unihub.ac.uk"><i class="bi bi-envelope me-1"></i>admissions@unihub.ac.uk</a>
    </div>

    <!-- Right side intentionally empty — sign in & how-to-apply removed from topbar -->

  </div>
</div>


<!-- ═══════════════════════════════════════════════════════════════
     MAIN HEADER / PRIMARY NAVIGATION
     Sticky on scroll. Hides when scrolling down, reappears on scroll up.
     Controlled via JS (classes: .scrolled, .hidden, .visible).
════════════════════════════════════════════════════════════════ -->
<header class="uni-header" id="uni-header" role="banner">
  <nav class="uni-nav" role="navigation" aria-label="Main navigation">
    <div class="container d-flex align-items-center justify-content-between">


      <!-- ── BRAND LOGO ──────────────────────────────────────────── -->
      <a class="uni-brand" href="<?= base_url('/') ?>" aria-label="UniHub University home">
        <!-- Monogram mark -->
        <span class="uni-brand__mark" aria-hidden="true">U</span>
        <!-- Name + tagline -->
        <span class="uni-brand__copy">
          <span class="uni-brand__name">UniHub</span>
          <span class="uni-brand__tag">University</span>
        </span>
      </a>


      <!-- ── DESKTOP NAV LINKS (hidden on tablet/mobile) ──────────── -->
      <ul class="uni-nav__links" role="list">

        <!-- Home — active class applied when on root path -->
        <li>
          <a href="<?= base_url('/') ?>"
             class="uni-nav__link <?= (($_SERVER['REQUEST_URI'] ?? '/') === '/') ? 'active' : '' ?>">
            Home
          </a>
        </li>

        <!-- Programmes — has a megamenu dropdown -->
        <li class="uni-nav__has-drop">
          <button class="uni-nav__link uni-nav__drop-btn"
                  aria-expanded="false"
                  aria-haspopup="true">
            Programmes <i class="bi bi-chevron-down uni-nav__chevron"></i>
          </button>

          <!-- ── Megamenu ──────────────────────────────────────── -->
          <div class="uni-megamenu" role="menu">
            <div class="uni-megamenu__inner">

              <!-- Column 1: Undergraduate -->
              <div class="uni-megamenu__col">
                <div class="uni-megamenu__head">Undergraduate</div>
                <a class="uni-megamenu__link" href="<?= base_url('/?level=Undergraduate') ?>">All UG Programmes</a>
                <a class="uni-megamenu__link" href="<?= base_url('/?level=Undergraduate&search=science') ?>">Science &amp; Technology</a>
                <a class="uni-megamenu__link" href="<?= base_url('/?level=Undergraduate&search=business') ?>">Business &amp; Management</a>
                <a class="uni-megamenu__link" href="<?= base_url('/?level=Undergraduate&search=engineering') ?>">Engineering</a>
                <a class="uni-megamenu__link" href="<?= base_url('/?level=Undergraduate&search=law') ?>">Law &amp; Humanities</a>
              </div>

              <!-- Column 2: Postgraduate -->
              <div class="uni-megamenu__col">
                <div class="uni-megamenu__head">Postgraduate</div>
                <a class="uni-megamenu__link" href="<?= base_url('/?level=Postgraduate') ?>">All PG Programmes</a>
                <a class="uni-megamenu__link" href="<?= base_url('/?level=Postgraduate&search=data') ?>">Data &amp; AI</a>
                <a class="uni-megamenu__link" href="<?= base_url('/?level=Postgraduate&search=cyber') ?>">Cyber Security</a>
                <a class="uni-megamenu__link" href="<?= base_url('/?level=Postgraduate&search=management') ?>">Management &amp; Leadership</a>
                <a class="uni-megamenu__link" href="<?= base_url('/?level=Postgraduate&search=law') ?>">Law &amp; International Studies</a>
              </div>

              <!-- Column 3: Quick CTA links -->
              <div class="uni-megamenu__col uni-megamenu__col--cta">
                <div class="uni-megamenu__head">Quick links</div>
                <a class="uni-megamenu__cta" href="<?= base_url('/') ?>">
                  <i class="bi bi-search"></i>
                  <span>Search all programmes</span>
                </a>
                <a class="uni-megamenu__cta" href="<?= base_url('/how-to-apply') ?>">
                  <i class="bi bi-calendar-event"></i>
                  <span>How to apply</span>
                </a>
                <a class="uni-megamenu__cta" href="<?= base_url('/my-interests') ?>">
                  <i class="bi bi-bookmark-heart"></i>
                  <span>Track your interest</span>
                </a>
              </div>

            </div>
          </div>
          <!-- ── /Megamenu ─────────────────────────────────────── -->

        </li>

        <!-- Standard nav links -->
        <li><a href="<?= base_url('/how-to-apply') ?>"  class="uni-nav__link">How to apply</a></li>
        <li><a href="<?= base_url('/campus-life') ?>"   class="uni-nav__link">Campus life</a></li>
        <li><a href="#contact"                          class="uni-nav__link">Contact</a></li>

        <!-- My Interests — bookmark icon link -->
        <li>
          <a href="<?= base_url('/my-interests') ?>" class="uni-nav__link uni-nav__link--interest">
            <i class="bi bi-bookmark-heart me-1" aria-hidden="true"></i>My interests
          </a>
        </li>

      </ul>
      <!-- ── /Desktop nav links ────────────────────────────────── -->


      <!-- ── ACTIONS: Sign in button + mobile burger ──────────────
           margin-left: auto in CSS pushes this block to the far right
      ─────────────────────────────────────────────────────────── -->
      <div class="uni-nav__actions">

        <!-- Sign in button — slide-fill effect on hover (see CSS) -->
        <a href="<?= base_url('/login') ?>" class="uni-nav__signin" aria-label="Staff and admin sign in">
          <i class="bi bi-person-circle"></i>
          <span>Sign in</span>
        </a>

        <!-- Hamburger — visible on tablet/mobile only -->
        <button class="uni-nav__burger"
                id="navBurger"
                aria-label="Toggle menu"
                aria-expanded="false"
                aria-controls="mobileMenu">
          <span></span>
          <span></span>
          <span></span>
        </button>

      </div>
      <!-- ── /Actions ──────────────────────────────────────────── -->


    </div>
  </nav>
</header>
<!-- ═══ /Main header ════════════════════════════════════════════ -->


<!-- ═══════════════════════════════════════════════════════════════
     MOBILE MENU OVERLAY
     Slides in from the right. Toggled by #navBurger via JS.
     aria-hidden toggled by JS for accessibility.
════════════════════════════════════════════════════════════════ -->
<div class="uni-mobile-menu" id="mobileMenu" aria-hidden="true">
  <div class="uni-mobile-menu__inner">

    <!-- Mobile nav links -->
    <ul>
      <li><a href="<?= base_url('/') ?>">Home</a></li>
      <li><a href="<?= base_url('/?level=Undergraduate') ?>">Undergraduate</a></li>
      <li><a href="<?= base_url('/?level=Postgraduate') ?>">Postgraduate</a></li>
      <li><a href="<?= base_url('/how-to-apply') ?>">How to apply</a></li>
      <li><a href="<?= base_url('/campus-life') ?>">Campus life</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>

    <!-- Mobile CTA buttons at the bottom of the menu -->
    <div class="uni-mobile-menu__actions">
      <a href="<?= base_url('/my-interests') ?>" class="uni-mobile-menu__signin">
        <i class="bi bi-bookmark-heart me-1"></i>My interests
      </a>
      <a href="<?= base_url('/login') ?>" class="uni-mobile-menu__apply" style="background:#e8f0fe;color:#1a56db;">
        <i class="bi bi-person me-1"></i>Staff / Admin
      </a>
    </div>

  </div>
</div>

<!-- Semi-transparent backdrop behind the mobile menu -->
<div class="uni-mobile-overlay" id="mobileOverlay"></div>


<!-- ── Main content starts here ── -->
<main id="main-content">