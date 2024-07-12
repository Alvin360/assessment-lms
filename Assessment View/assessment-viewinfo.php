<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment View</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="assessment-section">
        <?php
        session_start();
        
        if (isset($_SESSION["user_ID"])) {
            $user_ID = $_SESSION["user_ID"];
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "pup_lms";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if (isset($_GET['assessment_ID']) && isset($_GET['subject_Code'])) {
                $assessment_ID = $_GET['assessment_ID'];
                $subject_Code = $_GET['subject_Code'];

                $sql_select = $conn->prepare("SELECT * FROM assessment WHERE subject_Code = ? AND assessment_ID = ?");
                $sql_select->bind_param("ss", $subject_Code, $assessment_ID);
                $sql_select->execute();
                $result = $sql_select->get_result();

                if ($result->num_rows > 0) {
                    echo "<div class='assessment-list'>";
                    while ($row = $result->fetch_assoc()) {
                        echo "Username: " . htmlspecialchars($_SESSION["user_ID"]) . "<br>";
                        echo "<h2>" . htmlspecialchars($row["assessment_Name"]) . "</h2>";
                        echo "<p class='assessment_ID'>Assessment ID: " . htmlspecialchars($row["assessment_ID"]) . "</p>";
                        echo "<div class='assessment-item'>";
                        echo "<h3>Subject Code: " . htmlspecialchars($row["subject_Code"]) . "</h3>";
                        echo "<p>Creator ID: " . htmlspecialchars($row["creator_ID"]) . "</p>";
                        echo "<p>Date Created: " . htmlspecialchars($row["date_created"]) . "</p>";
                        echo "<p>Closing Date: " . htmlspecialchars($row["closing_date"]) . "</p>";
                        echo "<hr/>";
                        echo "<p>Allowed Attempts: " . htmlspecialchars($row["allowed_attempts"]) . "</p>";
                        echo "<p>Assessment Type: " . htmlspecialchars($row["assessment_Type"]) . "</p>";
                        echo "<p>No. of Items: " . htmlspecialchars($row["no_Of_Items"]) . "</p>";
                        echo "<hr/>";
                        echo "</div>";

                        $current_date = date("Y-m-d H:i:s");
                        $closing_date = $row["closing_date"];
                        $allowed_attempts = $row["allowed_attempts"];
                    }
                        
                    $sql_user_exam = $conn->prepare("SELECT attempt_number, score, date FROM user_exam_report WHERE user_ID = ? AND assessment_ID = ? AND subject_Code = ?");
                    $sql_user_exam->bind_param("sss", $user_ID, $assessment_ID, $subject_Code);
                    $sql_user_exam->execute();
                    $user_exam_result = $sql_user_exam->get_result();

                    $attempt_count = $user_exam_result->num_rows;
                    //Check first the attempt_number
                    if ($attempt_count > 0) {
                        echo "<p>Grades:</p>";
                        while ($exam_row = $user_exam_result->fetch_assoc()) {
                            echo "<p>Attempt " . htmlspecialchars($exam_row["attempt_number"]) . ": " . htmlspecialchars($exam_row["score"]) . " (Date: " . htmlspecialchars($exam_row["date"]) . ")</p>";
                        }
                        //Check if allowed attempts was all used up
                        if ($attempt_count >= $allowed_attempts) {
                            echo "<button>Review</button>";
                        } else {
                            echo "<button>Reattempt</button>";
                            echo "<p>You have " . ($allowed_attempts - $attempt_count) . " attempt(s) remaining.</p>";
                        }
                    } else {
                        //Check if the assessment is still open
                        if ($current_date <= $closing_date) {
                            echo "<button>Start</button>";
                        } else {
                            echo "<p class='notif'>The assessment is now closed. Contact your professor to reopen the exam.</p>";
                        }   
                    }

                    $sql_user_exam->close();
                } else {
                    echo "No records found for Subject Code: " . htmlspecialchars($subject_Code) . " and Assessment ID: " . htmlspecialchars($assessment_ID);
                }
                
                $sql_select->close();
            } else {
                echo "Assessment ID and Subject Code are required.";
            }

            $conn->close();
        } else {
            echo "User ID not set in session.";
        }
        ?>
    </div>
</body>
</html>
