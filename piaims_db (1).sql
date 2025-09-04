-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2025 at 03:36 AM
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
-- Database: `piaims_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `clinicpersonnel`
--

CREATE TABLE `clinicpersonnel` (
  `PersonnelID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `MiddleName` varchar(50) DEFAULT NULL,
  `RoleID` int(11) NOT NULL,
  `ContactNumber` varchar(20) DEFAULT NULL,
  `EmailAddress` varchar(100) DEFAULT NULL,
  `Username` varchar(50) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `HireDate` date DEFAULT NULL,
  `Status` varchar(20) DEFAULT 'Active',
  `Notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clinicpersonnel`
--

INSERT INTO `clinicpersonnel` (`PersonnelID`, `FirstName`, `LastName`, `MiddleName`, `RoleID`, `ContactNumber`, `EmailAddress`, `Username`, `PasswordHash`, `HireDate`, `Status`, `Notes`) VALUES
(1, 'Marvin', 'Ramos', NULL, 1, '09171234567', 'marvinramos@gmail.com', 'Marvin123', 'Marvin123', '2025-07-17', 'Active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `StudentID` int(11) NOT NULL,
  `School_ID` varchar(9) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `MiddleName` varchar(50) DEFAULT NULL,
  `DateOfBirth` date NOT NULL,
  `Age` int(2) NOT NULL,
  `Gender` varchar(10) NOT NULL,
  `Department` varchar(250) NOT NULL,
  `GradeLevel` varchar(10) NOT NULL,
  `Section` varchar(50) DEFAULT NULL,
  `StudentContactNumber` varchar(20) DEFAULT NULL,
  `StudentEmailAddress` varchar(100) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `GuardianFirstName` varchar(50) NOT NULL,
  `GuardianLastName` varchar(50) NOT NULL,
  `GuardianContactNumber` varchar(20) NOT NULL,
  `GuardianEmailAddress` varchar(100) DEFAULT NULL,
  `EmergencyContactName` varchar(100) NOT NULL,
  `EmergencyContactNumber` varchar(20) NOT NULL,
  `EmergencyContactRelation` varchar(50) NOT NULL,
  `BloodType` varchar(5) DEFAULT NULL,
  `KnownAllergies` text DEFAULT NULL,
  `ChronicConditions` text DEFAULT NULL,
  `CurrentMedications` text DEFAULT NULL,
  `EnrollmentDate` date DEFAULT current_timestamp(),
  `Status` varchar(20) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`StudentID`, `School_ID`, `FirstName`, `LastName`, `MiddleName`, `DateOfBirth`, `Age`, `Gender`, `Department`, `GradeLevel`, `Section`, `StudentContactNumber`, `StudentEmailAddress`, `Address`, `GuardianFirstName`, `GuardianLastName`, `GuardianContactNumber`, `GuardianEmailAddress`, `EmergencyContactName`, `EmergencyContactNumber`, `EmergencyContactRelation`, `BloodType`, `KnownAllergies`, `ChronicConditions`, `CurrentMedications`, `EnrollmentDate`, `Status`) VALUES
