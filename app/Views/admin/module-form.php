<?php
$module = $module ?? null;
$pageTitle = $module ? 'Edit Module' : 'New Module';
$action    = $module ? base_url('/admin/modules/' . $module['id']) : base_url('/admin/modules');
$currentPhoto = $module['photo'] ?? '';
$currentSrc = $currentPhoto ? base_url('/uploads/' . ltrim($currentPhoto, '/')) : '';
include __DIR__ . '/header.php';
?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="row g-0 align-items-stretch">
          <div class="col-md-5 bg-primary bg-gradient text-white p-4 d-flex flex-column justify-content-center">
            <h2 class="h4 fw-bold mb-2"><?= $module ? 'Edit Module' : 'Create New Module' ?></h2>
            <p class="mb-3 opacity-75">Uploaded image preview.</p>
            <div class="ratio ratio-16x9 rounded-3 overflow-hidden border border-2 border-white/20 shadow-sm bg-dark bg-opacity-10">
              <img
                id="module-photo-preview"
                src="<?= htmlspecialchars($currentSrc ?: base_url('/uploads/logo.png'), ENT_QUOTES) ?>"
                data-default-src="<?= htmlspecialchars($currentSrc, ENT_QUOTES) ?>"
                alt="Module photo preview"
                style="object-fit:cover; width:100%; height:100%; <?= $currentSrc ? '' : 'display:none;' ?>"
              >
              <div id="module-photo-empty" class="d-flex h-100 w-100 align-items-center justify-content-center text-white opacity-75" style="<?= $currentSrc ? 'display:none;' : '' ?>"></div>
            </div>
            <div class="mt-3 small">
              <div id="module-photo-name" class="mb-2 text-white-50"><?= $currentPhoto ? htmlspecialchars($currentPhoto, ENT_QUOTES) : 'Choose an image to preview it here' ?></div>
              <span class="badge bg-light text-dark me-2">Module</span>
              <span class="badge bg-light text-dark"><?= $module ? 'Editing' : 'New' ?></span>
            </div>
          </div>

          <div class="col-md-7 p-4 bg-white">
            <form id="module-form" method="POST" action="<?= $action ?>" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="title" class="form-label fw-semibold">Module Title</label>
                <input id="title" type="text" name="title" class="form-control form-control-lg shadow-sm" required
                       value="<?= htmlspecialchars($module['title'] ?? '', ENT_QUOTES) ?>">
              </div>

              <div class="mb-3 mt-3">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control" rows="5" required><?= htmlspecialchars($module['description'] ?? '', ENT_QUOTES) ?></textarea>

              </div>

              <div class="mb-3">
                <label for="photo" class="form-label">Upload image</label>
                <input id="photo" type="file" name="photo" accept="image/*" class="form-control">
                <div class="form-text">Recommended 1280×720, JPG/PNG/WEBP/GIF.</div>
                <div id="photo-error" class="invalid-feedback d-none"></div>
              </div>

              <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="<?= base_url('/admin/modules') ?>" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary btn-lg shadow-sm">Save Module</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function () {
    const form = document.getElementById('module-form');
    const input = document.getElementById('photo');
    const preview = document.getElementById('module-photo-preview');
    const emptyState = document.getElementById('module-photo-empty');
    const fileName = document.getElementById('module-photo-name');
    const errorBox = document.getElementById('photo-error');

    if (!form || !input || !preview || !emptyState || !fileName || !errorBox) {
      return;
    }

    const defaultSrc = preview.dataset.defaultSrc || '';

    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    function setError(message) {
      input.classList.add('is-invalid');
      input.setCustomValidity(message);
      errorBox.textContent = message;
      errorBox.classList.remove('d-none');
    }

    function clearError() {
      input.classList.remove('is-invalid');
      input.setCustomValidity('');
      errorBox.textContent = '';
      errorBox.classList.add('d-none');
    }

    function isValidImage(file) {
      if (!file) {
        return true;
      }

      if (allowedTypes.includes(file.type)) {
        return true;
      }

      return /\.(jpe?g|png|webp|gif)$/i.test(file.name);
    }

    input.addEventListener('change', function () {
      const file = input.files && input.files[0] ? input.files[0] : null;

      clearError();

      if (!file) {
        if (defaultSrc) {
          preview.src = defaultSrc;
          preview.style.display = 'block';
          emptyState.style.display = 'none';
          fileName.textContent = 'Current uploaded image';
        } else {
          preview.removeAttribute('src');
          preview.style.display = 'none';
          emptyState.style.display = 'flex';
          fileName.textContent = 'Choose an image to preview it here';
        }
        return;
      }

      if (!isValidImage(file)) {
        input.value = '';
        preview.removeAttribute('src');
        preview.style.display = 'none';
        emptyState.style.display = 'flex';
        fileName.textContent = 'Choose an image to preview it here';
        setError('Only image files are allowed. Please choose a JPG, PNG, WEBP, or GIF file.');
        return;
      }

      const objectUrl = URL.createObjectURL(file);
      preview.src = objectUrl;
      preview.style.display = 'block';
      emptyState.style.display = 'none';
      fileName.textContent = file.name;

      preview.onload = function () {
        URL.revokeObjectURL(objectUrl);
      };
    });

    form.addEventListener('submit', function (event) {
      const file = input.files && input.files[0] ? input.files[0] : null;

      if (file && !isValidImage(file)) {
        event.preventDefault();
        setError('Only image files are allowed. Please choose a JPG, PNG, WEBP, or GIF file.');
        input.focus();
        return;
      }

      clearError();
    });
  })();
</script>
<?php include __DIR__ . '/footer.php'; ?>
