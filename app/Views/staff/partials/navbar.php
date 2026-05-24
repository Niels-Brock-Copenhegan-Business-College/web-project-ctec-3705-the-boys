<?php
// ── staff/partials/navbar.php ────────────────────────────────────
// Staff portal top navigation bar.
// Highlights the active link based on current URL path.
// ─────────────────────────────────────────────────────────────────
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
$staffName   = htmlspecialchars($_SESSION['staff_name'] ?? 'Staff', ENT_QUOTES);

/**
 * Returns 'active" aria-current="page' when the given path matches.
 * $exact = true requires an exact match (used for dashboard only).
 */
function staffNavActive(string $path, string $currentPath, bool $exact = false): string {
    $match = $exact
        ? rtrim($currentPath, '/') === rtrim($path, '/')
        : str_starts_with($currentPath, $path);
    return $match ? 'active" aria-current="page' : '';
}
?>
<nav class="staff-navbar navbar navbar-expand-lg" role="navigation" aria-label="Staff portal navigation">
    <div class="container">

        <!-- Brand -->
        <a class="navbar-brand staff-navbar__brand" href="<?= base_url('/staff') ?>">
            <span class="staff-navbar__mark" aria-hidden="true">U</span>
            <span class="staff-navbar__copy">
                <span class="staff-navbar__title">UniHub</span>
                <span class="staff-navbar__sub">Staff Portal</span>
            </span>
        </a>

        <!-- Mobile toggler -->
        <button class="navbar-toggler border-0" type="button"
                data-bs-toggle="collapse" data-bs-target="#staffNav"
                aria-controls="staffNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="staffNav">

            <!-- Main nav links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= staffNavActive(base_url('/staff'), $currentPath, true) ?>"
                       href="<?= base_url('/staff') ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= staffNavActive(base_url('/staff/modules'), $currentPath) ?>"
                       href="<?= base_url('/staff/modules') ?>">My Modules</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= staffNavActive(base_url('/staff/programmes'), $currentPath) ?>"
                       href="<?= base_url('/staff/programmes') ?>">My Programmes</a>
                </li>
                <!-- My Interests — shows all registrations across assigned programmes -->
                <li class="nav-item">
                    <a class="nav-link <?= staffNavActive(base_url('/staff/interests'), $currentPath) ?>"
                       href="<?= base_url('/staff/interests') ?>">
                        <i class="bi bi-people me-1" aria-hidden="true"></i>Registrations
                    </a>
                </li>
            </ul>

            <!-- Right side: user dropdown (desktop) + flat links (mobile) -->
            <ul class="navbar-nav mb-2 mb-lg-0">

                <!-- Desktop: dropdown -->
                <li class="nav-item dropdown d-none d-lg-block">
                    <a class="nav-link dropdown-toggle text-white" href="#"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $staffName ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= base_url('/staff/profile/edit') ?>">Edit profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger"
                               href="<?= base_url('/staff/logout') ?>"
                               onclick="return confirm('Are you sure you want to log out?')">
                                Log out
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Mobile: flat links -->
                <li class="nav-item d-lg-none">
                    <span class="nav-link text-white opacity-75 small"><?= $staffName ?></span>
                </li>
                <li class="nav-item d-lg-none">
                    <a class="nav-link <?= staffNavActive(base_url('/staff/profile/edit'), $currentPath) ?>"
                       href="<?= base_url('/staff/profile/edit') ?>">Edit profile</a>
                </li>
                <li class="nav-item d-lg-none">
                    <a class="nav-link text-danger"
                       href="<?= base_url('/staff/logout') ?>"
                       onclick="return confirm('Are you sure you want to log out?')">Log out</a>
                </li>

            </ul>
        </div>
    </div>
</nav>