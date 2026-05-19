<?php
$staff     = $staff  ?? [];
$errors    = $errors ?? [];
$flash     = $flash  ?? [];
$pageTitle = 'Edit Profile';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES) ?> | Staff Portal | UniHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('/css/custom.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/css/staff.css') ?>">
</head>
<body class="staff-body">

<a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>

<?php include __DIR__ . '/partials/navbar.php'; ?>

<main id="main-content" class="staff-main">
<div class="container py-4" style="max-width:560px;">

    <div class="mb-4">
        <a href="<?= base_url('/staff') ?>" class="text-muted text-decoration-none small">
            &larr; Dashboard
        </a>
        <h1 class="h3 fw-semibold mb-0 mt-1">Edit Profile</h1>
    </div>

    <?php if (!empty($flash['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show auto-dismiss" role="alert" aria-live="polite">
            <?= htmlspecialchars($flash['success'], ENT_QUOTES) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="staff-section-card p-4">
        <form method="POST" action="<?= base_url('/staff/profile/edit') ?>">

            <div class="mb-3">
                <label for="full_name" class="form-label fw-medium">Full name</label>
                <input id="full_name"
                       type="text"
                       name="full_name"
                       class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($staff['full_name'] ?? '', ENT_QUOTES) ?>"
                       required
                       autocomplete="name">
                <?php if (isset($errors['full_name'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['full_name'], ENT_QUOTES) ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-medium">Email address</label>
                <input id="email"
                       type="email"
                       name="email"
                       class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($staff['email'] ?? '', ENT_QUOTES) ?>"
                       required
                       autocomplete="email">
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['email'], ENT_QUOTES) ?></div>
                <?php endif; ?>
            </div>

            <!-- Username shown read-only — only admin can change it -->
            <div class="mb-4">
                <label class="form-label fw-medium text-muted">Username</label>
                <input type="text"
                       class="form-control"
                       value="<?= htmlspecialchars($staff['username'] ?? '', ENT_QUOTES) ?>"
                       disabled
                       aria-describedby="username-hint">
                <div id="username-hint" class="form-text">Username can only be changed by an administrator.</div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save changes</button>
                <a href="<?= base_url('/staff/change-password') ?>" class="btn btn-outline-secondary">
                    Change password
                </a>
            </div>

        </form>
    </div>

</div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.auto-dismiss').forEach(function(el) {
        setTimeout(function() {
            el.style.transition = 'opacity .5s';
            el.style.opacity = '0';
            setTimeout(function() { el.remove(); }, 500);
        }, 4000);
    });
</script>
</body>
</html>