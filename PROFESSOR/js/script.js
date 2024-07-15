document.addEventListener('DOMContentLoaded', () => {
  // Fetch and populate course filter options
  fetch('../PROFESSOR/includes/get_courses.php')
      .then(response => response.json())
      .then(courses => {
          const courseFilter = document.getElementById('course-filter');
          courses.forEach(course => {
              const option = document.createElement('option');
              option.value = course.course_ID;
              option.textContent = course.course_ID;
              option.text = course.course_ID;
              courseFilter.appendChild(option);
          });
      });

  // Fetch and populate assessments initially
  fetchAssessments();

  // Add event listener to course filter
  document.getElementById('course-filter').addEventListener('change', (event) => {
      const selectedCourse = event.target.value;
      fetchAssessments(selectedCourse);
  });
});

function fetchAssessments(course_ID = 'all') {
  fetch(`../PROFESSOR/includes/get_assessment_details_prof.php?course_ID=${course_ID}`)
      .then(response => response.json())
      .then(assessments => {
          populateAssessments(assessments);
      })
      .catch(error => console.error('Error:', error));
}

function populateAssessments(assessments) {
  const assessmentsContainer = document.getElementById('container_section_assessment');
  assessmentsContainer.innerHTML = '';

  // Group assessments by subject code and name
  const groupedAssessments = assessments.reduce((acc, assessment) => {
      const subjectKey = `${assessment.subject_Code}: ${assessment.subject_name}`;
      if (!acc[subjectKey]) {
          acc[subjectKey] = [];
      }
      acc[subjectKey].push(assessment);
      return acc;
  }, {});

  // Create sections for each subject code and name
// Create sections for each subject code
  Object.keys(groupedAssessments).forEach(subjectCode => {
      const subjectSection = document.createElement('div');
      subjectSection.className = 'subject_section';
      subjectSection.innerHTML = `
          <h1 id='section1'>${subjectCode}</h1>
          <div id="container_section_assessment_${subjectCode}">
              <!-- Assessments for this subject will be populated here -->
          </div>
      `;

      assessmentsContainer.appendChild(subjectSection);

      const containerSectionAssessment = document.getElementById(`container_section_assessment_${subjectCode}`);
      
      groupedAssessments[subjectCode].forEach(assessment => {
          const assessmentCard = document.createElement('div');
          assessmentCard.className = 'card_topic';
          assessmentCard.innerHTML = `
              <div class="container_assessment">
                  <div class="container_collapsed">
                      <button class="button_collapse hidden">Collapse</button>
                      <button class="button_expand">Expand</button>
                      <h2>${assessment.assessment_name}</h2>

                      <div class="container_buttons">
                          <button class="button_export" data-id="${assessment.assessment_id}">Export</button>
                          <button class="button_edit" data-id="${assessment.assessment_id}">Edit</button>
                          <button class="button_report" data-id="${assessment.assessment_id}">Report</button>
                      </div>
                  </div>
  
                  <div class="container_expanded hidden">
                      <p>Opened: ${assessment.open_Date}</p>
                      <p>Due: ${assessment.closing_Date}</p>
                      <div class="container_student" assessmentID="${assessment.assessment_id}">
                          <!-- Students will be populated here -->
                      </div>
                  </div>
              </div>
          `;

          containerSectionAssessment.appendChild(assessmentCard);

          const containerStudent = assessmentCard.querySelector('.container_student');
          containerStudent.setAttribute('assessmentID', assessment.assessment_id);

          // Add event listeners for collapse and expand buttons
          const buttonCollapse = assessmentCard.querySelector('.button_collapse');
          const buttonExpand = assessmentCard.querySelector('.button_expand');
          const containerExpanded = assessmentCard.querySelector('.container_expanded');

          buttonCollapse.addEventListener('click', () => {
              buttonCollapse.classList.add('hidden');
              buttonExpand.classList.remove('hidden');
              containerExpanded.classList.add('hidden');
          });

          buttonExpand.addEventListener('click', () => {
              buttonExpand.classList.add('hidden');
              buttonCollapse.classList.remove('hidden');
              containerExpanded.classList.remove('hidden');
          });

          // Add event listeners for export, edit, and report buttons
          assessmentCard.querySelector('.button_export').addEventListener('click', () => {
              window.location.href = `../PROFESSOR/includes/export_assessment.php?id=${assessment.assessment_id}`;
          });

          assessmentCard.querySelector('.button_edit').addEventListener('click', () => {
              window.location.href = `../PROFESSOR/includes/edit_assessment.php?id=${assessment.assessment_id}&subject_Code=${assessment.subject_Code}`;
          });

          assessmentCard.querySelector('.button_report').addEventListener('click', () => {
              window.location.href = `../PROFESSOR/pages/student_report.html?assessmentID=${assessment.assessment_id}`;
          });

          // Fetch and display students
          fetchAndDisplayStudents(assessment.assessment_id, assessment.subject_Code);
      });
  });
}

function fetchAndDisplayStudents(assessmentID, subjectCode) {
  fetch(`../PROFESSOR/includes/assessment_prof_model.php?assessment_ID='${assessmentID}'&subject_Code='${subjectCode}'`)
    .then(response => {
          if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.json();
      })
    .then(students => {
          const studentContainers = document.querySelectorAll('.container_student');
          studentContainers.forEach(container => {
              if (container.getAttribute('assessmentid') === assessmentID) {
                  displayStudents(container, students, assessmentID);
              }
          });
      })
    .catch(error => {
          console.error('Error:', error);
      });
}

