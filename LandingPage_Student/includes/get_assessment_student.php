<?php
    require_once '../includes/config_session_inc.php';
    require_once '../includes/dbh_inc.php';
    require_once '../includes/execute_query_inc.php';

    $subject_assessments = [];
    $course_ID = 'BSCS';
    $user_ID = '202110755MN0';

    $query_subjects = "SELECT subject_ID, subject_name FROM subject WHERE course_ID = '$course_ID'";
    $result_subjects = executeQuery($mysqli, $query_subjects);

    $subject_ids = [];

    while ($row = $result_subjects['result']->fetch_assoc()) {
        $subject_ids[] = $row['subject_ID'];
        $subject_assessments[$row['subject_name']] = [];
    }

    $subject_ids_in = implode("','", $subject_ids);

    $query_assessments = "SELECT assessment_ID, subject_ID, assessment_name, date_opened, date_closed FROM assessment WHERE subject_ID IN ('$subject_ids_in')";
    $result_assessments = executeQuery($mysqli, $query_assessments);

    $query_attempted = "SELECT assessment_ID FROM user_exam_report WHERE user_ID = '$user_ID'";
    $result_attempted = executeQuery($mysqli, $query_attempted);

    $attempted_assessments = [];
    while ($row = $result_attempted['result']->fetch_assoc()) {
        $attempted_assessments[] = $row['assessment_ID'];
    }

    while ($row = $result_assessments['result']->fetch_assoc()) {
        foreach ($subject_assessments as $subject_name => $assessments) {
            if (in_array($row['subject_ID'], $subject_ids)) {
                $subject_assessments[$subject_name][] = [
                    'assessment_id' => $row['assessment_ID'],
                    'assessment_name' => $row['assessment_name'],
                    'date_opened' => $row['date_opened'],
                    'date_closed' => $row['date_closed'],
                    'attempted' => in_array($row['assessment_ID'], $attempted_assessments)
                ];
            }
        }
    }

    echo json_encode($subject_assessments);
?>
