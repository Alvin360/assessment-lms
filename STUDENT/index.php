<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Start</title>
    <link href="../STUDENT/styles.css" rel="stylesheet">
</head>
<body>
    <div  class='container'>
        <h1>Assessment (Student)</h1>
        <form action="../STUDENT/includes/verify_student.php" method="post">
            <div id='container_session'>
                <label for="student-id">Student ID:</label>
                <input name="student-id" id="student-id" type="text" required>
                <input type="submit" value="Submit" id="submit_session"/>
            </div>
        </form>
    </div>
</body>
</html>
