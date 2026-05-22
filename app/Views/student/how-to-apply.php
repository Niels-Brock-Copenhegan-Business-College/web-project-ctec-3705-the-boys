<?php
$pageTitle = 'How to Apply';
include __DIR__ . '/../layout/header.php';
?>
<main id="main-content">
<section class="py-5">
  <div class="container" style="max-width:820px">

    <h1 class="mb-1">How to apply</h1>
    <p class="text-muted mb-4">Follow these steps to begin your journey at UniHub University.</p>

    <div class="row g-4 mb-5">
      <div class="col-md-6">
        <div class="card h-100 border-0 shadow-sm p-4">
          <div class="fs-2 mb-3">🔍</div>
          <h2 class="h5">Step 1 — Explore programmes</h2>
          <p class="text-muted">Browse our undergraduate and postgraduate programmes. Use the search and filter tools to find the right course for you.</p>
          <a href="<?= base_url("/") ?>" class="btn btn-outline-primary btn-sm mt-auto">Browse programmes</a>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card h-100 border-0 shadow-sm p-4">
          <div class="fs-2 mb-3">💌</div>
          <h2 class="h5">Step 2 — Register your interest</h2>
          <p class="text-muted">Found a programme you like? Register your interest to receive updates about open days, deadlines, and entry requirements.</p>
          <a href="<?= base_url("/my-interests") ?>" class="btn btn-outline-primary btn-sm mt-auto">My interests</a>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card h-100 border-0 shadow-sm p-4">
          <div class="fs-2 mb-3">📋</div>
          <h2 class="h5">Step 3 — Check entry requirements</h2>
          <p class="text-muted">Each programme has specific academic requirements. Review these carefully on the programme detail page before applying.</p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card h-100 border-0 shadow-sm p-4">
          <div class="fs-2 mb-3">📝</div>
          <h2 class="h5">Step 4 — Submit your application</h2>
          <p class="text-muted">Undergraduate applications are submitted through UCAS. Postgraduate applications are made directly to the university via our admissions team.</p>
        </div>
      </div>
    </div>

    <div class="alert alert-info">
      <strong>Need help?</strong> Contact our admissions team at
      <a href="mailto:admissions@unihub.ac.uk">admissions@unihub.ac.uk</a>
      or call <a href="tel:+442012345678">+44 (0)20 1234 5678</a>, Mon–Fri 9am–5pm.
    </div>
  </div>
</section>
</main>
<?php include __DIR__ . '/../layout/footer.php'; ?>