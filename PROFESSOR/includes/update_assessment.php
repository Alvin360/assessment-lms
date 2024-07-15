<?php
session_start(); // Start the session
require_once '../includes/dbh_inc.php';

// Function to update assessment
function updateAssessment($conn, $data) {
    $sql = "UPDATE assessment SET assessment_Name = ?, creator_ID = ?, assessment_Type = ?, time_Limit = ?, no_Of_Items = ?, assessment_Desc = ?, allowed_Attempts = ?, subject_Code = ?";
    $params = [
        $data['assessmentName'], 
        $data['creatorID'], 
        $data['assessmentType'], 
        $data['timeLimit'], 
        $data['noOfItems'], 
        $data['assessmentDesc'], 
        $data['allowedAttempts'],
        $data['subjectCode']
    ];
    $types = 'ssssssss';

    if (!empty($data['open_Date'])) {
        $sql .= ", open_Date = ?";
        $params[] = $data['open_Date'];
        $types .= 's';
    }

    if (!empty($data['closing_Date'])) {
        $sql .= ", closing_Date = ?";
        $params[] = $data['closing_Date'];
        $types .= 's';
    }

    $sql .= " WHERE assessment_ID = ?";
    $params[] = $data['assessmentID'];
    $types .= 's';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    return $stmt->execute();
}

// Function to update questions
function updateQuestions($conn, $questions, $assessmentID) {
    foreach ($questions as $questionID => $questionData) {
        $sql = "UPDATE examination_bank SET question = ?, question_Type = ?, points = ?, choice1 = ?, choice2 = ?, choice3 = ?, choice4 = ? WHERE assessment_ID = ? AND question_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'ssisssssi', 
            $questionData['text'], 
            $questionData['type'], 
            $questionData['points'], 
            $questionData['options'][0],
            $questionData['options'][1],
            $questionData['options'][2],
            $questionData['options'][3],
            $assessmentID, 
            $questionID
        );
        if (!$stmt->execute()) {
            return false;
        }

        if (!updateAnswers($conn, $questionID, $questionData)) {
            return false;
        }
    }
    return true;
}

// Function to update answers
function updateAnswers($conn, $questionID, $questionData) {
    $sql = "";
    switch ($questionData['type']) {
        case 'M': // Multiple Choice
            $sql = "UPDATE exam_answer SET answer = ? WHERE question_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $questionData['correctAnswer'], $questionID);
            break;
        case 'T': // True or False
            $sql = "UPDATE exam_answer SET answer = ? WHERE question_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $questionData['correctAnswer'], $questionID);
            break;
        case 'S': // Short Answer
            $sql = "UPDATE exam_answer SET answer = ? WHERE question_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $questionData['correctAnswer'], $questionID);
            break;
        case 'F': // Match
            $sql = "UPDATE exam_answer SET m_Ans1 = ?, m_Ans2 = ?, m_Ans3 = ?, m_Ans4 = ?, m_Ans5 = ?, m_Ans6 = ?, m_Ans7 = ?, m_Ans8 = ?, m_Ans9 = ?, m_Ans10 = ? WHERE question_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iiiiiiiiiii', 
                $questionData['m_Ans1'], $questionData['m_Ans2'], $questionData['m_Ans3'], $questionData['m_Ans4'], 
                $questionData['m_Ans5'], $questionData['m_Ans6'], $questionData['m_Ans7'], $questionData['m_Ans8'], 
                $questionData['m_Ans9'], $questionData['m_Ans10'], $questionID
            );
            break;
    }
    return $stmt->execute();
}

// Function to generate a unique question_ID
function generateUniqueQuestionID($conn, $assessmentID) {
    $sql = "SELECT MAX(question_ID) as maxQuestionID FROM examination_bank WHERE assessment_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $assessmentID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['maxQuestionID'] ? $row['maxQuestionID'] + 1 : 1; // Start from 1 if no questions exist
}

