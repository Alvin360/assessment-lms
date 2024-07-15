<?php
require_once 'config_session_inc.php';
require_once 'dbh_inc.php';
require_once 'execute_query_inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student-id'];

    // Check if the student exists
    $query = "SELECT * FROM user_role WHERE user_ID = '$student_id' AND user_Role = '5'";
    $result = executeQuery($conn, $query);

    if ($result['result']->num_rows > 0) {
        // Store the user ID in the session
        $_SESSION['user_ID'] = $student_id;
        header("Location: ../index.php");
        exit();
    } else {
        echo "Invalid Student ID. You will be redirected to the session page in <span id='countdown'>3</span> seconds.";
        echo "<script>
              var countdown = 3;
              var countdownInterval = setInterval(function() {
                  document.getElementById('countdown').innerHTML = --countdown;
                  if (countdown <= 0) {
                      clearInterval(countdownInterval);
                      window.location.href='../session.php';
                  }
              }, 1000);
        </script>";
    }
}
?>