<?php
require_once '../includes/dbh_inc.php';
require_once '../includes/execute_query_inc.php';

$sql = "SELECT assessment_ID, assessment_Name, date FROM ASSESSMENT"; // Modify the query to include assessment_Name
$result = $conn->query($sql);

$assessments = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $assessments[] = $row;
    }
}

echo json_encode($assessments);

$conn->close();
?>

