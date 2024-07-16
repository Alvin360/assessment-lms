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
</head>

<body>
    <!-- Header section containing the navigation bars -->
    <div id="drawer">
        <div class="close-button">
            <img src="assets/close.png"/>
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
            <a>Assessment</a>
        </div>
    </div>
    <header>
        <div class="top-bar">
            <div class="toggle-hamburger">
                <img src="assets/hamburger.png" alt="PUP">
            </div>
            <div class="logo">
                <img src="assets/logo.png" alt="PUP">
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Dashboard</a></li>
                    <li class="toggle-nav"><a href="#">My courses</a></li>
                    <li class="toggle-nav"><a href="../PROFESSOR/index.php">Assessment</a></li>
                    <li class="more-nav-white">
                        <a id="more-white" class="more">More</a>
                        <ul class="dropdown-menu-white">
                            <li><a href="#">My Courses</a></li>
                            <li><a href="#">Assessment</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <nav class="profile-nav">
                <ul>
                    <li><a href="#"><img src="assets/bell.png"/></a></li>
                    <li><a href="#"><img src="assets/chat.png"/></a></li>
                    <li>
                        <a id="profile" href="#"><img src="assets/profile-picture.png"/></a>
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

    <div id="landing_page">
        <div class="flex_row-container">
            <div class="flex_row cont">
                <div id="filter-container">
                    <label for="course-filter">Filter by Course:</label>
                    <select id="course-filter">
                        <option value="all">All Courses</option>
                        <!-- Populate with course options dynamically -->
                    </select>
                </div>
                <div class="flex_row">
                    <button onclick="window.location.href='../PROFESSOR/pages/create_assessment.html'">+ Add Assessment</button>
                </div>
            </div>


            <div id="container_assessments_subject">
                <div id="container_section_assessment">
                    <!-- Assessments will be populated here -->
                </div>
            </div>
        </div>
    </div>
    
    
    <script src="../PROFESSOR/js/script.js"></script>
</body>
</html>
