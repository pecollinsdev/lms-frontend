<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard - LMS</title>
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
                            <i class="fas fa-user-circle me-1"></i> Instructor
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/lms-frontend/public/auth/logout">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5 pt-4">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Active Courses</h5>
                        <h2 class="mb-0"><?= count($courses ?? []) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Recent Assignments</h5>
                        <h2 class="mb-0"><?= count($assignments ?? []) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Pending Submissions</h5>
                        <h2 class="mb-0"><?= count($submissions ?? []) ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Courses Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Your Courses</h5>
                        <a href="/lms-frontend/public/instructor/courses/create" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> New Course
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($courses)): ?>
                            <p class="text-muted mb-0">No courses found.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Students</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($courses as $course): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($course['title']) ?></td>
                                                <td><?= $course['student_count'] ?? 0 ?></td>
                                                <td><?= date('M d, Y', strtotime($course['start_date'])) ?></td>
                                                <td><?= date('M d, Y', strtotime($course['end_date'])) ?></td>
                                                <td>
                                                    <a href="/lms-frontend/public/instructor/courses/<?= $course['id'] ?>/assignments" class="btn btn-sm btn-outline-primary me-1">
                                                        <i class="fas fa-tasks"></i>
                                                    </a>
                                                    <a href="/lms-frontend/public/instructor/courses/<?= $course['id'] ?>/progress" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-chart-line"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Assignments -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Recent Assignments</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($assignments)): ?>
                            <p class="text-muted mb-0">No recent assignments.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Course</th>
                                            <th>Due Date</th>
                                            <th>Submissions</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($assignments as $assignment): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($assignment['title']) ?></td>
                                                <td><?= htmlspecialchars($assignment['course_title']) ?></td>
                                                <td><?= date('M d, Y', strtotime($assignment['due_date'])) ?></td>
                                                <td><?= $assignment['submission_count'] ?? 0 ?></td>
                                                <td>
                                                    <a href="/lms-frontend/public/instructor/courses/<?= $assignment['course_id'] ?>/assignments/<?= $assignment['id'] ?>/submissions" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Submissions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Pending Submissions</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($submissions)): ?>
                            <p class="text-muted mb-0">No pending submissions.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Assignment</th>
                                            <th>Course</th>
                                            <th>Submitted</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($submissions as $submission): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($submission['student_name']) ?></td>
                                                <td><?= htmlspecialchars($submission['assignment_title']) ?></td>
                                                <td><?= htmlspecialchars($submission['course_title']) ?></td>
                                                <td><?= date('M d, Y H:i', strtotime($submission['submitted_at'])) ?></td>
                                                <td>
                                                    <a href="/lms-frontend/public/instructor/courses/<?= $submission['course_id'] ?>/assignments/<?= $submission['assignment_id'] ?>/submissions/<?= $submission['id'] ?>/grade" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-check"></i> Grade
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 