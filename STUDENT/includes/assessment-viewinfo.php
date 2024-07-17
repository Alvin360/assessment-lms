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
    <div id="drawer">
        <div class="close-button">
            <img src="../assets/close.png"/>
            <div>
                <span>PUP eMabini</span>
            </div>
        </div>
        <div>
            <a>Home</a>
        </div>
        <div>
            <a>Dashboard</a>
        </div>
        <div>
            <a>My Courses</a>
        </div>
        <div>
            <a style="text-decoration: none; color: black;" href="../../STUDENT/landing_page.php">Assessment</a>
        </div>
    </div>
    <header>
        <div class="top-bar">
            <div class="toggle-hamburger">
                <img src="../assets/hamburger.png" alt="PUP">
            </div>
            <div class="logo">
                <img src="../assets/logo.png" alt="PUP">
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Dashboard</a></li>
                    <li class="toggle-nav"><a href="#">My courses</a></li>
                    <li class="toggle-nav"><a href="../../STUDENT/landing_page.php">Assessment</a></li>
                    <li class="more-nav-white">
                        <a id="more-white" class="more">More</a>
                        <ul class="dropdown-menu-white">
                            <li><a href="#">My Courses</a></li>
                            <li><a href="../../STUDENT/landing_page.php">Assessment</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <nav class="profile-nav">
                <ul>
                    <li><a href="#"><img src="../assets/bell.png"/></a></li>
                    <li><a href="#"><img src="../assets/chat.png"/></a></li>
                    <li>
                        <a id="profile" href="#"><img src="../assets/profile-picture.png"/></a>
                        <ul class="dropdown-menu-profile">
                            <li><a href="#">Accessibility</a></li>
                            <div class="underline"></div>
                            <li><a href="#">Profile</a></li>
                            <li><a href="#">Grades</a></li>
                            <li><a href="#">Calendar</a></li>
                            <li><a href="#">Messages</a></li>
                            <li><a href="#">Private files</a></li>
                            <li><a href="#">Reports</a></li>
                            <div class="underline"></div>
                            <li><a href="#">Preferences</a></li>
                            <div class="underline"></div>
                            <li><a href="#">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="bottom-bar">
            <ul>
                <li><a href="#">Course</a></li>
                <li class="toggle-nav-red"><a href="#">Participants</a></li>
                <li class="toggle-nav-red"><a href="#">Grades</a></li>
                <li class="toggle-nav-red"><a href="#">Competencies</a></li>
                <li class="more-nav-red">
                    <a id="more-red" class="more_red">More</a>
                    <ul class="dropdown-menu-red">
                        <li><a href="#">Participants</a></li>
                        <li><a href="#">Grades</a></li>
                        <li><a href="#">Competencies</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </header>

    <!-- section containing the assessment page -->
    <div class="assessment-section content-body">
        <?php
        date_default_timezone_set('Asia/Manila');
        if (isset($_GET['assessment_ID']) && isset($_GET['subject_Code'])) {
            $assessment_ID = $_GET['assessment_ID'];
            $subject_Code = $_GET['subject_Code'];
            $user_ID = $_SESSION["user_ID"];

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
                echo "<div class='assessment-list body-container'>";
                while ($row = $result->fetch_assoc()) {
                    $dateCreated = new DateTime($row["date_Created"]);
                    $openDate = new DateTime($row["open_Date"]);
                    $closingDate = new DateTime($row["closing_Date"]);
                    
                    //Display data
                    //echo "Username: " . htmlspecialchars($_SESSION["user_ID"]) . "<br>";
                    echo "<h1>" . htmlspecialchars($row["assessment_Name"]) . "</h1>";
                    echo "<div class='content-block wide-padding'>";
                    echo "<div class='assessment-item upper-part'>";
                    echo "<div class='upper-section-1'>";
                    echo "<div>";
                    echo "<h3 style='display: inline-block; margin-bottom: 0px !important;'>" . htmlspecialchars($row["subject_Code"]) . ": " . htmlspecialchars($subject_Name) . "</h3>";
                    echo "<span style='display: inline-block;'>Time Limit: " . htmlspecialchars($row["time_Limit"]) . "</span>";
                    echo "</div>";
                    echo "<span style='display: block; font-size: 12px;'>Creator ID: " . htmlspecialchars($row["creator_ID"]) . "</span>";
                    echo "<span style='display: block; font-size: 12px;'>Assessment ID: " . htmlspecialchars($row["assessment_ID"]) . "</span>";
                    echo "<span style='display: block; font-size: 12px;'>Date Created: " . htmlspecialchars(strftime('%d, %B %Y, %I:%M %p', $dateCreated->getTimestamp())) . "</span>";
                    echo "</div>";
                    echo "<div class='upper-section-2'>";
                    echo "<p>Will open on: " . htmlspecialchars(strftime('%d, %B %Y, %I:%M %p', $openDate->getTimestamp())) . "</p>";
                    echo "<p>Will close on: " . htmlspecialchars(strftime('%d, %B %Y, %I:%M %p', $closingDate->getTimestamp())) . "</p>";
                    echo "</div>";
                    echo "<div class='upper-section-3'>";
                    echo "<p><strong>Allowed Attempts: </strong>" . htmlspecialchars($row["allowed_Attempts"]) . "</p>";
                    echo "<p><strong>Assessment Type: </strong>" . htmlspecialchars($row["assessment_Type"]) . "</p>";
                    echo "<p><strong>No. of Items: </strong>" . htmlspecialchars($row["no_Of_Items"]) . "</p>";
                    echo "</div>";
                    echo "<div class='underline'></div>";
                    echo "<div class='upper-section-4'>";
                    echo "<h3>Instructions</h3>";
                    echo "<p>". htmlspecialchars($row["assessment_Desc"]) . "</p>";
                    echo "</div>";
                    echo "<div class='underline'></div>";
                    echo "</div>";

                    //Assign date values to check whether the assessment is open or not
                    $current_date = new DateTime($row["date_Created"]);
                    $closing_date = new DateTime($row["closing_Date"]);
                    $open_date = new DateTime($row["open_Date"]);
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
                    echo "<div class='attempts-data'>";
                    echo "<p><strong>Grades:</strong></p>";
                    while ($exam_row = $user_exam_result->fetch_assoc()) {
                        $attemptDate = new DateTime($exam_row["date"]);
                        echo "<p><strong>Attempt " . htmlspecialchars($exam_row["attempt_number"]) . "</strong>: " . htmlspecialchars($exam_row["score"]) ."</p>";
                        echo "<p><strong>Finished on: </strong>" . htmlspecialchars(strftime('%d, %B %Y', $attemptDate->getTimestamp())) . "</p>";
                    }
                    //Check if allowed attempts was all used up
                    if ($attempt_count >= $allowed_attempts) {
                        echo "<p><strong>No more attempts are allowed.</strong></p>";
                    //If closed na yung assessment ket may remaining attempts ka pa
                    } else if($current_date<=$open_date &&$current_date>=$closing_date){
                        echo "<p class='notif'>The assessment is now <strong>closed</strong>. Contact your professor to open/reopen the exam.</p>";
                    }
                    //If open pa yung assessment and may remaining attempts ka pa
                    else if($current_date>=$open_date && $current_date<$closing_date){
                        echo "<p>You have <strong>" . ($allowed_attempts - $attempt_count) . "</strong> attempt(s) remaining.</p>";
                        echo '<button onclick="window.location.href=\'../pages/assessment_form.html?assessmentID=' . $assessment_ID . '&subjectCode=' . $subject_Code . '\'">Start</button>';
                    }
                    else{
                        echo "<p>The assessment is <strong>not opened yet.</strong></p>";
                    }
                    echo "</div>";
                //When there is no attempt yet
                } else {
                    //Check if the assessment is still open
                    if ($current_date <= $closing_date && $current_date>=$open_date) {
                        echo '<button onclick="window.location.href=\'../pages/assessment_form.html?assessmentID=' . $assessment_ID . '&subjectCode=' . $subject_Code . '\'">Start</button>';
                    } else if($current_date<$open_date){
                        echo "<p class='notif'>The assessment is <strong>still closed.</strong></p>";
                    }  
                    //If closed already
                    else {
                        echo "<p class='notif'>The assessment is now <strong>closed</strong>. Contact your professor to open/reopen the exam.</p>";
                    }   
                }

                echo "</div>";

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
        echo '<button id="back" onclick="window.location.href=\'../landing_page.php\'">Back</button>';
        ?>
    </div>
    <script src='../js/script.js'></script>
</body>
</html>
