<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course - LMS</title>
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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Edit Course</h4>
                            <span class="badge bg-<?= $course['status'] === 'active' ? 'success' : 'secondary' ?>">
                                <?= ucfirst($course['status']) ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= htmlspecialchars($error) ?>
                                </div>
                            <?php endif; ?>

                            <form action="/lms-frontend/public/instructor/courses/edit/<?= $course['id'] ?>" method="POST" class="needs-validation" novalidate>
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
                                           value="<?= htmlspecialchars($course['title']) ?>"
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
                                              placeholder="Enter course description"><?= htmlspecialchars($course['description']) ?></textarea>
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
                                               value="<?= htmlspecialchars($course['start_date']) ?>"
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
                                               value="<?= htmlspecialchars($course['end_date']) ?>"
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
                                        <option value="beginner" <?= $course['level'] === 'beginner' ? 'selected' : '' ?>>Beginner</option>
                                        <option value="intermediate" <?= $course['level'] === 'intermediate' ? 'selected' : '' ?>>Intermediate</option>
                                        <option value="advanced" <?= $course['level'] === 'advanced' ? 'selected' : '' ?>>Advanced</option>
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
                                        <option value="programming" <?= $course['category'] === 'programming' ? 'selected' : '' ?>>Programming</option>
                                        <option value="mathematics" <?= $course['category'] === 'mathematics' ? 'selected' : '' ?>>Mathematics</option>
                                        <option value="science" <?= $course['category'] === 'science' ? 'selected' : '' ?>>Science</option>
                                        <option value="language" <?= $course['category'] === 'language' ? 'selected' : '' ?>>Language</option>
                                        <option value="business" <?= $course['category'] === 'business' ? 'selected' : '' ?>>Business</option>
                                        <option value="arts" <?= $course['category'] === 'arts' ? 'selected' : '' ?>>Arts</option>
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
                                           value="<?= htmlspecialchars($course['max_students']) ?>"
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
                                              placeholder="Enter any prerequisites for the course"><?= htmlspecialchars($course['prerequisites'] ?? '') ?></textarea>
                                    <div class="form-text">
                                        Optional: List any prerequisites or requirements for students.
                                    </div>
                                </div>

                                <!-- Course Status -->
                                <div class="mb-3">
                                    <label for="status" class="form-label">Course Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="draft" <?= $course['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                                        <option value="active" <?= $course['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="archived" <?= $course['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a course status.
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-outline-secondary" id="exitBtn" data-bs-toggle="modal" data-bs-target="#exitModal">
                                        <i class="fas fa-times me-1"></i> Exit
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="confirmEditBtn">
                                        <i class="fas fa-check me-1"></i> Confirm Edit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exit Confirmation Modal -->
    <div class="modal fade" id="exitModal" tabindex="-1" aria-labelledby="exitModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exitModalLabel">Unsaved Draft</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>You have an unsaved draft. What would you like to do?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continue Editing</button>
                    <button type="button" class="btn btn-danger" id="discardDraftBtn">Discard Draft</button>
                    <button type="button" class="btn btn-primary" id="saveDraftBtn">Save Draft & Exit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Save Notification Container -->
    <div id="saveNotification" class="save-notification" style="display: none;">
        <i class="fas fa-check-circle me-1"></i> <span id="saveNotificationText">Draft saved</span>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Flatpickr JS for date picker -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // State management
        const state = {
            hasUnsavedChanges: false,
            isSaving: false,
            isSubmitting: false
        };

        // DOM Elements
        const elements = {
            form: document.querySelector('form'),
            formFields: document.querySelectorAll('input, textarea, select'),
            confirmEditBtn: document.getElementById('confirmEditBtn'),
            exitBtn: document.getElementById('exitBtn'),
            saveDraftBtn: document.getElementById('saveDraftBtn'),
            discardDraftBtn: document.getElementById('discardDraftBtn'),
            saveNotification: document.getElementById('saveNotification'),
            saveNotificationText: document.getElementById('saveNotificationText'),
            exitModal: new bootstrap.Modal(document.getElementById('exitModal'))
        };

        // Constants
        const CONSTANTS = {
            DRAFT_KEY: 'course_edit_draft_<?= $course['id'] ?>',
            NOTIFICATION_DURATION: 2000
        };

        // Initialize date pickers
        const datePickers = {
            start: flatpickr("#start_date", {
                dateFormat: "Y-m-d",
                minDate: "today",
                allowInput: true,
                onChange: handleDateChange
            }),
            end: flatpickr("#end_date", {
                dateFormat: "Y-m-d",
                minDate: "today",
                allowInput: true
            })
        };

        // Form validation
        (function() {
            'use strict';
            elements.form.addEventListener('submit', function(event) {
                event.preventDefault();
                
                if (!elements.form.checkValidity()) {
                    event.stopPropagation();
                    elements.form.classList.add('was-validated');
                    return;
                }

                if (state.isSubmitting) return;
                state.isSubmitting = true;
                elements.confirmEditBtn.disabled = true;

                // Clear draft before submitting
                localStorage.removeItem(CONSTANTS.DRAFT_KEY);
                
                // Submit the form
                elements.form.submit();
            });
        })();

        // Date picker validation
        function handleDateChange(selectedDates, dateStr) {
            datePickers.end.set('minDate', dateStr);
        }

        // Notification handling
        const notifications = {
            show: function(message) {
                elements.saveNotificationText.textContent = message;
                elements.saveNotification.style.display = 'block';
                setTimeout(() => {
                    elements.saveNotification.style.display = 'none';
                }, CONSTANTS.NOTIFICATION_DURATION);
            }
        };

        // Form data handling
        const formData = {
            collect: function() {
                const data = {};
                elements.formFields.forEach(field => {
                    data[field.name] = field.value;
                });
                return data;
            },

            saveDraft: async function() {
                if (state.isSaving) return;
                state.isSaving = true;

                try {
                    const data = this.collect();
                    localStorage.setItem(CONSTANTS.DRAFT_KEY, JSON.stringify(data));
                    state.hasUnsavedChanges = false;
                    notifications.show('Draft saved');
                } catch (error) {
                    console.error('Error saving draft:', error);
                    notifications.show('Error saving draft');
                } finally {
                    state.isSaving = false;
                }
            },

            load: function() {
                try {
                    const savedData = localStorage.getItem(CONSTANTS.DRAFT_KEY);
                    if (savedData) {
                        const data = JSON.parse(savedData);
                        Object.keys(data).forEach(key => {
                            const field = elements.form.querySelector(`[name="${key}"]`);
                            if (field) {
                                field.value = data[key];
                                if (field.classList.contains('datepicker')) {
                                    const datePicker = field._flatpickr;
                                    if (datePicker) {
                                        datePicker.setDate(data[key]);
                                    }
                                }
                            }
                        });
                    }
                } catch (error) {
                    console.error('Error loading draft:', error);
                    notifications.show('Error loading draft');
                }
            }
        };

        // Initialize event listeners
        function initializeEventListeners() {
            // Form field changes
            elements.formFields.forEach(field => {
                field.addEventListener('change', () => {
                    state.hasUnsavedChanges = true;
                });
            });

            // Exit handling
            elements.saveDraftBtn.addEventListener('click', async function() {
                await formData.saveDraft();
                window.location.href = '/lms-frontend/public/instructor/courses';
            });

            elements.discardDraftBtn.addEventListener('click', function() {
                localStorage.removeItem(CONSTANTS.DRAFT_KEY);
                window.location.href = '/lms-frontend/public/instructor/courses';
            });

            // Window beforeunload
            window.addEventListener('beforeunload', function(e) {
                if (state.hasUnsavedChanges) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });
        }

        // Initialize
        function initialize() {
            formData.load();
            initializeEventListeners();
        }

        // Start the application
        initialize();
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

        .btn:disabled {
            cursor: not-allowed;
            opacity: 0.65;
        }
    </style>
</body>
</html> 