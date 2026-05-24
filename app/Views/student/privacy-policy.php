<?php $pageTitle = 'Privacy Policy'; include __DIR__ . '/../layout/header.php';?>
<section class="py-5"><div class="container" style="max-width:820px">
  <h1 class="mb-1">Privacy policy</h1>
  <p class="text-muted mb-4">Last updated: <?= date('j F Y') ?></p>

  <h2 class="h5 mt-4">1. Who we are</h2>
  <p>UniHub University ("we", "us", "our") is the data controller for personal data collected through this website. Contact us at <a href="mailto:admissions@unihub.ac.uk">admissions@unihub.ac.uk</a>.</p>

  <h2 class="h5 mt-4">2. What data we collect</h2>
  <p>When you register your interest in a programme, we collect your first name, last name, and email address. We do not collect payment information, sensitive personal data, or any data from minors under 16.</p>

  <h2 class="h5 mt-4">3. How we use your data</h2>
  <ul>
    <li>To send you targeted communications about the programmes you expressed interest in (open days, deadlines, updates)</li>
    <li>To generate internal mailing lists used by university admissions staff</li>
    <li>To send you a withdrawal confirmation if you remove your interest registration</li>
  </ul>

  <h2 class="h5 mt-4">4. Legal basis</h2>
  <p>We process your data on the basis of your consent, given when you submit the Register Interest form. You may withdraw consent at any time by using the withdrawal link sent to your email, or by visiting <a href="<?= base_url('/my-interests') ?>">My interests</a>.</p>

  <h2 class="h5 mt-4">5. Data retention</h2>
  <p>Your data is retained until you withdraw your interest or request deletion. We do not retain interest registration data beyond 24 months of inactivity.</p>

  <h2 class="h5 mt-4">6. Your rights</h2>
  <p>Under UK GDPR you have the right to access, correct, delete, or restrict your data. To exercise these rights, contact <a href="mailto:admissions@unihub.ac.uk">admissions@unihub.ac.uk</a>.</p>

  <h2 class="h5 mt-4">7. Cookies</h2>
  <p>We use session cookies essential for the operation of this website. See our <a href="<?= base_url('/cookie-policy') ?>">cookie policy</a> for details.</p>
</div></section>
<?php include __DIR__ . '/../layout/footer.php'; ?>