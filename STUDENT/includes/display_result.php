<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Result</title>
    <link rel="stylesheet" href="../styles.css">
    <script>
        // Prevent back navigation
        window.onload = function() {
            if (typeof history.pushState === "function") {
                history.pushState(null, null, document.URL);
                window.addEventListener('popstate', function () {
                    history.pushState(null, null, document.URL);
                });
            }
        };

        // Optionally, you can show an alert when trying to go back
        window.addEventListener('popstate', function(event) {
            alert("Invalid action. Please proceed to the Student Landing Page.");
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Assessment Result</h1>
        <p id="score"></p>
        <p id="grade"></p>
        <div class="button-container">
            <a href="../index.php" class="button">Go to Student Landing Page</a>
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
