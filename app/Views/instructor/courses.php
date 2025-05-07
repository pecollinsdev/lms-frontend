<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - LMS</title>
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
                        <a class="nav-link active" href="/lms-frontend/public/instructor/courses">Courses</a>
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

        <!-- Back Button -->
        <div class="mb-2">
            <a href="/lms-frontend/public/instructor/dashboard" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-start mb-4">
            <h2 class="mb-0">My Courses</h2>
            <a href="/lms-frontend/public/instructor/courses/create" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Create New Course
            </a>
        </div>

        <!-- Courses Grid -->
        <?php if (empty($courses)): ?>
            <div class="text-center py-5">
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No courses found</h4>
                <p class="text-muted">Get started by creating your first course</p>
                <a href="/lms-frontend/public/instructor/courses/create" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Create Course
                </a>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($courses as $course): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
                                <p class="card-text text-muted small">
                                    <?= htmlspecialchars(substr($course['description'] ?? '', 0, 100)) ?>
                                    <?= strlen($course['description'] ?? '') > 100 ? '...' : '' ?>
                                </p>
                                <div class="mb-3">
                                    <span class="badge bg-primary me-1"><?= ucfirst($course['level'] ?? 'Not specified') ?></span>
                                    <span class="badge bg-secondary"><?= ucfirst($course['category'] ?? 'Uncategorized') ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-users me-1"></i>
                                        <?= $course['student_count'] ?? 0 ?> Students
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-tasks me-1"></i>
                                        <?= $course['assignment_count'] ?? 0 ?> Assignments
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?php if (!empty($course['start_date']) && !empty($course['end_date'])): ?>
                                            <?= date('M d, Y', strtotime($course['start_date'])) ?> - 
                                            <?= date('M d, Y', strtotime($course['end_date'])) ?>
                                        <?php else: ?>
                                            Dates not set
                                        <?php endif; ?>
                                    </small>
                                </div>
                                <div class="progress mb-3" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: <?= $course['progress'] ?? 0 ?>%"
                                         aria-valuenow="<?= $course['progress'] ?? 0 ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted d-block text-end">
                                    Course Progress: <?= $course['progress'] ?? 0 ?>%
                                </small>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <div class="btn-group w-100">
                                    <a href="/lms-frontend/public/instructor/courses/<?= $course['id'] ?>" 
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                    <a href="/lms-frontend/public/instructor/courses/<?= $course['id'] ?>/assignments" 
                                       class="btn btn-outline-success">
                                        <i class="fas fa-tasks me-1"></i> Assignments
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-secondary dropdown-toggle" 
                                            data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="/lms-frontend/public/instructor/courses/<?= $course['id'] ?>/edit">
                                                <i class="fas fa-edit me-1"></i> Edit Course
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/lms-frontend/public/instructor/courses/<?= $course['id'] ?>/students">
                                                <i class="fas fa-user-graduate me-1"></i> Manage Students
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/lms-frontend/public/instructor/courses/<?= $course['id'] ?>/progress">
                                                <i class="fas fa-chart-line me-1"></i> View Progress
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" 
                                               onclick="return confirm('Are you sure you want to delete this course?')">
                                                <i class="fas fa-trash me-1"></i> Delete Course
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 