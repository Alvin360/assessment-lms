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
                        echo "<p class='assessment_ID'>Date Created: " . htmlspecialchars($row["date_Created"]) . "</p>";
                        echo "<div class='assessment-item'>";
                        echo "<h3>Subject Code: " . htmlspecialchars($row["subject_Code"]) . "</h3>";
                        echo "<p>Creator ID: " . htmlspecialchars($row["creator_ID"]) . "</p>";
                        echo "<p>Will open on: " . htmlspecialchars($row["open_Date"]) . "</p>";
                        echo "<p>Will close on: " . htmlspecialchars($row["closing_Date"]) . "</p>";
                        echo "<hr/>";
                        echo "<p>Allowed Attempts: " . htmlspecialchars($row["allowed_Attempts"]) . "</p>";
                        echo "<p>Assessment Type: " . htmlspecialchars($row["assessment_Type"]) . "</p>";
                        echo "<p>No. of Items: " . htmlspecialchars($row["no_Of_Items"]) . "</p>";
                        echo "<hr/>";
                        echo "</div>";

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

                    if ($attempt_count > 0) {
                        echo "<p>Grades:</p>";
                        while ($exam_row = $user_exam_result->fetch_assoc()) {
                            echo "<p>Attempt " . htmlspecialchars($exam_row["attempt_number"]) . ": " . htmlspecialchars($exam_row["score"]) . " (Date: " . htmlspecialchars($exam_row["date"]) . ")</p>";
                        }
                        if ($attempt_count >= $allowed_attempts) {
                            echo "<button onclick=\"location.href='../AssessmentForm/assessment_form.html?assessment_ID=$assessment_ID&user_ID=$user_ID'\">Review</button>";
                        } else if ($current_date >= $open_date) {
                            echo "<button onclick=\"location.href='../AssessmentForm/assessment_form.html?assessment_ID=$assessment_ID&user_ID=$user_ID'\">Review</button>";
                            echo "<button onclick=\"location.href='../AssessmentForm/assessment_form.html?assessment_ID=$assessment_ID&user_ID=$user_ID'\">Reattempt</button>";
                            echo "<p>You have " . ($allowed_attempts - $attempt_count) . " attempt(s) remaining.</p>";
                        } else {
                            echo "<p>The assessment is not opened yet.</p>";
                        }
                    } else {
                        if ($current_date <= $closing_date && $current_date >= $open_date) {
                            echo "<button onclick=\"location.href='../AssessmentForm/assessment_form.html?assessment_ID=$assessment_ID&user_ID=$user_ID'\">Start</button>";
                        } else if ($current_date < $open_date) {
                            echo "<p class='notif'>The assessment is still closed.</p>";
                        } else {
                            echo "<p class='notif'>The assessment is now closed. Contact your professor to open/reopen the exam.</p>";
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
        echo '<button id="back" onclick="window.location.href=\'session.php\'">Back</button>';
        ?>
    </div>
</body>
</html>
