<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            padding: 12px 16px;
            margin-top: 8px;
        }

        .dropdown-content div {
            margin: 5px 0;
        }

        .dropdown-content input[type="checkbox"] {
            margin-right: 8px;
        }

        .dropdown-content button {
            width: 100%;
            padding: 8px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .dropdown-content button:hover {
            background-color: #45a049;
        }

        .assessment-item {
            margin-bottom: 20px;
        }
    </style>
    
    <title>Assessment List</title>
</head>
<body>
    <h1>Assessment List</h1>
    <div id="assessment-list">
        <!-- Existing assessments will be displayed here -->
    </div>
    <button onclick="window.location.href='create_assessment.html'">Create New Assessment</button>

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
                    <div class="dropdown">
                        <button onclick="editAssessment('${assessment.assessment_ID}')">Edit</button>
                        <button onclick="toggleDropdown('${assessment.assessment_ID}')">Export</button>
                        <div id="dropdown-${assessment.assessment_ID}" class="dropdown-content">
                            <div>
                                <input type="checkbox" class="checkbox" id="include-answer-key-${assessment.assessment_ID}">
                                <label for="include-answer-key-${assessment.assessment_ID}">Include Answer Key</label>
                            </div>
                            <div>
                                <input type="checkbox" class="checkbox" id="include-answer-sheet-${assessment.assessment_ID}">
                                <label for="include-answer-sheet-${assessment.assessment_ID}">Include Answer Sheet</label>
                            </div>
                            <div>
                                <input type="checkbox" class="checkbox" id="shuffle-questions-${assessment.assessment_ID}">
                                <label for="shuffle-questions-${assessment.assessment_ID}">Shuffle Questions</label>
                            </div>
                            <div>
                                <button onclick="exportAssessment('${assessment.assessment_ID}')">Export PDF</button>
                            </div>
                        </div>
                    </div>
                `;
                assessmentListDiv.appendChild(assessmentDiv);
            });
        }

        function toggleDropdown(id) {
            const dropdown = document.getElementById('dropdown-' + id);
            $('.dropdown-content').not(dropdown).hide(); // Hide other dropdowns
            $(dropdown).toggle(); // Toggle the clicked dropdown
        }

        fetchAssessments();

        function editAssessment(assessmentID) {
            // Redirect to edit page passing assessmentID as parameter
            window.location.href = `includes/edit_assessment.php?id=${assessmentID}`;
        }

        function exportAssessment(assessmentID) {
            const includeAnswerKey = document.getElementById(`include-answer-key-${assessmentID}`).checked;
            const shuffleQuestions = document.getElementById(`shuffle-questions-${assessmentID}`).checked;
            const includeAnswerSheet = document.getElementById(`include-answer-sheet-${assessmentID}`).checked;
            const url = `includes/export_assessment.php?id=${assessmentID}&includeAnswerKey=${includeAnswerKey}&includeAnswerSheet=${includeAnswerSheet}&shuffleQuestions=${shuffleQuestions}`;
            window.location.href = url;
        }
    </script>
</body>
</html>











