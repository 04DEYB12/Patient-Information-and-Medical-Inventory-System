-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2025 at 08:20 AM
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
(1, 'PIAMIS0001', 'CREATE', 'clinicpersonnel', 'PIAMIS0002', 'New user added: Dave Malaran', '2025-12-16 05:36:52'),
(2, 'PIAMIS0001', 'UPDATE', 'clinicpersonnel', 'PIAMIS0002', 'User Role updated: Administrator', '2025-12-16 05:46:53'),
(3, 'PIAMIS0001', 'CREATE', 'clinicpersonnel', 'PIAMIS0003', 'New user added: Anthony Arisgado', '2025-12-16 05:56:30'),
(4, 'PIAMIS0001', 'CREATE', 'clinicpersonnel', 'PIAMIS0004', 'New user added: Adella Malaran', '2025-12-16 06:17:06'),
(5, 'PIAMIS0001', 'CREATE', 'clinicpersonnel', 'PIAMIS0005', 'New user added: Elmar Dela Cruz', '2025-12-16 06:19:23'),
(6, 'PIAMIS0001', 'UPDATE', 'clinicpersonnel', 'PIAMIS0003', 'User Role updated: Administrator', '2025-12-16 06:19:40'),
(7, 'PIAMIS0001', 'UPDATE', 'clinicpersonnel', 'PIAMIS0003', 'User Role updated: Staff', '2025-12-16 06:20:46'),
(8, 'PIAMIS0001', 'UPDATE', 'clinicpersonnel', 'PIAMIS0004', 'User Role updated: Administrator', '2025-12-16 06:20:50'),
(9, 'PIAMIS0002', 'CREATE', 'medicine', '1', 'Added new medicine: Paracetamol (Qty: 55, Exp: 2026-06-10, Purposes: Headache Relief)', '2025-12-16 06:24:17'),
(10, 'PIAMIS0002', 'CREATE', 'medicine', '2', 'Added new medicine: Paracetamol (Qty: 26, Exp: 2025-12-03, Purposes: Headache Relief)', '2025-12-16 06:24:43'),
(11, 'PIAMIS0002', 'CREATE', 'medicine', '3', 'Added new medicine: Paracetamol (Qty: 2, Exp: 2025-12-24, Purposes: Headache Relief)', '2025-12-16 06:25:04'),
(12, 'PIAMIS0002', 'CREATE', 'medicine', '4', 'Added new medicine: Ibuprofen (Qty: 36, Exp: 2025-12-19, Purposes: Fever Reduction)', '2025-12-16 06:27:02'),
(13, 'PIAMIS0002', 'CREATE', 'medicine', '5', 'Added new medicine: Loperamide (Qty: 67, Exp: 2026-01-23, Purposes: Headache Relief)', '2025-12-16 06:29:06'),
(14, 'PIAMIS0002', 'CREATE', 'medicine', '6', 'Added new medicine: Antifungal (Qty: 3, Exp: 2026-02-28, Purposes: Allergy Treatment; Infection)', '2025-12-16 06:30:29'),
(15, 'PIAMIS0002', 'DEDUCT', 'medicine', '4', 'Deducted 2 of Ibuprofen. New stock: 34', '2025-12-16 06:31:33'),
(16, 'PIAMIS0003', 'DEDUCT', 'medicine', '5', 'Deducted 10 of Loperamide. New stock: 57', '2025-12-16 06:34:12'),
(17, 'PIAMIS0003', 'CREATE', 'medicine', '7', 'Added new medicine: Loperamide (Qty: 50, Exp: 2025-12-15, Purposes: Headache Relief)', '2025-12-16 06:34:47'),
(18, 'PIAMIS0003', 'UPDATE', 'medicine', '7', 'Updated medicine: Loperamide (Type: capsule)', '2025-12-16 06:37:10'),
(19, 'PIAMIS0003', 'UPDATE', 'medicine', '4', 'Updated existing batch of Ibuprofen (Qty: 10, New Total: 44, Exp: 2025-12-19)', '2025-12-16 06:37:54'),
(20, 'PIAMIS0003', 'UPDATE', 'medicine', '4', 'Updated existing batch of Ibuprofen (Qty: 10, New Total: 54, Exp: 2025-12-19)', '2025-12-16 06:38:39'),
(21, 'PIAMIS0001', 'DEDUCT', 'medicine', '6', 'Deducted 1 of Antifungal. New stock: 2', '2025-12-16 06:41:47'),
(30, 'PIAMIS0002', 'CREATE', 'studentcheckins', 'GC-220708', 'New check-in added: GC-220708', '2025-12-16 07:01:35'),
(31, 'PIAMIS0002', 'CREATE', 'studentcheckins', 'GC-220708', 'New check-in added: GC-220708', '2025-12-16 07:03:56'),
(32, 'PIAMIS0002', 'CREATE', 'medicine', '8', 'Added new medicine: Cetirizine (Qty: 50, Exp: 2026-02-20, Purposes: Infection)', '2025-12-16 07:05:33'),
(33, 'PIAMIS0002', 'DEDUCT', 'medicine', '8', 'Deducted 7 of Cetirizine. New stock: 43', '2025-12-16 07:06:09'),
(34, 'PIAMIS0002', 'UPDATE', 'studentcheckins', 'GC-220708', 'Check-up marked as done: GC-220708', '2025-12-16 07:07:39'),
(35, 'PIAMIS0002', 'CREATE', 'studentcheckins', 'GC-220708', 'New check-in added: GC-220708', '2025-12-16 07:10:49');

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
(1, 'PIAMIS0001', 'PIAMIS', 'S.A.', NULL, 3, NULL, 'Granby Colleges of Science and Technology', 'School Clinic', 'davemalaran2004@gmail.com', '$2y$10$42rrFIZ5rytqBGCzDbRubOvHD3hMXbJCnNPF4i6j7Ok2OKRc5y26.', '2025-12-16 13:34:15', NULL, 'Active'),
(2, 'PIAMIS0002', 'Dave', 'Malaran', '', 1, '09514572814', 'Nike Cavity', '', 'malarandave041204@gmail.com', '$2y$10$vRXHd/PjlPqveYBsavgc2utD6tK0QaY4.lf9RoNzjCDzA.UY.FC4u', '2025-12-16 13:52:43', '2025-12-16', 'Active'),
(3, 'PIAMIS0003', 'Anthony', 'Arisgado', '', 2, '09557893659', '', '', 'arisgadoanthony5@gmail.com', '$2y$10$5cqknthe68BJa7GqU5qqIeSaf.AYX7JxyrZ55rGfl/5uDINOIeqRa', '2025-12-16 13:59:07', '2025-12-16', 'Active'),
(5, 'PIAMIS0004', 'Adella', 'Malaran', '', 1, '09514572814', '', '', 'malaranadella@gmail.com', '$2y$10$Wn6gPDWiSqoSV9Rp2xnUpuXjLq0.VO2pAUK4oeI.vuP5W0QaJjHbe', NULL, '2025-12-16', 'Active'),
(6, 'PIAMIS0005', 'Elmar', 'Dela Cruz', '', 2, '09557893659', '', '', 'delacruzelmarr@gmail.com', '$2y$10$OxLWXmknKIhEw.OPa8eDUe9WR.iwlghJ6YmC7mGNp4LoZdt84UMFC', NULL, '2025-12-16', 'Active');

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
(1, 'Paracetamol', 'Headache Relief [Severity: mild]', 55, 'tablet', '2026-06-10', 'Admin', '2025-12-16 06:24:17'),
(2, 'Paracetamol', 'Headache Relief [Severity: mild]', 26, 'tablet', '2025-12-03', 'Admin', '2025-12-16 06:24:43'),
(3, 'Paracetamol', 'Headache Relief [Severity: mild]', 2, 'tablet', '2025-12-24', 'Admin', '2025-12-16 06:25:04'),
(4, 'Ibuprofen', 'Fever Reduction [Severity: mild]', 54, 'tablet', '2025-12-19', 'Admin', '2025-12-16 06:27:02'),
(5, 'Loperamide', 'Headache Relief [Severity: mild]', 57, 'capsule', '2026-01-23', 'Admin', '2025-12-16 06:29:06'),
(6, 'Antifungal', 'Allergy Treatment; Infection [Severity: moderate]', 2, 'ointment', '2026-02-28', 'Admin', '2025-12-16 06:30:29'),
(7, 'Loperamide', 'Headache Relief [Severity: mild]', 50, 'capsule', '2025-12-15', 'Admin', '2025-12-16 06:34:47'),
(8, 'Cetirizine', 'Infection [Severity: moderate]', 43, 'tablet', '2026-02-20', 'Admin', '2025-12-16 07:05:33');

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
(1, 4, 2, '2025-12-16 06:31:33'),
(2, 5, 10, '2025-12-16 06:34:12'),
(3, 6, 1, '2025-12-16 06:41:47'),
(4, 8, 7, '2025-12-16 07:06:09');

