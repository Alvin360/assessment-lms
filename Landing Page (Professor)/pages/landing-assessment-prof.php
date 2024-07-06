<?php
    require_once '../includes/config_session_inc.php';

    // check if user's role is admin

    // if (!isset($_SESSION["user_ID"]) || $_SESSION["role"] !== '3') {
    //     header("Location: ../login.php")
    //     die();
    // }
    
    require_once '../includes/dbh_inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment</title>
    <link href="../styles.css" rel="stylesheet">
</head>

<body>
    <div id="landing_page">
        <div id="nav_assessment_prof">
            <a href='#section1'>BSCS 3-5</a>
        </div>
        
        <div id="container_assessments_prof">
            <div class="flex_row">
                <button onclick="window.location.href='create_exam.html'">+ Add Assessment</button>
                <button>Dropdown</button>
            </div>
            <h1 id='section1'>BSCS 3-5</h1>

            <div id="container_section_assessment">
                <div class="card_assessment">
                </div>
            </div>
        </div>
    </div>
    
    
    <script src="../js/script.js"></script>
</body>
</html>
