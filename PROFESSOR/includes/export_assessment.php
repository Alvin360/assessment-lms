<<?php
require('../includes/fpdf/fpdf.php');
require_once '../includes/dbh_inc.php';

if (isset($_GET['id'])) {
    $assessmentID = $_GET['id'];
    $shuffleQuestions = isset($_GET['shuffleQuestions']) && $_GET['shuffleQuestions'] === 'true';

    // Fetch assessment from the database
    $sqlSelectAssessment = "SELECT * FROM ASSESSMENT WHERE assessment_ID = ?";
    $stmtSelectAssessment = $conn->prepare($sqlSelectAssessment);
    $stmtSelectAssessment->bind_param('s', $assessmentID);
    $stmtSelectAssessment->execute();
    $resultAssessment = $stmtSelectAssessment->get_result();
    $assessment = $resultAssessment->fetch_assoc();

    // Fetch questions from the database
    $sqlSelectQuestions = "SELECT * FROM EXAMINATION_BANK WHERE assessment_ID = ?";
    $stmtSelectQuestions = $conn->prepare($sqlSelectQuestions);
    $stmtSelectQuestions->bind_param('s', $assessmentID);
    $stmtSelectQuestions->execute();
    $resultQuestions = $stmtSelectQuestions->get_result();
    $questions = [];
    while ($row = $resultQuestions->fetch_assoc()) {
        $questions[] = $row;
    }

    if ($shuffleQuestions) {
        shuffle($questions);
    }

    function fetchCorrectAnswers($questionID, $questionType, $conn) {
        $sql = "";
        switch ($questionType) {
            case 'M':
            case 'T':
            case 'S':
                $sql = "SELECT answer FROM EXAM_ANSWER WHERE question_ID = ?";
                break;
            case 'F':
                $sql = "SELECT m_Ans1, m_Ans2, m_Ans3, m_Ans4, m_Ans5, m_Ans6, m_Ans7, m_Ans8, m_Ans9, m_Ans10 FROM EXAM_ANSWER WHERE question_ID = ?";
                break;
            default:
                return '';
        }

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $questionID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            return '';
        }
    }

    function fetchMatchingAnswers($questionID, $conn) {
        $sql = "SELECT m_Ans1, m_Ans2, m_Ans3, m_Ans4, m_Ans5, m_Ans6, m_Ans7, m_Ans8, m_Ans9, m_Ans10
                FROM EXAM_ANSWER WHERE question_ID = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $questionID); 
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return [];
        }
    }

    // Generate PDF
    class PDF extends FPDF
    {
        function Header()
        {
            // Logo
            $this->Image('../assets/logo.png',10,6,30); // Adjust the path and size of the logo as needed
            $this->SetFont('Arial','B',12);
            $this->Cell(0,5,'Republic of the Philippines',0,1,'C');
            $this->Cell(0,5,'Polytechnic University of the Philippines',0,1,'C');
            $this->Cell(0,5,'Sta. Mesa, Manila',0,1,'C');
            $this->Ln(10);

            // Assessment-specific header content
            global $assessment;
            $this->SetFont('Arial','B',14);
            $this->Cell(0,10,$assessment['assessment_Name'],0,1,'C');

            // Instructions only on the first page
            if ($this->PageNo() == 1) {
                $this->SetFont('Arial','I',10);
                $this->Cell(0,10,'Instructions: ' . $assessment['assessment_Desc'],0,1,'L');
                $this->Ln(10);
            }
        }

        function Footer()
        {
            if ($this->PageNo() == 1) {
                $this->SetY(-15);
                $this->SetFont('Arial','I',8);
                $this->Cell(0,10,$this->PageNo(),0,0,'C');
            }
        }

        function Question($question, $number, &$matchingQuestions, &$matchingAnswers)
        {
            $this->SetFont('Arial','B',12);
            $this->Cell(0,10,$number.'. '.$question['question'],0,1);
            $this->SetFont('Arial','',12);

            if ($question['question_Type'] === 'M') {
                $choices = ['a. '.$question['choice1'], 'b. '.$question['choice2'], 'c. '.$question['choice3'], 'd. '.$question['choice4']];
                $col_width = $this->GetPageWidth() / 2 - 20;

                for ($i = 0; $i < 2; $i++) {
                    $this->Cell($col_width, 10, $choices[$i] ?? '', 0, 0);
                    $this->Cell($col_width, 10, $choices[$i+2] ?? '', 0, 1);
                }

            } elseif ($question['question_Type'] === 'T') {
                $this->Cell(0,10,'a. True',0,1);
                $this->Cell(0,10,'b. False',0,1);
            } elseif ($question['question_Type'] === 'S') {
                $this->Cell(0,10,'_______________________',0,1);
            } elseif ($question['question_Type'] === 'F') {
                // Collect matching question
                for ($i = 1; $i <= 10; $i++) {
                    $match = 'match' . $i;
                    if (isset($question[$match]) && $question[$match]) {
                        $matchingQuestions[] = $question[$match];
                    }
                }

                // Fetch matching answers
                $answers = fetchMatchingAnswers($question['question_ID'], $GLOBALS['conn']);
                for ($i = 1; $i <= 10; $i++) {
                    $ans = 'm_Ans' . $i;
                    if (isset($answers[$ans]) && $answers[$ans]) {
                        $matchingAnswers[] = $answers[$ans];
                    }
                }
            }
            $this->Ln(5);
        }

        function MatchingQuestions($matchingQuestions, $matchingAnswers) {
            $this->SetFont('Arial','B',12);
            $this->Cell(0,10,'Match the following:', 0, 1, 'L');
            $this->SetFont('Arial','',12);

            $this->SetFillColor(230, 230, 230);
            $this->Cell(0,10,'Questions:', 0, 1, 'L', true);
            foreach ($matchingQuestions as $index => $question) {
                $this->Cell(0,10,chr($index + 97).'. '.$question, 0, 1);
            }

            $this->Ln(5);
            $this->SetFont('Arial','B',12);
            $this->Cell(0,10,'Answers:', 0, 1, 'L');
            $this->SetFont('Arial','',12);

            // Shuffle the answers
            shuffle($matchingAnswers);

            foreach ($matchingAnswers as $index => $answer) {
                $this->Cell(0,10,chr($index + 97).'. '.$answer, 0, 1);
            }
        }

        function AnswerSheet($imagePath)
        {
            $this->AddPage();
            // Header for answer sheet
            $this->SetFont('Arial','B',14);
            $this->Cell(0,10,'Answer Sheet',0,1,'C');
            $this->Ln(10);

            $this->SetFont('Arial','',12);
            $this->Cell(20,10,'Name:',0,0);
            $this->Cell(60,10,'_________________________',0,0);
            $this->Cell(20,10,'Date:',0,0);
            $this->Cell(60,10,'_________________________',0,1);
            $this->Cell(20,10,'Class:',0,0);
            $this->Cell(60,10,'_________________________',0,0);
            $this->Cell(20,10,'Score:',0,0);
            $this->Cell(60,10,'_________________________',0,1);
            $this->Ln(20);

            $imageWidth = 170;
            $imageY = 90; 
            $imageX = 10;
            $this->Image($imagePath, $imageX, $imageY, $imageWidth);
        }

        function AnswerKey($questions, $correctAnswersList, $matchingQuestions, $matchingAnswers)
        {
            $this->AddPage();
            // Header for answer key
            global $assessment;
            $this->SetFont('Arial','B',12);
            $this->Cell(0,10,'Answer Key',0,1,'C');
            $this->SetFont('Arial','B',12);
            $this->Ln(10);

            // Display correct answers
            $this->SetFont('Arial','',12);
            foreach ($questions as $index => $question) {
                $number = $index + 1;
                if ($question['question_Type'] === 'F') {
                    $correctAnswer = $correctAnswersList[$index];
                    $this->Cell(0,10,$number.'. '.$correctAnswer,0,1);
                } else {
                    $correctAnswer = strtoupper($correctAnswersList[$index]);
                    $this->Cell(0,10,$number.'. '.$correctAnswer,0,1);
                }
            }
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();
    $number = 1;
    $correctAnswersList = [];
    $matchingQuestions = [];
    $matchingAnswers = [];

    foreach ($questions as $question) {
        $correctAnswers = fetchCorrectAnswers($question['question_ID'], $question['question_Type'], $conn);
        $pdf->Question($question, $number, $matchingQuestions, $matchingAnswers);
        
        // Determine correct answer based on question type
        $correctAnswer = '';
        if ($question['question_Type'] === 'M' && isset($correctAnswers['answer'])) {
            $correctAnswerIndex = (int)$correctAnswers['answer']; // Convert answer to integer
            $choices = ['a', 'b', 'c', 'd'];
            $correctAnswer = $choices[$correctAnswerIndex - 1]; // Convert answer index to letter
        } elseif ($question['question_Type'] === 'T') {
            $correctAnswer = $correctAnswers['answer'] == 'T' ? 'A' : 'B';
        } elseif ($question['question_Type'] === 'S') {
            $correctAnswer = $correctAnswers['answer'] ?? '';
        } elseif ($question['question_Type'] === 'F') {
            $correctAnswer = '';
            for ($i = 1; $i <= 10; $i++) {
                $match = 'match' . $i;
                $ans = 'm_Ans' . $i;
                if (isset($question[$match]) && isset($correctAnswers[$ans])) {
                    $correctAnswer .= $i . '. ' . $question[$match] . "\n";
                }
            }
        }

        $correctAnswersList[] = $correctAnswer;
        $number++;
    }

    // Add matching questions and answers section
    if (!empty($matchingQuestions)) {
        $pdf->MatchingQuestions($matchingQuestions, $matchingAnswers);
    }

    // Always generate answer sheet
    $pdf->AnswerSheet('../assets/ans_sheet.png'); // Path to the answer sheet image

    // Always generate answer key
    $pdf->AnswerKey($questions, $correctAnswersList, $matchingQuestions, $matchingAnswers);

    // Avoid any output before this point
    ob_clean();
    $pdf->Output();
}
?>






