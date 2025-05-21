<?php
if (!function_exists('base_url')) {
    function base_url($path = '') {
        $base_url = '/lms-frontend/public';
        return $base_url . ($path ? '/' . ltrim($path, '/') : '');
    }
}

$isLoggedIn = isset($_COOKIE['token']);
$userRole = $_COOKIE['user_role'] ?? '';
$isHomePage = isset($isHomePage) && $isHomePage;

// Determine the correct dashboard and courses routes based on role
$dashboardRoute = $userRole === 'instructor' ? 'instructor/dashboard' : 'student/dashboard';
$coursesRoute = $userRole === 'instructor' ? 'instructor/courses' : 'student/courses';
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= base_url() ?>">LMS</a>
        <?php if ($isLoggedIn): ?>
            <div class="navbar-nav me-auto">
                <a class="nav-link" href="<?= base_url($dashboardRoute) ?>">Dashboard</a>
                <a class="nav-link" href="<?= base_url($coursesRoute) ?>">My Courses</a>
            </div>
        <?php endif; ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (!$isLoggedIn): ?>
                    <?php if ($isHomePage): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#features">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#about">About</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary ms-2" href="<?= base_url('auth/login') ?>">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary ms-2" href="<?= base_url('auth/register') ?>">Register</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-danger ms-2" href="<?= base_url('auth/logout') ?>">Logout</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav> 