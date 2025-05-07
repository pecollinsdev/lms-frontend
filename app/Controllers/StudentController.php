<?php

namespace App\Controllers;

use App\Core\Controller;
use GuzzleHttp\Client;

class StudentController extends Controller
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

            // Fetch student profile
            $profileResponse = $this->client->get($this->apiBase . 'student/profile');
            if ($profileResponse->getStatusCode() !== 200) {
                throw new \Exception('Failed to fetch profile: ' . $profileResponse->getReasonPhrase());
            }
            $profileData = json_decode($profileResponse->getBody(), true);
            $profile = $profileData['data'] ?? [];

            // Fetch enrolled courses
            $coursesResponse = $this->client->get($this->apiBase . 'student/courses');
            if ($coursesResponse->getStatusCode() !== 200) {
                throw new \Exception('Failed to fetch courses: ' . $coursesResponse->getReasonPhrase());
            }
            $coursesData = json_decode($coursesResponse->getBody(), true);
            $courses = $coursesData['data'] ?? [];

            // Fetch upcoming deadlines (assignments, quizzes, exams)
            $deadlinesResponse = $this->client->get($this->apiBase . 'student/deadlines');
            if ($deadlinesResponse->getStatusCode() !== 200) {
                throw new \Exception('Failed to fetch deadlines: ' . $deadlinesResponse->getReasonPhrase());
            }
            $deadlinesData = json_decode($deadlinesResponse->getBody(), true);
            $deadlines = $deadlinesData['data'] ?? [];

            // Fetch announcements
            $announcementsResponse = $this->client->get($this->apiBase . 'student/announcements');
            if ($announcementsResponse->getStatusCode() !== 200) {
                throw new \Exception('Failed to fetch announcements: ' . $announcementsResponse->getReasonPhrase());
            }
            $announcementsData = json_decode($announcementsResponse->getBody(), true);
            $announcements = $announcementsData['data'] ?? [];

            // Fetch notifications
            $notificationsResponse = $this->client->get($this->apiBase . 'student/notifications');
            if ($notificationsResponse->getStatusCode() !== 200) {
                throw new \Exception('Failed to fetch notifications: ' . $notificationsResponse->getReasonPhrase());
            }
            $notificationsData = json_decode($notificationsResponse->getBody(), true);
            $notifications = $notificationsData['data'] ?? [];

            // Fetch calendar events
            $calendarResponse = $this->client->get($this->apiBase . 'student/calendar');
            if ($calendarResponse->getStatusCode() !== 200) {
                throw new \Exception('Failed to fetch calendar events: ' . $calendarResponse->getReasonPhrase());
            }
            $calendarData = json_decode($calendarResponse->getBody(), true);
            $calendarEvents = $calendarData['data'] ?? [];

            $this->view('student/dashboard', [
                'profile' => $profile,
                'courses' => $courses,
                'deadlines' => $deadlines,
                'announcements' => $announcements,
                'notifications' => $notifications,
                'calendarEvents' => $calendarEvents
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $body = json_decode($response->getBody(), true);
            $errorMessage = $body['message'] ?? 'Failed to load dashboard data: ' . $e->getMessage();
            $this->view('student/dashboard', [
                'error' => $errorMessage,
                'profile' => [],
                'courses' => [],
                'deadlines' => [],
                'announcements' => [],
                'notifications' => [],
                'calendarEvents' => []
            ]);
        } catch (\Exception $e) {
            $this->view('student/dashboard', [
                'error' => 'Failed to load dashboard data: ' . $e->getMessage(),
                'profile' => [],
                'courses' => [],
                'deadlines' => [],
                'announcements' => [],
                'notifications' => [],
                'calendarEvents' => []
            ]);
        }
    }

    public function courses()
    {
        try {
            $response = $this->client->get($this->apiBase . 'student/courses');
            $courses = json_decode($response->getBody(), true)['data'] ?? [];
            $this->view('student/courses', ['courses' => $courses]);
        } catch (\Exception $e) {
            $this->view('student/courses', [
                'error' => 'Failed to load courses: ' . $e->getMessage()
            ]);
        }
    }

    public function assignments($courseId)
    {
        try {
            $response = $this->client->get($this->apiBase . "student/courses/{$courseId}/assignments");
            $assignments = json_decode($response->getBody(), true)['data'] ?? [];
            $this->view('student/assignments', [
                'courseId' => $courseId,
                'assignments' => $assignments
            ]);
        } catch (\Exception $e) {
            $this->view('student/assignments', [
                'error' => 'Failed to load assignments: ' . $e->getMessage()
            ]);
        }
    }

    public function submitAssignment($courseId, $assignmentId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $response = $this->client->post($this->apiBase . "assignments/{$assignmentId}/submissions", [
                    'json' => [
                        'content' => $_POST['content'],
                        'file_url' => $_POST['file_url'] ?? null,
                    ]
                ]);
                
                header("Location: /lms-frontend/public/student/courses/{$courseId}/assignments");
                exit;
            } catch (\Exception $e) {
                $this->view('student/submit-assignment', [
                    'courseId' => $courseId,
                    'assignmentId' => $assignmentId,
                    'error' => 'Failed to submit assignment: ' . $e->getMessage()
                ]);
            }
        } else {
            try {
                $response = $this->client->get($this->apiBase . "assignments/{$assignmentId}");
                $assignment = json_decode($response->getBody(), true)['data'] ?? [];
                $this->view('student/submit-assignment', [
                    'courseId' => $courseId,
                    'assignmentId' => $assignmentId,
                    'assignment' => $assignment
                ]);
            } catch (\Exception $e) {
                $this->view('student/submit-assignment', [
                    'error' => 'Failed to load assignment: ' . $e->getMessage()
                ]);
            }
        }
    }

    public function viewSubmission($courseId, $assignmentId, $submissionId)
    {
        try {
            $response = $this->client->get($this->apiBase . "submissions/{$submissionId}");
            $submission = json_decode($response->getBody(), true)['data'] ?? [];
            $this->view('student/view-submission', [
                'courseId' => $courseId,
                'assignmentId' => $assignmentId,
                'submission' => $submission
            ]);
        } catch (\Exception $e) {
            $this->view('student/view-submission', [
                'error' => 'Failed to load submission: ' . $e->getMessage()
            ]);
        }
    }

    public function progress($courseId)
    {
        try {
            $response = $this->client->get($this->apiBase . "student/courses/{$courseId}/progress");
            $progress = json_decode($response->getBody(), true)['data'] ?? [];
            $this->view('student/progress', [
                'courseId' => $courseId,
                'progress' => $progress
            ]);
        } catch (\Exception $e) {
            $this->view('student/progress', [
                'error' => 'Failed to load progress: ' . $e->getMessage()
            ]);
        }
    }
} 