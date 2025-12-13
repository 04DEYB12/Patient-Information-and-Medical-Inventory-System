-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2025 at 01:27 AM
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
(26, 'PIAMIS0001', 'UPDATE', 'clinicpersonnel', 'PIAMIS0002', 'User Role updated: Staff', '2025-09-27 02:07:02'),
(27, 'PIAMIS0001', 'CREATE', 'studentcheckins', 'GC-220708', 'New check-in added: GC-220708', '2025-10-03 12:42:35'),
(28, 'PIAMIS0001', 'CREATE', 'studentcheckins', 'GC-220723', 'New check-in added: GC-220723', '2025-10-04 04:18:32'),
(29, 'PIAMIS0001', 'CREATE', 'studentcheckins', 'GC-220723', 'New check-in added: GC-220723', '2025-10-04 04:45:15'),
(30, 'PIAMIS0001', 'CREATE', 'studentcheckins', 'GC-220723', 'New check-in added: GC-220723', '2025-10-04 04:49:17'),
(31, 'PIAMIS0001', 'CREATE', 'studentcheckins', 'GC-220723', 'New check-in added: GC-220723', '2025-10-04 04:52:11'),
(32, 'PIAMIS0001', 'CREATE', 'studentcheckins', 'GC-220708', 'New check-in added: GC-220708', '2025-10-04 05:09:19'),
(33, 'PIAMIS0001', 'CREATE', 'studentcheckins', 'GC-220708', 'New check-in added: GC-220708', '2025-10-04 05:10:09'),
(34, 'PIAMIS0001', 'CREATE', 'student', 'GC-229999', 'New student added: GC-229999', '2025-10-07 02:20:42'),
(35, 'PIAMIS0001', 'UPDATE', 'student', 'GC-229999', 'Student updated: GC-229999', '2025-10-07 02:25:22'),
(36, 'PIAMIS0001', 'UPDATE', 'student', 'GC-229999', 'Student updated: GC-229999', '2025-10-07 02:26:05'),
(37, 'PIAMIS0001', 'UPDATE', 'student', 'GC-229999', 'Student updated: GC-229999', '2025-10-07 02:28:12'),
(38, 'PIAMIS0001', 'UPDATE', 'student', 'GC-229999', 'Student updated: GC-229999', '2025-10-07 02:47:50'),
(39, 'PIAMIS0001', 'CREATE', 'studentcheckins', 'GC-220708', 'New check-in added: GC-220708', '2025-10-28 01:58:38'),
(40, 'PIAMIS0001', 'UPDATE', 'student', 'GC-220071', 'Student updated: GC-220071', '2025-10-28 03:02:04'),
(43, 'PIAMIS0001', 'UPDATE', 'studentcheckins', 'GC-220708', 'Check-in updated: GC-220708', '2025-10-28 04:16:03'),
(44, 'PIAMIS0001', 'UPDATE', 'studentcheckins', 'GC-220708', 'Check-in updated: GC-220708', '2025-10-28 04:16:48'),
(45, 'PIAMIS0001', 'UPDATE', 'student', 'GC-220708', 'Student updated: GC-220708', '2025-11-04 02:26:45'),
(46, 'PIAMIS0001', 'UPDATE', 'student', 'GC-220708', 'Student updated: GC-220708', '2025-11-04 07:20:07'),
(47, 'PIAMIS0001', 'CREATE', 'medicine', '0', 'Added new medicine: Paracetamol 500mg (Qty: 50, Exp: 2026-11-19)', '2025-11-04 15:18:34'),
(63, 'PIAMIS0001', 'CREATE', 'studentcheckins', 'GC-220708', 'New check-in added: GC-220708', '2025-11-21 04:47:14'),
(64, 'PIAMIS0001', 'UPDATE', 'studentcheckins', 'GC-220708', 'Check-in updated: GC-220708', '2025-11-21 04:52:22'),
(65, 'PIAMIS0001', 'CREATE', 'studentcheckins', 'GC-220708', 'New check-in added: GC-220708', '2025-11-21 05:00:04'),
(66, 'PIAMIS0001', 'UPDATE', 'studentcheckins', 'GC-220708', 'Check-in updated: GC-220708', '2025-11-21 13:22:32'),
(67, 'PIAMIS0001', 'CREATE', 'studentcheckins', 'GC-220708', 'New check-in added: GC-220708', '2025-11-21 13:22:43'),
(68, 'PIAMIS0001', 'DEDUCT', 'medicine', '7', 'Deducted 1 of Loratadine Syrup. New stock: 49', '2025-11-21 14:15:51'),
(69, 'PIAMIS0001', 'DEDUCT', 'medicine', '5', 'Deducted 8 of Omeprazole 20mg. New stock: 20', '2025-11-21 14:16:43'),
(70, 'PIAMIS0001', 'DEDUCT', 'medicine', '0', 'Deducted 23 of Paracetamol 500mg. New stock: 27', '2025-11-21 14:17:37'),
(71, 'PIAMIS0001', 'DEDUCT', 'medicine', '2', 'Deducted 5 of Amoxicillin 250mg. New stock: 3', '2025-11-21 14:18:00'),
(72, 'PIAMIS0001', 'CREATE', 'medicine', '0', 'Restocked new batch of Amoxicillin 250mg (Qty: 100, New Exp: 2026-11-26)', '2025-11-21 14:18:50'),
(73, 'PIAMIS0001', 'DEDUCT', 'medicine', '7', 'Deducted 15 of Loratadine Syrup. New stock: 34', '2025-11-21 14:20:41'),
(74, 'PIAMIS0001', 'CREATE', 'studentcheckins', 'GC-220723', 'New check-in added: GC-220723', '2025-11-21 15:46:08'),
(75, 'PIAMIS0001', 'CREATE', 'studentcheckins', 'GC-220708', 'New check-in added: GC-220708', '2025-11-22 01:54:16'),
(76, 'PIAMIS0001', 'DEDUCT', 'medicine', '2', 'Deducted 3 of Amoxicillin 250mg. New stock: 0', '2025-11-22 01:55:01'),
(77, 'PIAMIS0001', 'CREATE', 'studentcheckins', 'GC-220708', 'New check-in added: GC-220708', '2025-11-22 02:09:28'),
(78, 'PIAMIS0001', 'UPDATE', 'studentcheckins', 'GC-220708', 'Check-in updated: GC-220708', '2025-11-22 02:09:51'),
(79, 'PIAMIS0001', 'DEDUCT', 'medicine', '4', 'Deducted 2 of Cetirizine 10mg. New stock: 3', '2025-11-22 02:11:26'),
(80, 'PIAMIS0001', 'UPDATE', 'clinicpersonnel', 'PIAMIS0002', 'User Role updated: Administrator', '2025-11-22 02:12:24'),
(81, 'PIAMIS0001', 'UPDATE', 'clinicpersonnel', 'PIAMIS0002', 'User Role updated: Staff', '2025-11-22 02:12:41'),
(82, 'PIAMIS0001', 'CREATE', 'studentcheckins', 'GC-220708', 'New check-in added: GC-220708', '2025-11-22 02:35:43'),
(83, 'PIAMIS0001', 'CREATE', 'clinicpersonnel', 'PIAMIS0003', 'New user added: Anya Forger', '2025-11-24 14:26:27'),
(84, 'PIAMIS0001', 'UPDATE', 'clinicpersonnel', 'PIAMIS0003', 'User email updated: angelsarabosing123@gmail.com', '2025-11-24 14:27:51'),
(85, 'PIAMIS0001', 'UPDATE', 'clinicpersonnel', 'PIAMIS0003', 'User Password Reset', '2025-11-24 14:28:04'),
(86, 'PIAMIS0003', 'CREATE', 'clinicpersonnel', 'PIAMIS0004', 'New user added: E-mon Deila Xruz', '2025-11-24 16:23:15'),
(87, 'PIAMIS0003', 'CREATE', 'clinicpersonnel', 'PIAMIS0005', 'New user added: Berto Timba-do', '2025-11-24 16:24:17'),
(88, 'PIAMIS0003', 'UPDATE', 'clinicpersonnel', 'PIAMIS0004', 'User Role updated: Administrator', '2025-11-24 16:24:30'),
(89, 'PIAMIS0001', 'UPDATE', 'clinicpersonnel', 'PIAMIS0002', 'User status updated: Inactive', '2025-11-24 16:40:43'),
(90, 'PIAMIS0001', 'UPDATE', 'clinicpersonnel', 'PIAMIS0002', 'User status updated: Active', '2025-11-24 16:40:50'),
(91, 'PIAMIS0001', 'UPDATE', 'clinicpersonnel', 'PIAMIS0002', 'User status updated: Inactive', '2025-11-24 16:47:48'),
(92, 'PIAMIS0003', 'CREATE', 'clinicpersonnel', 'PIAMIS0006', 'New user added: MJ Sumaylo', '2025-11-24 17:13:47'),
(93, 'PIAMIS0003', 'CREATE', 'clinicpersonnel', 'PIAMIS0007', 'New user added: Dai Rocha', '2025-11-24 17:14:55'),
(94, 'PIAMIS0003', 'CREATE', 'clinicpersonnel', 'PIAMIS0008', 'New user added: George Greer', '2025-11-24 17:17:25'),
(95, 'PIAMIS0003', 'UPDATE', 'clinicpersonnel', 'PIAMIS0002', 'User Role updated: Administrator', '2025-11-24 17:21:30'),
(96, 'PIAMIS0003', 'UPDATE', 'clinicpersonnel', 'PIAMIS0002', 'User status updated: Active', '2025-11-24 17:21:48'),
(97, 'PIAMIS0003', 'UPDATE', 'clinicpersonnel', 'PIAMIS0002', 'User status updated: Inactive', '2025-11-24 17:23:54'),
(98, 'PIAMIS0003', 'UPDATE', 'clinicpersonnel', 'PIAMIS0002', 'User Role updated: Staff', '2025-11-24 17:24:01'),
(99, 'PIAMIS0003', 'UPDATE', 'clinicpersonnel', 'PIAMIS0002', 'User Role updated: Administrator', '2025-11-24 17:24:26'),
(100, 'PIAMIS0003', 'UPDATE', 'clinicpersonnel', 'PIAMIS0002', 'User status updated: Active', '2025-11-24 17:25:44'),
(101, 'PIAMIS0003', 'UPDATE', 'clinicpersonnel', 'PIAMIS0002', 'User status updated: Inactive', '2025-11-24 17:26:31'),
(102, 'PIAMIS0003', 'UPDATE', 'clinicpersonnel', 'PIAMIS0002', 'User Role updated: Staff', '2025-11-24 17:26:43'),
(103, 'PIAMIS0001', 'UPDATE', 'studentcheckins', 'GC-220708', 'Check-up updated: GC-220708', '2025-11-25 07:49:31'),
(104, 'PIAMIS0001', 'UPDATE', 'studentcheckins', 'GC-220708', 'Check-up updated: GC-220708', '2025-11-25 07:50:54'),
(105, 'PIAMIS0001', 'UPDATE', 'studentcheckins', 'GC-220708', 'Check-up updated: GC-220708', '2025-11-25 07:51:15'),
(106, 'PIAMIS0001', 'UPDATE', 'student', 'GC-228923', 'Student updated: GC-228923', '2025-11-25 08:00:16'),
(107, 'PIAMIS0001', 'CREATE', 'studentcheckins', 'GC-228923', 'New check-in added: GC-228923', '2025-11-25 08:01:02'),
(108, 'PIAMIS0001', 'UPDATE', 'studentcheckins', 'GC-228923', 'Check-up updated: GC-228923', '2025-11-25 08:03:46'),
(109, 'PIAMIS0001', 'UPDATE', 'studentcheckins', 'GC-220708', 'Check-up updated: GC-220708', '2025-11-25 08:24:52'),
(110, 'PIAMIS0001', 'UPDATE', 'student', 'GC-222235', 'Student updated: GC-222235', '2025-11-25 08:35:54'),
(111, 'PIAMIS0001', 'CREATE', 'studentcheckins', 'GC-222235', 'New check-in added: GC-222235', '2025-11-25 08:36:29'),
(112, 'PIAMIS0001', 'UPDATE', 'student', 'GC-220708', 'Student updated: GC-220708', '2025-11-25 15:21:27'),
(113, 'PIAMIS0001', 'UPDATE', 'studentcheckins', 'GC-220708', 'Check-up marked as done: GC-220708', '2025-11-25 16:28:22'),
(114, 'PIAMIS0001', 'UPDATE', 'student', 'GC-220723', 'Student updated: GC-220723', '2025-12-03 03:30:36'),
(115, 'PIAMIS0003', 'CREATE', 'studentcheckins', 'GC-220708', 'New check-in added: GC-220708', '2025-12-03 05:41:30'),
(116, 'PIAMIS0003', 'CREATE', 'studentcheckins', 'GC-220708', 'New check-in added: GC-220708', '2025-12-03 05:53:19'),
(117, 'PIAMIS0003', 'UPDATE', 'clinicpersonnel', 'PIAMIS0002', 'User status updated: Active', '2025-12-03 05:57:53'),
(118, 'PIAMIS0003', 'UPDATE', 'clinicpersonnel', 'PIAMIS0001', 'User Role updated: Staff', '2025-12-03 06:02:35'),
(119, 'PIAMIS0003', 'CREATE', 'medicine', '0', 'Added new medicine: Imogene Rivera (Qty: 935, Exp: 1981-03-01)', '2025-12-03 06:19:53'),
(120, 'PIAMIS0003', 'CREATE', 'medicine', '0', 'Added new medicine: Brendan Ayala (Qty: 708, Exp: 2026-10-13)', '2025-12-03 06:20:32'),
(121, 'PIAMIS0003', 'CREATE', 'medicine', '0', 'Added new medicine: Imogene Rivera (Qty: 96, Exp: 2025-12-03)', '2025-12-03 06:26:16'),
(122, 'PIAMIS0003', 'CREATE', 'studentcheckins', 'GC-220071', 'New check-in added: GC-220071', '2025-12-04 16:04:31'),
(123, 'PIAMIS0003', 'CREATE', 'studentcheckins', 'GC-222235', 'New check-in added: GC-222235', '2025-12-04 16:04:41'),
(124, 'PIAMIS0003', 'UPDATE', 'clinicpersonnel', 'PIAMIS0001', 'User Role updated: Administrator', '2025-12-04 16:32:48'),
(125, 'PIAMIS0003', 'UPDATE', 'student', 'GC-229999', 'Student updated: GC-229999', '2025-12-05 13:01:43'),
(126, 'PIAMIS0003', 'CREATE', 'studentcheckins', 'GC-220708', 'New check-in added: GC-220708', '2025-12-12 12:05:52'),
(127, 'PIAMIS0003', 'UPDATE', 'studentcheckins', 'GC-220708', 'Check-up marked as done: GC-220708', '2025-12-12 12:17:57'),
(128, 'PIAMIS0002', 'CREATE', 'medicine', '0', 'Restocked NEW batch of Amoxicillin 250mg (expired old batch) (Qty: 50, Exp: 2026-06-13)', '2025-12-12 12:25:33'),
(129, 'PIAMIS0002', 'CREATE', 'medicine', '0', 'Restocked NEW batch of Amlodipine 5mg (expired old batch) (Qty: 20, Exp: 2026-08-21)', '2025-12-12 12:25:33'),
(130, 'PIAMIS0002', 'CREATE', 'medicine', '0', 'Added new medicine: adbwdaw (Qty: 15, Exp: 2026-03-18, Purposes: Headache Relief)', '2025-12-12 12:27:15'),
(131, 'PIAMIS0003', 'CREATE', 'studentcheckins', 'GC-220708', 'New check-in added: GC-220708', '2025-12-12 12:38:17');

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
(4, 'PIAMIS0001', 'Adella', 'Malaran', 'Olaguir', 1, '0951 452 4125', 'Nike Cavity', 'Medical Room', 'malaranadella@gmail.com', '$2y$10$ioUFKiBHXgoiTR8uYvRkJufDRs/yqWLP7bbr5BQ1BX2.aN8XaOQDS', '2025-11-24 23:17:07', '2025-09-26', 'Active'),
(17, 'PIAMIS0002', 'Dave', 'Malaran', 'Omac', 2, '09557893659', '', '', 'malarandave041204@gmail.com', '$2y$10$2HbjOQsEGMgFIIG37dKl9.62c5YdBNOdJ2kjHPdZyj2qSjy5v9wN2', '2025-11-24 23:20:10', '2025-09-26', 'Active'),
(18, 'PIAMIS0003', 'Anya', 'Forger', '', 3, '09557893659', '', '', 'angelsarabosing123@gmail.com', '$2y$10$DF3zTS/MGRyuR5N.OM2m/eGrI18ME599.q6M.Nhjqn7Ai0Mlr0dsW', '2025-11-24 23:19:05', '2025-11-24', 'Active'),
(19, 'PIAMIS0004', 'E-mon', 'Deila Xruz', '', 1, '09123456789', '', '', 'davemalaran04@yahoo.com', '$2y$10$6A9hYKiqpMZ4eJJ01IN.XOQ0mHB9YD2UzlJFO3EYpD4ivz1DBQGWS', NULL, '2025-11-25', 'Active'),
(20, 'PIAMIS0005', 'Berto', 'Timba-do', 'D.', 2, '09557893659', '', '', 'jobert@jobert.com', '$2y$10$/UFFs/kYJuil.Wtzn66rAudGCUr4wZUL0zeMD1xKs6KrxI.Yhj3nW', NULL, '2025-11-25', 'Active');

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
(2, 'Amoxicillin 250mg', 'Antibiotic for bacterial infections.', 0, 'capsule', '2025-02-15', 'Admin', '2025-09-09 17:59:29'),
(3, 'Ibuprofen 400mg', 'Anti-inflammatory pain reliever.', 32, 'tablet', '2026-06-20', 'Admin', '2025-09-09 17:59:29'),
(4, 'Cetirizine 10mg', 'Antihistamine for allergies.', 3, 'tablet', '2024-11-01', 'Admin', '2025-09-09 17:59:29'),
(5, 'Omeprazole 20mg', 'Proton pump inhibitor for acid reflux.', 20, 'capsule', '2027-04-10', 'Admin', '2025-09-09 17:59:29'),
(6, 'Metformin 500mg', 'Diabetes medication.', 12, 'tablet', '2025-05-20', 'Admin', '2025-09-09 17:59:29'),
(7, 'Loratadine Syrup', 'Antihistamine syrup for children.', 34, 'syrup', '2026-03-05', 'Admin', '2025-09-09 17:59:29'),
(8, 'Amlodipine 5mg', 'Calcium channel blocker for hypertension.', 19, 'tablet', '2025-08-15', 'Admin', '2025-09-09 17:59:29'),
(9, 'Hydrocortisone Cream', 'Topical steroid for skin inflammation.', 15, 'ointment', '2025-01-25', 'Admin', '2025-09-09 17:59:29'),
(10, 'Penicillin G', 'Injectable antibiotic.', 3, 'injection', '2024-10-30', 'Admin', '2025-09-09 17:59:29'),
(0, 'Paracetamol 500mg', '', 27, 'tablet', '2026-11-19', 'Admin', '2025-11-04 15:18:34'),
(0, 'Amoxicillin 250mg', 'Antibiotic for bacterial infections.', 100, 'capsule', '2026-11-26', 'Admin', '2025-11-21 14:18:50'),
(0, 'Imogene Rivera', 'Esse cumque repudian', 935, 'tablet', '1981-03-01', 'Admin', '2025-12-03 06:19:53'),
(0, 'Brendan Ayala', 'Ut do nostrud culpa', 708, 'capsule', '2026-10-13', 'Admin', '2025-12-03 06:20:32'),
(0, 'Imogene Rivera', 'awdawjh', 96, 'tablet', '2025-12-03', 'Admin', '2025-12-03 06:26:16'),
(0, 'Amoxicillin 250mg', 'Antibiotic for bacterial infections.', 50, 'capsule', '2026-06-13', 'Admin', '2025-12-12 12:25:33'),
(0, 'Amlodipine 5mg', 'Calcium channel blocker for hypertension.', 20, 'tablet', '2026-08-21', 'Admin', '2025-12-12 12:25:33'),
(0, 'adbwdaw', 'Headache Relief [Severity: mild]', 15, 'capsule', '2026-03-18', 'Admin', '2025-12-12 12:27:15');

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
(0, 1, 1, '2025-09-17 01:00:19'),
(0, 7, 1, '2025-11-21 14:15:51'),
(0, 5, 8, '2025-11-21 14:16:43'),
(0, 0, 23, '2025-11-21 14:17:37'),
(0, 2, 5, '2025-11-21 14:18:00'),
(0, 7, 15, '2025-11-21 14:20:41'),
(0, 2, 3, '2025-11-22 01:55:01'),
(0, 4, 2, '2025-11-22 02:11:26');

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

