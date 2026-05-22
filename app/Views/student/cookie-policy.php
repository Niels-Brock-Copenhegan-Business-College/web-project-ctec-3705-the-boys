<?php $pageTitle = 'Cookie Policy'; include __DIR__ . '/../layout/header.php'; ?>
<section class="py-5"><div class="container" style="max-width:820px">
  <h1 class="mb-1">Cookie policy</h1>
  <p class="text-muted mb-4">Last updated: <?= date('j F Y') ?></p>

  <h2 class="h5 mt-4">What are cookies?</h2>
  <p>Cookies are small text files stored on your device when you visit a website. They help the site remember information about your visit.</p>

  <h2 class="h5 mt-4">Cookies we use</h2>
  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead class="table-light">
        <tr><th>Cookie name</th><th>Purpose</th><th>Duration</th><th>Type</th></tr>
      </thead>
      <tbody>
        <tr><td><code>PHPSESSID</code></td><td>Maintains your session (e.g. flash messages, form state)</td><td>Session</td><td>Strictly necessary</td></tr>
      </tbody>
    </table>
  </div>

  <h2 class="h5 mt-4">Third-party cookies</h2>
  <p>This website does not use any third-party tracking, advertising, or analytics cookies.</p>

  <h2 class="h5 mt-4">Managing cookies</h2>
  <p>You can control and delete cookies through your browser settings. Disabling the session cookie may affect functionality such as form submissions. For more information visit <a href="https://www.aboutcookies.org" target="_blank" rel="noopener">aboutcookies.org</a>.</p>
</div></section>
<?php include __DIR__ . '/../layout/footer.php'; ?>