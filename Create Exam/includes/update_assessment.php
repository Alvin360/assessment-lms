<?php
session_start(); // Start the session
require "db.php";

// Function to update assessment
function updateAssessment($conn, $data) {
    $sql = "UPDATE ASSESSMENT SET assessment_Name = ?, date = ?, open_Date = ?, creator_ID = ?, subject_Code = ?, assessment_Type = ?, time_Limit = ?, no_Of_Items = ?, closing_Date = ?, assessment_Desc = ?, allowed_Attempts = ? WHERE assessment_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'sssssssissss', 
        $data['assessmentName'], 
        $data['date'], 
        $data['open_date'],
        $data['creatorID'], 
        $data['subjectCode'], 
        $data['assessmentType'], 
        $data['timeLimit'], 
        $data['noOfItems'], 
        $data['closingDate'], 
        $data['assessmentDesc'], 
        $data['allowedAttempts'], 
        $data['assessmentID']
    );
    return $stmt->execute();
}

// Function to update questions
function updateQuestions($conn, $questions, $assessmentID) {
    foreach ($questions as $questionID => $questionData) {
        $sql = "UPDATE EXAMINATION_BANK SET question = ?, question_Type = ?, points = ? WHERE assessment_ID = ? AND question_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'ssisi', 
            $questionData['text'], 
            $questionData['type'], 
            $questionData['points'], 
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
        case 'T': // True or False
        case 'S': // Short Answer
            $sql = "UPDATE EXAM_ANSWER SET answer = ? WHERE question_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $questionData['correctAnswer'], $questionID);
            break;
        case 'F': // Match
            $sql = "UPDATE EXAM_ANSWER SET m_Ans1 = ?, m_Ans2 = ?, m_Ans3 = ?, m_Ans4 = ?, m_Ans5 = ?, m_Ans6 = ?, m_Ans7 = ?, m_Ans8 = ?, m_Ans9 = ?, m_Ans10 = ? WHERE question_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssssssssi', 
                $questionData['match'][0], $questionData['match'][1], $questionData['match'][2], $questionData['match'][3], 
                $questionData['match'][4], $questionData['match'][5], $questionData['match'][6], $questionData['match'][7], 
                $questionData['match'][8], $questionData['match'][9], $questionID
            );
            break;
    }
    return $stmt->execute();
}

// Function to add new questions
function addNewQuestions($conn, $newQuestions, $assessmentID) {
    $maxQuestionNoSql = "SELECT MAX(question_No) as maxQuestionNo FROM EXAMINATION_BANK WHERE assessment_ID = ?";
    $stmt = $conn->prepare($maxQuestionNoSql);
    $stmt->bind_param('s', $assessmentID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $questionNo = $row['maxQuestionNo'] ?? 0;

    foreach ($newQuestions as $newQuestion) {
        $questionNo++;
        $sql = "INSERT INTO EXAMINATION_BANK (assessment_ID, question, question_Type, points, question_No) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssii', $assessmentID, $newQuestion['text'], $newQuestion['type'], $newQuestion['points'], $questionNo);
        if (!$stmt->execute()) {
            return false;
        }
        $newQuestionID = $stmt->insert_id;
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
        case 'T': // True or False
        case 'S': // Short Answer
            $sql = "INSERT INTO EXAM_ANSWER (assessment_ID, question_ID, answer) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sis', $assessmentID, $newQuestionID, $newQuestion['correctAnswer']);
            break;
        case 'F': // Match
            $sql = "INSERT INTO EXAM_ANSWER (assessment_ID, question_ID, m_Ans1, m_Ans2, m_Ans3, m_Ans4, m_Ans5, m_Ans6, m_Ans7, m_Ans8, m_Ans9, m_Ans10) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('issssssssssi', 
                $assessmentID, $newQuestionID, 
                $newQuestion['match'][0], $newQuestion['match'][1], $newQuestion['match'][2], $newQuestion['match'][3], 
                $newQuestion['match'][4], $newQuestion['match'][5], $newQuestion['match'][6], $newQuestion['match'][7], 
                $newQuestion['match'][8], $newQuestion['match'][9]
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

    $creatorID = $_SESSION['creatorID'];
    $subjectCode = $_SESSION['subjectCode'];

    $data = [
        'assessmentID' => $assessmentID,
        'assessmentName' => $_POST['assessmentName'],
        'date' => date('Y-m-d'),
        'open_date' => $_POST['openDate'],
        'creatorID' => $creatorID, // Replace with actual creator ID
        'subjectCode' => $subjectCode, // Replace with actual subject code
        'assessmentType' => 'Assessment',
        'timeLimit' => $_POST['timeLimit'],
        'closingDate' => $_POST['closingDate'],
        'assessmentDesc' => $_POST['assessmentDesc'],
        'allowedAttempts' => $_POST['allowedAttempts'],
        'noOfItems' => count($questions) + count($newQuestions),
    ];

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
    header("Location: edit_assessment.php?id=$assessmentID&success=1");
    exit();
} catch (Exception $e) {
    // Rollback transaction
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}
?>








