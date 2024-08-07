document.addEventListener('DOMContentLoaded', () => {
    fetch('../LandingPage_Student/includes/get_assessment_student.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(assessments => {
            populateAssessments(assessments);
        })
        .catch(error => console.error('Error:', error));
});

function populateAssessments(assessments) {
    const assessmentsContainer = document.getElementById('container_assessment_subject');
    assessmentsContainer.innerHTML = ''; 
    
    for (const subject in assessments) {
        if (assessments.hasOwnProperty(subject)) {
            const subjectHeader = document.createElement('h2');
            subjectHeader.textContent = subject;
            assessmentsContainer.appendChild(subjectHeader);

            assessments[subject].forEach(assessment => {
                const assessmentCard = document.createElement('div');
                assessmentCard.className = 'card_topic';
                assessmentCard.innerHTML = `
                    <div class="container_assessment_student">
                        <div class="flex_row">
                            <h3>${assessment.assessment_name}</h3>
                            <button class="assessment-button">${assessment.attempted ? 'Review' : 'Start'}</button>
                        </div>
                        <p>Opened: ${assessment.date_opened}</p>
                        <p>Due: ${assessment.date_closed}</p>
                        
                    </div>
                `;
                const button = assessmentCard.querySelector('.assessment-button');
                button.addEventListener('click', () => {
                    
                    const pageUrl = assessment.attempted ? 'review_page_placeholder' : 'start_page_placeholder';
                    window.location.href = `${pageUrl}?assessmentID=${assessment.assessment_id}`;
                });
                assessmentsContainer.appendChild(assessmentCard);
            });
        }
    }
}
