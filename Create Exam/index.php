<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="styles.css">
    
    <title>Assessment List</title>

</head>
<body>
    <h1>Assessment List</h1>
    <div id="assessment-list">
        <!-- Existing assessments will be displayed here -->
    </div>
    <button onclick="window.location.href='create-assessment.html'">Create New Assessment</button>

    <script>
        async function fetchAssessments() {
            const response = await fetch('includes/get_assessments.php');
            const assessments = await response.json();

            const assessmentListDiv = document.getElementById('assessment-list');
            assessments.forEach(assessment => {
                const assessmentDiv = document.createElement('div');
                assessmentDiv.className = 'assessment-item';
                assessmentDiv.innerHTML = `
                    <h3>${assessment.assessment_Name}</h3>
                    <button onclick="editAssessment('${assessment.assessment_ID}')">Edit</button>
                `;
                assessmentListDiv.appendChild(assessmentDiv);
            });
        }

        fetchAssessments();

        function editAssessment(assessmentID) {
            // Redirect to edit page passing assessmentID as parameter
            window.location.href = `includes/edit_assessment.php?id=${assessmentID}`;
        }
    </script>
</body>
</html>





