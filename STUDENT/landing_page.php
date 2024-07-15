<?php
require_once '../STUDENT/includes/config_session_inc.php';
require_once '../STUDENT/includes/dbh_inc.php';

if (!isset($_SESSION["user_ID"])) {
    header("Location: ../STUDENT/start_session.php");
    die();
}

$user_ID = $_SESSION['user_ID'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment</title>
    <link href="../STUDENT/styles.css" rel="stylesheet">
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
                <li><a href="../STUDENT/index.php">Assessment</a></li>
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

<div id="landing_page">
    <div id="nav_subjects"></div>
    
    <div id="container_assessments_students">
        <h2 id='subject-heading'></h2>
        
        <div id="container_assessment_subject">
            <div class="card_assessment"></div>
        </div>
    </div>
</div>

<script>
    const userID = "<?php echo $user_ID; ?>";
</script>
<script src="../STUDENT/js/script.js"></script>
</body>
</html>
