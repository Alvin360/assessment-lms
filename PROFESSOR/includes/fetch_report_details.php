<?php
    require_once '../includes/dbh_inc.php';

    $assessmentID = $_GET['assessmentID']; 
    
    $query = "SELECT user_ID, attempt_Number, score, grade, subject_Code, date FROM user_exam_report WHERE assessment_ID='$assessmentID'";
    $result = $conn->query($query);

    $reportDetails = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row['grade'] = number_format($row['grade'], 2);

            $reportDetails[] = $row;
        }
    }

    echo json_encode($reportDetails);

    $conn->close();
?>
