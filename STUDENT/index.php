<?php
    require_once '../STUDENT/includes/config_session_inc.php';

    // check if user's role is student

    // if (!isset($_SESSION["user_ID"]) || $_SESSION["role"] !== '5') {
    //     header("Location: ../STUDENT/index.php")
    //     die();
    // }
    
    require_once '../STUDENT/includes/dbh_inc.php';
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
    <div id="landing_page">
        <div id="nav_subjects">
            
        </div>
        
        <div id="container_assessments_students">
            <h2 id='subject-heading'></h2>
            
            <div id="container_assessment_subject">
                <div class="card_assessment">
                </div>
            </div>
        </div>
    </div>
    
    
    <script src="../STUDENT/js/script.js"></script>
</body>
</html>
