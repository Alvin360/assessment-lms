<?php
    require_once '../includes/config_session_inc.php';
    require_once '../includes/dbh_inc.php';
    require_once '../includes/execute_query_inc.php';

    if (!isset($_SESSION['user_ID'])) {
        echo json_encode(['error' => 'User ID not found in session.']);
        exit();
    }

    $user_ID = $_SESSION['user_ID'];
    $subject_assessments = [];

    $query_course = "SELECT course_ID FROM course_enrolled WHERE user_ID = '$user_ID'";
    $result_course = executeQuery($conn, $query_course);
    $course_ID = mysqli_fetch_assoc($result_course['result'])['course_ID'];

    // Get subjects of the given course
    $query_subjects = "SELECT subject_ID, subject_name FROM subject WHERE course_ID = '$course_ID'";
    $result_subjects = executeQuery($conn, $query_subjects);

    $subject_Codes = [];
    $subject_Map = [];

    while ($row = $result_subjects['result']->fetch_assoc()) {
        $subject_Codes[] = $row['subject_ID'];
        $subject_Map[$row['subject_ID']] = $row['subject_name']; // Map subject_ID to subject_name
        $subject_assessments[$row['subject_name']] = [];
    }

    $subject_Codes_in = implode("','", $subject_Codes);

    // Get assessment details
    $query_assessments = "SELECT assessment_ID, subject_Code, assessment_name, open_Date, closing_Date, allowed_Attempts FROM assessment WHERE subject_Code IN ('$subject_Codes_in')  AND is_Archived='0'";
    $result_assessments = executeQuery($conn, $query_assessments);

    $query_attempted = "SELECT assessment_ID, COUNT(*) AS attempts FROM user_exam_report WHERE user_ID = '$user_ID' GROUP BY assessment_ID";
    $result_attempted = executeQuery($conn, $query_attempted);

    $attempted_assessments = [];
    while ($row = $result_attempted['result']->fetch_assoc()) {
        $attempted_assessments[$row['assessment_ID']] = $row['attempts'];
    }

    $current_time = time();

    while ($row = $result_assessments['result']->fetch_assoc()) {
        if (in_array($row['subject_Code'], $subject_Codes)) {
            $subject_name = $subject_Map[$row['subject_Code']]; // Get the subject name using the subject_Code
            $assessment_id = $row['assessment_ID'];
            $attempts = $attempted_assessments[$assessment_id] ?? 0;
            $open_date = strtotime($row['open_Date']);
            $closing_date = strtotime($row['closing_Date']);
            $allowed_attempts = $row['allowed_Attempts'];

            $status = 'Start';
            if ($current_time > $closing_date && $attempts == 0) {
                $status = 'Missed';
            } 
            elseif ($attempts >= $allowed_attempts) {
                $status = 'View Details';
            } 
            elseif ($attempts > 0 && $attempts < $allowed_attempts) {
                $status = 'Reattempt';
            }

            $assessment = [
                'subject_Code' => $row['subject_Code'],
                'assessment_id' => $row['assessment_ID'],
                'assessment_name' => $row['assessment_name'],
                'open_Date' => date('g:iA F j, Y', $open_date),
                'closing_Date' => date('g:iA F j, Y', $closing_date),
                'status' => $status
            ];
            $subject_assessments[$subject_name][] = $assessment;
        }
    }

    echo json_encode($subject_assessments);
?>
