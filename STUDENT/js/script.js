document.addEventListener('DOMContentLoaded', () => {
    fetch(`../STUDENT/includes/get_assessment_student.php?user_ID=${userID}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(assessments => {
            console.log(assessments);
            populateAssessments(assessments);
        })
        .catch(error => {
            console.error('Error:', error);
        });

    fetchAssessmentDetails();
    document.getElementById('assessment-form').addEventListener('submit', event => {
        event.preventDefault();
        const confirmed = confirm('Are you sure you want to submit the assessment?');
        if (confirmed) {
            submitAssessment();
        }
    });
});

function populateAssessments(assessments) {
    const assessmentsContainer = document.getElementById('container_assessment_subject');
    assessmentsContainer.innerHTML = ''; 

    for (const subject in assessments) {
        if (assessments.hasOwnProperty(subject) && assessments[subject].length > 0) {
            const subjectSection = document.createElement('div');
            subjectSection.className = 'subject_section';
            const subjectHeader = document.createElement('h1');
            subjectHeader.textContent = subject;

            subjectSection.appendChild(subjectHeader);
            const underline = document.createElement('div');
            underline.className = 'underline';
            subjectSection.appendChild(underline);

            //assessmentsContainer.appendChild(subjectHeader);

            const cardContainer = document.createElement('div');
            cardContainer.className = 'card_container';

            assessments[subject].forEach(assessment => {
                const assessmentCard = document.createElement('div');
                assessmentCard.className = 'card_topic';
                const buttonClass = getButtonClass(assessment.status);
                assessmentCard.innerHTML = `
                        <div class="flex_row">
                            <h2>${assessment.assessment_name}</h2>
                            <button class="assessment-button ${buttonClass}">${assessment.status}</button>
                        </div>
                        <div class="p_cont">
                            <p class="p_nospace">Opened: ${assessment.open_Date}</p>
                            <p class="p_nospace">Due: ${assessment.closing_Date}</p>
                        </div>
                `;
                const button = assessmentCard.querySelector('.assessment-button');
                button.addEventListener('click', () => {
                    window.location.href = `../STUDENT/includes/assessment-viewinfo.php?assessment_ID=${assessment.assessment_id}&subject_Code=${assessment.subject_Code}`;
                });
                cardContainer.appendChild(assessmentCard);
            });

            subjectSection.appendChild(cardContainer);
            assessmentsContainer.appendChild(subjectSection);
        }
    }
}

function getButtonClass(status) {
    switch (status) {
        case 'Start':
            return 'start-status';
        case 'Reattempt':
            return 'reattempt-status';
        case 'View Details':
            return 'view-details-status';
        case 'Missed':
            return 'missed-status';
        default:
            return '';
    }
}

async function fetchAssessmentDetails() {
    const urlParams = new URLSearchParams(window.location.search);
    const assessmentID = urlParams.get('assessmentID');

    // Fetch assessment details from the server
    const response = await fetch(`../includes/fetch_assessment_details.php?assessmentID=${assessmentID}`);
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
        questionDiv.classList.add('content-block');

        let questionHTML = `<p class='question-txt'><strong>${question.question_No}. ${question.question} </strong>(${question.points} points)</p>`;

        questionHTML += '<div class="choices-field">'
        if (question.question_Type === 'M') {
            // Multiple choice question
            questionHTML += `<div class="form-group"><div class="field-block">
                <label><input type="radio" name="question-${question.question_ID}" value="${question.choice1}" required> ${question.choice1}</label><br>
                <label><input type="radio" name="question-${question.question_ID}" value="${question.choice2}" required> ${question.choice2}</label><br>
                <label><input type="radio" name="question-${question.question_ID}" value="${question.choice3}" required> ${question.choice3}</label><br>
                <label><input type="radio" name="question-${question.question_ID}" value="${question.choice4}" required> ${question.choice4}</label>
            </div></div>`;
        } else if (question.question_Type === 'T') {
            // True/false question
            questionHTML += `<div class="form-group"><div class="field-block">
                <label><input type="radio" name="question-${question.question_ID}" value="T" required> True</label><br>
                <label><input type="radio" name="question-${question.question_ID}" value="F" required> False</label>
            </div></div>`;
        } else if (question.question_Type === 'S') {
            // Short answer question
            questionHTML += `<div class="form-group"><div class="field-block"><input class='textbox' type="text" name="question-${question.question_ID}" required></div></div>`;
        } else if (question.question_Type === 'F') {
            // Matching question
            questionHTML += '<div class="form-group"><table>';
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
                            <select class="dropdown" name="question-${question.question_ID}-match${index + 1}" required>
                                <option value="">Select...</option>
                                ${matchOptions.map(option => `<option value="${option}">${option}</option>`).join('')}
                            </select>
                        </td>
                    </tr>
                `;
            });
            questionHTML += '</table></div>';
        } else if (question.question_Type === 'E') {
            // Short answer question
            questionHTML += `<div class="form-group"><div class="field-block"><textarea class="textarea" type="text" name="question-${question.question_ID}" required></textarea></div></div>`;
        }

        questionHTML += '</div>'

        questionDiv.innerHTML = questionHTML;
        questionsContainer.appendChild(questionDiv);
    });


    // Set up the timer if there is a time limit
    if (data.time_Limit) {
        console.log('Setting up timer with limit:', data.time_Limit);
        setupTimer(data.time_Limit);
    }

    // Add hidden input fields for assessmentID and userID
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'assessmentID';
    hiddenInput.value = assessmentID;
    document.getElementById('assessment-form').appendChild(hiddenInput);

    const userIDInput = document.createElement('input');
    userIDInput.type = 'hidden';
    userIDInput.name = 'userID';
    userIDInput.value = $userID; // Use the actual userID from the session
    document.getElementById('assessment-form').appendChild(userIDInput);

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

async function submitAssessment(assessmentID, subjectCode) {
    const formData = new FormData(document.getElementById('assessment-form'));

    const response = await fetch('../includes/submit_assessment.php', {
        method: 'POST',
        body: formData
    });

    const result = await response.json();
    window.location.href = `../includes/display_result.php?score=${result.score}&grade=${result.grade}&totalPoints=${result.totalPoints}&subjectCode=${subjectCode}&assessmentID=${assessmentID}`;
}

document.addEventListener('DOMContentLoaded', () => {
    fetchAssessmentDetails();
    document.getElementById('assessment-form').addEventListener('submit', event => {
        event.preventDefault();
        const confirmed = confirm('Are you sure you want to submit the assessment?');
        if (confirmed) {
            const urlParams = new URLSearchParams(window.location.search);
            const assessmentID = urlParams.get('assessmentID');
            const subjectCode = urlParams.get('subjectCode');
            submitAssessment(assessmentID, subjectCode);
        }
    });
});

//navbar
document.getElementById("more-white").addEventListener("click", function(){
    document.getElementsByClassName("dropdown-menu-white")[0].classList.toggle("toggle-in");
  })
  
  //navbar
  document.getElementById("more-red").addEventListener("click", function(){
    document.getElementsByClassName("dropdown-menu-red")[0].classList.toggle("toggle-in");
  })
  
  //profile
  document.getElementById("profile").addEventListener("click", function(){
    document.getElementsByClassName("dropdown-menu-profile")[0].classList.toggle("toggle-in");
  })
  
  //drawer
  document.getElementsByClassName("toggle-hamburger")[0].addEventListener("click", function(){
    document.getElementById("drawer").classList.toggle("enter-from-left");
  })
  
  document.getElementsByClassName("close-button")[0].addEventListener("click", function(){
    document.getElementById("drawer").classList.toggle("enter-from-left");
  })