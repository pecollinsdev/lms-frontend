<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment Details - LMS</title>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Assignment Details</h1>
            <div>
                <a href="/lms-frontend/public/instructor/courses/<?= $courseId ?>/assignments/<?= $assignment['id'] ?>/edit" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Assignment
                </a>
                <a href="/lms-frontend/public/instructor/courses/<?= $courseId ?>/assignments" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Assignments
                </a>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Assignment Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="mb-3"><?= htmlspecialchars($assignment['title']) ?></h4>
                        <div class="mb-4">
                            <h6 class="font-weight-bold">Description</h6>
                            <p class="text-gray-800"><?= nl2br(htmlspecialchars($assignment['description'])) ?></p>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="font-weight-bold">Due Date</h6>
                                <p class="text-gray-800">
                                    <?= date('F j, Y', strtotime($assignment['due_date'])) ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="font-weight-bold">Total Points</h6>
                                <p class="text-gray-800"><?= isset($assignment['total_points']) ? $assignment['total_points'] : 0 ?> points</p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="font-weight-bold">Status</h6>
                                <p class="text-gray-800">
                                    <?php
                                    $statusClass = [
                                        'draft' => 'secondary',
                                        'published' => 'success',
                                        'closed' => 'danger'
                                    ];
                                    $status = $assignment['status'] ?? 'draft';
                                    ?>
                                    <span class="badge bg-<?= $statusClass[$status] ?>">
                                        <?= ucfirst($status) ?>
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="font-weight-bold">Created At</h6>
                                <p class="text-gray-800">
                                    <?= date('F j, Y', strtotime($assignment['created_at'])) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="font-weight-bold mb-3">Quick Stats</h6>
                                <div class="mb-3">
                                    <h6 class="text-muted">Total Submissions</h6>
                                    <h4><?= $assignment['total_submissions'] ?? 0 ?></h4>
                                </div>
                                <div class="mb-3">
                                    <h6 class="text-muted">Graded Submissions</h6>
                                    <h4><?= $assignment['graded_submissions'] ?? 0 ?></h4>
                                </div>
                                <div class="mb-3">
                                    <h6 class="text-muted">Average Score</h6>
                                    <h4><?= number_format($assignment['average_score'] ?? 0, 1) ?>%</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Recent Submissions</h6>
                <a href="/lms-frontend/public/instructor/courses/<?= $courseId ?>/assignments/<?= $assignment['id'] ?>/submissions" class="btn btn-primary btn-sm">
                    View All Submissions
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($recentSubmissions)): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Submitted At</th>
                                    <th>Status</th>
                                    <th>Score</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentSubmissions as $submission): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if (isset($submission['student']) && !empty($submission['student']['profile_picture'])): ?>
                                                    <img src="<?= htmlspecialchars($submission['student']['profile_picture']) ?>" 
                                                         class="rounded-circle me-2" 
                                                         width="32" height="32" 
                                                         alt="Profile Picture">
                                                <?php else: ?>
                                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 32px; height: 32px;">
                                                        <?= isset($submission['student']['name']) ? strtoupper(substr($submission['student']['name'], 0, 1)) : '?' ?>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <div class="font-weight-bold"><?= isset($submission['student']['name']) ? htmlspecialchars($submission['student']['name']) : 'Unknown Student' ?></div>
                                                    <div class="small text-muted"><?= isset($submission['student']['email']) ? htmlspecialchars($submission['student']['email']) : 'No email' ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= isset($submission['submitted_at']) ? date('M j, Y g:i A', strtotime($submission['submitted_at'])) : 'Not submitted' ?></td>
                                        <td>
                                            <?php
                                            $statusClass = [
                                                'pending' => 'warning',
                                                'graded' => 'success',
                                                'late' => 'danger'
                                            ];
                                            $status = isset($submission['status']) ? $submission['status'] : 'pending';
                                            ?>
                                            <span class="badge bg-<?= $statusClass[$status] ?? 'secondary' ?>">
                                                <?= ucfirst($status) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (isset($submission['score']) && $submission['score'] !== null): ?>
                                                <?= $submission['score'] ?>/<?= isset($assignment['total_points']) ? $assignment['total_points'] : 0 ?>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (isset($submission['id'])): ?>
                                                <a href="/lms-frontend/public/instructor/courses/<?= $courseId ?>/assignments/<?= $assignment['id'] ?>/submissions/<?= $submission['id'] ?>/grade" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="fas fa-check"></i> Grade
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    <i class="fas fa-check"></i> Grade
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <p class="text-muted mb-0">No submissions yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 