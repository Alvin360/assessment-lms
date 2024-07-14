<?php
require_once '../includes/config_session_inc.php';
require_once '../includes/dbh_inc.php';
require_once '../includes/execute_query_inc.php';

$course_ID = 'BSCS';

// Get subjects of the given course
$query_subjects = "SELECT subject_ID, subject_name FROM subject WHERE course_ID = '$course_ID'";
$result_subjects = executeQuery($conn, $query_subjects);

$subject_Codes = [];
$subject_Map = [];
$subject_assessments = [];

while ($row = $result_subjects['result']->fetch_assoc()) {
    $subject_Codes[] = $row['subject_ID'];
    $subject_Map[$row['subject_ID']] = $row['subject_name']; 
    $subject_assessments[$row['subject_name']] = [];
}

// Properly format the subject codes string for the SQL query
$subject_Codes_str = implode("','", $subject_Codes);
$subject_Codes_str = "'$subject_Codes_str'";

$query_assessment = "SELECT assessment_id, subject_Code, assessment_name, open_Date, closing_Date 
                     FROM assessment 
                     WHERE subject_Code IN ($subject_Codes_str)";
$result = executeQuery($conn, $query_assessment);

$assessments = array();

while ($row = $result['result']->fetch_assoc()) {
    // Format open_Date
    $open_Date = date('g:iA F j, Y', strtotime($row['open_Date']));
    
    // Format closing_Date
    $closing_Date = date('g:iA F j, Y', strtotime($row['closing_Date']));
    
    // Add subject name to the row
    $row['subject_name'] = $subject_Map[$row['subject_Code']];

    // Replace the original open_Date and closing_Date with formatted dates
    $row['open_Date'] = $open_Date;
    $row['closing_Date'] = $closing_Date;
    
    // Add row to assessments array
    $assessments[] = $row;
}

// Sort assessments by open date
usort($assessments, function($a, $b) {
    return strtotime($a['open_Date']) - strtotime($b['open_Date']);
});

echo json_encode($assessments);
?>
