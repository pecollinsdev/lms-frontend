<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course - LMS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/lms-frontend/public/css/style.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Flatpickr CSS for date picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Create New Course</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <form action="/lms-frontend/public/instructor/courses/create" method="POST" class="needs-validation" novalidate>
                            <!-- Course Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Course Title <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control" 
                                       id="title" 
                                       name="title" 
                                       required 
                                       minlength="3" 
                                       maxlength="100"
                                       placeholder="Enter course title">
                                <div class="invalid-feedback">
                                    Please enter a valid course title (3-100 characters).
                                </div>
                            </div>

                            <!-- Course Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Course Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" 
                                          id="description" 
                                          name="description" 
                                          rows="4" 
                                          required 
                                          minlength="10" 
                                          maxlength="1000"
                                          placeholder="Enter course description"></textarea>
                                <div class="invalid-feedback">
                                    Please enter a valid course description (10-1000 characters).
                                </div>
                            </div>

                            <!-- Course Dates -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control datepicker" 
                                           id="start_date" 
                                           name="start_date" 
                                           required
                                           placeholder="Select start date">
                                    <div class="invalid-feedback">
                                        Please select a valid start date.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control datepicker" 
                                           id="end_date" 
                                           name="end_date" 
                                           required
                                           placeholder="Select end date">
                                    <div class="invalid-feedback">
                                        Please select a valid end date.
                                    </div>
                                </div>
                            </div>

                            <!-- Course Level -->
                            <div class="mb-3">
                                <label for="level" class="form-label">Course Level <span class="text-danger">*</span></label>
                                <select class="form-select" id="level" name="level" required>
                                    <option value="">Select course level</option>
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="advanced">Advanced</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select a course level.
                                </div>
                            </div>

                            <!-- Course Category -->
                            <div class="mb-3">
                                <label for="category" class="form-label">Course Category <span class="text-danger">*</span></label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Select course category</option>
                                    <option value="programming">Programming</option>
                                    <option value="mathematics">Mathematics</option>
                                    <option value="science">Science</option>
                                    <option value="language">Language</option>
                                    <option value="business">Business</option>
                                    <option value="arts">Arts</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select a course category.
                                </div>
                            </div>

                            <!-- Course Capacity -->
                            <div class="mb-3">
                                <label for="max_students" class="form-label">Maximum Students <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control" 
                                       id="max_students" 
                                       name="max_students" 
                                       required 
                                       min="1" 
                                       max="100"
                                       placeholder="Enter maximum number of students">
                                <div class="invalid-feedback">
                                    Please enter a valid number of students (1-100).
                                </div>
                            </div>

                            <!-- Course Prerequisites -->
                            <div class="mb-3">
                                <label for="prerequisites" class="form-label">Prerequisites</label>
                                <textarea class="form-control" 
                                          id="prerequisites" 
                                          name="prerequisites" 
                                          rows="2" 
                                          maxlength="500"
                                          placeholder="Enter any prerequisites for the course"></textarea>
                                <div class="form-text">
                                    Optional: List any prerequisites or requirements for students.
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="/lms-frontend/public/instructor/courses" class="btn btn-outline-secondary me-md-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Create Course
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Flatpickr JS for date picker -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize date pickers
        flatpickr(".datepicker", {
            dateFormat: "Y-m-d",
            minDate: "today",
            allowInput: true
        });

        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()

        // End date validation
        document.getElementById('start_date').addEventListener('change', function() {
            const endDatePicker = document.getElementById('end_date')._flatpickr;
            endDatePicker.set('minDate', this.value);
        });
    </script>
</body>
</html> 