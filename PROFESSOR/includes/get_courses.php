<?php
    require_once '../includes/dbh_inc.php';

    // Fetch course IDs and course names
    $query = "SELECT course_ID FROM course";
    $result = $conn->query($query);

    $courses = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
    }

    echo json_encode($courses);

    $conn->close();
?>
