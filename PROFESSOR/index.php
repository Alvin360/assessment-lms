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
    <div id="landing_page">
        <div class="flex_row">
            <button onclick="window.location.href='../PROFESSOR/pages/create_assessment.html'">+ Add Assessment</button>
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
