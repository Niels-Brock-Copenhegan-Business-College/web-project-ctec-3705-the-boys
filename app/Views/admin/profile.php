<?php
$admin = $admin ?? [];
$pageTitle = 'Admin Profile';
$avatarPath = $admin['avatar'] ?? '';
$avatarSrc = $avatarPath ? base_url('/' . ltrim($avatarPath, '/')) : base_url('/uploads/admin-avatar.png');
include __DIR__ . '/header.php';
?>

<?php if (!empty($flash['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show auto-dismiss" role="alert">
        <?= htmlspecialchars($flash['success'], ENT_QUOTES) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (!empty($flash['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show auto-dismiss" role="alert">
        <?= htmlspecialchars($flash['error'], ENT_QUOTES) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="row g-0 align-items-stretch">
                    <div class="col-md-5 bg-primary bg-gradient text-white p-4 d-flex flex-column justify-content-center">
                        <h2 class="h4 fw-bold mb-2">Admin Profile</h2>

                        <div class="ratio ratio-1x1 rounded-circle overflow-hidden border border-3 border-white shadow-sm mx-auto" style="max-width:240px;">
                            <img id="admin-avatar-preview" src="<?= htmlspecialchars($avatarSrc, ENT_QUOTES) ?>" data-default-src="<?= htmlspecialchars($avatarSrc, ENT_QUOTES) ?>" alt="Admin profile picture" style="object-fit:cover; width:100%; height:100%;">
                        </div>
                        <div class="mt-3 small text-center">
                            <div id="admin-avatar-name" class="mb-2 text-white-50"><?= !empty($avatarPath) ? htmlspecialchars(basename($avatarPath), ENT_QUOTES) : 'Choose an image to preview it here' ?></div>
                            <span class="badge bg-light text-dark">Admin ID: <?= (int) ($admin['id'] ?? 0) ?></span>
                        </div>
                    </div>

                    <div class="col-md-7 p-4 bg-white">
                        <form id="admin-profile-form" method="POST" action="<?= base_url('/admin/profile') ?>" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="row g-3 mb-4">
                                <div class="col-sm-6">
                                    <div class="border rounded-3 p-3 bg-light h-100">
                                        <div class="text-muted small mb-1">Username</div>
                                        <div class="fw-semibold"><?= htmlspecialchars($admin['username'] ?? '—', ENT_QUOTES) ?></div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="border rounded-3 p-3 bg-light h-100">
                                        <div class="text-muted small mb-1">Email</div>
                                        <div class="fw-semibold"><?= htmlspecialchars($admin['email'] ?? '—', ENT_QUOTES) ?></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="border rounded-3 p-3 bg-light h-100">
                                        <div class="text-muted small mb-1">Secret code</div>
                                        <div class="fw-semibold"><?= !empty($admin['secret_code_hash']) ? 'Set' : 'Not set' ?></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="border rounded-3 p-3 bg-light h-100">
                                        <div class="text-muted small mb-1">Secret code updated</div>
                                        <div class="fw-semibold"><?= !empty($admin['secret_code_set_at']) ? htmlspecialchars($admin['secret_code_set_at'], ENT_QUOTES) : '—' ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="avatar" class="form-label fw-semibold">Upload profile picture</label>
                                <input id="avatar" type="file" name="avatar" accept="image/*" class="form-control">
                                <div class="form-text">JPG, PNG, WEBP or GIF only. The new photo will replace the current one.</div>
                                <div id="avatar-error" class="invalid-feedback d-none"></div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="<?= base_url('/admin') ?>" class="btn btn-outline-secondary">Back to Dashboard</a>
                                <button type="submit" class="btn btn-primary btn-lg shadow-sm">Save Profile Picture</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const form = document.getElementById('admin-profile-form');
        const input = document.getElementById('avatar');
        const preview = document.getElementById('admin-avatar-preview');
        const fileName = document.getElementById('admin-avatar-name');
        const errorBox = document.getElementById('avatar-error');

        if (!form || !input || !preview || !fileName || !errorBox) {
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

        input.addEventListener('change', function() {
            const file = input.files && input.files[0] ? input.files[0] : null;
            clearError();

            if (!file) {
                preview.src = defaultSrc;
                fileName.textContent = defaultSrc ? 'Current profile picture' : 'Choose an image to preview it here';
                return;
            }

            if (!isValidImage(file)) {
                input.value = '';
                preview.src = defaultSrc;
                fileName.textContent = defaultSrc ? 'Current profile picture' : 'Choose an image to preview it here';
                setError('Only image files are allowed. Please choose a JPG, PNG, WEBP, or GIF file.');
                return;
            }

            const objectUrl = URL.createObjectURL(file);
            preview.src = objectUrl;
            fileName.textContent = file.name;
            preview.onload = function() {
                URL.revokeObjectURL(objectUrl);
            };
        });

        form.addEventListener('submit', function(event) {
            const file = input.files && input.files[0] ? input.files[0] : null;
            if (!file) {
                event.preventDefault();
                setError('Please choose an image to upload.');
                input.focus();
                return;
            }
            if (!isValidImage(file)) {
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