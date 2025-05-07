<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Submission - LMS</title>
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

        <!-- Back Button -->
        <div class="mb-3">
            <a href="/lms-frontend/public/instructor/courses/<?= $courseId ?>/assignments/<?= $assignmentId ?>/submissions" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Submissions
            </a>
        </div>

        <?php if (empty($submission)): ?>
            <div class="alert alert-warning" role="alert">
                Submission not found or could not be loaded.
            </div>
        <?php else: ?>
            <div class="row">
                <!-- Left Column - Submission Details -->
                <div class="col-md-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div>
                                    <h2 class="h4 mb-1">Grade Submission</h2>
                                    <p class="text-muted mb-0">
                                        <?= htmlspecialchars($submission['assignment']['title'] ?? 'Untitled Assignment') ?>
                                    </p>
                                </div>
                                <div>
                                    <span class="badge bg-<?= ($submission['status'] ?? '') === 'late' ? 'warning' : 'primary' ?>">
                                        <?= ucfirst($submission['status'] ?? 'pending') ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Student Info -->
                            <div class="mb-4">
                                <h5 class="mb-3">Student Information</h5>
                                <div class="d-flex align-items-center">
                                    <?php if (!empty($submission['student']['profile_picture'])): ?>
                                        <img src="<?= htmlspecialchars($submission['student']['profile_picture']) ?>" 
                                             class="rounded-circle me-3" 
                                             width="64" height="64" 
                                             alt="Profile Picture">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" 
                                             style="width: 64px; height: 64px; font-size: 24px;">
                                            <?= strtoupper(substr($submission['student']['name'] ?? '?', 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <h6 class="mb-1"><?= htmlspecialchars($submission['student']['name'] ?? 'Unknown Student') ?></h6>
                                        <p class="text-muted mb-0"><?= htmlspecialchars($submission['student']['email'] ?? 'No email') ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Submission Content -->
                            <div class="mb-4">
                                <h5 class="mb-3">Submission</h5>
                                <?php if ($submission['submission_type'] === 'file'): ?>
                                    <div class="mb-3">
                                        <a href="<?= htmlspecialchars($submission['file_url'] ?? '#') ?>" 
                                           class="btn btn-outline-primary"
                                           target="_blank">
                                            <i class="fas fa-download me-1"></i> Download Submission
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <?= nl2br(htmlspecialchars($submission['content'] ?? 'No content provided')) ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Grading Form -->
                            <form method="POST" action="/lms-frontend/public/instructor/courses/<?= $courseId ?>/assignments/<?= $assignmentId ?>/submissions/<?= $submission['id'] ?>/grade">
                                <div class="mb-3">
                                    <label for="score" class="form-label">Score <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control" 
                                               id="score" 
                                               name="score" 
                                               min="0" 
                                               max="<?= $submission['assignment']['total_points'] ?? 100 ?>" 
                                               step="0.1"
                                               value="<?= htmlspecialchars($submission['score'] ?? '') ?>"
                                               required>
                                        <span class="input-group-text">/ <?= $submission['assignment']['total_points'] ?? 100 ?></span>
                                    </div>
                                    <div class="form-text">
                                        Maximum points: <?= $submission['assignment']['total_points'] ?? 100 ?>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="feedback" class="form-label">Feedback</label>
                                    <textarea class="form-control" 
                                              id="feedback" 
                                              name="feedback" 
                                              rows="4"
                                              placeholder="Provide feedback for the student..."><?= htmlspecialchars($submission['feedback'] ?? '') ?></textarea>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="/lms-frontend/public/instructor/courses/<?= $courseId ?>/assignments/<?= $assignmentId ?>/submissions" 
                                       class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-check me-1"></i> Submit Grade
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Assignment Info -->
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Assignment Details</h5>
                            <div class="mb-3">
                                <label class="form-label text-muted">Due Date</label>
                                <p class="mb-0">
                                    <?php if (!empty($submission['assignment']['due_date'])): ?>
                                        <?= date('M d, Y g:i A', strtotime($submission['assignment']['due_date'])) ?>
                                    <?php else: ?>
                                        Not set
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Submitted</label>
                                <p class="mb-0">
                                    <?php if (!empty($submission['submitted_at'])): ?>
                                        <?= date('M d, Y g:i A', strtotime($submission['submitted_at'])) ?>
                                    <?php else: ?>
                                        Not submitted
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Total Points</label>
                                <p class="mb-0"><?= $submission['assignment']['total_points'] ?? 100 ?></p>
                            </div>
                            <?php if (!empty($submission['assignment']['description'])): ?>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Description</label>
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($submission['assignment']['description'])) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 