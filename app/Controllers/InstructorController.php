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

            // Fetch instructor's courses
            $coursesResponse = $this->client->get($this->apiBase . 'courses');
            if ($coursesResponse->getStatusCode() !== 200) {
                throw new \Exception('Failed to fetch courses: ' . $coursesResponse->getReasonPhrase());
            }
            $coursesData = json_decode($coursesResponse->getBody(), true);
            $courses = $coursesData['data'] ?? [];

            // Fetch recent assignments
            $assignmentsResponse = $this->client->get($this->apiBase . 'courses/assignments');
            if ($assignmentsResponse->getStatusCode() !== 200) {
                throw new \Exception('Failed to fetch assignments: ' . $assignmentsResponse->getReasonPhrase());
            }
            $assignmentsData = json_decode($assignmentsResponse->getBody(), true);
            $assignments = $assignmentsData['data'] ?? [];

            // Fetch pending submissions
            $submissionsResponse = $this->client->get($this->apiBase . 'assignments/submissions');
            if ($submissionsResponse->getStatusCode() !== 200) {
                throw new \Exception('Failed to fetch submissions: ' . $submissionsResponse->getReasonPhrase());
            }
            $submissionsData = json_decode($submissionsResponse->getBody(), true);
            $submissions = $submissionsData['data'] ?? [];

            $this->view('instructor/dashboard', [
                'courses' => $courses,
                'assignments' => $assignments,
                'submissions' => $submissions
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $body = json_decode($response->getBody(), true);
            $errorMessage = $body['message'] ?? 'Failed to load dashboard data: ' . $e->getMessage();
            $this->view('instructor/dashboard', [
                'error' => $errorMessage,
                'courses' => [],
                'assignments' => [],
                'submissions' => []
            ]);
        } catch (\Exception $e) {
            $this->view('instructor/dashboard', [
                'error' => 'Failed to load dashboard data: ' . $e->getMessage(),
                'courses' => [],
                'assignments' => [],
                'submissions' => []
            ]);
        }
    }

    public function courses()
    {
        try {
            $response = $this->client->get($this->apiBase . 'courses');
            $courses = json_decode($response->getBody(), true)['data'] ?? [];
            $this->view('instructor/courses', ['courses' => $courses]);
        } catch (\Exception $e) {
            $this->view('instructor/courses', [
                'error' => 'Failed to load courses: ' . $e->getMessage()
            ]);
        }
    }

    public function createCourse()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $response = $this->client->post($this->apiBase . 'courses', [
                    'json' => [
                        'title' => $_POST['title'],
                        'description' => $_POST['description'],
                        'start_date' => $_POST['start_date'],
                        'end_date' => $_POST['end_date'],
                        'max_students' => $_POST['max_students'],
                    ]
                ]);
                
                header('Location: /lms-frontend/public/instructor/courses');
                exit;
            } catch (\Exception $e) {
                $this->view('instructor/create-course', [
                    'error' => 'Failed to create course: ' . $e->getMessage()
                ]);
            }
        } else {
            $this->view('instructor/create-course');
        }
    }

    public function assignments($courseId)
    {
        try {
            $response = $this->client->get($this->apiBase . "courses/{$courseId}/assignments");
            $assignments = json_decode($response->getBody(), true)['data'] ?? [];
            $this->view('instructor/assignments', [
                'courseId' => $courseId,
                'assignments' => $assignments
            ]);
        } catch (\Exception $e) {
            $this->view('instructor/assignments', [
                'error' => 'Failed to load assignments: ' . $e->getMessage()
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
                $this->view('instructor/create-assignment', [
                    'courseId' => $courseId,
                    'error' => 'Failed to create assignment: ' . $e->getMessage()
                ]);
            }
        } else {
            $this->view('instructor/create-assignment', ['courseId' => $courseId]);
        }
    }

    public function submissions($courseId, $assignmentId)
    {
        try {
            $response = $this->client->get($this->apiBase . "assignments/{$assignmentId}/submissions");
            $submissions = json_decode($response->getBody(), true)['data'] ?? [];
            $this->view('instructor/submissions', [
                'courseId' => $courseId,
                'assignmentId' => $assignmentId,
                'submissions' => $submissions
            ]);
        } catch (\Exception $e) {
            $this->view('instructor/submissions', [
                'error' => 'Failed to load submissions: ' . $e->getMessage()
            ]);
        }
    }

    public function gradeSubmission($courseId, $assignmentId, $submissionId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $response = $this->client->patch($this->apiBase . "submissions/{$submissionId}", [
                    'json' => [
                        'score' => $_POST['score'],
                        'feedback' => $_POST['feedback'],
                    ]
                ]);
                
                header("Location: /lms-frontend/public/instructor/courses/{$courseId}/assignments/{$assignmentId}/submissions");
                exit;
            } catch (\Exception $e) {
                $this->view('instructor/grade-submission', [
                    'error' => 'Failed to grade submission: ' . $e->getMessage()
                ]);
            }
        } else {
            try {
                $response = $this->client->get($this->apiBase . "submissions/{$submissionId}");
                $submission = json_decode($response->getBody(), true)['data'] ?? [];
                $this->view('instructor/grade-submission', [
                    'courseId' => $courseId,
                    'assignmentId' => $assignmentId,
                    'submission' => $submission
                ]);
            } catch (\Exception $e) {
                $this->view('instructor/grade-submission', [
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
} 