<?php
    require_once '../includes/config_session_inc.php';
    require_once '../includes/dbh_inc.php';
    require_once '../includes/execute_query_inc.php';

    $assessment_ID = $_GET['assessment_ID']; 
    $subject_Code = $_GET['subject_Code']; 

    // Check if assessment_ID is set and not empty
    if (!isset($assessment_ID) || empty($assessment_ID)) {
        die('Error: assessment_ID is missing or empty.');
    }

    // Check if subject_Code is set and not empty
    if (!isset($subject_Code) || empty($subject_Code)) {
        die('Error: subject_Code is missing or empty.');
    }

    // Query to fetch course code of the given subject
    $query_id_course = "SELECT course_ID FROM subject WHERE subject_ID = $subject_Code;";
    $result_id_course = executeQuery($conn, $query_id_course);

    if ($result_id_course['result'] && mysqli_num_rows($result_id_course['result']) > 0) {
        $course = mysqli_fetch_assoc($result_id_course['result']);
        $course_ID = $course['course_ID'];
    } 
    else {
        die('Error: No course found for the given subject code.');
    }

    // Query to fetch user IDs of students enrolled in the given course
    $query_id_students = "SELECT user_ID FROM course_enrolled WHERE course_ID = '$course_ID';";
    $result_id_students = executeQuery($conn, $query_id_students);

    if ($result_id_students['result'] && mysqli_num_rows($result_id_students['result']) > 0) {
        $user_ids = array();
        
        while ($row = mysqli_fetch_assoc($result_id_students['result'])) {
            $user_ids[] = $row['user_ID'];
        }

        // Query to fetch student names
        $query_name_students = "SELECT user_ID, last_Name, first_Name, middle_Name FROM user_information WHERE user_ID IN ('" . implode("','", $user_ids) . "');";
        $result_name_students = executeQuery($conn, $query_name_students);

        if ($result_name_students['result'] && mysqli_num_rows($result_name_students['result']) > 0) {
            $students = array();

            // Query to fetch user IDs of students who attempted the quiz
            $query_attempted = "SELECT user_ID FROM user_exam_report WHERE assessment_ID = $assessment_ID;";
            $result_attempted = executeQuery($conn, $query_attempted);

            $attempted_user_ids = array();
            if ($result_attempted['result'] && mysqli_num_rows($result_attempted['result']) > 0) {
                while ($row = mysqli_fetch_assoc($result_attempted['result'])) {
                    $attempted_user_ids[] = $row['user_ID'];
                }
            }

            // Fetch student's name and check if they attempted the quiz
            while ($row = mysqli_fetch_assoc($result_name_students['result'])) {
                $full_name = $row['last_Name'] . ', ' . $row['first_Name'] . ' ' . $row['middle_Name'];
                $attempted = in_array($row['user_ID'], $attempted_user_ids);
                $students[] = array('assessment_ID' => $assessment_ID, 'name' => $full_name, 'attempted' => $attempted);
            }

            // Sort students array by last name 
            usort($students, function($a, $b) {
                $aLastName = explode(', ', $a['name'])[0];
                $bLastName = explode(', ', $b['name'])[0];
                return strcasecmp($aLastName, $bLastName);
            });
            
            // Encode sorted students array as JSON and output
            echo json_encode($students);
        } 
        else {
            echo json_encode(['error' => 'No students found']);
        }
    } 
    else {
        echo json_encode(['error' => 'No results found']);
    }
?>
