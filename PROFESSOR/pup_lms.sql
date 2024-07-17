-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307:3307
-- Generation Time: Jul 14, 2024 at 09:38 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pup_lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessment`
--

CREATE TABLE `assessment` (
  `assessment_ID` varchar(10) NOT NULL,
  `assessment_Name` varchar(255) NOT NULL,
  `date_Created` datetime NOT NULL,
  `open_Date` datetime DEFAULT NULL,
  `creator_ID` varchar(12) DEFAULT NULL,
  `subject_Code` varchar(10) DEFAULT NULL,
  `assessment_Type` char(1) DEFAULT NULL,
  `time_Limit` time DEFAULT NULL,
  `no_Of_Items` varchar(3) DEFAULT NULL,
  `closing_Date` datetime NOT NULL,
  `assessment_Desc` varchar(200) NOT NULL,
  `allowed_Attempts` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assessment`
--

INSERT INTO `assessment` (`assessment_ID`, `assessment_Name`, `date_Created`, `open_Date`, `creator_ID`, `subject_Code`, `assessment_Type`, `time_Limit`, `no_Of_Items`, `closing_Date`, `assessment_Desc`, `allowed_Attempts`) VALUES
('A669361683', 'Quiz 1: Auditing Theories', '2024-07-14 07:26:00', '2024-07-01 13:00:00', '201510754MN0', 'ACCO123', 'M', '00:50:00', '2', '2024-07-01 14:00:00', 'Select the correct answer', 1),
('A669361ee8', 'Quiz 1: HTML', '2024-07-14 07:28:14', '2024-06-01 12:00:00', '201510754MN0', 'COMP124', 'M', '00:30:00', '1', '2024-06-01 23:59:00', 'Select the best answer', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cohort`
--

CREATE TABLE `cohort` (
  `user_ID` varchar(12) DEFAULT NULL,
  `cohort_ID` varchar(5) DEFAULT NULL,
  `cohort_Name` varchar(50) DEFAULT NULL,
  `cohort_Size` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `college`
--

CREATE TABLE `college` (
  `college_ID` varchar(10) NOT NULL,
  `college_Name` varchar(50) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `college`
--

INSERT INTO `college` (`college_ID`, `college_Name`, `description`) VALUES
('CAF', 'College of Accountancy and Finance', NULL),
('CCIS', 'College of Computer and Information Sciences', '.');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `course_ID` varchar(15) NOT NULL,
  `course_description` varchar(50) NOT NULL,
  `college_id` varchar(10) NOT NULL,
  `no_of_years` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_ID`, `course_description`, `college_id`, `no_of_years`) VALUES
('BSA', 'BS Accountancy', 'CAF', '4'),
('BSCS', 'BS Computer Science', 'CCIS', '4'),
('BSIT', 'BS Information Technology', 'CCIS', '4');

-- --------------------------------------------------------

--
-- Table structure for table `course_enrolled`
--

