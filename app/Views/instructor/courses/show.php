<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details - LMS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/lms-frontend/public/css/style.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    .list-group-item > a:hover, 
    .list-group-item > a:focus {
        background-color: #f0f6ff;
        transition: background 0.2s;
        text-decoration: none;
        color: #212529;
        border-radius: 0.375rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    </style>
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
        <div class="mb-3">
            <a href="/lms-frontend/public/instructor/courses" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Courses
            </a>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php if (!empty($course)): ?>
                <pre class="bg-light p-2 border rounded small"><?= htmlspecialchars(json_encode($course, JSON_PRETTY_PRINT)) ?></pre>
            <?php endif; ?>
        <?php elseif (empty($course) || empty($course['id'])): ?>
            <div class="alert alert-warning" role="alert">
                Course not found or could not be loaded.
            </div>
            <pre class="bg-light p-2 border rounded small"><?php echo htmlspecialchars(json_encode($course, JSON_PRETTY_PRINT)); ?></pre>
        <?php else: ?>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h2 class="mb-1"><?= htmlspecialchars($course['title'] ?? 'Untitled Course') ?></h2>
                        <div class="mb-2">
                            <span class="badge bg-primary me-1"><?= ucfirst($course['level'] ?? 'Not specified') ?></span>
                            <span class="badge bg-secondary"><?= ucfirst($course['category'] ?? 'Uncategorized') ?></span>
                        </div>
                    </div>
                    <div class="btn-group">
                        <a href="/lms-frontend/public/instructor/courses/<?= $course['id'] ?? '' ?>/edit" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <a href="/lms-frontend/public/instructor/courses/<?= $course['id'] ?? '' ?>/assignments" class="btn btn-outline-success">
                            <i class="fas fa-tasks me-1"></i> Assignments
                        </a>
                        <a href="/lms-frontend/public/instructor/courses/<?= $course['id'] ?? '' ?>/students" class="btn btn-outline-info">
                            <i class="fas fa-user-graduate me-1"></i> Students
                        </a>
                        <a href="/lms-frontend/public/instructor/courses/<?= $course['id'] ?? '' ?>/progress" class="btn btn-outline-warning">
                            <i class="fas fa-chart-line me-1"></i> Progress
                        </a>
                    </div>
                </div>
                <p class="text-muted mb-3">
                    <?= nl2br(htmlspecialchars($course['description'] ?? 'No description provided.')) ?>
                </p>
                <div class="row mb-3">
                    <div class="col-md-3 mb-2">
                        <i class="fas fa-users me-1"></i> <strong><?= $course['student_count'] ?? 0 ?></strong> Students
                    </div>
                    <div class="col-md-3 mb-2">
                        <i class="fas fa-tasks me-1"></i> <strong><?= $course['assignment_count'] ?? 0 ?></strong> Assignments
                    </div>
                    <div class="col-md-3 mb-2">
                        <i class="fas fa-layer-group me-1"></i> <strong><?= $course['module_count'] ?? 0 ?></strong> Modules
                    </div>
                    <div class="col-md-3 mb-2">
                        <i class="fas fa-list me-1"></i> <strong><?= $course['total_items'] ?? 0 ?></strong> Items
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <i class="fas fa-calendar me-1"></i>
                        <?php if (!empty($course['start_date']) && !empty($course['end_date'])): ?>
                            <?= date('M d, Y', strtotime($course['start_date'])) ?> -
                            <?= date('M d, Y', strtotime($course['end_date'])) ?>
                        <?php else: ?>
                            Dates not set
                        <?php endif; ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Course Progress</label>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" role="progressbar"
                             style="width: <?= $course['progress'] ?? 0 ?>%"
                             aria-valuenow="<?= $course['progress'] ?? 0 ?>"
                             aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <small class="text-muted">Progress: <?= $course['progress'] ?? 0 ?>%</small>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($modules)): ?>
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Course Modules</h5>
            </div>
            <div class="card-body">
                <?php foreach ($modules as $module): ?>
                    <div class="mb-4 border rounded p-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Module: <?= htmlspecialchars($module['title']) ?></h5>
                            <span class="text-muted small">
                                <?php if (!empty($module['start_date']) && !empty($module['end_date'])): ?>
                                    <?= date('M d, Y', strtotime($module['start_date'])) ?> - <?= date('M d, Y', strtotime($module['end_date'])) ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="mb-2 text-muted"> <?= htmlspecialchars($module['description'] ?? '') ?> </div>
                        <?php if (!empty($module['items'])): ?>
                            <ul class="list-group">
                                <?php foreach ($module['items'] as $item): ?>
                                    <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-md-center p-0">
                                        <a href="/lms-frontend/public/instructor/module-items/<?= $item['id'] ?>" class="d-flex flex-grow-1 flex-column flex-md-row justify-content-between align-items-md-center text-decoration-none text-dark p-3">
                                            <div>
                                                <strong><?= htmlspecialchars($item['title']) ?></strong>
                                                <span class="badge bg-secondary ms-2"> <?= ucfirst($item['type']) ?> </span>
                                                <div class="small text-muted"> <?= htmlspecialchars($item['description'] ?? '') ?> </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                                                <?php if (!empty($item['due_date'])): ?>
                                                    <span class="badge bg-warning text-dark">Due: <?= date('M d, Y', strtotime($item['due_date'])) ?></span>
                                                <?php endif; ?>
                                                <i class="fas fa-chevron-right ms-2"></i>
                                            </div>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="text-muted small">No items in this module.</div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 