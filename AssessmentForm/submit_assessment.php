<?php
require "db.php";

$assessmentID = $_POST['assessment_ID'];
$userID = $_POST['user_ID'];


$userAnswers = $_POST;

// Fetch all questions related to the assessment
$questionsSQL = "SELECT * FROM examination_bank WHERE assessment_ID = ?";
$stmt = $conn->prepare($questionsSQL);
$stmt->bind_param("s", $assessmentID);
$stmt->execute();
$questionsResult = $stmt->get_result();
$stmt->close();

$totalPoints = 0;
$earnedPoints = 0;

while ($questionData = $questionsResult->fetch_assoc()) {
    $questionID = $questionData['question_ID'];
    $questionType = $questionData['question_Type'];
    $points = $questionData['points'];

    $totalPoints += $points;

    switch ($questionType) {
        case 'M': // Multiple Choice
            if (isset($userAnswers["question-$questionID"])) {
                $userAnswer = $userAnswers["question-$questionID"];

                // Fetch the correct answer for the question
                $correctAnswerSQL = "SELECT answer FROM exam_answer WHERE assessment_ID = ? AND question_ID = ?";
                $stmt = $conn->prepare($correctAnswerSQL);
                $stmt->bind_param("si", $assessmentID, $questionID);
                $stmt->execute();
                $result = $stmt->get_result();
                $correctAnswerData = $result->fetch_assoc();
                $stmt->close();

                $correctChoiceIndex = $correctAnswerData['answer'];
                $correctAnswer = '';

                switch ($correctChoiceIndex) {
                    case 1:
                        $correctAnswer = $questionData['choice1'];
                        break;
                    case 2:
                        $correctAnswer = $questionData['choice2'];
                        break;
                    case 3:
                        $correctAnswer = $questionData['choice3'];
                        break;
                    case 4:
                        $correctAnswer = $questionData['choice4'];
                        break;
                }

                if (strcasecmp(trim($userAnswer), trim($correctAnswer)) == 0) {
                    $earnedPoints += $points;
                }
            }
            break;
        case 'T': // True/False
        case 'S': // Short Answer
            if (isset($userAnswers["question-$questionID"])) {
                $userAnswer = $userAnswers["question-$questionID"];

                // Fetch the correct answer for the question
                $correctAnswerSQL = "SELECT answer FROM exam_answer WHERE assessment_ID = ? AND question_ID = ?";
                $stmt = $conn->prepare($correctAnswerSQL);
                $stmt->bind_param("si", $assessmentID, $questionID);
                $stmt->execute();
                $result = $stmt->get_result();
                $correctAnswerData = $result->fetch_assoc();
                $stmt->close();

                $correctAnswer = $correctAnswerData['answer'];

                if (strcasecmp(trim($userAnswer), trim($correctAnswer)) == 0) {
                    $earnedPoints += $points;
                }
            }
            break;
        case 'F': // Matching
            // Fetch the correct match answers for the question
            $correctAnswerSQL = "SELECT m_Ans1, m_Ans2, m_Ans3, m_Ans4, m_Ans5, m_Ans6, m_Ans7, m_Ans8, m_Ans9, m_Ans10 FROM exam_answer WHERE assessment_ID = ? AND question_ID = ?";
            $stmt = $conn->prepare($correctAnswerSQL);
            $stmt->bind_param("si", $assessmentID, $questionID);
            $stmt->execute();
            $result = $stmt->get_result();
            $correctAnswerData = $result->fetch_assoc();
            $stmt->close();

            if ($correctAnswerData) {
                for ($i = 1; $i <= 10; $i++) {
                    if (isset($userAnswers["question-$questionID-match$i"])) {
                        $userAnswer = $userAnswers["question-$questionID-match$i"];
                        $correctMatchField = 'match' . $correctAnswerData["m_Ans$i"];
                        $correctMatchAnswer = $questionData[$correctMatchField];

                        if ($userAnswer == $correctMatchAnswer) {
                            $earnedPoints += $points / 10; // Assuming each match is worth 1/10th of the question's points
                        }
                    }
                }
            }
            break;
    }
}

// Fetch the subject code related to the assessment
$subjectCodeSQL = "SELECT subject_Code FROM assessment WHERE assessment_ID = ?";
$stmt = $conn->prepare($subjectCodeSQL);
$stmt->bind_param("s", $assessmentID);
$stmt->execute();
$result = $stmt->get_result();
$subjectCodeData = $result->fetch_assoc();
$subjectCode = $subjectCodeData['subject_Code'];
$stmt->close();

// Calculate the grade as a percentage
$grade = ($totalPoints > 0) ? ($earnedPoints / $totalPoints) * 100 : 0;

// Fetch the current attempt number
$attemptSQL = "SELECT MAX(attempt_Number) as max_attempt FROM user_exam_report WHERE user_ID = ? AND assessment_ID = ?";
$stmt = $conn->prepare($attemptSQL);
$stmt->bind_param("ss", $userID, $assessmentID);
$stmt->execute();
$result = $stmt->get_result();
$attemptData = $result->fetch_assoc();
$attemptNumber = $attemptData['max_attempt'] + 1;
$stmt->close();

// Save the score (total correct points) and grade (percentage) with incremented attempt number
$stmt = $conn->prepare("INSERT INTO user_exam_report (user_ID, assessment_ID, score, grade, subject_Code, date, attempt_Number) VALUES (?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE score = VALUES(score), grade = VALUES(grade), subject_Code = VALUES(subject_Code), attempt_number = attempt_number + 1");
$date = date('Y-m-d');
$stmt->bind_param("sssdssi", $userID, $assessmentID, $earnedPoints, $grade, $subjectCode, $date, $attemptNumber);
$stmt->execute();
$stmt->close();

$dateEnd = date('Y-m-d');
$timeEnd = date('H:i:s');

$sql = "INSERT INTO user_examination (user_ID, assessment_ID, date_End, time_End, score, grade) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $userID, $assessmentID, $dateEnd, $timeEnd, $earnedPoints, $grade);
$stmt->execute();
$stmt->close();
$conn->close();

$response = [
    'score' => $earnedPoints,
    'grade' => $grade,
    'totalPoints' => $totalPoints
];

header('Content-Type: application/json');
echo json_encode($response);


?>
