async function fetchAssessmentDetails() {
    const urlParams = new URLSearchParams(window.location.search);
    const assessmentID = urlParams.get('assessmentID');

    // Fetch assessment details from the server
    const response = await fetch(`fetch_assessment_details.php?assessmentID=${assessmentID}`);
    const data = await response.json();

    // Log the fetched data to verify
    console.log('Fetched data:', data);

    // Set the assessment name in the HTML
    document.getElementById('assessment-name').textContent = data.assessment_Name;

    const questionsContainer = document.getElementById('questions-container');
    questionsContainer.innerHTML = '';

    // Loop through each question and create HTML elements accordingly
    data.questions.forEach(question => {
        const questionDiv = document.createElement('div');
        questionDiv.classList.add('question');

        let questionHTML = `<p>${question.question_No}. ${question.question} (${question.points} points)</p>`;

        if (question.question_Type === 'M') {
            // Multiple choice question
            questionHTML += `
                <label><input type="radio" name="question-${question.question_ID}" value="${question.choice1}" required> ${question.choice1}</label><br>
                <label><input type="radio" name="question-${question.question_ID}" value="${question.choice2}" required> ${question.choice2}</label><br>
                <label><input type="radio" name="question-${question.question_ID}" value="${question.choice3}" required> ${question.choice3}</label><br>
                <label><input type="radio" name="question-${question.question_ID}" value="${question.choice4}" required> ${question.choice4}</label>
            `;
        } else if (question.question_Type === 'T') {
            // True/false question
            questionHTML += `
                <label><input type="radio" name="question-${question.question_ID}" value="T" required> True</label><br>
                <label><input type="radio" name="question-${question.question_ID}" value="F" required> False</label>
            `;
        } else if (question.question_Type === 'S') {
            // Short answer question
            questionHTML += `<input type="text" name="question-${question.question_ID}" required>`;
        } else if (question.question_Type === 'F') {
            // Matching question
            questionHTML += '<table>';
            const matchOptions = [];
            const matches = [];
            for (let i = 1; i <= 10; i++) {
                if (question[`match${i}`]) {
                    matches.push(question[`match${i}`]);
                }
                if (question[`match${i}`]) {
                    matchOptions.push(question[`match${i}`]);
                }
            }
            matches.forEach((match, index) => {
                questionHTML += `
                    <tr>
                        <td>${match}</td>
                        <td>
                            <select name="question-${question.question_ID}-match${index + 1}" required>
                                <option value="">Select...</option>
                                ${matchOptions.map(option => `<option value="${option}">${option}</option>`).join('')}
                            </select>
                        </td>
                    </tr>
                `;
            });
            questionHTML += '</table>';
        }

        questionDiv.innerHTML = questionHTML;
        questionsContainer.appendChild(questionDiv);
    });

    // Add hidden input fields for assessmentID and userID
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'assessmentID';
    hiddenInput.value = assessmentID;
    document.getElementById('assessment-form').appendChild(hiddenInput);

    const userIDInput = document.createElement('input');
    userIDInput.type = 'hidden';
    userIDInput.name = 'userID';
    userIDInput.value = 'USER123'; // Replace with actual userID
    document.getElementById('assessment-form').appendChild(userIDInput);

    // Set up the timer if there is a time limit
    if (data.time_Limit) {
        console.log('Setting up timer with limit:', data.time_Limit);
        setupTimer(data.time_Limit);
    }
}

function setupTimer(timeLimit) {
    const timerElement = document.getElementById('timer');

    // Parse the time limit in format Hr:Min:Sec
    const timeParts = timeLimit.split(':');
    let timeRemaining = (+timeParts[0] * 3600) + (+timeParts[1] * 60) + (+timeParts[2]); // Convert to seconds

    const interval = setInterval(() => {
        const hours = Math.floor(timeRemaining / 3600);
        const minutes = Math.floor((timeRemaining % 3600) / 60);
        const seconds = timeRemaining % 60;

        timerElement.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

        if (timeRemaining <= 0) {
            clearInterval(interval);
            alert('Time is up! Your assessment will be submitted.');
            document.getElementById('assessment-form').submit();
        }

        timeRemaining--;
    }, 1000);
}

async function submitAssessment() {
    // Get form data
    const formData = new FormData(document.getElementById('assessment-form'));

    // Submit assessment to the server
    const response = await fetch('submit_assessment.php', {
        method: 'POST',
        body: formData
    });

    // Show the result of the submission
    const result = await response.text();
    alert(result);
}

document.addEventListener('DOMContentLoaded', () => {
    // Fetch assessment details when the page is loaded
    fetchAssessmentDetails();

    // Submit assessment when the form is submitted
    document.getElementById('assessment-form').addEventListener('submit', event => {
        event.preventDefault();
        submitAssessment();
    });
});
