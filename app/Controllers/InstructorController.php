<?php

namespace App\Controllers;

use App\Core\Controller;
use GuzzleHttp\Client;

class InstructorController extends Controller
{
    protected $apiBase = 'http://localhost/lms-api/api/public/api/';
    protected $client;

    public function __construct()
    {
        $token = $_COOKIE['token'] ?? '';
        if (empty($token)) {
            header('Location: /lms-frontend/public/auth/login');
            exit;
        }

        $this->client = new Client([
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ],
            'verify' => false // Only for development
        ]);
    }

    public function dashboard()
    {
        try {
            // First verify the token
            $verifyResponse = $this->client->get($this->apiBase . 'verify-token');
            if ($verifyResponse->getStatusCode() !== 200) {
                throw new \Exception('Invalid authentication token');
            }

            // Fetch dashboard data
            $dashboardResponse = $this->client->get($this->apiBase . 'instructor/dashboard');
            if ($dashboardResponse->getStatusCode() !== 200) {
                throw new \Exception('Failed to fetch dashboard data: ' . $dashboardResponse->getReasonPhrase());
            }
            $dashboardData = json_decode($dashboardResponse->getBody(), true);
            
            // Extract data from response
            $profile = $dashboardData['profile'] ?? [];
            $courses = $dashboardData['courses'] ?? [];
            $assignments = $dashboardData['recent_assignments'] ?? [];
            $submissions = $dashboardData['pending_submissions'] ?? [];
            $notifications = $dashboardData['notifications'] ?? [];
            
            // Process calendar events
            $calendarEvents = [];
            if (isset($dashboardData['calendar'])) {
                // Add assignments to calendar
                foreach ($dashboardData['calendar']['assignments'] as $assignment) {
                    $calendarEvents[] = [
                        'title' => $assignment['title'],
                        'start_date' => $assignment['due_date'],
                        'end_date' => $assignment['due_date'],
                        'type' => 'assignment',
                        'url' => "/lms-frontend/public/instructor/courses/{$assignment['course_id']}/assignments/{$assignment['id']}/submissions"
                    ];
                }
                
                // Add course dates to calendar
                foreach ($dashboardData['calendar']['courses'] as $course) {
                    $calendarEvents[] = [
                        'title' => $course['title'] . ' (Start)',
                        'start_date' => $course['start_date'],
                        'end_date' => $course['start_date'],
                        'type' => 'course'
                    ];
                    $calendarEvents[] = [
                        'title' => $course['title'] . ' (End)',
                        'start_date' => $course['end_date'],
                        'end_date' => $course['end_date'],
                        'type' => 'course'
                    ];
                }
            }

            $this->view('instructor/dashboard', [
                'profile' => $profile,
                'courses' => $courses,
                'assignments' => $assignments,
                'submissions' => $submissions,
                'notifications' => $notifications,
                'calendarEvents' => $calendarEvents
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $body = json_decode($response->getBody(), true);
            $errorMessage = $body['message'] ?? 'Failed to load dashboard data: ' . $e->getMessage();
            $this->view('instructor/dashboard', [
                'error' => $errorMessage,
                'profile' => [],
                'courses' => [],
                'assignments' => [],
                'submissions' => [],
                'notifications' => [],
                'calendarEvents' => []
            ]);
        } catch (\Exception $e) {
            $this->view('instructor/dashboard', [
                'error' => 'Failed to load dashboard data: ' . $e->getMessage(),
                'profile' => [],
                'courses' => [],
                'assignments' => [],
                'submissions' => [],
                'notifications' => [],
                'calendarEvents' => []
            ]);
        }
    }

    public function courses()
    {
        try {
            // Fetch paginated courses data from the API
            $response = $this->client->get($this->apiBase . 'courses');
            if ($response->getStatusCode() !== 200) {
                throw new \Exception('Failed to fetch courses: ' . $response->getReasonPhrase());
            }
            $data = json_decode($response->getBody(), true);
            $courses = $data['data'] ?? [];

            // Fetch instructor profile for nav
            $dashboardResponse = $this->client->get($this->apiBase . 'instructor/dashboard');
            $dashboardData = json_decode($dashboardResponse->getBody(), true);
            $profile = $dashboardData['profile'] ?? [];

            $this->view('instructor/courses', [
                'courses' => $courses,
                'profile' => $profile
            ]);
        } catch (\Exception $e) {
            $this->view('instructor/courses', [
                'error' => 'Failed to load courses: ' . $e->getMessage(),
                'courses' => [],
                'profile' => []
            ]);
        }
    }

    public function createCourse()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate required fields
                $requiredFields = ['title', 'description', 'start_date', 'end_date', 'level', 'category', 'max_students'];
                foreach ($requiredFields as $field) {
                    if (empty($_POST[$field])) {
                        throw new \Exception("Please fill in all required fields.");
                    }
                }

                // Prepare course data
                $courseData = [
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'start_date' => $_POST['start_date'],
                    'end_date' => $_POST['end_date'],
                    'level' => $_POST['level'],
                    'category' => $_POST['category'],
                    'max_students' => (int)$_POST['max_students'],
                    'prerequisites' => $_POST['prerequisites'] ?? null
                ];

                // Send request to create course
                $response = $this->client->post($this->apiBase . 'courses', [
                    'json' => $courseData
                ]);

                if ($response->getStatusCode() === 201) {
                    // Redirect to courses list on success
                    header('Location: /lms-frontend/public/instructor/courses');
                    exit;
                } else {
                    throw new \Exception('Failed to create course. Please try again.');
                }
            } catch (\Exception $e) {
                $this->view('instructor/courses/create', [
                    'error' => $e->getMessage(),
                    'formData' => $_POST // Pass form data back to repopulate the form
                ]);
            }
        } else {
            // Display the create course form
            $this->view('instructor/courses/create');
        }
    }

    public function editCourse($courseId)
    {
        try {
            // Fetch course details
            $response = $this->client->get($this->apiBase . "courses/{$courseId}");
            if ($response->getStatusCode() !== 200) {
                throw new \Exception('Failed to fetch course: ' . $response->getReasonPhrase());
            }
            $courseData = json_decode($response->getBody(), true);
            $course = $courseData['data'] ?? [];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                try {
                    // Validate required fields
                    $requiredFields = ['title', 'description', 'start_date', 'end_date', 'level', 'category', 'max_students', 'status'];
                    foreach ($requiredFields as $field) {
                        if (empty($_POST[$field])) {
                            throw new \Exception("Please fill in all required fields.");
                        }
                    }

                    // Prepare course data
                    $courseData = [
                        'title' => $_POST['title'],
                        'description' => $_POST['description'],
                        'start_date' => $_POST['start_date'],
                        'end_date' => $_POST['end_date'],
                        'level' => $_POST['level'],
                        'category' => $_POST['category'],
                        'max_students' => (int)$_POST['max_students'],
                        'prerequisites' => $_POST['prerequisites'] ?? null,
                        'status' => $_POST['status']
                    ];

                    // Send request to update course
                    $response = $this->client->put($this->apiBase . "courses/{$courseId}", [
                        'json' => $courseData
                    ]);

                    if ($response->getStatusCode() === 200) {
                        // Redirect to courses list on success
                        header('Location: /lms-frontend/public/instructor/courses');
                        exit;
                    } else {
                        throw new \Exception('Failed to update course. Please try again.');
                    }
                } catch (\Exception $e) {
                    $this->view('instructor/courses/edit', [
                        'error' => $e->getMessage(),
                        'course' => $course
                    ]);
                }
            } else {
                // Display the edit course form
                $this->view('instructor/courses/edit', [
                    'course' => $course
                ]);
            }
        } catch (\Exception $e) {
            $this->view('instructor/courses/edit', [
                'error' => 'Failed to load course: ' . $e->getMessage(),
                'course' => []
            ]);
        }
    }

    public function assignments($courseId)
    {
        try {
            $response = $this->client->get($this->apiBase . "courses/{$courseId}/assignments");
            $data = json_decode($response->getBody(), true);
            
            // Debug the API response
            error_log('Assignments API Response: ' . print_r($data, true));
            
            // Ensure we have an array of assignments
            $assignments = [];
            if (isset($data['data']) && is_array($data['data'])) {
                $assignments = $data['data'];
            } elseif (is_array($data)) {
                $assignments = $data;
            }

            // Debug the processed assignments
            error_log('Processed Assignments: ' . print_r($assignments, true));

            $this->view('instructor/courses/assignments/index', [
                'courseId' => $courseId,
                'assignments' => $assignments
            ]);
        } catch (\Exception $e) {
            error_log('Assignments Error: ' . $e->getMessage());
            $this->view('instructor/courses/assignments/index', [
                'courseId' => $courseId,
                'error' => 'Failed to load assignments: ' . $e->getMessage(),
                'assignments' => []
            ]);
        }
    }

    public function createAssignment($courseId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $response = $this->client->post($this->apiBase . "courses/{$courseId}/assignments", [
                    'json' => [
                        'title' => $_POST['title'],
                        'description' => $_POST['description'],
                        'due_date' => $_POST['due_date'],
                        'total_points' => $_POST['total_points'],
                    ]
                ]);
                
                header("Location: /lms-frontend/public/instructor/courses/{$courseId}/assignments");
                exit;
            } catch (\Exception $e) {
                $this->view('instructor/courses/assignments/create', [
                    'courseId' => $courseId,
                    'error' => 'Failed to create assignment: ' . $e->getMessage()
                ]);
            }
        } else {
            $this->view('instructor/courses/assignments/create', ['courseId' => $courseId]);
        }
    }

    public function submissions($courseId, $assignmentId)
    {
        try {
            // Get assignment details
            $assignmentResponse = $this->client->get($this->apiBase . "courses/{$courseId}/assignments/{$assignmentId}");
            $assignmentData = json_decode($assignmentResponse->getBody(), true);
            $assignment = $assignmentData['data'] ?? [];

            // Get current page from query string, default to 1
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = 15; // Default page size

            // Get submissions for this specific assignment with pagination
            $submissionsResponse = $this->client->get($this->apiBase . "courses/{$courseId}/assignments/{$assignmentId}/submissions", [
                'query' => [
                    'page' => $page,
                    'per_page' => $perPage
                ]
            ]);
            $submissionsData = json_decode($submissionsResponse->getBody(), true);
            
            // Debug the API responses
            error_log('Assignment API Response: ' . print_r($assignmentData, true));
            error_log('Submissions API Response: ' . print_r($submissionsData, true));

            // Process submissions data
            $submissions = [];
            if (isset($submissionsData['data']) && is_array($submissionsData['data'])) {
                // Handle paginated response
                $submissions = $submissionsData;
                
                // Process each submission to ensure student data is properly structured
                foreach ($submissions['data']['data'] as &$submission) {
                    // Ensure student data is properly structured
                    $submission['student'] = [
                        'name' => $submission['student']['name'] ?? 'Unknown Student',
                        'email' => $submission['student']['email'] ?? 'No email',
                        'profile_picture' => $submission['student']['profile_picture'] ?? null
                    ];
                }
            } elseif (is_array($submissionsData)) {
                // Handle non-paginated response
                $submissions = [
                    'data' => array_map(function($submission) {
                        // Ensure each submission has required fields
                        return array_merge([
                            'id' => $submission['id'] ?? null,
                            'status' => $submission['status'] ?? 'pending',
                            'submitted_at' => $submission['submitted_at'] ?? null,
                            'score' => $submission['grade'] ?? null,
                            'submission_type' => $submission['submission_type'] ?? null,
                            'file_url' => $submission['file_path'] ?? null,
                            'student' => [
                                'name' => $submission['student']['name'] ?? 'Unknown Student',
                                'email' => $submission['student']['email'] ?? 'No email',
                                'profile_picture' => $submission['student']['profile_picture'] ?? null
                            ]
                        ], $submission);
                    }, $submissionsData),
                    'current_page' => $page,
                    'last_page' => 1,
                    'total' => count($submissionsData),
                    'per_page' => $perPage
                ];
            }

            // Debug processed data
            error_log('Processed Assignment: ' . print_r($assignment, true));
            error_log('Processed Submissions: ' . print_r($submissions, true));

            $this->view('instructor/courses/assignments/submissions', [
                'courseId' => $courseId,
                'assignmentId' => $assignmentId,
                'assignment' => $assignment,
                'submissions' => $submissions
            ]);
        } catch (\Exception $e) {
            error_log('Submissions Error: ' . $e->getMessage());
            $this->view('instructor/courses/assignments/submissions', [
                'courseId' => $courseId,
                'assignmentId' => $assignmentId,
                'error' => 'Failed to load submissions: ' . $e->getMessage(),
                'assignment' => [],
                'submissions' => ['data' => []]
            ]);
        }
    }

    public function gradeSubmission($courseId, $assignmentId, $submissionId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $response = $this->client->patch($this->apiBase . "courses/{$courseId}/assignments/{$assignmentId}/submissions/{$submissionId}", [
                    'json' => [
                        'score' => $_POST['score'],
                        'feedback' => $_POST['feedback'],
                    ]
                ]);
                
                header("Location: /lms-frontend/public/instructor/courses/{$courseId}/assignments/{$assignmentId}/submissions");
                exit;
            } catch (\Exception $e) {
                $this->view('instructor/courses/assignments/grade', [
                    'error' => 'Failed to grade submission: ' . $e->getMessage()
                ]);
            }
        } else {
            try {
                $response = $this->client->get($this->apiBase . "courses/{$courseId}/assignments/{$assignmentId}/submissions/{$submissionId}");
                $submission = json_decode($response->getBody(), true)['data'] ?? [];
                $this->view('instructor/courses/assignments/grade', [
                    'courseId' => $courseId,
                    'assignmentId' => $assignmentId,
                    'submission' => $submission
                ]);
            } catch (\Exception $e) {
                $this->view('instructor/courses/assignments/grade', [
                    'error' => 'Failed to load submission: ' . $e->getMessage()
                ]);
            }
        }
    }

    public function studentProgress($courseId)
    {
        try {
            $response = $this->client->get($this->apiBase . "courses/{$courseId}/progress");
            $progress = json_decode($response->getBody(), true)['data'] ?? [];
            $this->view('instructor/student-progress', [
                'courseId' => $courseId,
                'progress' => $progress
            ]);
        } catch (\Exception $e) {
            $this->view('instructor/student-progress', [
                'error' => 'Failed to load student progress: ' . $e->getMessage()
            ]);
        }
    }

    public function showCourse($courseId)
    {
        try {
            $client = $this->client;
            // Fetch course details with modules and items
            $response = $client->get($this->apiBase . "courses/{$courseId}", [
                'query' => [
                    'include' => 'modules.items'
                ]
            ]);
            if ($response->getStatusCode() !== 200) {
                throw new \Exception('Failed to fetch course: ' . $response->getReasonPhrase());
            }
            $courseResponse = json_decode($response->getBody(), true);
            $course = $courseResponse['data'] ?? [];

            // Calculate total items and modules
            $totalItems = 0;
            $totalModules = count($course['modules'] ?? []);
            if (!empty($course['modules'])) {
                foreach ($course['modules'] as $module) {
                    $totalItems += count($module['items'] ?? []);
                }
            }
            $course['total_items'] = $totalItems;
            $course['module_count'] = $totalModules;

            // Fetch instructor profile for nav (still using dashboard, or replace with /instructor/profile if available)
            $dashboardResponse = $client->get($this->apiBase . 'instructor/dashboard');
            $dashboardData = json_decode($dashboardResponse->getBody(), true);
            $profile = $dashboardData['profile'] ?? [];

            $this->view('instructor/courses/show', [
                'course' => $course,
                'profile' => $profile,
                'modules' => $course['modules'] ?? []
            ]);
        } catch (\Exception $e) {
            $this->view('instructor/courses/show', [
                'error' => 'Failed to load course: ' . $e->getMessage(),
                'course' => []
            ]);
        }
    }

    public function showAssignment($courseId, $assignmentId)
    {
        try {
            // Get assignment details
            $assignmentResponse = $this->client->get($this->apiBase . "courses/{$courseId}/assignments/{$assignmentId}");
            if ($assignmentResponse->getStatusCode() !== 200) {
                throw new \Exception('Failed to fetch assignment: ' . $assignmentResponse->getReasonPhrase());
            }
            $assignmentData = json_decode($assignmentResponse->getBody(), true);
            $assignment = $assignmentData['data'] ?? [];

            // Get recent submissions
            $submissionsResponse = $this->client->get($this->apiBase . "courses/{$courseId}/assignments/{$assignmentId}/submissions", [
                'query' => [
                    'page' => 1,
                    'per_page' => 5 // Get only 5 most recent submissions
                ]
            ]);
            $submissionsData = json_decode($submissionsResponse->getBody(), true);
            $recentSubmissions = $submissionsData['data'] ?? [];

            $this->view('instructor/courses/assignments/show', [
                'courseId' => $courseId,
                'assignment' => $assignment,
                'recentSubmissions' => $recentSubmissions
            ]);
        } catch (\Exception $e) {
            error_log('Show Assignment Error: ' . $e->getMessage());
            $this->view('instructor/courses/assignments/show', [
                'courseId' => $courseId,
                'error' => 'Failed to load assignment: ' . $e->getMessage(),
                'assignment' => [],
                'recentSubmissions' => []
            ]);
        }
    }

    public function editAssignment($courseId, $assignmentId)
    {
        try {
            // Get assignment details
            $assignmentResponse = $this->client->get($this->apiBase . "courses/{$courseId}/assignments/{$assignmentId}");
            if ($assignmentResponse->getStatusCode() !== 200) {
                throw new \Exception('Failed to fetch assignment: ' . $assignmentResponse->getReasonPhrase());
            }
            $assignmentData = json_decode($assignmentResponse->getBody(), true);
            $assignment = $assignmentData['data'] ?? [];

            // Get instructor profile for nav
            $dashboardResponse = $this->client->get($this->apiBase . 'instructor/dashboard');
            $dashboardData = json_decode($dashboardResponse->getBody(), true);
            $profile = $dashboardData['profile'] ?? [];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                try {
                    $response = $this->client->patch($this->apiBase . "courses/{$courseId}/assignments/{$assignmentId}", [
                        'json' => [
                            'title' => $_POST['title'],
                            'description' => $_POST['description'],
                            'due_date' => $_POST['due_date'],
                            'total_points' => $_POST['total_points'],
                            'status' => $_POST['status']
                        ]
                    ]);
                    
                    header("Location: /lms-frontend/public/instructor/courses/{$courseId}/assignments/{$assignmentId}");
                    exit;
                } catch (\Exception $e) {
                    $this->view('instructor/courses/assignments/edit', [
                        'courseId' => $courseId,
                        'assignment' => $assignment,
                        'profile' => $profile,
                        'error' => 'Failed to update assignment: ' . $e->getMessage()
                    ]);
                }
            } else {
                $this->view('instructor/courses/assignments/edit', [
                    'courseId' => $courseId,
                    'assignment' => $assignment,
                    'profile' => $profile
                ]);
            }
        } catch (\Exception $e) {
            error_log('Edit Assignment Error: ' . $e->getMessage());
            $this->view('instructor/courses/assignments/edit', [
                'courseId' => $courseId,
                'error' => 'Failed to load assignment: ' . $e->getMessage(),
                'assignment' => [],
                'profile' => []
            ]);
        }
    }

    public function moduleItem($itemId)
    {
        try {
            $response = $this->client->get($this->apiBase . "module-items/{$itemId}", [
                'query' => [
                    'include' => 'module.course'
                ]
            ]);
            $data = json_decode($response->getBody(), true);
            $item = $data['data'] ?? [];

            // Fetch instructor profile for nav
            $dashboardResponse = $this->client->get($this->apiBase . 'instructor/dashboard');
            $dashboardData = json_decode($dashboardResponse->getBody(), true);
            $profile = $dashboardData['profile'] ?? [];

            $this->view('instructor/courses/module_item', [
                'item' => $item,
                'profile' => $profile
            ]);
        } catch (\Exception $e) {
            $this->view('instructor/courses/module_item', [
                'error' => 'Failed to load module item: ' . $e->getMessage(),
                'item' => []
            ]);
        }
    }

    public function index()
    {
        header('Location: /lms-frontend/public/instructor/dashboard');
        exit;
    }
} 