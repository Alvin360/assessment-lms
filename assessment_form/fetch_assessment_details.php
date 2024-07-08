<?php
require "db.php";
$assessmentID = $_GET['assessmentID'];
$assessmentID = 'A668c22466';

$assessmentSQL = "SELECT assessment_Name FROM assessment WHERE assessment_ID = ?";
$stmt = $conn->prepare($assessmentSQL);
$stmt->bind_param("s", $assessmentID);
$stmt->execute();
$assessmentResult = $stmt->get_result();
$assessment = $assessmentResult->fetch_assoc();

$questionsSQL = "SELECT * FROM examination_bank WHERE assessment_ID = ?";
$stmt = $conn->prepare($questionsSQL);
$stmt->bind_param("s", $assessmentID);
$stmt->execute();
$questionsResult = $stmt->get_result();

$questions = [];
while ($row = $questionsResult->fetch_assoc()) {
    $questions[] = $row;
}

$response = [
    'assessment_Name' => $assessment['assessment_Name'],
    'questions' => $questions
];

header('Content-Type: application/json');
echo json_encode($response);

$stmt->close();
$conn->close();
?>
