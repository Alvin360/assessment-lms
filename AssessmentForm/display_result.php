<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Result</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Assessment Result</h1>
        <p id="score"></p>
        <p id="grade"></p>
        <div class="button-container">
            <a href="student_landing_page.php" class="button">Go to Student Landing Page</a>
            <a href="student_report_page.php" class="button">Proceed to Student Report Page</a>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const score = urlParams.get('score');
            const grade = urlParams.get('grade');
            const totalPoints = urlParams.get('totalPoints');

            document.getElementById('score').textContent = `Score: ${score} out of ${totalPoints}`;
            document.getElementById('grade').textContent = `Grade: ${grade}%`;
        });
    </script>
</body>
</html>
