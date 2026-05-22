<?php $pageTitle = 'Terms of Use'; include __DIR__ . '/../layout/header.php'; ?>
<section class="py-5"><div class="container" style="max-width:820px">
  <h1 class="mb-1">Terms of use</h1>
  <p class="text-muted mb-4">Last updated: <?= date('j F Y') ?></p>

  <h2 class="h5 mt-4">1. Acceptance</h2>
  <p>By using this website you agree to these terms. If you do not agree, please do not use the site.</p>

  <h2 class="h5 mt-4">2. Use of this website</h2>
  <p>This website is provided for prospective students to explore programmes and register their interest. You agree not to misuse it, submit false information, or attempt to disrupt the service.</p>

  <h2 class="h5 mt-4">3. Interest registrations</h2>
  <p>When you register your interest, you consent to receiving programme-related communications from UniHub University. You may withdraw at any time via the <a href="<?= base_url('/my-interests') ?>">My interests</a> page.</p>

  <h2 class="h5 mt-4">4. Accuracy of information</h2>
  <p>We endeavour to keep programme information accurate and up to date. However, details such as fees, entry requirements, and module content are subject to change. Always confirm details with the admissions team before applying.</p>

  <h2 class="h5 mt-4">5. Intellectual property</h2>
  <p>All content on this website — including text, images, and code — is the property of UniHub University and may not be reproduced without permission.</p>

  <h2 class="h5 mt-4">6. Limitation of liability</h2>
  <p>UniHub University is not liable for any loss or damage arising from your use of this website or reliance on its content.</p>

  <h2 class="h5 mt-4">7. Governing law</h2>
  <p>These terms are governed by the laws of England and Wales.</p>

  <h2 class="h5 mt-4">Contact</h2>
  <p>Questions about these terms? Email <a href="mailto:admissions@unihub.ac.uk">admissions@unihub.ac.uk</a>.</p>
</div></section>
<?php include __DIR__ . '/../layout/footer.php'; ?>