(2, 'GC-225687', 'Mark', 'Gomez', 'L.', '2009-11-20', 16, 'Male', '', 'Grade 9', 'Section B', '09194564567', 'mark.gomez@student.school.edu.ph', '456 Oak Ave, Brgy. South, Naic, Cavite', 'Susan', 'Gomez', '09203334444', 'susan.gomez@email.com', 'Roberto Gomez', '09203334444', 'Father', 'A-', 'Penicillin', 'Diabetes', 'Insulin (daily)', '2025-07-17', 'Active'),
(3, 'GC-221569', 'Ynah Mou', 'Tan', NULL, '2011-03-01', 14, 'Female', 'Elementary', 'Grade 1', 'a', '09217897890', 'chloe.tan@student.school.edu.ph', '789 Pine Rd, Brgy. East, Naic, Cavite', 'Michael', 'Tan', '09225556666', 'michael.tan@email.com', 'Samantha Tan', '09225556666', 'Mother', 'B+', 'None', 'None', 'None', '2025-07-17', 'Active'),
(4, 'GC-221704', 'Dave', 'Malaran', 'Olaguir', '2004-04-12', 21, 'Male', 'College', 'Year 4', 'B', '09514572814', 'malarandave0412@gmail.com', 'B41 L20, Southmorning View Phase 5, Timalan Balsahan, Naic, Cavite.', 'Milca', 'Malaran', '09303715482', 'milcamalaran@gmail.com', 'Milca Malaran', '09303715482', 'Mother', 'O+', NULL, NULL, NULL, '2025-07-17', 'Active'),
(8, 'GC-220723', 'Mark Jhone', 'Sumaylo', NULL, '2000-07-07', 25, 'Male', 'College', 'Year 4', 'B', '09123456789', 'mjsumaylo32@gmail.com', 'Naic, Cavite. 4110', 'Guardian', 'Sumaylo', '09123456789', 'Sumaylo@gmail.com', 'Guardian Sumaylo', '09123456789', 'Mother', 'AB', NULL, NULL, NULL, '2025-07-31', 'Active'),
(10, 'GC-220708', 'Jobert', 'Tumbado', NULL, '2000-07-07', 25, 'Male', '', '4', 'B', '09123456789', 'jbert.tumbado@gmail.com', 'Naic, Cavite. 4110', 'Guardian', 'Tumbado', '09123456789', 'Tumbado@gmail.com', 'Guardian Tumbado', '09123456789', 'Mother', 'AB', NULL, NULL, NULL, '2025-07-31', 'Active'),
(11, 'GC-220071', 'Anthony', 'Arisgado', NULL, '2000-07-07', 25, 'Male', '', '4', 'B', '09123456789', 'arisgadoanthony5@gmail.com', 'Naic, Cavite. 4110', 'Guardian', 'Arisgado', '09123456789', 'Arisgado@gmail.com', 'Guardian Arisgado', '09123456789', 'Mother', 'AB', NULL, NULL, NULL, '2025-07-31', 'Active'),
(12, 'GC-226395', 'John Kristler', 'Abad', NULL, '2000-07-07', 25, 'Male', '', '4', 'B', '09123456789', 'johnkristlerabad@gmail.com', 'Naic, Cavite. 4110', 'Guardian', 'Abad', '09123456789', 'Abad@gmail.com', 'Guardian Abad', '09123456789', 'Mother', 'AB', NULL, NULL, NULL, '2025-07-31', 'Active'),
(13, 'GC-214536', 'Argielyn', 'Tapsawani', NULL, '2000-07-07', 25, 'Female', '', '4', 'B', '09123456789', 'rglyntpswn@gmail.com', 'Naic, Cavite. 4110', 'Guardian', 'Tapsawani', '09123456789', 'Tapsawani@gmail.com', 'Guardian Tapsawani', '09123456789', 'Mother', 'AB', NULL, NULL, NULL, '2025-07-31', 'Active'),
(14, 'GC-231725', 'Angel', 'Sarabosing', NULL, '2000-07-07', 25, 'Female', '', '3', 'C', '09123456789', 'angelsarabosing123@gmail.com', 'Naic, Cavite. 4110', 'Catherine', 'Sarabosing', '09123456789', 'Sarabosing@gmail.com', 'Catherine Sarabosing', '09123456789', 'Mother', 'B', NULL, NULL, NULL, '0000-00-00', 'Active'),
(27, 'GC-223659', 'Geramil', 'Malaran', 'Olaguir', '1996-07-15', 29, 'Female', '', 'year2', 'B', '09303715482', 'geramilmalaran@gmail.com', 'Naic, Cavite', 'Milca', 'Malaran', '09514572814', 'milcamalaran@gmail.com', 'Milca Malaran', '09514572814', 'Mother', 'O+', '', '', '', '2025-08-23', 'Active'),
(28, 'GC-896669', 'Anthony', 'Arisgado', 'Dahilan', '2003-08-12', 22, 'Male', 'College', 'year3', 'F', '09303715482', 'arisgado@gmail.com', 'Naic Cavite', 'Luzviminda', 'Arisgado', '09514572814', 'Luzviminda@gmail.com', 'Luzviminda Arisgado', '09514572814', 'Mother', 'AB-', '', '', '', '2025-08-26', 'Active'),
(29, 'GC-336699', 'Adella', 'Malaran', 'Olaguir', '2000-09-20', 24, 'Female', 'Elementary', '3', 'E', '09303715482', 'adellamae@gmail.com', 'Naic Cavite', 'Milca', 'Malaran', '09514572814', 'milcamalaran@gmail.com', 'Milca Malaran', '09303715482', 'Mother', 'AB-', '', '', '', '2025-08-26', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `studentcheckins`
--

CREATE TABLE `studentcheckins` (
  `ID` int(11) NOT NULL,
  `StudentID` varchar(50) NOT NULL,
  `DateTime` datetime NOT NULL,
  `Reason` text NOT NULL,
  `Status` enum('Pending','In Progress','Completed','Referred') DEFAULT 'Pending',
  `Outcome` text DEFAULT NULL,
  `StaffID` int(11) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studentcheckins`
--

INSERT INTO `studentcheckins` (`ID`, `StudentID`, `DateTime`, `Reason`, `Status`, `Outcome`, `StaffID`, `CreatedAt`, `UpdatedAt`) VALUES
(2, 'GC-221704', '2025-09-02 11:46:00', 'matutulog daw  po', 'In Progress', NULL, 1, '2025-09-02 03:46:58', '2025-09-02 03:46:58'),
(3, 'GC-221569', '2025-09-02 12:52:00', 'dwadwad', 'In Progress', NULL, 1, '2025-09-02 04:52:46', '2025-09-02 04:52:46');

-- --------------------------------------------------------

--
-- Table structure for table `userrole`
--

CREATE TABLE `userrole` (
  `RoleID` int(11) NOT NULL,
  `RoleName` varchar(50) NOT NULL,
  `Permissions` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userrole`
--

INSERT INTO `userrole` (`RoleID`, `RoleName`, `Permissions`) VALUES
(1, 'Administrator', 'Full access to all modules: patient info, inventory, reports, user management.'),
(2, 'Staff', 'View and edit patient info, create and manage visits, administer medications, manage supplies.Manage inventory, create purchase orders, update stock levels.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clinicpersonnel`
--
ALTER TABLE `clinicpersonnel`
  ADD PRIMARY KEY (`PersonnelID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `EmailAddress` (`EmailAddress`),
  ADD KEY `RoleID` (`RoleID`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`StudentID`),
  ADD UNIQUE KEY `StudentEmailAddress` (`StudentEmailAddress`);

--
-- Indexes for table `studentcheckins`
--
ALTER TABLE `studentcheckins`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `userrole`
--
ALTER TABLE `userrole`
  ADD PRIMARY KEY (`RoleID`),
  ADD UNIQUE KEY `RoleName` (`RoleName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clinicpersonnel`
--
ALTER TABLE `clinicpersonnel`
  MODIFY `PersonnelID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `StudentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `studentcheckins`
--
ALTER TABLE `studentcheckins`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `userrole`
--
ALTER TABLE `userrole`
  MODIFY `RoleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clinicpersonnel`
--
ALTER TABLE `clinicpersonnel`
  ADD CONSTRAINT `clinicpersonnel_ibfk_1` FOREIGN KEY (`RoleID`) REFERENCES `userrole` (`RoleID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
