<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module Item - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/lms-frontend/public/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
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
    <div class="container mt-5 pt-5">
        <div class="mb-3">
            <a href="/lms-frontend/public/instructor/courses/<?= htmlspecialchars($item['module']['course']['id'] ?? '') ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Course
            </a>
        </div>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php elseif (empty($item)): ?>
            <div class="alert alert-warning" role="alert">
                Module item not found or could not be loaded.
            </div>
        <?php else: ?>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h3 class="mb-1">
                            <?= htmlspecialchars($item['title'] ?? 'Untitled Item') ?>
                            <span class="badge bg-secondary ms-2"> <?= ucfirst($item['type'] ?? 'item') ?> </span>
                        </h3>
                        <div class="mb-2 text-muted"> <?= htmlspecialchars($item['description'] ?? '') ?> </div>
                    </div>
                    <div class="text-end">
                        <div class="small text-muted">Module: <strong><?= htmlspecialchars($item['module']['title'] ?? '') ?></strong></div>
                        <div class="small text-muted">Course: <strong><?= htmlspecialchars($item['module']['course']['title'] ?? '') ?></strong></div>
                    </div>
                </div>
                <?php if (!empty($item['due_date'])): ?>
                    <div class="mb-2">
                        <span class="badge bg-warning text-dark">Due: <?= date('M d, Y', strtotime($item['due_date'])) ?></span>
                    </div>
                <?php endif; ?>
                <hr>
                <!-- Content by type -->
                <?php switch ($item['type'] ?? ''):
                    case 'video': ?>
                        <div class="mb-3">
                            <strong>Video Content:</strong><br>
                            <?php if (!empty($item['content_url'])): ?>
                                <video src="<?= htmlspecialchars($item['content_url']) ?>" controls style="max-width:100%;height:auto;"></video>
                            <?php else: ?>
                                <span class="text-muted">No video URL provided.</span>
                            <?php endif; ?>
                        </div>
                        <?php break;
                    case 'document': ?>
                        <div class="mb-3">
                            <strong>Document:</strong><br>
                            <?php if (!empty($item['document_url'])): ?>
                                <a href="<?= htmlspecialchars($item['document_url']) ?>" target="_blank" class="btn btn-outline-primary">
                                    <i class="fas fa-file-alt me-1"></i> View Document
                                </a>
                            <?php else: ?>
                                <span class="text-muted">No document URL provided.</span>
                            <?php endif; ?>
                        </div>
                        <?php break;
                    case 'quiz': ?>
                        <div class="mb-3">
                            <strong>Quiz:</strong><br>
                            <?php if (!empty($item['questions'])): ?>
                                <ul>
                                    <?php foreach ($item['questions'] as $q): ?>
                                        <li><?= htmlspecialchars($q['question'] ?? '') ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <span class="text-muted">No quiz questions available.</span>
                            <?php endif; ?>
                        </div>
                        <?php break;
                    case 'assignment': ?>
                        <div class="mb-3">
                            <strong>Assignment Details:</strong><br>
                            <?php if (!empty($item['assignment_details'])): ?>
                                <pre><?= htmlspecialchars($item['assignment_details']) ?></pre>
                            <?php else: ?>
                                <span class="text-muted">No assignment details provided.</span>
                            <?php endif; ?>
                        </div>
                        <?php break;
                    default: ?>
                        <div class="mb-3">
                            <span class="text-muted">No additional content for this item type.</span>
                        </div>
                <?php endswitch; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 