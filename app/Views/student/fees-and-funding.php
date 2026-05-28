<?php
$pageTitle = 'Fees and Funding';
include __DIR__ . '/../layout/header.php';
?>
<main id="main-content">
<section class="py-5">
  <div class="container" style="max-width:820px">

    <h1 class="mb-1">Fees &amp; funding</h1>
    <p class="text-muted mb-4">We are committed to making world-class education accessible. Here is an overview of our tuition fees and the funding options available to you.</p>

    <h2 class="h4 mt-4 mb-3">Tuition fees 2026/27</h2>

<div class="table-responsive mb-4">
  <table class="table table-bordered align-middle">
    <caption class="text-muted small">
      Annual tuition fees for home/UK and international students for the 2026/27 academic year.
    </caption>

    <thead class="table-light">
      <tr>
        <th scope="col">Programme level</th>
        <th scope="col">Home / UK students</th>
        <th scope="col">International students</th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <th scope="row">Undergraduate (per year)</th>
        <td>£9,250</td>
        <td>£16,500</td>
      </tr>

      <tr>
        <th scope="row">Postgraduate taught (per year)</th>
        <td>£12,000</td>
        <td>£19,000</td>
      </tr>

      <tr>
        <th scope="row">Postgraduate research (per year)</th>
        <td>£4,786</td>
        <td>£17,000</td>
      </tr>
    </tbody>
  </table>
</div>

<h2 class="h4 mt-4 mb-3">Funding options</h2>

<div class="row g-3 mb-4">
  <div class="col-md-6">
    <div class="card border-0 shadow-sm p-3 h-100">
      <h3 class="h6 fw-bold" id="funding-loans">Government student loans</h3>
      <p class="text-muted small mb-0">
        UK students may be eligible for tuition fee loans and maintenance loans from Student Finance England,
        covering all or part of their costs.
      </p>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card border-0 shadow-sm p-3 h-100">
      <h3 class="h6 fw-bold" id="funding-bursaries">UniHub bursaries</h3>
      <p class="text-muted small mb-0">
        We offer means-tested bursaries of up to £3,000 per year for eligible UK students from lower-income households.
      </p>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card border-0 shadow-sm p-3 h-100">
      <h3 class="h6 fw-bold" id="funding-merit">Merit scholarships</h3>
      <p class="text-muted small mb-0">
        High-achieving students may qualify for merit-based scholarships.
        See our <a href="<?= base_url('/scholarships') ?>" aria-describedby="funding-merit">scholarships page</a>
        for full details.
      </p>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card border-0 shadow-sm p-3 h-100">
      <h3 class="h6 fw-bold" id="funding-postgrad">Postgraduate loans</h3>
      <p class="text-muted small mb-0">
        UK students studying a postgraduate taught or research programme may be eligible for a government postgraduate
        loan of up to £12,167.
      </p>
    </div>
  </div>
</div>

<div class="alert alert-info" role="alert">
  For personalised funding advice, contact 
  <a href="mailto:admissions@unihub.ac.uk" class="alert-link">
    admissions@unihub.ac.uk
  </a>.
</div>

</div>
</section>
</main>

<?php include __DIR__ . '/../layout/footer.php'; ?>