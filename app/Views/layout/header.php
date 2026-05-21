<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'UniHub', ENT_QUOTES) ?> | UniHub University</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= base_url('css/custom.css') ?>">
</head>
<body>
<a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>

<!-- ── Top utility bar ──────────────────────────────────────────── -->
<div class="uni-topbar">
  <div class="container d-flex justify-content-between align-items-center">
    <div class="uni-topbar__left">
      <a href="#"><i class="bi bi-telephone me-1"></i>+44 (0)20 1234 5678</a>
      <span class="uni-topbar__sep">|</span>
      <a href="mailto:admissions@unihub.ac.uk"><i class="bi bi-envelope me-1"></i>admissions@unihub.ac.uk</a>
    </div>
    <div class="uni-topbar__right">
      <a href="<?= base_url('/my-interests') ?>"><i class="bi bi-bookmark-heart me-1"></i>My interests</a>
      <span class="uni-topbar__sep">|</span>
      <a href="<?= base_url('/login') ?>"><i class="bi bi-person me-1"></i>Sign in</a>
      <span class="uni-topbar__sep">|</span>
      <a href="#open-days"><i class="bi bi-calendar-event me-1"></i>Open Days</a>
    </div>
  </div>
</div>

<!-- ── Main navigation ──────────────────────────────────────────── -->
<header class="uni-header" id="uni-header" role="banner">
  <nav class="uni-nav" role="navigation" aria-label="Main navigation">
    <div class="container d-flex align-items-center justify-content-between">

      <!-- Brand -->
      <a class="uni-brand" href="<?= base_url('/') ?>" aria-label="UniHub University home">
        <span class="uni-brand__mark" aria-hidden="true">U</span>
        <span class="uni-brand__copy">
          <span class="uni-brand__name">UniHub</span>
          <span class="uni-brand__tag">University</span>
        </span>
      </a>

      <!-- Desktop nav links -->
      <ul class="uni-nav__links" role="list">
        <li>
          <a href="<?= base_url('/') ?>" class="uni-nav__link <?= (($_SERVER['REQUEST_URI'] ?? '/') === '/') ? 'active' : '' ?>">Home</a>
        </li>
        <li class="uni-nav__has-drop">
          <button class="uni-nav__link uni-nav__drop-btn" aria-expanded="false" aria-haspopup="true">
            Programmes <i class="bi bi-chevron-down uni-nav__chevron"></i>
          </button>
          <div class="uni-megamenu" role="menu">
            <div class="uni-megamenu__inner">
              <div class="uni-megamenu__col">
                <div class="uni-megamenu__head">Undergraduate</div>
                <a class="uni-megamenu__link" href="<?= base_url('/?level=Undergraduate') ?>">All UG Programmes</a>
                <a class="uni-megamenu__link" href="<?= base_url('/?level=Undergraduate&search=science') ?>">Science &amp; Technology</a>
                <a class="uni-megamenu__link" href="<?= base_url('/?level=Undergraduate&search=business') ?>">Business &amp; Management</a>
                <a class="uni-megamenu__link" href="<?= base_url('/?level=Undergraduate&search=engineering') ?>">Engineering</a>
                <a class="uni-megamenu__link" href="<?= base_url('/?level=Undergraduate&search=law') ?>">Law &amp; Humanities</a>
              </div>
              <div class="uni-megamenu__col">
                <div class="uni-megamenu__head">Postgraduate</div>
                <a class="uni-megamenu__link" href="<?= base_url('/?level=Postgraduate') ?>">All PG Programmes</a>
                <a class="uni-megamenu__link" href="<?= base_url('/?level=Postgraduate&search=data') ?>">Data &amp; AI</a>
                <a class="uni-megamenu__link" href="<?= base_url('/?level=Postgraduate&search=cyber') ?>">Cyber Security</a>
                <a class="uni-megamenu__link" href="<?= base_url('/?level=Postgraduate&search=management') ?>">Management &amp; Leadership</a>
                <a class="uni-megamenu__link" href="<?= base_url('/?level=Postgraduate&search=law') ?>">Law &amp; International Studies</a>
              </div>
              <div class="uni-megamenu__col uni-megamenu__col--cta">
                <div class="uni-megamenu__head">Quick links</div>
                <a class="uni-megamenu__cta" href="<?= base_url('/') ?>">
                  <i class="bi bi-search"></i>
                  <span>Search all programmes</span>
                </a>
                <a class="uni-megamenu__cta" href="#open-days">
                  <i class="bi bi-calendar-event"></i>
                  <span>Book an open day</span>
                </a>
                <a class="uni-megamenu__cta" href="<?= base_url('/my-interests') ?>">
                  <i class="bi bi-bookmark-heart"></i>
                  <span>Track your interest</span>
                </a>
              </div>
            </div>
          </div>
        </li>
        <li><a href="#open-days" class="uni-nav__link">Open Days</a></li>
        <li><a href="#why-unihub" class="uni-nav__link">About</a></li>
        <li><a href="#contact" class="uni-nav__link">Contact</a></li>
        <li>
          <a href="<?= base_url('/my-interests') ?>" class="uni-nav__link uni-nav__link--interest">
            <i class="bi bi-bookmark-heart me-1" aria-hidden="true"></i>My interests
          </a>
        </li>
      </ul>

      <!-- CTA -->
      <div class="uni-nav__actions">
        <a href="<?= base_url('/login') ?>" class="uni-nav__signin" aria-label="Staff and admin sign in">
          <i class="bi bi-person-circle"></i>
        </a>
        <a href="<?= base_url('/') ?>" class="uni-nav__cta">
          Apply now
        </a>
        <!-- Mobile hamburger -->
        <button class="uni-nav__burger" id="navBurger" aria-label="Toggle menu" aria-expanded="false" aria-controls="mobileMenu">
          <span></span><span></span><span></span>
        </button>
      </div>

    </div>
  </nav>
</header>

<!-- ── Mobile menu overlay ──────────────────────────────────────── -->
<div class="uni-mobile-menu" id="mobileMenu" aria-hidden="true">
  <div class="uni-mobile-menu__inner">
    <ul>
      <li><a href="<?= base_url('/') ?>">Home</a></li>
      <li><a href="<?= base_url('/?level=Undergraduate') ?>">Undergraduate</a></li>
      <li><a href="<?= base_url('/?level=Postgraduate') ?>">Postgraduate</a></li>
      <li><a href="#open-days">Open Days</a></li>
      <li><a href="#why-unihub">About</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
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
<div class="uni-mobile-overlay" id="mobileOverlay"></div>

<main id="main-content">