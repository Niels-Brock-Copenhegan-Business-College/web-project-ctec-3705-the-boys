<?php
$prog = $prog ?? null;
$pageTitle = $prog ? 'Edit Programme' : 'New Programme';
$action    = $prog ? base_url('/admin/programmes/' . $prog['id']) : base_url('/admin/programmes');
$currentImg = $prog['image_url'] ?? '';
$currentSrc = $currentImg ? (preg_match('#^https?://#i', $currentImg) ? $currentImg : base_url('/' . ltrim($currentImg, '/'))) : '';
include __DIR__ . '/header.php';
?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="row g-0 align-items-stretch">
          <div class="col-md-5 bg-primary bg-gradient text-white p-4 d-flex flex-column justify-content-center">
            <h2 class="h4 fw-bold mb-2"><?= $prog ? 'Edit Programme' : 'Create New Programme' ?></h2>
            <p class="mb-3 opacity-75">Uploaded image preview.</p>
            <div class="ratio ratio-16x9 rounded-3 overflow-hidden border border-2 border-white/20 shadow-sm bg-dark bg-opacity-10">
              <img
                id="programme-image-preview"
                src="<?= htmlspecialchars($currentSrc ?: base_url('/uploads/logo.png'), ENT_QUOTES) ?>"
                data-default-src="<?= htmlspecialchars($currentSrc, ENT_QUOTES) ?>"
                alt="Programme image preview"
                style="object-fit:cover; width:100%; height:100%; <?= $currentSrc ? '' : 'display:none;' ?>"
              >
              <div id="programme-image-empty" class="d-flex h-100 w-100 align-items-center justify-content-center text-white opacity-75" style="<?= $currentSrc ? 'display:none;' : '' ?>"></div>
            </div>
            <div class="mt-3 small">
              <div id="programme-image-name" class="mb-2 text-white-50"><?= $currentImg ? htmlspecialchars(basename($currentImg), ENT_QUOTES) : 'Choose an image to preview it here' ?></div>
              <span class="badge bg-light text-dark me-2">Level: <?= htmlspecialchars($prog['level'] ?? '—', ENT_QUOTES) ?></span>
              <span class="badge bg-light text-dark">Status: <?= !empty($prog['is_published']) ? 'Published' : 'Draft' ?></span>
            </div>
          </div>

          <div class="col-md-7 p-4 bg-white">
            <form id="programme-form" method="POST" action="<?= $action ?>" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="title" class="form-label fw-semibold">Title</label>
                <input id="title" type="text" name="title" class="form-control form-control-lg shadow-sm" required
                       value="<?= htmlspecialchars($prog['title'] ?? '', ENT_QUOTES) ?>">
              </div>

              <div class="row g-3">
                <div class="col-sm-6">
                  <label for="level" class="form-label">Level</label>
                  <select id="level" name="level" class="form-select" required>
                    <option value="Undergraduate" <?= ($prog['level'] ?? '') === 'Undergraduate' ? 'selected' : '' ?>>Undergraduate</option>
                    <option value="Postgraduate"  <?= ($prog['level'] ?? '') === 'Postgraduate'  ? 'selected' : '' ?>>Postgraduate</option>
                  </select>
                </div>
                <div class="col-sm-6">
                  <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1"
                           <?= !empty($prog['is_published']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_published">Published</label>
                  </div>
                </div>
              </div>

              <div class="mb-3 mt-3">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control" rows="5" required><?= htmlspecialchars($prog['description'] ?? '', ENT_QUOTES) ?></textarea>
              </div>

              

              <div class="mb-3">
                <label for="image" class="form-label">Upload image</label>
                <input id="image" type="file" name="image" accept="image/*" class="form-control">
                <div class="form-text">Recommended 1280×720, JPG/PNG/WEBP. This will override the URL when uploaded.</div>
                <div id="image-error" class="invalid-feedback d-none"></div>
              </div>

              <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="<?= base_url('/admin/programmes') ?>" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary btn-lg shadow-sm">Save Programme</button>
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
    const form = document.getElementById('programme-form');
    const input = document.getElementById('image');
    const preview = document.getElementById('programme-image-preview');
    const emptyState = document.getElementById('programme-image-empty');
    const fileName = document.getElementById('programme-image-name');
    const errorBox = document.getElementById('image-error');

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
