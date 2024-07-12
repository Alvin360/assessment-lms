<?php

session_start();

// Set session variables
$_SESSION["user_ID"] = "amasarqu";

echo "Session variables are set.<br>";
?>


<form action="assessment-viewinfo.php" method="get">
    <label for="assessment_ID">Assessment ID:</label>
    <input type="text" id="assessment_ID" name="assessment_ID" required><br>
    <label for="subject_Code">Subject Code:</label>
    <input type="text" id="subject_Code" name="subject_Code" required><br>
    <input type="submit" value="Submit">
</form>