function displayStudents(container, students) {
  container.innerHTML = '';

  students.forEach(student => {
      const studentElement = document.createElement('div');
      studentElement.className = 'card_student';

      const h3Element = document.createElement('h3');
      h3Element.textContent = student.name;

      const reviewButton = document.createElement('div');
      reviewButton.className = 'review-button';

      // Set the button text based on the 'attempted' flag
      reviewButton.textContent = student.attempted ? 'Done' : 'Not Attempted';

      if (student.attempted) {
        reviewButton.classList.add('button-done');
        reviewButton.classList.remove('button-not-attempted');
    } else {
        reviewButton.classList.add('button-not-attempted');
        reviewButton.classList.remove('button-done');
    }

      // if (!student.attempted) {
      //   reviewButton.disabled = true; // Disable the button if not attempted
      // } 
      // else {
      //     reviewButton.addEventListener('click', () => {
      //         const assessmentID = student.assessmentID;
      //         console.log(`Reviewing attempted assessment for student: ${student.name}, assessment ID: ${assessmentID}`);
      //         // Redirect to PHP file with parameters
      //         window.location.href = `../PROFESSOR/includes/review.php?assessmentID=${assessmentID}&user_ID=${student.user_ID}}`;
      //     });
      // }

      studentElement.appendChild(h3Element);
      studentElement.appendChild(reviewButton);

      // Append the studentElement to the container
      container.appendChild(studentElement);
  });
}

// Create Exam folder

const days = Array.from({ length: 31 }, (_, i) => i + 1);
const months = [
  "January",
  "February",
  "March",
  "April",
  "May",
  "June",
  "July",
  "August",
  "September",
  "October",
  "November",
  "December",
];
const years = Array.from(
  { length: 101 },
  (_, i) => new Date().getFullYear() - 50 + i
);
const hours = Array.from({ length: 24 }, (_, i) =>
  i.toString().padStart(2, "0")
);
const minutes = Array.from({ length: 60 }, (_, i) =>
  i.toString().padStart(2, "0")
);
const time_limit_type = ["weeks", "days", "hours", "minutes", "seconds"];
const time_limit_expire = [
  "Open attempts are submitted automatically",
  "Attempts must be submitted before time expires, or they are not counted",
];
//   Might not be constant but updated if a teacher adds new grading category
const grade_category = ["Uncategorized"];
const grade_attempts = Array.from({ length: 10 }, (_, i) => i + 1);
const grading_method = ["Highest grade", "Average grade", "First attempt", "Last attempt"]

function populateSelect(selectId, values, defaultValue) {
  const select = document.getElementById(selectId);
  values.forEach((value) => {
    const option = document.createElement("option");
    option.value = value;
    option.textContent = value;
    if (value === defaultValue) {
      option.selected = true;
    }
    select.appendChild(option);
  });
}

document.addEventListener("DOMContentLoaded", () => {
  const currentDate = new Date();

  populateSelect("open_quiz_day", days, currentDate.getDate());
  populateSelect(
    "open_quiz_month",
    months,
    months[currentDate.getMonth()]
  );
  populateSelect("open_quiz_year", years, currentDate.getFullYear());
  populateSelect(
    "open_quiz_hour",
    hours,
    currentDate.getHours().toString().padStart(2, "0")
  );
  populateSelect(
    "open_quiz_minute",
    minutes,
    currentDate.getMinutes().toString().padStart(2, "0")
  );

  populateSelect("close_quiz_day", days, currentDate.getDate());
  populateSelect(
    "close_quiz_month",
    months,
    months[currentDate.getMonth()]
  );
  populateSelect("close_quiz_year", years, currentDate.getFullYear());
  populateSelect(
    "close_quiz_hour",
    hours,
    currentDate.getHours().toString().padStart(2, "0")
  );
  populateSelect(
    "close_quiz_minute",
    minutes,
    currentDate.getMinutes().toString().padStart(2, "0")
  );
  populateSelect("time_limit_type", time_limit_type);
  populateSelect("time_limit_expire", time_limit_expire);
  populateSelect("grade_category", grade_category);
  populateSelect("grade_attempts", grade_attempts);
  populateSelect("grading_method", grading_method);
});




// Student Report Page


document.addEventListener('DOMContentLoaded', function() {
  const urlParams = new URLSearchParams(window.location.search);
  const assessmentID = urlParams.get('assessmentID');
  const params = new URLSearchParams();
  params.append('assessmentID', assessmentID);
  fetch(`../includes/fetch_report_details.php?${params}`)
      .then(response => response.json())
      .then(data => {
          const reportDetails = document.getElementById('report-details');
          data.forEach(report => {
              const row = document.createElement('tr');
              row.innerHTML = `
                  <td>${report.user_ID}</td>
                  <td>${report.student_name}</td>
                  <td>${report.attempt_Number}</td>
                  <td>${report.score}</td>
                  <td>${report.grade}</td>
                  <td>${report.subject_Code}</td>
                  <td>${report.date}</td>
                  `;
              reportDetails.appendChild(row);
          });
      })
      .catch(error => console.error('Error fetching report details:', error));
});