-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2025 at 09:32 AM
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
-- Table structure for table `audit_trail`
--

CREATE TABLE `audit_trail` (
  `audit_id` int(11) NOT NULL,
  `user_id` varchar(250) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `table_name` varchar(100) NOT NULL,
  `record_id` varchar(250) DEFAULT NULL,
  `action_details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_trail`
--

INSERT INTO `audit_trail` (`audit_id`, `user_id`, `action_type`, `table_name`, `record_id`, `action_details`, `created_at`) VALUES
(1, 'PIAMIS0001', 'CREATE', 'clinicpersonnel', '1', '\"Added new staff account\"', '2025-09-26 02:35:00'),
(3, 'PIAMIS0001', 'CREATE', 'clinicpersonnel', 'PIAMIS0002', 'New user added: Dave Malaran', '2025-09-26 06:41:59'),
(18, 'PIAMIS0001', 'UPDATE', 'clinicpersonnel', 'PIAMIS0002', 'User Role updated: Staff', '2025-09-27 01:57:07'),
(19, 'PIAMIS0001', 'UPDATE', 'clinicpersonnel', 'PIAMIS0002', 'User Role updated: Administrator', '2025-09-27 02:03:58'),
(20, 'PIAMIS0002', 'UPDATE', 'clinicpersonnel', 'PIAMIS0001', 'User Role updated: Staff', '2025-09-27 02:04:48'),
(21, 'PIAMIS0002', 'UPDATE', 'clinicpersonnel', 'PIAMIS0001', 'User status updated: Inactive', '2025-09-27 02:05:21'),
(22, 'PIAMIS0002', 'UPDATE', 'clinicpersonnel', 'PIAMIS0001', 'User status updated: Active', '2025-09-27 02:05:27'),
(23, 'PIAMIS0002', 'UPDATE', 'clinicpersonnel', 'PIAMIS0001', 'User Role updated: Administrator', '2025-09-27 02:05:31'),
(24, 'PIAMIS0001', 'UPDATE', 'clinicpersonnel', 'PIAMIS0001', 'User Role updated: Staff', '2025-09-27 02:06:46'),
(25, 'PIAMIS0001', 'UPDATE', 'clinicpersonnel', 'PIAMIS0001', 'User Role updated: Administrator', '2025-09-27 02:06:56'),
(26, 'PIAMIS0001', 'UPDATE', 'clinicpersonnel', 'PIAMIS0002', 'User Role updated: Staff', '2025-09-27 02:07:02');

-- --------------------------------------------------------

--
-- Table structure for table `clinicpersonnel`
--

CREATE TABLE `clinicpersonnel` (
  `cp_ID` int(250) NOT NULL,
  `PersonnelID` varchar(250) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `MiddleName` varchar(50) DEFAULT NULL,
  `RoleID` int(11) NOT NULL,
  `ContactNumber` varchar(20) DEFAULT NULL,
  `Address` varchar(250) NOT NULL,
  `Office` varchar(250) NOT NULL,
  `EmailAddress` varchar(100) DEFAULT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `PasswordChangeDT` datetime DEFAULT NULL,
  `HireDate` date DEFAULT NULL,
  `Status` varchar(20) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clinicpersonnel`
--

INSERT INTO `clinicpersonnel` (`cp_ID`, `PersonnelID`, `FirstName`, `LastName`, `MiddleName`, `RoleID`, `ContactNumber`, `Address`, `Office`, `EmailAddress`, `PasswordHash`, `PasswordChangeDT`, `HireDate`, `Status`) VALUES
(4, 'PIAMIS0001', 'Adella', 'Malaran', 'Olaguir', 1, '09557893659', '', '', 'malaranadella@gmail.com', '$2y$10$u0Yzl5nQ.JP5YEQcg24CZeEKdyn2YcfUuQShONmuqSSTFcO.eomLm', NULL, '2025-09-26', 'Active'),
(17, 'PIAMIS0002', 'Dave', 'Malaran', 'Olaguir', 2, '09557893659', '', '', 'malarandave041204@gmail.com', '$2y$10$cdiYMLdAczIG218t.gwNwO6U2vTbXYq7Kz9LL19C2Z3ZzD1STKCO.', NULL, '2025-09-26', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `disposed_medicines`
--

CREATE TABLE `disposed_medicines` (
  `disposal_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('low_stock','near_expiry','expired','damaged') NOT NULL,
  `quantity` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `reason` text NOT NULL,
  `disposed_by` varchar(255) DEFAULT NULL,
  `disposed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE `medicine` (
  `med_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `type` enum('tablet','capsule','syrup','injection','ointment','other') NOT NULL,
  `expiry_date` date NOT NULL,
  `added_by` varchar(255) DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine`
--

INSERT INTO `medicine` (`med_id`, `name`, `description`, `quantity`, `type`, `expiry_date`, `added_by`, `added_at`) VALUES
(1, 'Paracetamol 500mg', 'Pain reliever and fever reducer.', 44, 'tablet', '2027-12-31', 'Admin', '2025-09-09 17:59:29'),
(2, 'Amoxicillin 250mg', 'Antibiotic for bacterial infections.', 8, 'capsule', '2025-02-15', 'Admin', '2025-09-09 17:59:29'),
(3, 'Ibuprofen 400mg', 'Anti-inflammatory pain reliever.', 32, 'tablet', '2026-06-20', 'Admin', '2025-09-09 17:59:29'),
(4, 'Cetirizine 10mg', 'Antihistamine for allergies.', 5, 'tablet', '2024-11-01', 'Admin', '2025-09-09 17:59:29'),
(5, 'Omeprazole 20mg', 'Proton pump inhibitor for acid reflux.', 28, 'capsule', '2027-04-10', 'Admin', '2025-09-09 17:59:29'),
(6, 'Metformin 500mg', 'Diabetes medication.', 12, 'tablet', '2025-05-20', 'Admin', '2025-09-09 17:59:29'),
(7, 'Loratadine Syrup', 'Antihistamine syrup for children.', 50, 'syrup', '2026-03-05', 'Admin', '2025-09-09 17:59:29'),
(8, 'Amlodipine 5mg', 'Calcium channel blocker for hypertension.', 19, 'tablet', '2025-08-15', 'Admin', '2025-09-09 17:59:29'),
(9, 'Hydrocortisone Cream', 'Topical steroid for skin inflammation.', 15, 'ointment', '2025-01-25', 'Admin', '2025-09-09 17:59:29'),
(10, 'Penicillin G', 'Injectable antibiotic.', 3, 'injection', '2024-10-30', 'Admin', '2025-09-09 17:59:29');

-- --------------------------------------------------------

--
-- Table structure for table `medicine_report`
--

CREATE TABLE `medicine_report` (
  `report_id` int(11) NOT NULL,
  `med_id` int(11) DEFAULT NULL,
  `report_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('low_stock','high_stock','near_expiry','expired') NOT NULL,
  `type` enum('tablet','capsule','syrup','injection','ointment','other') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicine_usage`
--

CREATE TABLE `medicine_usage` (
  `usage_id` int(11) NOT NULL,
  `med_id` int(11) DEFAULT NULL,
  `quantity_used` int(11) NOT NULL,
  `usage_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine_usage`
--

INSERT INTO `medicine_usage` (`usage_id`, `med_id`, `quantity_used`, `usage_date`) VALUES
(0, 1, 1, '2025-09-17 01:00:19');

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
(8, 'GC-220723', 'Mark Jhone', 'Sumaylo', NULL, '2000-07-07', 25, 'Male', 'College', 'Year 4', 'B', '09123456789', 'mjsumaylo32@gmail.com', 'Naic, Cavite. 4110', 'Guardian', 'Sumaylo', '09123456789', 'Sumaylo@gmail.com', 'Guardian Sumaylo', '09123456789', 'Mother', 'AB', NULL, NULL, NULL, '2025-07-31', 'Active'),
(10, 'GC-220708', 'Jobert', 'Tumbado', NULL, '2000-07-07', 25, 'Male', '', '4', 'B', '09123456789', 'jbert.tumbado@gmail.com', 'Naic, Cavite. 4110', 'Guardian', 'Tumbado', '09123456789', 'Tumbado@gmail.com', 'Guardian Tumbado', '09123456789', 'Mother', 'AB', NULL, NULL, NULL, '2025-07-31', 'Active'),
(11, 'GC-220071', 'Anthony', 'Arisgado', NULL, '2000-07-07', 25, 'Male', '', '4', 'B', '09123456789', 'arisgadoanthony5@gmail.com', 'Naic, Cavite. 4110', 'Guardian', 'Arisgado', '09123456789', 'Arisgado@gmail.com', 'Guardian Arisgado', '09123456789', 'Mother', 'AB', NULL, NULL, NULL, '2025-07-31', 'Active'),
(12, 'GC-226395', 'John Kristler', 'Abad', NULL, '2000-07-07', 25, 'Male', '', '4', 'B', '09123456789', 'johnkristlerabad@gmail.com', 'Naic, Cavite. 4110', 'Guardian', 'Abad', '09123456789', 'Abad@gmail.com', 'Guardian Abad', '09123456789', 'Mother', 'AB', NULL, NULL, NULL, '2025-07-31', 'Active'),
(13, 'GC-214536', 'Argielyn', 'Tapsawani', NULL, '2000-07-07', 25, 'Female', '', '4', 'B', '09123456789', 'rglyntpswn@gmail.com', 'Naic, Cavite. 4110', 'Guardian', 'Tapsawani', '09123456789', 'Tapsawani@gmail.com', 'Guardian Tapsawani', '09123456789', 'Mother', 'AB', NULL, NULL, NULL, '2025-07-31', 'Active'),
(30, 'GC-222235', 'Elmar Reymond', 'Dela Cruz', 'Arroyo', '2003-09-15', 21, 'Male', 'College', 'year4', 'B', '09514785965', 'email@gmail.com', 'Dorethea 2, Calubcob, Naic, Cavite. 4110', 'Emong', 'Dela Cruz', '09632586966', 'emong@gmail.com', 'Emong Dela Cruz', '09632586966', 'Father', 'AB+', 'Dog allergy', 'sakit sa ASO', '', '2025-09-04', 'Active');

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
(1, 'GC-220071', '2025-09-16 21:18:00', 'Stomach ache', 'Completed', 'Pinauwi ko na. natae na eh', 1, '2025-09-16 13:20:17', '2025-09-16 13:23:00'),
(2, 'GC-220723', '2025-09-16 21:20:00', 'Fever', 'In Progress', NULL, 1, '2025-09-16 13:20:37', '2025-09-16 13:20:37'),
(3, 'GC-220708', '2025-09-16 21:20:00', 'Cough', 'In Progress', NULL, 1, '2025-09-16 13:20:44', '2025-09-16 13:20:44'),
(4, 'GC-226395', '2025-09-16 21:20:00', 'Fever', 'In Progress', NULL, 1, '2025-09-16 13:21:08', '2025-09-16 13:21:08'),
(5, 'GC-214536', '2025-09-16 21:21:00', 'Dizziness', 'In Progress', NULL, 1, '2025-09-16 13:21:58', '2025-09-16 13:22:49'),
(6, 'GC-220071', '2025-09-16 21:22:00', 'Dizziness', 'In Progress', NULL, 1, '2025-09-16 13:23:15', '2025-09-16 13:23:15');

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
-- Indexes for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD PRIMARY KEY (`audit_id`);

--
-- Indexes for table `clinicpersonnel`
--
ALTER TABLE `clinicpersonnel`
  ADD PRIMARY KEY (`cp_ID`),
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
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `clinicpersonnel`
--
ALTER TABLE `clinicpersonnel`
  MODIFY `cp_ID` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `StudentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `studentcheckins`
--
ALTER TABLE `studentcheckins`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
