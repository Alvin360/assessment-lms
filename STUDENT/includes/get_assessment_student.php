<?php
    require_once '../includes/config_session_inc.php';
    require_once '../includes/dbh_inc.php';
    require_once '../includes/execute_query_inc.php';

    $user_ID = '202110755MN0';
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
    $query_assessments = "SELECT assessment_ID, subject_Code, assessment_name, open_Date, closing_Date FROM assessment WHERE subject_Code IN ('$subject_Codes_in')";
    $result_assessments = executeQuery($conn, $query_assessments);

    $query_attempted = "SELECT assessment_ID FROM user_exam_report WHERE user_ID = '$user_ID'";
    $result_attempted = executeQuery($conn, $query_attempted);

    $attempted_assessments = [];
    while ($row = $result_attempted['result']->fetch_assoc()) {
        $attempted_assessments[] = $row['assessment_ID'];
    }

    while ($row = $result_assessments['result']->fetch_assoc()) {
        if (in_array($row['subject_Code'], $subject_Codes)) {
            $subject_name = $subject_Map[$row['subject_Code']]; // Get the subject name using the subject_Code
            $assessment = [
                'subject_Code' => $row['subject_Code'],
                'assessment_id' => $row['assessment_ID'],
                'assessment_name' => $row['assessment_name'],
                'open_Date' => $row['open_Date'],
                'closing_Date' => $row['closing_Date'],
                'attempted' => in_array($row['assessment_ID'], $attempted_assessments)
            ];
            $subject_assessments[$subject_name][] = $assessment;
        }
    }

    echo json_encode($subject_assessments);
?>
