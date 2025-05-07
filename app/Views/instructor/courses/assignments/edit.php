<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Assignment - LMS</title>
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
            <h1 class="h3 mb-0 text-gray-800">Edit Assignment</h1>
            <a href="/lms-frontend/public/instructor/courses/<?= $courseId ?>/assignments/<?= $assignment['id'] ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Assignment
            </a>
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
                <form action="/lms-frontend/public/instructor/courses/<?= $courseId ?>/assignments/<?= $assignment['id'] ?>/edit" method="POST">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>" 
                                       id="title" 
                                       name="title" 
                                       value="<?= htmlspecialchars($assignment['title'] ?? '') ?>" 
                                       required>
                                <?php if (isset($errors['title'])): ?>
                                    <div class="invalid-feedback"><?= $errors['title'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>" 
                                          id="description" 
                                          name="description" 
                                          rows="5" 
                                          required><?= htmlspecialchars($assignment['description'] ?? '') ?></textarea>
                                <?php if (isset($errors['description'])): ?>
                                    <div class="invalid-feedback"><?= $errors['description'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                                        <input type="datetime-local" 
                                               class="form-control <?= isset($errors['due_date']) ? 'is-invalid' : '' ?>" 
                                               id="due_date" 
                                               name="due_date" 
                                               value="<?= date('Y-m-d\TH:i', strtotime($assignment['due_date'] ?? '')) ?>" 
                                               required>
                                        <?php if (isset($errors['due_date'])): ?>
                                            <div class="invalid-feedback"><?= $errors['due_date'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="total_points" class="form-label">Total Points <span class="text-danger">*</span></label>
                                        <input type="number" 
                                               class="form-control <?= isset($errors['total_points']) ? 'is-invalid' : '' ?>" 
                                               id="total_points" 
                                               name="total_points" 
                                               value="<?= htmlspecialchars($assignment['total_points'] ?? '') ?>" 
                                               min="1" 
                                               required>
                                        <?php if (isset($errors['total_points'])): ?>
                                            <div class="invalid-feedback"><?= $errors['total_points'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select <?= isset($errors['status']) ? 'is-invalid' : '' ?>" 
                                        id="status" 
                                        name="status">
                                    <option value="draft" <?= ($assignment['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                                    <option value="published" <?= ($assignment['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                                    <option value="closed" <?= ($assignment['status'] ?? '') === 'closed' ? 'selected' : '' ?>>Closed</option>
                                </select>
                                <?php if (isset($errors['status'])): ?>
                                    <div class="invalid-feedback"><?= $errors['status'] ?></div>
                                <?php endif; ?>
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

                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Note:</strong> Changing the total points after students have submitted their work may affect their grades.
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                        <a href="/lms-frontend/public/instructor/courses/<?= $courseId ?>/assignments/<?= $assignment['id'] ?>" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 