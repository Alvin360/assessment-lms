<?php
require_once '../includes/config_session_inc.php';
require_once '../includes/dbh_inc.php';
require_once '../includes/execute_query_inc.php';

$course_ID = isset($_GET['course_ID']) ? $_GET['course_ID'] : 'all';

if ($course_ID == 'all') {
    $query_subjects = "SELECT subject_ID, subject_name FROM subject";
} else {
    $query_subjects = "SELECT subject_ID, subject_name FROM subject WHERE course_ID = '$course_ID'";
}
$result_subjects = executeQuery($conn, $query_subjects);

$subject_Codes = [];
$subject_Map = [];
$subject_assessments = [];

while ($row = $result_subjects['result']->fetch_assoc()) {
    $subject_Codes[] = $row['subject_ID'];
    $subject_Map[$row['subject_ID']] = $row['subject_name']; 
    $subject_assessments[$row['subject_name']] = [];
}

if (count($subject_Codes) > 0) {
    $subject_Codes_str = implode("','", $subject_Codes);
    $subject_Codes_str = "'$subject_Codes_str'";
} else {
    $subject_Codes_str = "''";
}

$query_assessment = "SELECT assessment_id, subject_Code, assessment_name, open_Date, closing_Date 
                     FROM assessment 
                     WHERE subject_Code IN ($subject_Codes_str) AND is_Archived='0'";
$result = executeQuery($conn, $query_assessment);

$assessments = array();

while ($row = $result['result']->fetch_assoc()) {
    $open_Date = date('g:iA F j, Y', strtotime($row['open_Date']));
    $closing_Date = date('g:iA F j, Y', strtotime($row['closing_Date']));
    $row['subject_name'] = $subject_Map[$row['subject_Code']];
    $row['open_Date'] = $open_Date;
    $row['closing_Date'] = $closing_Date;
    $assessments[] = $row;
}

usort($assessments, function($a, $b) {
    return strtotime($a['open_Date']) - strtotime($b['open_Date']);
});

echo json_encode($assessments);
?>
