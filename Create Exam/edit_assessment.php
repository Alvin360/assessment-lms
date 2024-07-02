<?php
require "db.php";

$assessmentID = $_GET['id']; // Assuming assessmentID is passed in the URL

$sqlSelectAssessment = "SELECT * FROM ASSESSMENT WHERE assessment_ID = ?";
$stmtSelectAssessment = $conn->prepare($sqlSelectAssessment);
$stmtSelectAssessment->bind_param('s', $assessmentID);
$stmtSelectAssessment->execute();
$resultAssessment = $stmtSelectAssessment->get_result();
$assessment = $resultAssessment->fetch_assoc();

$sqlSelectQuestions = "SELECT * FROM EXAMINATION_BANK WHERE assessment_ID = ?";
$stmtSelectQuestions = $conn->prepare($sqlSelectQuestions);
$stmtSelectQuestions->bind_param('s', $assessmentID);
$stmtSelectQuestions->execute();
$resultQuestions = $stmtSelectQuestions->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Assessment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-group {
            margin-bottom: 10px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 5px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
        }
    </style>
<script>
    let questionCount = <?php echo $resultQuestions->num_rows; ?>;

    function addQuestion() {
        questionCount++;
        const questionsContainer = document.getElementById('new-questions-container');

        const questionDiv = document.createElement('div');
        questionDiv.className = 'form-group';

        questionDiv.innerHTML = `
            <label for="new-question-${questionCount}">New Question ${questionCount}:</label>
            <input type="text" id="new-question-${questionCount}" name="newQuestions[${questionCount}][text]" required>
            
            <label for="new-question-type-${questionCount}">Question Type:</label>
            <select id="new-question-type-${questionCount}" name="newQuestions[${questionCount}][type]" onchange="displayOptions(this, ${questionCount})" required>
                <option value="">Select Type</option>
                <option value="M">Multiple Choice</option>
                <option value="T">True or False</option>
                <option value="S">Short Answer (Fill-in-the-Blank)</option>
                <option value="F">Match</option>
            </select>

            <div id="options-new-${questionCount}" class="options-container"></div>
        `;

        questionsContainer.appendChild(questionDiv);
    }

    function displayOptions(selectElement, count) {
        const selectedType = selectElement.value;
        const optionsContainer = document.getElementById(`options-new-${count}`);
        optionsContainer.innerHTML = '';

        if (selectedType === 'M') {
            optionsContainer.innerHTML = `
                <label for="option1-${count}">Option 1:</label>
                <input type="text" id="option1-${count}" name="newQuestions[${count}][options][0]" required>
                <label for="option2-${count}">Option 2:</label>
                <input type="text" id="option2-${count}" name="newQuestions[${count}][options][1]" required>
                <label for="option3-${count}">Option 3:</label>
                <input type="text" id="option3-${count}" name="newQuestions[${count}][options][2]" required>
                <label for="option4-${count}">Option 4:</label>
                <input type="text" id="option4-${count}" name="newQuestions[${count}][options][3]" required>
                <label for="correct-answer-${count}">Correct Answer:</label>
                <input type="text" id="correct-answer-${count}" name="newQuestions[${count}][correctAnswer]" required>
            `;
        } else if (selectedType === 'T') {
            optionsContainer.innerHTML = `
                <label for="boolean-${count}">Correct Answer (True/False):</label>
                <select id="boolean-${count}" name="newQuestions[${count}][boolean]" required>
                    <option value="T">True</option>
                    <option value="F">False</option>
                </select>
            `;
        } else if (selectedType === 'S') {
            optionsContainer.innerHTML = `
                <label for="fill-blank-${count}">Correct Answer:</label>
                <input type="text" id="fill-blank-${count}" name="newQuestions[${count}][fillBlank]" required>
            `;
        } else if (selectedType === 'F') {
            optionsContainer.innerHTML = `
                <label for="match1-${count}">Match 1:</label>
                <input type="text" id="match1-${count}" name="newQuestions[${count}][match][0]" required>
                <label for="match2-${count}">Match 2:</label>
                <input type="text" id="match2-${count}" name="newQuestions[${count}][match][1]" required>
                <label for="match3-${count}">Match 3:</label>
                <input type="text" id="match3-${count}" name="newQuestions[${count}][match][2]" required>
                <label for="match4-${count}">Match 4:</label>
                <input type="text" id="match4-${count}" name="newQuestions[${count}][match][3]" required>
                
                <label for="m-ans1-${count}">Match Correct Answer 1:</label>
                <input type="text" id="m-ans1-${count}" name="newQuestions[${count}][m_Ans1]" required>
                <label for="m-ans2-${count}">Match Correct Answer 2:</label>
                <input type="text" id="m-ans2-${count}" name="newQuestions[${count}][m_Ans2]" required>
                <label for="m-ans3-${count}">Match Correct Answer 3:</label>
                <input type="text" id="m-ans3-${count}" name="newQuestions[${count}][m_Ans3]" required>
                <label for="m-ans4-${count}">Match Correct Answer 4:</label>
                <input type="text" id="m-ans4-${count}" name="newQuestions[${count}][m_Ans4]" required>
            `;
        }
    }
