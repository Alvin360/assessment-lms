<?php
    require_once '../includes/config_session_inc.php';
    require_once '../includes/dbh_inc.php';
    require_once '../includes/execute_query_inc.php';

    // Assume these are the subject codes you want to fetch assessments for
    $subject_Codes = ['COMP124', 'COMP123INS', 'COSC123', 'ACCO123']; // Add as many subject codes as needed

    // Create a string of subject codes for the SQL IN clause
    $subject_Codes_str = "'" . implode("', '", $subject_Codes) . "'";

    $query_assessment = "SELECT assessment_id, subject_Code, assessment_name, open_date, closing_date 
                        FROM assessment 
                        WHERE subject_Code IN ($subject_Codes_str)";
    $result = executeQuery($conn, $query_assessment);

    $assessments = array();

    while ($row = $result['result']->fetch_assoc()) {
        // Format open_date
        $open_date = date('g:iA F j, Y', strtotime($row['open_date']));
        
        // Format closing_date
        $closing_date = date('g:iA F j, Y', strtotime($row['closing_date']));
        
        // Replace the original open_date and closing_date with formatted dates
        $row['open_date'] = $open_date;
        $row['closing_date'] = $closing_date;
        
        // Add row to assessments array
        $assessments[] = $row;
    }

    // Sort assessments by open date
    usort($assessments, function($a, $b) {
        return strtotime($a['open_date']) - strtotime($b['open_date']);
    });

    echo json_encode($assessments);
?>
