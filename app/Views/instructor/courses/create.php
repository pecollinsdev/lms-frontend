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
    <div class="course-creation-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
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
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="saveExitDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-save me-1"></i> Save & Exit
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="saveExitDropdown">
                                            <li>
                                                <a class="dropdown-item" href="/lms-frontend/public/instructor/courses">
                                                    <i class="fas fa-check-circle me-2"></i>Save and return to courses
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" id="clearDraftBtn">
                                                    <i class="fas fa-trash-alt me-2"></i>Clear draft and exit
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
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
    </div>

    <!-- Save Notification Container -->
    <div id="saveNotification" class="save-notification" style="display: none;">
        <i class="fas fa-check-circle me-1"></i> Draft saved
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

        // Auto-save functionality
        const form = document.querySelector('form');
        const formFields = form.querySelectorAll('input, textarea, select');
        const DRAFT_KEY = 'course_draft';
        const AUTO_SAVE_INTERVAL = 30000; // 30 seconds
        let isFirstLoad = true;
        let autoSaveInterval;
        let isLoadingDraft = false;
        const saveNotification = document.getElementById('saveNotification');
        const clearDraftBtn = document.getElementById('clearDraftBtn');

        // Function to show save notification
        function showSaveNotification() {
            saveNotification.style.display = 'block';
            setTimeout(() => {
                saveNotification.style.display = 'none';
            }, 2000);
        }

        // Function to save form data
        function saveFormData() {
            // Don't save on first load or while loading draft
            if (isFirstLoad || isLoadingDraft) return;

            const formData = {};
            formFields.forEach(field => {
                formData[field.name] = field.value;
            });
            localStorage.setItem(DRAFT_KEY, JSON.stringify(formData));
            showSaveNotification();
        }

        // Function to clear draft
        function clearDraft() {
            localStorage.removeItem(DRAFT_KEY);
            if (autoSaveInterval) {
                clearInterval(autoSaveInterval);
            }
            window.location.href = '/lms-frontend/public/instructor/courses';
        }

        // Clear draft button handler
        clearDraftBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to clear the draft and exit? This action cannot be undone.')) {
                clearDraft();
            }
        });

        // Function to load saved draft
        function loadSavedDraft() {
            isLoadingDraft = true;
            const savedData = localStorage.getItem(DRAFT_KEY);
            if (savedData) {
                const formData = JSON.parse(savedData);
                Object.keys(formData).forEach(key => {
                    const field = form.querySelector(`[name="${key}"]`);
                    if (field) {
                        // Temporarily remove event listeners
                        const newField = field.cloneNode(true);
                        field.parentNode.replaceChild(newField, field);
                        
                        // Set the value
                        newField.value = formData[key];
                        
                        // Re-add event listeners
                        newField.addEventListener('change', () => {
                            handleFirstInteraction();
                            saveFormData();
                        });
                        newField.addEventListener('input', debounce(() => {
                            handleFirstInteraction();
                            saveFormData();
                        }, 1000));
                        
                        // Handle date pickers
                        if (newField.classList.contains('datepicker')) {
                            const datePicker = newField._flatpickr;
                            if (datePicker) {
                                datePicker.setDate(formData[key]);
                            }
                        }
                    }
                });
            }
            isLoadingDraft = false;
        }

        // Function to start auto-save
        function startAutoSave() {
            if (!autoSaveInterval) {
                autoSaveInterval = setInterval(saveFormData, AUTO_SAVE_INTERVAL);
            }
        }

        // Function to handle first user interaction
        function handleFirstInteraction() {
            if (isFirstLoad) {
                isFirstLoad = false;
                startAutoSave();
            }
        }

        // Auto-save on field change
        formFields.forEach(field => {
            field.addEventListener('change', () => {
                handleFirstInteraction();
                saveFormData();
            });
            field.addEventListener('input', debounce(() => {
                handleFirstInteraction();
                saveFormData();
            }, 1000));
        });

        // Load saved draft on page load
        loadSavedDraft();

        // Clear draft when form is successfully submitted
        form.addEventListener('submit', function() {
            localStorage.removeItem(DRAFT_KEY);
            if (autoSaveInterval) {
                clearInterval(autoSaveInterval);
            }
        });

        // Debounce function to prevent too frequent saves
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    </script>

    <style>
        .save-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 0.875rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1050;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .save-notification[style*="display: block"] {
            opacity: 1;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
        }

        .dropdown-item i {
            width: 1rem;
            text-align: center;
        }

        .dropdown-item.text-danger:hover {
            background-color: #dc3545;
            color: white !important;
        }
    </style>
</body>
</html> 