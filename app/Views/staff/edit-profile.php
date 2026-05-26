<?php
$staff     = $staff  ?? [];
$errors    = $errors ?? [];
$flash     = $flash  ?? [];
$pageTitle = 'Edit Profile';

$photoSrc = !empty($staff['photo'])
    ? base_url('/uploads/staff/' . htmlspecialchars($staff['photo'], ENT_QUOTES))
    : null;
$initials  = mb_strtoupper(mb_substr($staff['full_name'] ?? 'S', 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES) ?> | Staff Portal | UniHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('/css/custom.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/css/staff.css') ?>">
   
</head>
<body class="staff-body">
<a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>

<?php include __DIR__ . '/partials/navbar.php'; ?>

<main id="main-content" class="staff-main">
<div class="container py-4" style="max-width:620px;">

    <div class="mb-3">
        <a href="<?= base_url('/staff') ?>" class="text-muted text-decoration-none small">
            <i class="bi bi-house me-1"></i>Dashboard
        </a>
        <span class="text-muted small mx-1">/</span>
        <span class="small fw-medium">Edit Profile</span>
    </div>

    <!-- Hero banner with floating avatar -->
    <div class="ep-hero">
        <div class="ep-hero__title">Staff Portal — My Profile</div>
        <div class="ep-hero__name"><?= htmlspecialchars($staff['full_name'] ?? 'Staff Member', ENT_QUOTES) ?></div>
        <div class="ep-hero__role">
            <span class="staff-role-badge staff-role-<?= htmlspecialchars($staff['role'] ?? 'instructor', ENT_QUOTES) ?>">
                <?= ucfirst(htmlspecialchars($staff['role'] ?? 'instructor', ENT_QUOTES)) ?>
            </span>
        </div>
        <div class="ep-avatar-wrap">
            <div class="ep-avatar" id="ep-hero-avatar">
                <?php if ($photoSrc): ?>
                    <img src="<?= $photoSrc ?>" alt="Profile photo">
                <?php else: ?>
                    <span><?= $initials ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (!empty($flash['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show auto-dismiss" role="alert" aria-live="polite">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= htmlspecialchars($flash['success'], ENT_QUOTES) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="ep-card">
        <form method="POST" action="<?= base_url('/staff/profile/edit') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Read-only: admin-managed fields -->
            <div class="ep-section-head">Account details &nbsp;&middot;&nbsp; Managed by admin</div>

            <div class="ep-readonly-group">
                <div class="ep-readonly-label">Username</div>
                <div class="ep-readonly-value">
                    <i class="bi bi-person-badge"></i>
                    <?= htmlspecialchars($staff['username'] ?? '', ENT_QUOTES) ?>
                    <span class="ep-lock"><i class="bi bi-lock-fill me-1"></i>Admin only</span>
                </div>
            </div>

            <div class="ep-readonly-group">
                <div class="ep-readonly-label">Email address</div>
                <div class="ep-readonly-value">
                    <i class="bi bi-envelope"></i>
                    <?= htmlspecialchars($staff['email'] ?? '', ENT_QUOTES) ?>
                    <span class="ep-lock"><i class="bi bi-lock-fill me-1"></i>Admin only</span>
                </div>
            </div>

            <!-- Editable fields -->
            <div class="ep-section-head mt-4">Your editable details</div>

            <div class="mb-4">
                <label for="full_name" class="form-label fw-medium">
                    Full name <span class="text-danger">*</span>
                </label>
                <input id="full_name"
                       type="text"
                       name="full_name"
                       class="form-control form-control-lg <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($staff['full_name'] ?? '', ENT_QUOTES) ?>"
                       required
                       autocomplete="name"
                       placeholder="Your display name">
                <?php if (isset($errors['full_name'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['full_name'], ENT_QUOTES) ?></div>
                <?php endif; ?>
            </div>

            <!-- Bio -->
            <div class="mb-4">
                <label for="bio" class="form-label fw-medium">Bio</label>
                <textarea id="bio"
                          name="bio"
                          rows="4"
                          class="form-control"
                          maxlength="500"
                          placeholder="A short paragraph about yourself — this is shown to students on programme pages…"><?= htmlspecialchars($staff['bio'] ?? '', ENT_QUOTES) ?></textarea>
                <div class="form-text">Max 500 characters. Shown to prospective students on programme pages.</div>
            </div>

            <!-- Photo upload -->
            <div class="mb-4">
                <label class="form-label fw-medium d-block">Profile photo</label>
                <label class="ep-photo-zone" for="ep-photo-input" tabindex="0"
                       aria-label="Upload a profile photo">
                    <div class="ep-photo-zone__icon"><i class="bi bi-cloud-arrow-up"></i></div>
                    <div class="ep-photo-zone__text fw-medium">Click to upload a new photo</div>
                    <div class="ep-photo-zone__hint">JPG, PNG, WEBP or GIF &nbsp;&middot;&nbsp; Max 3 MB</div>
                    <input type="file" name="photo" id="ep-photo-input" accept="image/*">
                </label>
                <?php if (isset($errors['photo'])): ?>
                    <div class="text-danger small mt-1">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        <?= htmlspecialchars($errors['photo'], ENT_QUOTES) ?>
                    </div>
                <?php endif; ?>

                <div id="ep-preview-wrap" class="text-center">
                    <img id="ep-preview-img" src="#" alt="Preview">
                    <div class="text-muted small mt-1" id="ep-preview-name"></div>
                </div>

                <?php if ($photoSrc): ?>
                    <div class="mt-2 d-flex align-items-center gap-2">
                        <img src="<?= $photoSrc ?>" alt="Current photo"
                             style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0;">
                        <span class="small text-muted">Current photo — upload a new one to replace it</span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2 pt-1">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-1"></i>Save changes
                </button>
                <a href="<?= base_url('/staff') ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>

        </form>
    </div>

    <div class="alert alert-info d-flex align-items-start gap-2 mt-3 border-0"
         style="background:#eef4fd;border-radius:12px;">
        <i class="bi bi-info-circle-fill text-primary mt-1 flex-shrink-0"></i>
        <div class="small">
            <strong>Need to change your password or email?</strong><br>
            Contact your administrator — they can update your login credentials on your behalf.
        </div>
    </div>

</div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.auto-dismiss').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity .5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }, 4000);
    });

    const input       = document.getElementById('ep-photo-input');
    const previewWrap = document.getElementById('ep-preview-wrap');
    const previewImg  = document.getElementById('ep-preview-img');
    const previewName = document.getElementById('ep-preview-name');

    input?.addEventListener('change', () => {
        const file = input.files[0];
        if (!file) return;
        const url = URL.createObjectURL(file);
        previewImg.src = url;
        previewName.textContent = file.name;
        previewWrap.style.display = 'block';
        // Live-update hero avatar
        document.getElementById('ep-hero-avatar').innerHTML =
            `<img src="${url}" alt="Preview" style="width:100%;height:100%;object-fit:cover;">`;
    });
</script>
</body>
</html>