<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Result</title>
    <link rel="stylesheet" href="../styles.css">
    <script>
        // Prevent back navigation and redirect to assessment_viewinfo.php
        window.onload = function() {
            if (typeof history.pushState === "function") {
                history.pushState(null, null, document.URL);
                window.addEventListener('popstate', function () {
                    history.pushState(null, null, document.URL);
                    window.location.href = 'assessment_viewinfo.php'; // Adjust the path to your file
                });
            }
        };

        // Optionally, you can show an alert when trying to go back
        window.addEventListener('popstate', function(event) {
            alert("You are being redirected to the assessment view information page.");
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Assessment Result</h1>
        <p id="score"></p>
        <p id="grade"></p>
        <div class="button-container">
            <a href="../landing_page.php" class="button">Go to Assessment Page</a>
            <a id="assessment-info-link" class="button">Go to Assessment Info</a>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const score = urlParams.get('score');
            const grade = urlParams.get('grade');
            const totalPoints = urlParams.get('totalPoints');
            const assessmentID = urlParams.get('assessmentID');
            const subjectCode = urlParams.get('subjectCode');


            document.getElementById('score').textContent = `Score: ${score} out of ${totalPoints}`;
            document.getElementById('grade').textContent = `Grade: ${grade}%`;
        
            // Set the href for the "Go to Assessment Info" button
            const assessmentInfoLink = document.getElementById('assessment-info-link');
            assessmentInfoLink.href = `assessment-viewinfo.php?assessment_ID=${assessmentID}&subject_Code=${subjectCode}`;
        });
    </script>
</body>
</html>
