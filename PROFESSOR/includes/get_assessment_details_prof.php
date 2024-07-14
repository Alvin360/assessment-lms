<?php
    require_once '../includes/config_session_inc.php';
    require_once '../includes/dbh_inc.php';
    require_once '../includes/execute_query_inc.php';

    // Assume these are the subject codes you want to fetch assessments for
    $subject_Codes = ['COMP124', 'COMP123INS', 'COSC123', 'ACCO123']; // Add as many subject codes as needed

    // Create a string of subject codes for the SQL IN clause
    $subject_Codes_str = "'" . implode("', '", $subject_Codes) . "'";

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