// Function to add new questions
function addNewQuestions($conn, $newQuestions, $assessmentID) {
    $maxQuestionNoSql = "SELECT MAX(question_No) as maxQuestionNo FROM examination_bank WHERE assessment_ID = ?";
    $stmt = $conn->prepare($maxQuestionNoSql);
    $stmt->bind_param('s', $assessmentID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $questionNo = $row['maxQuestionNo'] ?? 0;

    foreach ($newQuestions as $newQuestion) {
        $questionNo++;
        $newQuestionID = generateUniqueQuestionID($conn, $assessmentID); 

        $sql = "INSERT INTO examination_bank (assessment_ID, question_ID, question, question_Type, points, question_No, choice1, choice2, choice3, choice4) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sissiiisss', 
            $assessmentID, 
            $newQuestionID, 
            $newQuestion['text'], 
            $newQuestion['type'], 
            $newQuestion['points'], 
            $questionNo, 
            $newQuestion['options'][0], 
            $newQuestion['options'][1], 
            $newQuestion['options'][2], 
            $newQuestion['options'][3]
        );
        if (!$stmt->execute()) {
            return false;
        }
        if (!addNewAnswers($conn, $newQuestionID, $newQuestion, $assessmentID)) {
            return false;
        }
    }
    return true;
}

// Function to add new answers
function addNewAnswers($conn, $newQuestionID, $newQuestion, $assessmentID) {
    $sql = "";
    switch ($newQuestion['type']) {
        case 'M': // Multiple Choice
            $sql = "INSERT INTO exam_answer (assessment_ID, question_ID, answer) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sis', $assessmentID, $newQuestionID, $newQuestion['correctAnswer']);
            break;
        case 'T': // True or False
            $sql = "INSERT INTO exam_answer (assessment_ID, question_ID, answer) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sis', $assessmentID, $newQuestionID, $newQuestion['correctAnswer']);
            break;
        case 'S': // Short Answer
            $sql = "INSERT INTO exam_answer (assessment_ID, question_ID, answer) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sis', $assessmentID, $newQuestionID, $newQuestion['correctAnswer']);
            break;
        case 'F': // Match
            $sql = "INSERT INTO exam_answer (assessment_ID, question_ID, m_Ans1, m_Ans2, m_Ans3, m_Ans4, m_Ans5, m_Ans6, m_Ans7, m_Ans8, m_Ans9, m_Ans10) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('isiiiiiiiiii', 
                $assessmentID, 
                $newQuestionID, 
                $newQuestion['m_Ans1'], $newQuestion['m_Ans2'], $newQuestion['m_Ans3'], $newQuestion['m_Ans4'], 
                $newQuestion['m_Ans5'], $newQuestion['m_Ans6'], $newQuestion['m_Ans7'], $newQuestion['m_Ans8'], 
                $newQuestion['m_Ans9'], $newQuestion['m_Ans10']
            );
            break;
    }
    return $stmt->execute();
}


// Start transaction
$conn->begin_transaction();

try {
    $assessmentID = $_POST['assessmentID'];
    if (empty($assessmentID)) {
        throw new Exception("Assessment ID is missing");
    }

    // Initialize questions and newQuestions as arrays
    $questions = isset($_POST['questions']) ? $_POST['questions'] : [];
    $newQuestions = isset($_POST['newQuestions']) ? $_POST['newQuestions'] : [];

    // $creatorID = $_SESSION['creatorID'];
    $creatorID = '201510754MN0';

    $data = [
        'assessmentID' => $assessmentID,
        'assessmentName' => $_POST['assessmentName'],
        'creatorID' => $creatorID, // Replace with actual creator ID
        'subjectCode' => $_POST['subjectCode'], 
        'assessmentType' => 'Q',
        'timeLimit' => $_POST['timeLimit'],
        'assessmentDesc' => $_POST['assessmentDesc'],
        'allowedAttempts' => $_POST['allowedAttempts'],
        'noOfItems' => count($questions) + count($newQuestions),
    ];

    // Include dates only if they are set
    if (!empty($_POST['openDate'])) {
        $data['open_Date'] = date('Y-m-d H:i:s', strtotime($_POST['openDate']));
    }

    if (!empty($_POST['closingDate'])) {
        $data['closing_Date'] = date('Y-m-d H:i:s', strtotime($_POST['closingDate']));
    }

    // Update assessment
    if (!updateAssessment($conn, $data)) {
        throw new Exception("Failed to update assessment");
    }

    // Update existing questions
    if (!updateQuestions($conn, $questions, $assessmentID)) {
        throw new Exception("Failed to update questions");
    }

    // Add new questions
    if (!addNewQuestions($conn, $newQuestions, $assessmentID)) {
        throw new Exception("Failed to add new questions");
    }

    // Commit transaction
    $conn->commit();
    header('Location: ../index.php');
    exit();
} catch (Exception $e) {
    // Rollback transaction
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}
?>