</script>



</head>
<body>
    <h2>Edit Assessment</h2>
    <form action="update_assessment.php" method="post">
        <input type="hidden" name="assessmentID" value="<?php echo $assessment['assessment_ID']; ?>">
        <div class="form-group">
            <label for="assessmentName">Assessment Name:</label>
            <input type="text" id="assessmentName" name="assessmentName" value="<?php echo htmlspecialchars($assessment['assessment_Name']); ?>" required>
        </div>
        <div id="existing-questions-container">
            <?php while ($question = $resultQuestions->fetch_assoc()): ?>
                <div class="form-group">
                    <label for="question-<?php echo $question['question_ID']; ?>">Question <?php echo $question['question_No']; ?>:</label>
                    <input type="text" id="question-<?php echo $question['question_ID']; ?>" name="questions[<?php echo $question['question_ID']; ?>][text]" value="<?php echo htmlspecialchars($question['question']); ?>" required>
                    
                    <label for="question-type-<?php echo $question['question_ID']; ?>">Question Type:</label>
                    <select id="question-type-<?php echo $question['question_ID']; ?>" name="questions[<?php echo $question['question_ID']; ?>][type]" onchange="displayOptions(this, <?php echo $question['question_ID']; ?>)" required>
                        <option value="">Select Type</option>
                        <option value="M" <?php echo ($question['question_Type'] === 'M') ? 'selected' : ''; ?>>Multiple Choice</option>
                        <option value="T" <?php echo ($question['question_Type'] === 'T') ? 'selected' : ''; ?>>True or False</option>
                        <option value="S" <?php echo ($question['question_Type'] === 'S') ? 'selected' : ''; ?>>Short Answer (Fill-in-the-Blank)</option>
                        <option value="F" <?php echo ($question['question_Type'] === 'F') ? 'selected' : ''; ?>>Match</option>
                    </select>

                    <div id="options-<?php echo $question['question_ID']; ?>" class="options-container">
                        <?php if ($question['question_Type'] === 'M'): ?>
                            <label for="option1-<?php echo $question['question_ID']; ?>">Option 1:</label>
                            <input type="text" id="option1-<?php echo $question['question_ID']; ?>" name="questions[<?php echo $question['question_ID']; ?>][options][0]" value="<?php echo htmlspecialchars($question['choice1']); ?>" required>
                            <label for="option2-<?php echo $question['question_ID']; ?>">Option 2:</label>
                            <input type="text" id="option2-<?php echo $question['question_ID']; ?>" name="questions[<?php echo $question['question_ID']; ?>][options][1]" value="<?php echo htmlspecialchars($question['choice2']); ?>" required>
                            <label for="option3-<?php echo $question['question_ID']; ?>">Option 3:</label>
                            <input type="text" id="option3-<?php echo $question['question_ID']; ?>" name="questions[<?php echo $question['question_ID']; ?>][options][2]" value="<?php echo htmlspecialchars($question['choice3']); ?>" required>
                            <label for="option4-<?php echo $question['question_ID']; ?>">Option 4:</label>
                            <input type="text" id="option4-<?php echo $question['question_ID']; ?>" name="questions[<?php echo $question['question_ID']; ?>][options][3]" value="<?php echo htmlspecialchars($question['choice4']); ?>" required>
                            <label for="correct-answer-<?php echo $question['question_ID']; ?>">Correct Answer:</label>
                            <input type="text" id="correct-answer-<?php echo $question['question_ID']; ?>" name="questions[<?php echo $question['question_ID']; ?>][correctAnswer]" value="<?php echo htmlspecialchars($question['correctAnswer']); ?>" required>
                        <?php elseif ($question['question_Type'] === 'T'): ?>
                            <label for="boolean-<?php echo $question['question_ID']; ?>">Correct Answer (True/False):</label>
                            <select id="boolean-<?php echo $question['question_ID']; ?>" name="questions[<?php echo $question['question_ID']; ?>][boolean]" required>
                                <option value="T" <?php echo ($question['boolean'] === 'T') ? 'selected' : ''; ?>>True</option>
                                <option value="F" <?php echo ($question['boolean'] === 'F') ? 'selected' : ''; ?>>False</option>
                            </select>
                        <?php elseif ($question['question_Type'] === 'S'): ?>
                            <label for="fill-blank-<?php echo $question['question_ID']; ?>">Correct Answer:</label>
                            <input type="text" id="fill-blank-<?php echo $question['question_ID']; ?>" name="questions[<?php echo $question['question_ID']; ?>][fillBlank]" value="<?php echo htmlspecialchars($question['fill_Blank']); ?>" required>
                        <?php elseif ($question['question_Type'] === 'F'): ?>
                            <label for="match1-<?php echo $question['question_ID']; ?>">Match 1:</label>
                            <input type="text" id="match1-<?php echo $question['question_ID']; ?>" name="questions[<?php echo $question['question_ID']; ?>][match][0]" value="<?php echo htmlspecialchars($question['match1']); ?>" required>
                            <label for="match2-<?php echo $question['question_ID']; ?>">Match 2:</label>
                            <input type="text" id="match2-<?php echo $question['question_ID']; ?>" name="questions[<?php echo $question['question_ID']; ?>][match][1]" value="<?php echo htmlspecialchars($question['match2']); ?>" required>
                            <label for="match3-<?php echo $question['question_ID']; ?>">Match 3:</label>
                            <input type="text" id="match3-<?php echo $question['question_ID']; ?>" name="questions[<?php echo $question['question_ID']; ?>][match][2]" value="<?php echo htmlspecialchars($question['match3']); ?>" required>
                            <label for="match4-<?php echo $question['question_ID']; ?>">Match 4:</label>
                            <input type="text" id="match4-<?php echo $question['question_ID']; ?>" name="questions[<?php echo $question['question_ID']; ?>][match][3]" value="<?php echo htmlspecialchars($question['match4']); ?>" required>

                            <label for="m-ans1-<?php echo $question['question_ID']; ?>">Match Correct Answer 1:</label>
                            <input type="text" id="m-ans1-<?php echo $question['question_ID']; ?>" name="questions[<?php echo $question['question_ID']; ?>][m_Ans1]" value="<?php echo htmlspecialchars($question['m_Ans1']); ?>" required>
                            <label for="m-ans2-<?php echo $question['question_ID']; ?>">Match Correct Answer 2:</label>
                            <input type="text" id="m-ans2-<?php echo $question['question_ID']; ?>" name="questions[<?php echo $question['question_ID']; ?>][m_Ans2]" value="<?php echo htmlspecialchars($question['m_Ans2']); ?>" required>
                            <label for="m-ans3-<?php echo $question['question_ID']; ?>">Match Correct Answer 3:</label>
                            <input type="text" id="m-ans3-<?php echo $question['question_ID']; ?>" name="questions[<?php echo $question['question_ID']; ?>][m_Ans3]" value="<?php echo htmlspecialchars($question['m_Ans3']); ?>" required>
                            <label for="m-ans4-<?php echo $question['question_ID']; ?>">Match Correct Answer 4:</label>
                            <input type="text" id="m-ans4-<?php echo $question['question_ID']; ?>" name="questions[<?php echo $question['question_ID']; ?>][m_Ans4]" value="<?php echo htmlspecialchars($question['m_Ans4']); ?>" required>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div id="new-questions-container"></div>
        <button type="button" onclick="addQuestion()">Add Question</button>
        <button type="submit">Save Changes</button>
    </form>
</body>
</html>

