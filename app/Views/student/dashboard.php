<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - LMS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/lms-frontend/public/css/style.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/lms-frontend/public/student/dashboard">LMS Student</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/lms-frontend/public/student/courses">My Courses</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($profile['name'] ?? 'Student') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/lms-frontend/public/student/profile">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
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

        <!-- Welcome Panel -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img src="<?= htmlspecialchars($profile['profile_picture'] ?? '/lms-frontend/public/images/default-avatar.png') ?>" 
                                 alt="Profile Picture" 
                                 class="rounded-circle me-3"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                            <div>
                                <h4 class="mb-1">Welcome back, <?= htmlspecialchars($profile['name'] ?? 'Student') ?>!</h4>
                                <p class="text-muted mb-0"><?= date('l, F j, Y') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Current GPA</h5>
                        <h2 class="mb-0"><?= number_format($profile['gpa'] ?? 0, 2) ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column -->
            <div class="col-md-8">
                <!-- My Courses -->
                <div class="card mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">My Courses</h5>
                        <a href="/lms-frontend/public/student/courses" class="btn btn-primary btn-sm">
                            <i class="fas fa-book me-1"></i> View All
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($courses)): ?>
                            <p class="text-muted mb-0">You are not enrolled in any courses yet.</p>
                        <?php else: ?>
                            <div class="row row-cols-1 row-cols-md-2 g-4">
                                <?php foreach ($courses as $course): ?>
                                    <div class="col">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
                                                <p class="card-text text-muted">
                                                    <small>Instructor: <?= htmlspecialchars($course['instructor'] ?? 'Not assigned') ?></small>
                                                </p>
                                                <div class="progress mb-3" style="height: 5px;">
                                                    <div class="progress-bar" role="progressbar" style="width: <?= $course['current_grade'] ?? 0 ?>%"></div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">Grade: <?= $course['current_grade'] ?? 'N/A' ?></small>
                                                    <div class="btn-group">
                                                        <a href="/lms-frontend/public/student/courses/<?= $course['id'] ?>/materials" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-book"></i>
                                                        </a>
                                                        <a href="/lms-frontend/public/student/courses/<?= $course['id'] ?>/assignments" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-tasks"></i>
                                                        </a>
                                                        <a href="/lms-frontend/public/student/courses/<?= $course['id'] ?>/discussions" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-comments"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Upcoming Deadlines -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Upcoming Deadlines</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($deadlines)): ?>
                            <p class="text-muted mb-0">No upcoming deadlines.</p>
                        <?php else: ?>
                            <div class="list-group">
                                <?php foreach ($deadlines as $deadline): ?>
                                    <?php
                                    $dueDate = strtotime($deadline['due_date']);
                                    $isThisWeek = $dueDate <= strtotime('+7 days');
                                    $isToday = date('Y-m-d', $dueDate) === date('Y-m-d');
                                    ?>
                                    <div class="list-group-item <?= $isToday ? 'list-group-item-warning' : ($isThisWeek ? 'list-group-item-info' : '') ?>">
                                        <div class="d-flex w-100 justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1"><?= htmlspecialchars($deadline['title']) ?></h6>
                                                <small class="text-muted">
                                                    Due: <?= date('M d, Y', strtotime($deadline['due_date'])) ?>
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge <?= $isToday ? 'bg-warning' : ($isThisWeek ? 'bg-info' : 'bg-secondary') ?>">
                                                    <?= date('M d, Y', strtotime($deadline['due_date'])) ?>
                                                </span>
                                                <a href="/lms-frontend/public/student/courses/<?= $deadline['course_id'] ?>/assignments/<?= $deadline['id'] ?>/submit" 
                                                   class="btn btn-sm btn-primary ms-2">
                                                    Submit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-4">
                <!-- Recent Announcements -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Recent Announcements</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($announcements)): ?>
                            <p class="text-muted mb-0">No recent announcements.</p>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($announcements as $announcement): ?>
                                    <div class="list-group-item px-0">
                                        <h6 class="mb-1"><?= htmlspecialchars($announcement['title']) ?></h6>
                                        <p class="mb-1 small"><?= htmlspecialchars($announcement['content']) ?></p>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($announcement['course_title'] ?? 'System') ?> - 
                                            <?= date('M d, Y', strtotime($announcement['created_at'])) ?>
                                        </small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Messages / Notifications -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Messages & Notifications</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($notifications)): ?>
                            <p class="text-muted mb-0">No new messages or notifications.</p>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($notifications as $notification): ?>
                                    <div class="list-group-item px-0">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas <?= $notification['type'] === 'message' ? 'fa-envelope' : 'fa-bell' ?> 
                                                           <?= $notification['type'] === 'message' ? 'text-primary' : 'text-warning' ?>"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="mb-1"><?= htmlspecialchars($notification['message']) ?></p>
                                                <small class="text-muted">
                                                    <?= date('M d, Y H:i', strtotime($notification['created_at'])) ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Calendar -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Calendar</h5>
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                events: <?= json_encode(array_map(function($event) {
                    return [
                        'title' => $event['title'],
                        'start' => $event['start_date'],
                        'end' => $event['end_date'],
                        'url' => $event['url'] ?? null,
                        'backgroundColor' => $event['type'] === 'assignment' ? '#dc3545' : 
                                          ($event['type'] === 'quiz' ? '#ffc107' : '#0dcaf0')
                    ];
                }, $calendarEvents)) ?>,
                eventClick: function(info) {
                    if (info.event.url) {
                        window.location.href = info.event.url;
                    }
                }
            });
            calendar.render();
        });
    </script>
</body>
</html> 