-- --------------------------------------------------------

--
-- Table structure for table `otp_request`
--

CREATE TABLE `otp_request` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp_code` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_used` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(8, 'GC-220723', 'Mark Jhone', 'Sumaylo', NULL, '2000-07-07', 25, 'Male', 'College', 'Year 4', 'B', '09123456789', 'malarandave041204@gmail.com', 'Naic, Cavite. 4110', 'Guardian', 'Sumaylo', '09123456789', 'Sumaylo@gmail.com', 'Guardian Sumaylo', '09123456789', 'Mother', 'AB', NULL, NULL, NULL, '2025-07-31', 'Inactive'),
(10, 'GC-220708', 'Jobert', 'Tumbado', NULL, '2000-07-07', 25, 'Male', 'College', '4', 'A', '09123456789', 'jbert.tumbado@gmail.com', 'B41 L20, Southmorning View P5, Timalan Balsahan, Naic, Cavite. 4110', 'Guardian', 'Tumbado', '09123456789', 'malarandave041204@gmail.com', 'Guardian Tumbado', '09123456789', 'Mother', 'AB', NULL, NULL, NULL, '2025-07-31', 'Active'),
(11, 'GC-220071', 'Anthony', 'Arisgado', NULL, '2000-07-07', 25, 'Male', 'College', 'Year 4', 'C', '09123456789', 'arisgadoanthony5@gmail.com', 'Naic, Cavite. 4110', 'Guardian', 'Arisgado', '09123456789', 'Arisgado@gmail.com', 'Guardian Arisgado', '09123456789', 'Mother', 'AB', NULL, NULL, NULL, '2025-07-31', 'Active'),
(12, 'GC-226395', 'John Kristler', 'Abad', NULL, '2000-07-07', 25, 'Male', 'College', '4', 'B', '09123456789', 'johnkristlerabad@gmail.com', 'Naic, Cavite. 4110', 'Guardian', 'Abad', '09123456789', 'Abad@gmail.com', 'Guardian Abad', '09123456789', 'Mother', 'AB', NULL, NULL, NULL, '2025-07-31', 'Active'),
(13, 'GC-214536', 'Argielyn', 'Tapsawani', NULL, '2000-07-07', 25, 'Female', 'College', '4', 'B', '09123456789', 'rglyntpswn@gmail.com', 'Naic, Cavite. 4110', 'Guardian', 'Tapsawani', '09123456789', 'Tapsawani@gmail.com', 'Guardian Tapsawani', '09123456789', 'Mother', 'AB', NULL, NULL, NULL, '2025-07-31', 'Active'),
(30, 'GC-222235', 'Elmar Reymond', 'Dela Cruz', 'Arroyo', '2003-09-15', 21, 'Male', 'College', 'year4', 'B', '09514785965', 'delacruzelmarr@gmail.com', 'Dorethea 2, Calubcob, Naic, Cavite. 4110', 'Emong', 'Dela Cruz', '09632586966', 'emong@gmail.com', 'Emong Dela Cruz', '09632586966', 'Father', 'AB+', 'Dog allergy', 'sakit sa ASO', '', '2025-09-04', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `studentcheckins`
--

CREATE TABLE `studentcheckins` (
  `ID` int(11) NOT NULL,
  `StudentID` varchar(50) NOT NULL,
  `DateTime` datetime NOT NULL,
  `Reason` text NOT NULL,
  `Notes` text NOT NULL,
  `Status` enum('In Progress','Follow-up','Lapsed','Completed') DEFAULT 'In Progress',
  `Outcome` text DEFAULT NULL,
  `StaffID` varchar(50) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `FollowUpDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studentcheckins`
--

INSERT INTO `studentcheckins` (`ID`, `StudentID`, `DateTime`, `Reason`, `Notes`, `Status`, `Outcome`, `StaffID`, `CreatedAt`, `UpdatedAt`, `FollowUpDate`) VALUES
(1, 'GC-220708', '2025-12-16 15:01:00', 'Fever', 'Temperature: 38.0 °C\r\nObservation: chills and sweating', 'Follow-up', NULL, 'PIAMIS0002', '2025-12-16 07:01:35', '2025-12-16 07:02:28', '2025-12-16 18:02:00'),
(2, 'GC-220708', '2025-12-16 15:02:00', 'Cough / Cold', 'Observation: Scratchy Throat', 'Completed', 'Returned to class and was given 7 tablets of cetirizine.', 'PIAMIS0002', '2025-12-16 07:03:56', '2025-12-16 07:07:39', NULL),
(3, 'GC-220708', '2025-12-16 15:07:00', 'Wound / Injury', 'Injury sustained during COI Sportfest basketball; wound not severe.', 'Follow-up', NULL, 'PIAMIS0002', '2025-12-16 07:10:49', '2025-12-16 07:11:22', '2025-12-23 14:00:00');

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
(1, 'Administrator', 'Full access to all modules: patient info, inventory, user management.'),
(2, 'Staff', 'View and edit patient info, create and manage visits, administer medications, manage supplies.Manage inventory, create purchase orders, update stock levels.'),
(3, 'Super Administrator', 'Full access to all modules: patient info, inventory, user management, User Control.');

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
-- Indexes for table `disposed_medicines`
--
ALTER TABLE `disposed_medicines`
  ADD PRIMARY KEY (`disposal_id`);

--
-- Indexes for table `medicine`
--
ALTER TABLE `medicine`
  ADD PRIMARY KEY (`med_id`);

--
-- Indexes for table `medicine_report`
--
ALTER TABLE `medicine_report`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `medicine_usage`
--
ALTER TABLE `medicine_usage`
  ADD PRIMARY KEY (`usage_id`);

--
-- Indexes for table `otp_request`
--
ALTER TABLE `otp_request`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `clinicpersonnel`
--
ALTER TABLE `clinicpersonnel`
  MODIFY `cp_ID` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `disposed_medicines`
--
ALTER TABLE `disposed_medicines`
  MODIFY `disposal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medicine`
--
ALTER TABLE `medicine`
  MODIFY `med_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `medicine_report`
--
ALTER TABLE `medicine_report`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medicine_usage`
--
ALTER TABLE `medicine_usage`
  MODIFY `usage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `otp_request`
--
ALTER TABLE `otp_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `StudentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `studentcheckins`
--
ALTER TABLE `studentcheckins`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `userrole`
--
ALTER TABLE `userrole`
  MODIFY `RoleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
