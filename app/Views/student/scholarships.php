<<?php
$pageTitle = 'Scholarships';
include __DIR__ . '/../layout/header.php';
?>
<main id="main-content">
<section class="py-5">
  <div class="container" style="max-width:820px">

    <h1 class="mb-1">Scholarships</h1>
    <p class="text-muted mb-4">
      UniHub offers a range of scholarships designed to recognise academic excellence and support students from all backgrounds.
    </p>

    <div class="row g-4 mb-4">
      <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100">
          <span class="badge bg-primary mb-2">Undergraduate</span>
          <h2 class="h5">Vice-Chancellor Excellence Award</h2>
          <p class="text-muted">
            Awarded to students achieving AAA or equivalent at A-Level. Provides £2,000 off tuition fees in Year 1.
          </p>
          <ul class="small text-muted">
            <li>Open to all undergraduate home and international applicants</li>
            <li>Automatically assessed during application</li>
            <li>Non-renewable (Year 1 only)</li>
          </ul>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100">
          <span class="badge bg-success mb-2">Undergraduate</span>
          <h2 class="h5">Access &amp; Participation Bursary</h2>
          <p class="text-muted">
            £1,500 per year for eligible students from underrepresented groups or low-income households.
          </p>
          <ul class="small text-muted">
            <li>Means-tested — household income below £35,000</li>
            <li>Renewable each year of study</li>
            <li>Apply through the admissions office</li>
          </ul>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100">
          <span class="badge bg-purple mb-2" style="background:#7c3aed!important">Postgraduate</span>
          <h2 class="h5">UniHub Masters Scholarship</h2>
          <p class="text-muted">
            £3,000 reduction in postgraduate tuition fees for applicants with a First Class undergraduate degree.
          </p>
          <ul class="small text-muted">
            <li>Open to home and international applicants</li>
            <li>One award per programme per cohort</li>
            <li>Automatically assessed during application</li>
          </ul>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100">
          <span class="badge bg-warning text-dark mb-2">International</span>
          <h2 class="h5">Global Futures Scholarship</h2>
          <p class="text-muted">
            £4,000 award for international students demonstrating exceptional academic achievement and leadership potential.
          </p>
          <ul class="small text-muted">
            <li>Essay and academic reference required</li>
            <li>Deadline: 1 March each year</li>
            <li>Apply through the admissions team</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="alert alert-info">
      For scholarship applications or enquiries, email
      <a href="mailto:admissions@unihub.ac.uk">admissions@unihub.ac.uk</a>.
    </div>

  </div>
</section>
</main>
<?php include __DIR__ . '/../layout/footer.php'; ?>