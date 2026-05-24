<?php
$pageTitle = 'Scholarships';
include __DIR__ . '/../layout/header.php';
?>
<main id="main-content">
<section class="py-5">
  <div class="container" style="max-width:820px">

    <h1 class="mb-1">Scholarships</h1>
    <p class="text-muted mb-4">UniHub offers a range of scholarships to reward academic excellence and support students from all backgrounds.</p>

    <div class="row g-4 mb-4">
      <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100">
          <span class="badge bg-primary mb-2">Undergraduate</span>
          <h2 class="h5">Vice-Chancellor Excellence Award</h2>
          <p class="text-muted">Awarded to students achieving AAA or equivalent at A-Level. Covers £2,000 off tuition fees in Year 1.</p>
          <ul class="small text-muted">
            <li>Open to all UG home and international applicants</li>
            <li>Automatically considered on application</li>
            <li>Non-renewable (Year 1 only)</li>
          </ul>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100">
          <span class="badge bg-success mb-2">Undergraduate</span>
          <h2 class="h5">Access &amp; Participation Bursary</h2>
          <p class="text-muted">£1,500 per year for eligible students from underrepresented groups or low-income households.</p>
          <ul class="small text-muted">
            <li>Means-tested — household income under £35,000</li>
            <li>Renewable for each year of study</li>
            <li>Apply via the admissions office</li>
          </ul>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100">
          <span class="badge bg-purple mb-2" style="background:#7c3aed!important">Postgraduate</span>
          <h2 class="h5">UniHub Masters Scholarship</h2>
          <p class="text-muted">£3,000 off postgraduate tuition for applicants with a First Class undergraduate degree.</p>
          <ul class="small text-muted">
            <li>Open to home and international applicants</li>
            <li>One award per programme per cohort</li>
            <li>Automatically considered on application</li>
          </ul>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100">
          <span class="badge bg-warning text-dark mb-2">International</span>
          <h2 class="h5">Global Futures Scholarship</h2>
          <p class="text-muted">£4,000 award for international students demonstrating exceptional academic achievement and leadership potential.</p>
          <ul class="small text-muted">
            <li>Essay and reference required</li>
            <li>Deadline: 1 March each year</li>
            <li>Contact admissions to apply</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="alert alert-info">
      To apply or enquire about scholarships, email <a href="mailto:admissions@unihub.ac.uk">admissions@unihub.ac.uk</a>.
    </div>
  </div>
</section>
</main>
<?php include __DIR__ . '/../layout/footer.php'; ?>