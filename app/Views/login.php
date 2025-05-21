<?php
\App\Core\Layout::start('main');

\App\Core\Layout::section('title');
echo 'Login - LMS';
\App\Core\Layout::endSection();

\App\Core\Layout::section('bodyClass');
echo 'bg-light';
\App\Core\Layout::endSection();

\App\Core\Layout::section('content');
?>
<!-- Login Section -->
<section class="registration-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header bg-white text-center py-4">
                        <h3 class="fw-bold mb-0">Welcome Back!</h3>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="/lms-frontend/public/auth/login" id="loginForm">
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                                <label class="form-check-label" for="rememberMe">Remember me</label>
                                <a href="/lms-frontend/public/auth/forgot-password" class="float-end text-primary">Forgot Password?</a>
                            </div>

                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= htmlspecialchars($error) ?>
                                </div>
                            <?php endif; ?>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Sign In</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bg-white text-center py-3">
                        <div class="small">
                            Don't have an account? <a href="/lms-frontend/public/auth/register" class="text-primary">Sign up!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
\App\Core\Layout::endSection();

\App\Core\Layout::section('scripts');
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password toggle functionality
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });
</script>
<?php
\App\Core\Layout::endSection();
\App\Core\Layout::end();
?>
