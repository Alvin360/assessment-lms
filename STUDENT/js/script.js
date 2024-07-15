document.addEventListener('DOMContentLoaded', () => {
    fetch('../STUDENT/includes/get_assessment_student.php')
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
});

function populateAssessments(assessments) {
    const assessmentsContainer = document.getElementById('container_assessment_subject');
    assessmentsContainer.innerHTML = ''; 

    for (const subject in assessments) {
        if (assessments.hasOwnProperty(subject) && assessments[subject].length > 0) {
            const subjectHeader = document.createElement('h2');
            subjectHeader.textContent = subject;
            assessmentsContainer.appendChild(subjectHeader);

            assessments[subject].forEach(assessment => {
                const assessmentCard = document.createElement('div');
                assessmentCard.className = 'card_topic';
                const buttonClass = getButtonClass(assessment.status);
                assessmentCard.innerHTML = `
                    <div class="container_assessment_student">
                        <div class="flex_row">
                            <h3>${assessment.assessment_name}</h3>
                            <button class="assessment-button ${buttonClass}">${assessment.status}</button>
                        </div>
                        <p>Opened: ${assessment.open_Date}</p>
                        <p>Due: ${assessment.closing_Date}</p>
                    </div>
                `;
                const button = assessmentCard.querySelector('.assessment-button');
                button.addEventListener('click', () => {
                    window.location.href = `../STUDENT/includes/assessment-viewinfo.php?assessment_ID=${assessment.assessment_id}&subject_Code=${assessment.subject_Code}`;
                });
                assessmentsContainer.appendChild(assessmentCard);
            });
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