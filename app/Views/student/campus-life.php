<?php
$pageTitle = 'Campus Life';
include __DIR__ . '/../layout/header.php';
?>
<main id="main-content">
<section class="py-5">
  <div class="container" style="max-width:820px">

    <h1 class="mb-1">Campus life</h1>
    <p class="text-muted mb-4">UniHub is more than a place to study — it is a vibrant community where you will grow, make lifelong friends, and build your future.</p>

    <div class="row g-4 mb-5">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 h-100 text-center">
          <div class="fs-1 mb-3">🏛️</div>
          <h2 class="h5">Modern facilities</h2>
          <p class="text-muted small">State-of-the-art labs, a 24/7 library, high-spec computing suites, and collaborative workspaces across our London campus.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 h-100 text-center">
          <div class="fs-1 mb-3">🎭</div>
          <h2 class="h5">Student societies</h2>
          <p class="text-muted small">Over 80 student-led clubs and societies — from coding and entrepreneurship to drama, music, and sports teams.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 h-100 text-center">
          <div class="fs-1 mb-3">🏠</div>
          <h2 class="h5">Accommodation</h2>
          <p class="text-muted small">On-campus and partner halls of residence within easy reach of the university, with 24/7 security and pastoral support.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 h-100 text-center">
          <div class="fs-1 mb-3">🍽️</div>
          <h2 class="h5">Food &amp; social spaces</h2>
          <p class="text-muted small">Multiple cafés, a student union bar, and a food hall with diverse catering options including vegetarian and halal choices.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 h-100 text-center">
          <div class="fs-1 mb-3">🧠</div>
          <h2 class="h5">Wellbeing support</h2>
          <p class="text-muted small">Free counselling, mental health advisors, disability support, and a dedicated student wellbeing hub open throughout the year.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 h-100 text-center">
          <div class="fs-1 mb-3">🌍</div>
          <h2 class="h5">International community</h2>
          <p class="text-muted small">Students from over 90 countries. We celebrate diversity with cultural events, international weeks, and language exchange programmes.</p>
        </div>
      </div>
    </div>

    <div class="alert alert-info">
      Want to see it for yourself? Explore our programmes and register your interest to stay updated.
      <a href="<?= base_url("/") ?>" class="btn btn-primary btn-sm ms-2">Browse programmes</a>
    </div>
  </div>
</section>
</main>
<?php include __DIR__ . '/../layout/footer.php'; ?>