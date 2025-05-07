<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment Submissions - LMS</title>
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

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Assignment Submissions</h2>
                <p class="text-muted mb-0">
                    <?= htmlspecialchars($assignment['title'] ?? 'Untitled Assignment') ?> - 
                    <?= number_format($assignment['max_score'] ?? 0, 1) ?> Points
                </p>
            </div>
            <a href="/lms-frontend/public/instructor/courses/<?= $courseId ?>/assignments" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Assignments
            </a>
        </div>

        <!-- Submissions List -->
        <?php if (empty($submissions['data']['data'])): ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No submissions yet</h4>
                <p class="text-muted">Students haven't submitted their work for this assignment</p>
            </div>
        <?php else: ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Submitted</th>
                                    <th>Status</th>
                                    <th>Score</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($submissions['data']['data'] as $submission): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?= htmlspecialchars($submission['student']['profile_picture'] ?? '/lms-frontend/public/images/default-avatar.png') ?>" 
                                                     alt="Student Avatar" 
                                                     class="rounded-circle me-2"
                                                     style="width: 32px; height: 32px; object-fit: cover;">
                                                <div>
                                                    <div class="fw-medium"><?= htmlspecialchars($submission['student']['name'] ?? 'Unknown Student') ?></div>
                                                    <small class="text-muted"><?= htmlspecialchars($submission['student']['email'] ?? 'No email') ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span><?= date('M d, Y', strtotime($submission['submitted_at'] ?? 'now')) ?></span>
                                                <small class="text-muted"><?= date('H:i', strtotime($submission['submitted_at'] ?? 'now')) ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $status = $submission['status'] ?? 'pending';
                                            $statusClass = match($status) {
                                                'submitted' => 'bg-primary',
                                                'graded' => 'bg-success',
                                                'late' => 'bg-warning',
                                                default => 'bg-secondary'
                                            };
                                            ?>
                                            <span class="badge <?= $statusClass ?>">
                                                <?= ucfirst($status) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (($submission['status'] ?? '') === 'graded'): ?>
                                                <span class="fw-medium">
                                                    <?= number_format($submission['grade'] ?? 0, 1) ?> / 
                                                    <?= number_format($assignment['max_score'] ?? 0, 1) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">Not graded</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <?php if (($submission['submission_type'] ?? '') === 'file'): ?>
                                                    <a href="<?= htmlspecialchars($submission['file_path'] ?? '#') ?>" 
                                                       class="btn btn-sm btn-outline-primary"
                                                       target="_blank">
                                                        <i class="fas fa-download me-1"></i> Download
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <?php if (isset($submission['id'])): ?>
                                                    <?php if (($submission['status'] ?? '') !== 'graded'): ?>
                                                        <a href="/lms-frontend/public/instructor/courses/<?= $courseId ?>/assignments/<?= $assignmentId ?>/submissions/<?= $submission['id'] ?>/grade" 
                                                           class="btn btn-sm btn-primary">
                                                            <i class="fas fa-check me-1"></i> Grade
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="/lms-frontend/public/instructor/courses/<?= $courseId ?>/assignments/<?= $assignmentId ?>/submissions/<?= $submission['id'] ?>/grade" 
                                                           class="btn btn-sm btn-outline-secondary">
                                                            <i class="fas fa-edit me-1"></i> Update Grade
                                                        </a>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-secondary" disabled>
                                                        <i class="fas fa-exclamation-circle me-1"></i> No ID
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if (isset($submissions['data']['last_page']) && $submissions['data']['last_page'] > 1): ?>
                        <nav class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php if (isset($submissions['data']['current_page']) && $submissions['data']['current_page'] > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $submissions['data']['current_page'] - 1 ?>">
                                            <i class="fas fa-chevron-left"></i> Previous
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php 
                                $currentPage = $submissions['data']['current_page'] ?? 1;
                                $lastPage = $submissions['data']['last_page'] ?? 1;
                                for ($i = 1; $i <= $lastPage; $i++): 
                                ?>
                                    <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if (isset($submissions['data']['current_page']) && $submissions['data']['current_page'] < $lastPage): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $submissions['data']['current_page'] + 1 ?>">
                                            Next <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 