<?php
    require_once '../includes/config_session_inc.php';
    require_once '../includes/dbh_inc.php';
    require_once '../includes/execute_query_inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment View</title>
    <link href="../styles.css" rel="stylesheet">
</head>
<body>
    <!-- Header section containing the navigation bars -->
    <header>
            <div class="top-bar">
                <div class="logo">
                    <img src="logo.png" alt="PUP">
                </div>
                <nav class="main-nav">
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Dashboard</a></li>
                        <li><a href="#">My courses</a></li>
                        <li><a href="../index.php">Assessment</a></li>
                    </ul>
                </nav>
                <nav class="profile-nav">
                    <ul>
                        <li><a href="#">Notification</a></li>
                        <li><a href="#">Messages</a></li>
                        <li><a href="#">Profile</a></li>
                    </ul>
                </nav>
            </div>
            <div class="bottom-bar">
                <ul>
                    <li><a href="#">Course</a></li>
                    <li><a href="#">Participants</a></li>
                    <li><a href="#">Grades</a></li>
                    <li><a href="#">Competencies</a></li>
                </ul>
            </div>
        </header>

    <!-- section containing the assessment page -->
    <div class="assessment-section">
        <?php
        $user_ID = '202110755MN0';

        if (isset($_GET['assessment_ID']) && isset($_GET['subject_Code'])) {
            $assessment_ID = $_GET['assessment_ID'];
            $subject_Code = $_GET['subject_Code'];

            // Fetch the subject name using subject_Code
            $sql_subject = $conn->prepare("SELECT subject_Name FROM subject WHERE subject_ID = ?");
            $sql_subject->bind_param("s", $subject_Code);
            $sql_subject->execute();
            $subject_result = $sql_subject->get_result();

            if ($subject_result->num_rows > 0) {
                $subject_row = $subject_result->fetch_assoc();
                $subject_Name = $subject_row["subject_Name"];
            } else {
                $subject_Name = "Unknown Subject";
            }

            $sql_subject->close();


            $sql_select = $conn->prepare("SELECT * FROM assessment WHERE subject_Code = ? AND assessment_ID = ?");
            $sql_select->bind_param("ss", $subject_Code, $assessment_ID);
            $sql_select->execute();
            $result = $sql_select->get_result();

            if ($result->num_rows > 0) {
                echo "<div class='assessment-list'>";
                while ($row = $result->fetch_assoc()) {
                    //Display data
                    //echo "Username: " . htmlspecialchars($_SESSION["user_ID"]) . "<br>";
                    echo "<h2>" . htmlspecialchars($row["assessment_Name"]) . "</h2>";
                    echo "<p class='assessment_ID'>Assessment ID: " . htmlspecialchars($row["assessment_ID"]) . "</p>";
                    echo "<p class='assessment_ID'>Date Created: " . htmlspecialchars($row["date_Created"]) . "</p>";
                    echo "<div class='assessment-item'>";
                    echo "<h3>" . htmlspecialchars($row["subject_Code"]) . ": " . htmlspecialchars($subject_Name) . "</h3>";
                    echo "<p>Creator ID: " . htmlspecialchars($row["creator_ID"]) . "</p>";
                    echo "<p>Will open on: " . htmlspecialchars($row["open_Date"]) . "</p>";
                    echo "<p>Will close on: " . htmlspecialchars($row["closing_Date"]) . "</p>";
                    echo "<hr/>";
                    echo "<p>Allowed Attempts: " . htmlspecialchars($row["allowed_Attempts"]) . "</p>";
                    echo "<p>Assessment Type: " . htmlspecialchars($row["assessment_Type"]) . "</p>";
                    echo "<p>No. of Items: " . htmlspecialchars($row["no_Of_Items"]) . "</p>";
                    echo "<hr/>";
                    echo "</div>";

                    //Assign date values to check whether the assessment is open or not
                    $current_date = date("Y-m-d H:i:s");
                    $closing_date = $row["closing_Date"];
                    $open_date = $row["open_Date"];
                    $allowed_attempts = $row["allowed_Attempts"];
                }
                    
                $sql_user_exam = $conn->prepare("SELECT attempt_number, score, date FROM user_exam_report WHERE user_ID = ? AND assessment_ID = ? AND subject_Code = ?");
                $sql_user_exam->bind_param("sss", $user_ID, $assessment_ID, $subject_Code);
                $sql_user_exam->execute();
                $user_exam_result = $sql_user_exam->get_result();

                $attempt_count = $user_exam_result->num_rows;

                //Check if there is an attempt already
                if ($attempt_count > 0) {
                    //To show attempts data
                    echo "<p>Grades:</p>";
                    while ($exam_row = $user_exam_result->fetch_assoc()) {
                        echo "<p>Attempt " . htmlspecialchars($exam_row["attempt_number"]) . ": " . htmlspecialchars($exam_row["score"]) . " (Date: " . htmlspecialchars($exam_row["date"]) . ")</p>";
                    }
                    //Check if allowed attempts was all used up
                    if ($attempt_count >= $allowed_attempts) {
                        echo "<p>No more attempts...</p>";
                    //If there is still available attempts
                    } else if($current_date>=$open_date){
                        echo "<button>Reattempt</button>";
                        echo "<p>You have " . ($allowed_attempts - $attempt_count) . " attempt(s) remaining.</p>";
                    }
                    else{
                        echo "<p>The assessment is not opened yet.</p>";
                    }
                //When there is no attempt yet
                } else {
                    //Check if the assessment is still open
                    if ($current_date <= $closing_date && $current_date>=$open_date) {
                        echo '<button onclick="window.location.href=\'../pages/assessment_form.html?assessmentID=' . $assessment_ID . '\'">Start</button>';
                    } else if($current_date<$open_date){
                        echo "<p class='notif'>The assessment is still closed.</p>";
                    }  
                    //If closed already
                    else {
                        echo "<p class='notif'>The assessment is now closed. Contact your professor to open/reopen the exam.</p>";
                    }   
                }

                $sql_user_exam->close();
            } else {
                echo "No records found for Subject Code: " . htmlspecialchars($subject_Code) . " and Assessment ID: " . htmlspecialchars($assessment_ID);
            }
            
            $sql_select->close();
        } 
        
        else {
            echo "Assessment ID and Subject Code are required.";
        }

        $conn->close();
        echo '<button id="back" onclick="window.location.href=\'../index.php\'">Back</button>';
        ?>
    </div>
</body>
</html>
