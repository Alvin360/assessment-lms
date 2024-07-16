<?php
    require_once '../includes/dbh_inc.php';

    $assessment_ID = $_GET['assessment_ID'];

    // SQL query to update the assessment
    $sql_archive = "UPDATE `assessment` SET `is_Archived` = '1' WHERE `assessment`.`assessment_ID` = '$assessment_ID'";
    $result_archived = mysqli_query($conn, $sql_archive);

    if ($result_archived) {
        if (mysqli_affected_rows($conn) > 0) {
            echo '<script>
                    alert("Assessment Archived Successfully.");
                    window.location.href = "../index.php";
                </script>';
        } 
        else {
            echo 'Error archiving.';
        }
    } 
    else {
        echo 'Error archiving assessment: ' . mysqli_error($conn);
    }
?>
