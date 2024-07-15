<?php
    require_once '../PROFESSOR/includes/config_session_inc.php';

    // check if user's role is admin

    // if (!isset($_SESSION["user_ID"]) || $_SESSION["role"] !== '3') {
    //     header("Location: ../PROFESSOR/index.php")
    //     die();
    // }
    
    require_once '../PROFESSOR/includes/dbh_inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment</title>
    <link href="../PROFESSOR/styles.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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
                    <li><a href="../PROFESSOR/index.php">Assessment</a></li>
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
        <div class="flex_row">
            <div class="flex_row">
                <button onclick="window.location.href='../PROFESSOR/pages/create_assessment.html'">+ Add Assessment</button>
            </div>

            <div id="filter-container">
                    <label for="course-filter">Filter by Course:</label>
                    <select id="course-filter">
                        <option value="all">All Courses</option>
                        <!-- Populate with course options dynamically -->
                    </select>
            </div>
        </div>


        <div id="container_assessments_subject">
            <h1 id='section1'></h1>

            <div id="container_section_assessment">
                <!-- Assessments will be populated here -->
            </div>
        </div>



    </div>
    
    
    <script src="../PROFESSOR/js/script.js"></script>
</body>
</html>
