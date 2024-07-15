<?php
    require_once '../includes/dbh_inc.php';

    // Check if course parameter is set in the URL
    if (isset($_GET['course'])) {
        $course = $_GET['course'];

        // Fetch subject ID and subject_Name
        $query = "SELECT subject_ID, subject_Name FROM subject WHERE course='$course'";
        $result = $conn->query($query);

        $subjects = array();

        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $subjects[] = $row;
                }
            }
        } else {
            // Handle query error
            echo json_encode(array("error" => "Query failed: " . $conn->error));
            exit();
        }

        echo json_encode($subjects);
    } else {
        echo json_encode(array("error" => "Course parameter is missing"));
    }

    $conn->close();
?>
