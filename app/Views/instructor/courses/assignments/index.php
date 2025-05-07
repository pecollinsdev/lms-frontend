<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Assignments - LMS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/lms-frontend/public/css/style.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/lms-frontend/public/instructor/dashboard">LMS Instructor</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/lms-frontend/public/instructor/courses">Courses</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($profile['name'] ?? 'Instructor') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/lms-frontend/public/instructor/profile">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/lms-frontend/public/auth/logout">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5 pt-5">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="/lms-frontend/public/instructor/courses/<?= $courseId ?>" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Back to Course
                </a>
                <h2 class="mb-0">Course Assignments</h2>
            </div>
            <a href="/lms-frontend/public/instructor/courses/<?= $courseId ?>/assignments/create" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Create Assignment
            </a>
        </div>

        <!-- Assignments Grid -->
        <?php if (empty($assignments['data'])): ?>
            <div class="text-center py-5">
                <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No assignments found</h4>
                <p class="text-muted">Get started by creating your first assignment</p>
                <a href="/lms-frontend/public/instructor/courses/<?= $courseId ?>/assignments/create" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Create Assignment
                </a>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($assignments['data'] as $assignment): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($assignment['title']) ?></h5>
                                <p class="card-text text-muted small">
                                    <?= htmlspecialchars(substr($assignment['description'] ?? '', 0, 100)) ?>
                                    <?= strlen($assignment['description'] ?? '') > 100 ? '...' : '' ?>
                                </p>
                                <div class="mb-3">
                                    <span class="badge bg-primary me-1">
                                        <?= ucfirst($assignment['submission_type'] ?? 'file') ?>
                                    </span>
                                    <span class="badge bg-secondary">
                                        <?= number_format($assignment['max_score'] ?? 0, 1) ?> Points
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?= date('M d, Y', strtotime($assignment['due_date'])) ?>
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        <?= date('H:i', strtotime($assignment['due_date'])) ?>
                                    </small>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <div class="btn-group w-100">
                                    <a href="/lms-frontend/public/instructor/courses/<?= $courseId ?>/assignments/<?= $assignment['id'] ?>" 
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                    <a href="/lms-frontend/public/instructor/courses/<?= $courseId ?>/assignments/<?= $assignment['id'] ?>/submissions" 
                                       class="btn btn-outline-info">
                                        <i class="fas fa-list me-1"></i> Submissions
                                    </a>
                                    <a href="/lms-frontend/public/instructor/courses/<?= $courseId ?>/assignments/<?= $assignment['id'] ?>/edit" 
                                       class="btn btn-outline-secondary">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger"
                                            onclick="if(confirm('Are you sure you want to delete this assignment? This action cannot be undone.')) { 
                                                window.location.href='/lms-frontend/public/instructor/courses/<?= $courseId ?>/assignments/<?= $assignment['id'] ?>/delete'; 
                                            }">
                                        <i class="fas fa-trash me-1"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($assignments['last_page'] > 1): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($assignments['current_page'] > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $assignments['current_page'] - 1 ?>">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $assignments['last_page']; $i++): ?>
                            <li class="page-item <?= $i === $assignments['current_page'] ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($assignments['current_page'] < $assignments['last_page']): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $assignments['current_page'] + 1 ?>">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 