--
-- Dumping data for table `otp_request`
--

INSERT INTO `otp_request` (`id`, `email`, `otp_code`, `created_at`, `is_used`) VALUES
(1, 'malarandave041204@gmail.com', '$2y$10$SFrgioi9O1hOFLighEieze4lB5AWdb1iLBbbbXFn7sPEx7cHECYTG', '2025-10-02 17:53:00', 1),
(2, 'malarandave041204@gmail.com', '$2y$10$MHjzzLZUAuFK9DwvT/i1cuM67CKgdDNyqNUDAzi1ay9ikq4xf3DHu', '2025-10-02 17:56:50', 0),
(3, 'malarandave041204@gmail.com', '$2y$10$bFvlEv5SuDm3PjZQpq901up97hjp.60mlALVYxAYTTZsO1V6Zn33S', '2025-10-02 17:59:27', 1),
(4, 'malarandave041204@gmail.com', '$2y$10$YAoXoK/KWfe4KLi6jzXV7ODm6mfAPjLxC4ucXRO.uIoDwW4YsOt8y', '2025-10-02 18:03:06', 1),
(5, 'malarandave041204@gmail.com', '$2y$10$ge98zMNyqnNEw5ed00P38erbM9vII2xj3.aSATZ4JWZ5bsslAiWUG', '2025-10-02 18:06:12', 1),
(6, 'malarandave041204@gmail.com', '$2y$10$/cOegLLu4p45c9vUTFPgVut1Tkeno1h6drIQzBBswZmPjwrCo4ugy', '2025-12-03 08:32:40', 0);

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
(30, 'GC-222235', 'Elmar Reymond', 'Dela Cruz', 'Arroyo', '2003-09-15', 21, 'Male', 'College', 'year4', 'B', '09514785965', 'delacruzelmarr@gmail.com', 'Dorethea 2, Calubcob, Naic, Cavite. 4110', 'Emong', 'Dela Cruz', '09632586966', 'emong@gmail.com', 'Emong Dela Cruz', '09632586966', 'Father', 'AB+', 'Dog allergy', 'sakit sa ASO', '', '2025-09-04', 'Active'),
(31, 'GC-228923', 'Angel', 'Sarabosing', 'Miasco', '2003-02-28', 22, 'Female', 'College', 'year3', 'C', '09557893659', 'angelsarabosing123@gmail.com', 'SMV', 'Catherine', 'Sarabosing', '09123456789', 'Catherine@gmail.com', 'Catherine Sarabosing', '09123456789', 'Mother', 'AB+', '', '', '', '2025-10-03', 'Active'),
(40, 'GC-229999', 'ondoy', 'Olaguir', 'Olaguir', '1985-02-12', 40, 'Male', 'Elementary', '1', 'B', '09557893659', 'dwafdawghda@gmail.com', 'dwadwdwdwd', 'Christian', 'Guardian', '09123456789', 'adwdwa@gmail.com', 'ChirstianGuardian', '09123456789', 'Mother', 'B+', 'dwadw', '', '', '2025-10-07', 'Active');

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
(1, 'GC-220708', '2025-11-21 12:46:00', 'Fever', 'aguyyy may sakit hahah', 'Lapsed', 'dawdw', 'PIAMIS0001', '2025-11-21 04:47:14', '2025-11-25 06:53:20', NULL),
(2, 'GC-220708', '2025-11-21 12:59:00', 'Wound / Injury', 'nakagat ng langgam', 'Completed', 'kagat lang yan', 'PIAMIS0001', '2025-11-21 05:00:04', '2025-11-21 13:22:00', NULL),
(3, 'GC-220708', '2025-11-21 21:22:00', 'Cough / Cold', 'fireworks na ubo', 'Follow-up', NULL, 'PIAMIS0001', '2025-11-21 13:22:43', '2025-11-25 08:31:11', '2025-11-28 16:30:00'),
(4, 'GC-220723', '2025-11-21 23:45:00', 'Fever', 'kakaselpon daw sabi ni mama', 'Follow-up', NULL, 'PIAMIS0001', '2025-11-21 15:46:08', '2025-12-03 03:31:11', '2025-12-17 13:30:00'),
(5, 'GC-220708', '2025-11-22 09:54:00', 'Dizziness', 'dwadwa', 'Follow-up', NULL, 'PIAMIS0001', '2025-11-22 01:54:16', '2025-11-25 07:51:15', '2025-11-26 00:00:00'),
(6, 'GC-220708', '2025-11-22 10:09:00', 'Cough / Cold', 'dawjkdnkwa', 'Completed', 'abwudwad', 'PIAMIS0001', '2025-11-22 02:09:28', '2025-11-25 15:44:59', '2025-12-10 23:44:38'),
(7, 'GC-220708', '2025-11-22 10:35:00', 'Fever', 'Nag iinit hindi na pwede sa mundo ng mga Tao', 'Completed', 'Administered 12 tablets of Paracetamol 500mg. The patient\'s condition improved and they were discharged in stable condition.', 'PIAMIS0001', '2025-11-22 02:35:43', '2025-11-25 16:28:22', '2025-12-05 00:00:00'),
(8, 'GC-228923', '2025-11-25 16:00:00', 'Cough / Cold', 'I LOVE YOU haahha', 'Follow-up', NULL, 'PIAMIS0001', '2025-11-25 08:01:02', '2025-11-25 08:33:36', '2025-11-29 16:29:00'),
(9, 'GC-222235', '2025-11-25 16:35:00', 'Wound / Injury', 'nasaksak ng ballpen :0', 'Follow-up', NULL, 'PIAMIS0001', '2025-11-25 08:36:29', '2025-11-25 08:37:11', '2025-11-28 16:30:00'),
(10, 'GC-220708', '2025-12-03 13:40:00', 'Headache', ',awjdwdj', 'In Progress', NULL, 'PIAMIS0003', '2025-12-03 05:41:30', '2025-12-03 05:41:30', NULL),
(11, 'GC-220708', '2025-12-03 13:53:00', 'Cough / Cold', 'jgjb', 'In Progress', NULL, 'PIAMIS0003', '2025-12-03 05:53:19', '2025-12-03 05:53:19', NULL),
(12, 'GC-220071', '2025-12-05 00:04:00', 'Headache', 'dwadwd', 'In Progress', NULL, 'PIAMIS0003', '2025-12-04 16:04:31', '2025-12-04 16:04:31', NULL),
(13, 'GC-222235', '2025-12-05 00:04:00', 'Fever', 'dawdwad', 'In Progress', NULL, 'PIAMIS0003', '2025-12-04 16:04:41', '2025-12-04 16:04:41', NULL),
(14, 'GC-220708', '2025-12-12 20:05:00', 'Dizziness', 'ahwvns', 'Completed', 'awvvahsw', 'PIAMIS0003', '2025-12-12 12:05:52', '2025-12-12 12:17:57', '2025-12-25 16:20:00'),
(15, 'GC-220708', '2025-12-12 20:36:00', 'Fever', '', 'Follow-up', NULL, 'PIAMIS0003', '2025-12-12 12:38:17', '2025-12-12 15:45:31', '2025-12-12 01:45:00');

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
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `clinicpersonnel`
--
ALTER TABLE `clinicpersonnel`
  MODIFY `cp_ID` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `otp_request`
--
ALTER TABLE `otp_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `StudentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `studentcheckins`
--
ALTER TABLE `studentcheckins`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
