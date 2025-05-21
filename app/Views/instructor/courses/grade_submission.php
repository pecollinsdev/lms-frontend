<?php
\App\Core\Layout::start('main');

\App\Core\Layout::section('title');
echo 'Grade Submission - LMS';
\App\Core\Layout::endSection();

\App\Core\Layout::section('bodyClass');
echo 'bg-light';
\App\Core\Layout::endSection();

\App\Core\Layout::section('content');
?>

<!-- Main Content -->
<div class="container mt-5 pt-5">
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h2 class="mb-1">Grade Submission</h2>
                    <p class="text-muted mb-0">
                        <?= htmlspecialchars($moduleItem['title'] ?? '') ?>
                    </p>
                </div>
                <div class="text-end">
                    <span class="badge bg-<?= ($moduleItem['type'] ?? '') === 'quiz' ? 'primary' : 'success' ?> mb-2">
                        <?= ucfirst(htmlspecialchars($moduleItem['type'] ?? 'unknown')) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Information -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-user me-2"></i>Student Information
            </h5>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <img src="<?= isset($submission['user']['profile_picture']) && !empty($submission['user']['profile_picture']) ? $submission['user']['profile_picture'] : 'https://ui-avatars.com/api/?name=' . urlencode($submission['user']['name'] ?? 'Student') ?>" 
                         class="rounded-circle" 
                         width="60" 
                         height="60" 
                         alt="Student Avatar">
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="mb-1"><?= htmlspecialchars($submission['user']['name'] ?? 'Unknown Student') ?></h5>
                    <p class="text-muted mb-0"><?= htmlspecialchars($submission['user']['email'] ?? '') ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Grading Form -->
    <form action="/lms-frontend/public/instructor/courses/<?= $courseId ?>/items/<?= $moduleItem['id'] ?>/submissions/<?= $submission['id'] ?>/grade" method="POST">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-check-circle me-2"></i>Grade Details
                </h5>
            </div>
            <div class="card-body">
                <?php if ($moduleItem['type'] === 'quiz'): ?>
                    <!-- Quiz Grading -->
                    <?php
                    $content = json_decode($submission['content'] ?? '{}', true);
                    $answers = $content['answers'] ?? [];
                    $totalScore = 0;
                    $maxScore = 0;
                    ?>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Question</th>
                                    <th>Student's Answer</th>
                                    <th>Result</th>
                                    <th>Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $questions = $moduleItem['questions'] ?? [];
                                if (empty($questions)) {
                                    echo '<tr><td colspan="4" class="text-center">No questions found in the quiz</td></tr>';
                                } else {
                                    foreach ($questions as $question): 
                                        $answer = array_filter($answers, function($a) use ($question) {
                                            return isset($a['question_id']) && $a['question_id'] == $question['id'];
                                        });
                                        $answer = reset($answer);
                                        $selectedOption = null;
                                        if ($answer && isset($answer['selected_option_id'])) {
                                            $selectedOption = array_filter($question['options'] ?? [], function($o) use ($answer) {
                                                return $o['id'] == $answer['selected_option_id'];
                                            });
                                            $selectedOption = reset($selectedOption);
                                        }
                                        $points = ($answer && $answer['is_correct']) ? 10 : 0;
                                        $totalScore += $points;
                                        $maxScore += 10;
                                    ?>
                                        <tr>
                                            <td>
                                                <?php 
                                                if (isset($question['text'])) {
                                                    echo htmlspecialchars($question['text']);
                                                } else {
                                                    echo '<span class="text-danger">Question text missing (ID: ' . htmlspecialchars($question['id']) . ')</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                if ($selectedOption) {
                                                    echo htmlspecialchars($selectedOption['text']);
                                                } else {
                                                    echo '<span class="text-muted">No answer provided</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($answer && $answer['is_correct']): ?>
                                                    <span class="badge bg-success">Correct</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Incorrect</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $points ?>/10</td>
                                        </tr>
                                    <?php endforeach;
                                } ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">Total Score:</th>
                                    <th><?= $totalScore ?>/<?= $maxScore ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <input type="hidden" name="score" value="<?= $totalScore ?>">
                <?php else: ?>
                    <!-- Assignment Grading -->
                    <div class="mb-4">
                        <label for="score" class="form-label">Score</label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control" 
                                   id="score" 
                                   name="score" 
                                   min="0" 
                                   max="<?= $moduleItem['max_score'] ?? 100 ?>" 
                                   value="<?= $submission['score'] ?? $submission['grade'] ?? 0 ?>" 
                                   required>
                            <span class="input-group-text">/<?= $moduleItem['max_score'] ?? 100 ?></span>
                        </div>
                        <div class="form-text">
                            Maximum score: <?= $moduleItem['max_score'] ?? 100 ?> points
                        </div>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="feedback" class="form-label">Feedback</label>
                    <textarea class="form-control" 
                              id="feedback" 
                              name="feedback" 
                              rows="4" 
                              placeholder="Provide feedback for the student..."><?= htmlspecialchars($submission['feedback'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-end gap-2">
            <button type="button" onclick="window.history.back()" class="btn btn-outline-secondary">
                Cancel
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Save Grade
            </button>
        </div>
    </form>
</div>

<?php
\App\Core\Layout::endSection();

\App\Core\Layout::end();
?> 