CREATE TABLE `course_enrolled` (
  `user_ID` varchar(12) DEFAULT NULL,
  `course_ID` varchar(15) DEFAULT NULL,
  `ay` varchar(4) DEFAULT NULL,
  `semester` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_enrolled`
--

INSERT INTO `course_enrolled` (`user_ID`, `course_ID`, `ay`, `semester`) VALUES
('202110345MN0', 'BSCS', '2024', '6'),
('202110345MN0', 'BSCS', '2024', '6'),
('202110750MN0', 'BSCS', '2024', '6'),
('202110755MN0', 'BSCS', '2024', '6'),
('202120322MN0', 'BSA', '2024', '6');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_ID` varchar(10) NOT NULL,
  `department_Name` varchar(50) DEFAULT NULL,
  `department_Description` varchar(100) DEFAULT NULL,
  `college_ID` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `examination_bank`
--

CREATE TABLE `examination_bank` (
  `assessment_ID` varchar(10) NOT NULL,
  `question_ID` int(11) NOT NULL,
  `question_No` int(11) DEFAULT NULL,
  `question` varchar(200) DEFAULT NULL,
  `points` float DEFAULT NULL,
  `question_Type` char(1) DEFAULT NULL,
  `choice1` varchar(200) DEFAULT NULL,
  `choice2` varchar(200) DEFAULT NULL,
  `choice3` varchar(200) DEFAULT NULL,
  `choice4` varchar(200) DEFAULT NULL,
  `boolean` char(1) DEFAULT NULL,
  `fill_Blank` varchar(50) DEFAULT NULL,
  `match1` varchar(50) DEFAULT NULL,
  `match2` varchar(50) DEFAULT NULL,
  `match3` varchar(50) DEFAULT NULL,
  `match4` varchar(50) DEFAULT NULL,
  `match5` varchar(50) DEFAULT NULL,
  `match6` varchar(50) DEFAULT NULL,
  `match7` varchar(50) DEFAULT NULL,
  `match8` varchar(50) DEFAULT NULL,
  `match9` varchar(50) DEFAULT NULL,
  `match10` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `examination_bank`
--

INSERT INTO `examination_bank` (`assessment_ID`, `question_ID`, `question_No`, `question`, `points`, `question_Type`, `choice1`, `choice2`, `choice3`, `choice4`, `boolean`, `fill_Blank`, `match1`, `match2`, `match3`, `match4`, `match5`, `match6`, `match7`, `match8`, `match9`, `match10`) VALUES
('A669361683', 1, 1, 'Which of the following is the primary objective of an audit?', 1, 'M', ' To detect fraud and irregularities.', 'To express an opinion on the fairness of the financial statements.', 'To ensure compliance with all laws and regulations.', 'To provide assurance on the efficiency and effectiveness of operations.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('A669361683', 2, 2, 'What is the primary difference between internal and external auditors?', 1, 'M', 'Internal auditors report to management, while external auditors report to shareholders.', 'Internal auditors focus on financial statements, while external auditors focus on internal controls.', 'Internal auditors are required by law, while external auditors are voluntary.', 'Internal auditors are certified public accountants, while external auditors are not.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('A669361ee8', 1, 1, 'What is the meaning of HTML', 1, 'S', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `exam_answer`
--

CREATE TABLE `exam_answer` (
  `assessment_ID` varchar(10) NOT NULL,
  `question_ID` int(11) NOT NULL,
  `answer` varchar(250) DEFAULT NULL,
  `m_Ans1` varchar(250) DEFAULT NULL,
  `m_Ans2` varchar(250) DEFAULT NULL,
  `m_Ans3` varchar(250) DEFAULT NULL,
  `m_Ans4` varchar(250) DEFAULT NULL,
  `m_Ans5` varchar(250) DEFAULT NULL,
  `m_Ans6` varchar(250) DEFAULT NULL,
  `m_Ans7` varchar(250) DEFAULT NULL,
  `m_Ans8` varchar(250) DEFAULT NULL,
  `m_Ans9` varchar(250) DEFAULT NULL,
  `m_Ans10` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_answer`
--

INSERT INTO `exam_answer` (`assessment_ID`, `question_ID`, `answer`, `m_Ans1`, `m_Ans2`, `m_Ans3`, `m_Ans4`, `m_Ans5`, `m_Ans6`, `m_Ans7`, `m_Ans8`, `m_Ans9`, `m_Ans10`) VALUES
('A669361683', 1, '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('A669361683', 2, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('A669361ee8', 1, 'H', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `interactive_video`
--

CREATE TABLE `interactive_video` (
  `video_ID` varchar(6) NOT NULL,
  `user_ID` varchar(12) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `filename` varchar(50) DEFAULT NULL,
  `path` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interactive_video_assessment`
--

CREATE TABLE `interactive_video_assessment` (
  `video_ID` varchar(6) DEFAULT NULL,
  `user_ID` varchar(12) DEFAULT NULL,
  `assessment_Type` char(1) DEFAULT NULL,
  `choice1` varchar(200) DEFAULT NULL,
  `choice2` varchar(200) DEFAULT NULL,
  `choice3` varchar(200) DEFAULT NULL,
  `choice4` varchar(200) DEFAULT NULL,
  `answer` char(1) DEFAULT NULL,
  `true_False` varchar(200) DEFAULT NULL,
  `fill_Blanks` varchar(50) DEFAULT NULL,
  `date_Taken` date DEFAULT NULL,
  `score` varchar(50) DEFAULT NULL,
  `grade` varchar(4) DEFAULT NULL,
  `certificate` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_maintenance`
--

CREATE TABLE `password_maintenance` (
  `user_ID` varchar(12) DEFAULT NULL,
  `current_Password` varchar(50) DEFAULT NULL,
  `previous_Password` varchar(50) DEFAULT NULL,
  `date_Created` date DEFAULT NULL,
  `expiry_Days` smallint(6) DEFAULT NULL,
  `login_Attempt` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_submission`
--

CREATE TABLE `student_submission` (
  `submission_ID` varchar(6) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `file_Type` varchar(5) DEFAULT NULL,
  `user_ID` varchar(12) DEFAULT NULL,
  `subject_ID` varchar(10) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `remarks` varchar(200) DEFAULT NULL,
  `note` varchar(200) DEFAULT NULL,
  `requirement_Code` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subject_ID` varchar(10) NOT NULL,
  `subject_Name` varchar(50) DEFAULT NULL,
  `subject_Description` varchar(100) DEFAULT NULL,
  `semester` char(1) DEFAULT NULL,
  `course_ID` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subject_ID`, `subject_Name`, `subject_Description`, `semester`, `course_ID`) VALUES
('ACCO123', 'Auditing', NULL, '6', 'BSA'),
('COMP123INS', 'Information Management', '.', '4', 'BSCS'),
('COMP124', 'Web Development', '.', '6', 'BSCS'),
('COSC123', 'Intro to AI', '.', '6', 'BSCS');

-- --------------------------------------------------------

--
-- Table structure for table `submission_requirement`
--

CREATE TABLE `submission_requirement` (
  `requirement_Code` varchar(6) NOT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `date_Start` date DEFAULT NULL,
  `time_Start` time DEFAULT NULL,
  `date_End` date DEFAULT NULL,
  `time_End` time DEFAULT NULL,
  `remarks` varchar(200) DEFAULT NULL,
  `note` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_access`
--

CREATE TABLE `user_access` (
  `user_ID` varchar(12) DEFAULT NULL,
  `user_Password` varchar(50) DEFAULT NULL,
  `last_Access` date DEFAULT NULL,
  `time_Access` time DEFAULT NULL,
  `first_Access` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_examination`
--

CREATE TABLE `user_examination` (
  `user_ID` varchar(12) DEFAULT NULL,
  `assessment_ID` varchar(10) DEFAULT NULL,
  `date_Start` date DEFAULT NULL,
  `time_Start` time DEFAULT NULL,
  `date_End` date DEFAULT NULL,
  `time_End` time DEFAULT NULL,
  `score` char(3) DEFAULT NULL,
  `grade` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_exam_report`
--

CREATE TABLE `user_exam_report` (
  `user_ID` varchar(12) DEFAULT NULL,
  `assessment_ID` varchar(10) DEFAULT NULL,
  `attempt_Number` int(11) NOT NULL,
  `score` char(3) DEFAULT NULL,
  `grade` float DEFAULT NULL,
  `subject_Code` varchar(10) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_information`
--

CREATE TABLE `user_information` (
  `user_ID` varchar(12) NOT NULL,
  `last_Name` varchar(50) DEFAULT NULL,
  `first_Name` varchar(75) DEFAULT NULL,
  `middle_Name` varchar(50) DEFAULT NULL,
  `date_Of_Birth` date DEFAULT NULL,
  `email_Address` varchar(75) DEFAULT NULL,
  `mobile_Number` varchar(13) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `region` varchar(20) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `zip_Code` varchar(5) DEFAULT NULL,
  `date_Created` date DEFAULT NULL,
  `account_Status` char(1) DEFAULT NULL,
  `time_Created` time DEFAULT NULL,
  `id_Number` char(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_information`
--

INSERT INTO `user_information` (`user_ID`, `last_Name`, `first_Name`, `middle_Name`, `date_Of_Birth`, `email_Address`, `mobile_Number`, `country`, `city`, `region`, `province`, `zip_Code`, `date_Created`, `account_Status`, `time_Created`, `id_Number`) VALUES
('201510754MN0', 'Canlas', 'Arlene', 'B', '2014-06-01', 'arlene@gmail.com', '09413472121', 'Philippines', 'Manila City', 'NCR', 'Metro Manila', '2000', '2024-06-28', 'A', '21:18:56', '2'),
('202110345MN0', 'Peña', 'Ma. Charissa', 'Bartolome', '2014-04-15', 'macharissa@gmail.com', '09235124121', 'Philippines', 'Bocaue', 'Central Luzon', 'Bulacan', '3018', '2024-06-28', 'A', '21:18:56', '3'),
('202110750MN0', 'Aquino', 'MJ', NULL, '2002-01-01', 'mj@gmail.com', '09232141231', 'Philippines', 'Quezon City', 'NCR', 'Metro Manila', '1234', '2024-07-06', 'A', '04:37:28', '4'),
('202110755MN0', 'Bautista', 'Pauline Ann', 'Panganiban', '2014-06-10', 'paulineann@gmail.com', '09213184121', 'Philippines', 'Hagonoy', 'Central Luzon', 'Bulacan', '3002', '2024-06-28', 'A', '21:08:23', '1'),
('202120322MN0', 'Bautista', 'Ma. Alessandra', 'Soriano', '2014-07-14', 'maalessandra@gmail.com', '09123315812', 'Philippines', 'Hagonoy', 'Central Luzon', 'Bulacan', '3002', '2024-07-14', 'A', '11:38:49', '5');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `user_ID` varchar(12) DEFAULT NULL,
  `user_Role` char(1) DEFAULT NULL,
  `date_Assigned` date DEFAULT NULL,
  `previous_Role` char(1) DEFAULT NULL,
  `date_Change` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`user_ID`, `user_Role`, `date_Assigned`, `previous_Role`, `date_Change`) VALUES
('202110345MN0', '5', '2024-06-28', '0', '2024-06-28'),
('202110755MN0', '5', '2024-06-28', '0', '2024-06-28'),
('201510754MN0', '3', '2024-06-28', '0', '2024-06-28'),
('202110750MN0', '5', '2024-07-06', '0', '2024-06-28'),
('202120322MN0', '5', '2024-07-14', '0', '2024-07-14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessment`
--
ALTER TABLE `assessment`
  ADD PRIMARY KEY (`assessment_ID`),
  ADD KEY `creator_ID` (`creator_ID`);

--
-- Indexes for table `cohort`
--
ALTER TABLE `cohort`
  ADD KEY `user_ID` (`user_ID`);

--
-- Indexes for table `college`
--
ALTER TABLE `college`
  ADD PRIMARY KEY (`college_ID`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`course_ID`),
  ADD KEY `college_id_fk` (`college_id`);

--
-- Indexes for table `course_enrolled`
--
ALTER TABLE `course_enrolled`
  ADD KEY `user_id_ibfk_1` (`user_ID`),
  ADD KEY `course_id_ibfk_2` (`course_ID`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_ID`),
  ADD KEY `department_ibfk_1` (`college_ID`);

--
-- Indexes for table `examination_bank`
--
ALTER TABLE `examination_bank`
  ADD PRIMARY KEY (`assessment_ID`,`question_ID`);

--
-- Indexes for table `exam_answer`
--
ALTER TABLE `exam_answer`
  ADD PRIMARY KEY (`assessment_ID`,`question_ID`);

--
-- Indexes for table `interactive_video`
--
ALTER TABLE `interactive_video`
  ADD PRIMARY KEY (`video_ID`),
  ADD KEY `user_ID` (`user_ID`);

--
-- Indexes for table `interactive_video_assessment`
--
ALTER TABLE `interactive_video_assessment`
  ADD KEY `video_ID` (`video_ID`),
  ADD KEY `user_ID` (`user_ID`);

--
-- Indexes for table `password_maintenance`
--
ALTER TABLE `password_maintenance`
  ADD KEY `user_ID` (`user_ID`);

--
-- Indexes for table `student_submission`
--
ALTER TABLE `student_submission`
  ADD PRIMARY KEY (`submission_ID`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `subject_ID` (`subject_ID`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subject_ID`),
  ADD KEY `course_id_ibfk_3` (`course_ID`);

--
-- Indexes for table `submission_requirement`
--
ALTER TABLE `submission_requirement`
  ADD PRIMARY KEY (`requirement_Code`);

--
-- Indexes for table `user_access`
--
ALTER TABLE `user_access`
  ADD KEY `user_ID` (`user_ID`);

--
-- Indexes for table `user_examination`
--
ALTER TABLE `user_examination`
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `assessment_ID` (`assessment_ID`);

--
-- Indexes for table `user_exam_report`
--
ALTER TABLE `user_exam_report`
  ADD KEY `user_id_ibfk` (`user_ID`),
  ADD KEY `assessment_id_ibfk` (`assessment_ID`),
  ADD KEY `subject_code_ibfk` (`subject_Code`);

--
-- Indexes for table `user_information`
--
ALTER TABLE `user_information`
  ADD PRIMARY KEY (`user_ID`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD KEY `user_ID` (`user_ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assessment`
--
ALTER TABLE `assessment`
  ADD CONSTRAINT `assessment_ibfk_1` FOREIGN KEY (`creator_ID`) REFERENCES `user_information` (`user_ID`),
  ADD CONSTRAINT `subject_id_ibfk1` FOREIGN KEY (`subject_Code`) REFERENCES `subject` (`subject_ID`);

--
-- Constraints for table `cohort`
--
ALTER TABLE `cohort`
  ADD CONSTRAINT `cohort_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`);

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `college_id_fk_1` FOREIGN KEY (`college_id`) REFERENCES `college` (`college_ID`);

--
-- Constraints for table `course_enrolled`
--
ALTER TABLE `course_enrolled`
  ADD CONSTRAINT `course_id_ibfk_2` FOREIGN KEY (`course_ID`) REFERENCES `course` (`course_ID`),
  ADD CONSTRAINT `user_id_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`);

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `department_ibfk_1` FOREIGN KEY (`college_ID`) REFERENCES `college` (`college_ID`);

--
-- Constraints for table `examination_bank`
--
ALTER TABLE `examination_bank`
  ADD CONSTRAINT `examination_bank_ibfk_1` FOREIGN KEY (`assessment_ID`) REFERENCES `assessment` (`assessment_ID`);

--
-- Constraints for table `exam_answer`
--
ALTER TABLE `exam_answer`
  ADD CONSTRAINT `exam_answer_ibfk_1` FOREIGN KEY (`assessment_ID`,`question_ID`) REFERENCES `examination_bank` (`assessment_ID`, `question_ID`);

--
-- Constraints for table `interactive_video`
--
ALTER TABLE `interactive_video`
  ADD CONSTRAINT `interactive_video_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`);

--
-- Constraints for table `interactive_video_assessment`
--
ALTER TABLE `interactive_video_assessment`
  ADD CONSTRAINT `interactive_video_assessment_ibfk_1` FOREIGN KEY (`video_ID`) REFERENCES `interactive_video` (`video_ID`),
  ADD CONSTRAINT `interactive_video_assessment_ibfk_2` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`);

--
-- Constraints for table `password_maintenance`
--
ALTER TABLE `password_maintenance`
  ADD CONSTRAINT `password_maintenance_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`);

--
-- Constraints for table `student_submission`
--
ALTER TABLE `student_submission`
  ADD CONSTRAINT `student_submission_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`),
  ADD CONSTRAINT `student_submission_ibfk_2` FOREIGN KEY (`subject_ID`) REFERENCES `subject` (`subject_ID`);

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `course_id_ibfk_3` FOREIGN KEY (`course_ID`) REFERENCES `course` (`course_ID`);

--
-- Constraints for table `user_access`
--
ALTER TABLE `user_access`
  ADD CONSTRAINT `user_access_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`);

--
-- Constraints for table `user_examination`
--
ALTER TABLE `user_examination`
  ADD CONSTRAINT `user_examination_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`),
  ADD CONSTRAINT `user_examination_ibfk_2` FOREIGN KEY (`assessment_ID`) REFERENCES `assessment` (`assessment_ID`);

--
-- Constraints for table `user_exam_report`
--
ALTER TABLE `user_exam_report`
  ADD CONSTRAINT `assessment_id_ibfk` FOREIGN KEY (`assessment_ID`) REFERENCES `assessment` (`assessment_ID`),
  ADD CONSTRAINT `subject_code_ibfk` FOREIGN KEY (`subject_Code`) REFERENCES `subject` (`subject_ID`),
  ADD CONSTRAINT `user_id_ibfk` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`);

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user_information` (`user_ID`);
COMMIT;


-- ADD ESSAY TYPE QUESTION
--

ALTER TABLE `examination_bank`
ADD COLUMN `long_Answer` TEXT NULL AFTER `choice4`;

ALTER TABLE `exam_answer`
ADD COLUMN `long_Answer` TEXT NULL AFTER `m_Ans10`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
