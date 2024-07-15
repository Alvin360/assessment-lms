<?php
    require_once '../includes/dbh_inc.php';

    $assessmentID = $_GET['assessmentID']; 

    $query = "SELECT user_ID, attempt_Number, score, grade, subject_Code, date FROM user_exam_report WHERE assessment_ID='$assessmentID'";
    $result = $conn->query($query);

    $reportDetails = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $user_ID = $row['user_ID'];
            $query_name = "SELECT last_Name, first_Name, middle_Name FROM user_information WHERE user_ID = '$user_ID'";
            $result_name = $conn->query($query_name);
            $row_name = $result_name->fetch_assoc();
            $student_name = $row_name['last_Name'] . ', ' . $row_name['first_Name'] . ' ' . $row_name['middle_Name'];
            $row['student_name'] = $student_name;
            $row['grade'] = number_format($row['grade'], 2);
            $reportDetails[] = $row;
        }
    }

    echo json_encode($reportDetails);

    $conn->close();
?>