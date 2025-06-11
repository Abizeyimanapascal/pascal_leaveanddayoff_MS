-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2025 at 01:52 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `utab_leave_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `day_off_requests`
--

CREATE TABLE `day_off_requests` (
  `request_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `day_off_date` date NOT NULL,
  `day_off_reason` longtext DEFAULT NULL,
  `decision` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `decision_reason` text DEFAULT 'N/A',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `day_off_requests`
--

INSERT INTO `day_off_requests` (`request_id`, `staff_id`, `day_off_date`, `day_off_reason`, `decision`, `decision_reason`, `created_at`) VALUES
(17, 14, '2025-06-12', 'marriage', 'Rejected', 'N/A', '2025-06-10 09:53:03'),
(18, 16, '2025-06-11', 'visit', 'Rejected', 'N/A', '2025-06-10 10:53:54'),
(19, 14, '2025-06-14', 'my day off', 'Pending', 'N/A', '2025-06-10 13:18:16'),
(20, 16, '2025-06-12', 'wedding', 'Pending', 'N/A', '2025-06-10 13:20:57');

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `history_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `event_date` varchar(200) DEFAULT NULL,
  `data_snapshot` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data_snapshot`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`history_id`, `staff_id`, `event_type`, `event_date`, `data_snapshot`, `created_at`) VALUES
(19, 14, 'leave_decision', ' From 2025-06-08 To 2025-06-08', '{\"request_date\":\"2025-06-08 22:19:27\",\"event_date\":\" From 2025-06-08 To 2025-06-08\",\"days_requested\":13,\"reason\":null,\"request_type\":\"LEAVE\",\"tense\":\"has been\",\"decision\":\"Approved\",\"staff_id\":14}', '2025-06-10 09:11:19'),
(20, 14, 'day_off_req', '2025-06-12', '{\"request_date\":\"2025-06-10 11:53:03\",\"event_date\":\"2025-06-12\",\"days_requested\":\"1\",\"reason\":\"marriage\",\"request_type\":\"DAY OFF\",\"tense\":\"is now\",\"decision\":\"Pending\",\"staff_id\":14}', '2025-06-10 09:53:03'),
(22, 14, 'leave_req', NULL, '{\"request_date\":\"2025-06-10 12:03:52\",\"event_date\":\" From 2025-06-14 To 2025-06-27\",\"days_requested\":14,\"reason\":\"visit\",\"request_type\":\"LEAVE\",\"tense\":\"is now\",\"decision\":\"Pending\",\"staff_id\":14}', '2025-06-10 10:03:52'),
(23, 14, 'leave_req', NULL, '{\"request_date\":\"2025-06-10 12:05:15\",\"event_date\":\" From 2025-06-10 To 2025-06-20\",\"days_requested\":11,\"reason\":\"fg\",\"request_type\":\"LEAVE\",\"tense\":\"is now\",\"decision\":\"Pending\",\"staff_id\":14}', '2025-06-10 10:05:15'),
(24, 14, 'leave_req', NULL, '{\"request_date\":\"2025-06-10 12:06:33\",\"event_date\":\"2025-06-20\",\"days_requested\":8,\"reason\":\"dfg\",\"request_type\":\"LEAVE\",\"tense\":\"is now\",\"decision\":\"Pending\",\"staff_id\":14}', '2025-06-10 10:06:33'),
(25, 14, 'leave_req', ' From 2025-06-13 To 2025-06-20', '{\"request_date\":\"2025-06-10 12:07:42\",\"event_date\":\" From 2025-06-13 To 2025-06-20\",\"days_requested\":8,\"reason\":\"fg\",\"request_type\":\"LEAVE\",\"tense\":\"is now\",\"decision\":\"Pending\",\"staff_id\":14}', '2025-06-10 10:07:42'),
(26, 14, 'leave_req', ' From 2025-06-10 To 2025-06-25', '{\"request_date\":\"2025-06-10 12:39:08\",\"event_date\":\" From 2025-06-10 To 2025-06-25\",\"days_requested\":16,\"reason\":\"visit\",\"request_type\":\"LEAVE\",\"tense\":\"is now\",\"decision\":\"Pending\",\"staff_id\":14}', '2025-06-10 10:39:08'),
(27, 14, 'leave_decision', ' From 2025-06-10 To 2025-06-10', '{\"request_date\":\"2025-06-10 12:39:08\",\"event_date\":\" From 2025-06-10 To 2025-06-10\",\"days_requested\":16,\"reason\":null,\"request_type\":\"LEAVE\",\"tense\":\"has been\",\"decision\":\"Approved\",\"staff_id\":14}', '2025-06-10 10:40:41'),
(28, 16, 'day_off_req', '2025-06-11', '{\"request_date\":\"2025-06-10 12:53:54\",\"event_date\":\"2025-06-11\",\"days_requested\":\"1\",\"reason\":\"visit\",\"request_type\":\"DAY OFF\",\"tense\":\"is now\",\"decision\":\"Pending\",\"staff_id\":16}', '2025-06-10 10:53:54'),
(29, 16, 'leave_req', ' From 2025-06-12 To 2025-06-20', '{\"request_date\":\"2025-06-10 12:55:41\",\"event_date\":\" From 2025-06-12 To 2025-06-20\",\"days_requested\":9,\"reason\":\"marriage\",\"request_type\":\"LEAVE\",\"tense\":\"is now\",\"decision\":\"Pending\",\"staff_id\":16}', '2025-06-10 10:55:41'),
(30, 16, 'leave_decision', ' From 2025-06-12 To 2025-06-12', '{\"request_date\":\"2025-06-10 12:55:41\",\"event_date\":\" From 2025-06-12 To 2025-06-12\",\"days_requested\":9,\"reason\":null,\"request_type\":\"LEAVE\",\"tense\":\"has been\",\"decision\":\"Approved\",\"staff_id\":16}', '2025-06-10 10:58:28'),
(32, 16, 'day_off_decision', '2025-06-11', '{\"event_date\":\"2025-06-11\",\"days_requested\":\"1\",\"reason\":null,\"request_type\":\"DAY OFF\",\"tense\":\"has been\",\"decision\":\"Approved\",\"staff_id\":16}', '2025-06-10 11:04:25'),
(33, 14, 'day_off_decision', '2025-06-12', '{\"event_date\":\"2025-06-12\",\"days_requested\":\"1\",\"reason\":null,\"request_type\":\"DAY OFF\",\"tense\":\"has been\",\"decision\":\"Rejected\",\"staff_id\":14}', '2025-06-10 11:45:07'),
(34, 14, 'day_off_decision', '2025-06-12', '{\"event_date\":\"2025-06-12\",\"days_requested\":\"1\",\"reason\":\"marriage\",\"request_type\":\"DAY OFF\",\"tense\":\"has been\",\"decision\":\"Rejected\",\"staff_id\":14}', '2025-06-10 11:46:17'),
(35, 14, 'day_off_decision', '2025-06-12', '{\"event_date\":\"2025-06-12\",\"days_requested\":\"1\",\"reason\":\"marriage\",\"request_type\":\"DAY OFF\",\"tense\":\"has been\",\"decision\":\"Rejected\",\"staff_id\":14}', '2025-06-10 11:46:49'),
(36, 16, 'day_off_decision', '2025-06-11', '{\"event_date\":\"2025-06-11\",\"days_requested\":\"1\",\"reason\":\"visit\",\"request_type\":\"DAY OFF\",\"tense\":\"has been\",\"decision\":\"Rejected\",\"staff_id\":16}', '2025-06-10 11:48:53'),
(37, 16, 'leave_decision', ' From 2025-06-12 To 2025-06-12', '{\"request_date\":\"2025-06-10 12:55:41\",\"event_date\":\" From 2025-06-12 To 2025-06-12\",\"days_requested\":9,\"reason\":null,\"request_type\":\"LEAVE\",\"tense\":\"has been\",\"decision\":\"Approved\",\"staff_id\":16}', '2025-06-10 11:51:54'),
(38, 16, 'leave_decision', ' From 2025-06-12 To 2025-06-12', '{\"request_date\":\"2025-06-10 12:55:41\",\"event_date\":\" From 2025-06-12 To 2025-06-12\",\"days_requested\":9,\"reason\":\"marriage\",\"request_type\":\"LEAVE\",\"tense\":\"has been\",\"decision\":\"Approved\",\"staff_id\":16}', '2025-06-10 11:52:43'),
(39, 16, 'leave_decision', ' From 2025-06-12 To 2025-06-12', '{\"request_date\":\"2025-06-10 12:55:41\",\"event_date\":\" From 2025-06-12 To 2025-06-12\",\"days_requested\":9,\"reason\":\"marriage\",\"request_type\":\"LEAVE\",\"tense\":\"has been\",\"decision\":\"Rejected\",\"staff_id\":16}', '2025-06-10 11:55:39'),
(40, 16, 'leave_decision', ' From 2025-06-12 To 2025-06-12', '{\"request_date\":\"2025-06-10 12:55:41\",\"event_date\":\" From 2025-06-12 To 2025-06-12\",\"days_requested\":9,\"reason\":\"marriage\",\"request_type\":\"LEAVE\",\"tense\":\"has been\",\"decision\":\"Rejected\",\"staff_id\":16}', '2025-06-10 11:57:01'),
(41, 14, 'day_off_req', '2025-06-14', '{\"request_date\":\"2025-06-10 15:18:16\",\"event_date\":\"2025-06-14\",\"days_requested\":\"1\",\"reason\":\"my day off\",\"request_type\":\"DAY OFF\",\"tense\":\"is now\",\"decision\":\"Pending\",\"staff_id\":14}', '2025-06-10 13:18:16'),
(42, 16, 'day_off_req', '2025-06-12', '{\"request_date\":\"2025-06-10 15:20:57\",\"event_date\":\"2025-06-12\",\"days_requested\":\"1\",\"reason\":\"wedding\",\"request_type\":\"DAY OFF\",\"tense\":\"is now\",\"decision\":\"Pending\",\"staff_id\":16}', '2025-06-10 13:20:57'),
(43, 16, 'leave_req', ' From 2025-06-19 To 2025-06-29', '{\"request_date\":\"2025-06-10 22:11:33\",\"event_date\":\" From 2025-06-19 To 2025-06-29\",\"days_requested\":11,\"reason\":\"private\",\"request_type\":\"LEAVE\",\"tense\":\"is now\",\"decision\":\"Pending\",\"staff_id\":16}', '2025-06-10 20:11:33');

-- --------------------------------------------------------

--
-- Table structure for table `hr`
--

CREATE TABLE `hr` (
  `hr_id` int(11) NOT NULL,
  `hr_password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hr`
--

INSERT INTO `hr` (`hr_id`, `hr_password`) VALUES
(1, 'hr');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `request_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `leave_reason` text DEFAULT NULL,
  `days_requested` int(11) NOT NULL,
  `decision` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `decision_reason` text DEFAULT 'N/A',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`request_id`, `staff_id`, `start_date`, `end_date`, `leave_reason`, `days_requested`, `decision`, `decision_reason`, `created_at`) VALUES
(18, 14, '2025-06-10', '2025-06-25', 'visit', 16, 'Approved', 'N/A', '2025-06-10 10:39:08'),
(19, 16, '2025-06-12', '2025-06-20', 'marriage', 9, 'Rejected', 'N/A', '2025-06-10 10:55:41'),
(20, 16, '2025-06-19', '2025-06-29', 'private', 11, 'Pending', 'N/A', '2025-06-10 20:11:33');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `request_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `request_type` varchar(200) NOT NULL,
  `request_reason` longtext NOT NULL,
  `from` varchar(200) NOT NULL,
  `to` varchar(200) NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `days` int(11) NOT NULL,
  `hr_decision` varchar(200) NOT NULL DEFAULT 'Waiting',
  `decision_reason` longtext NOT NULL DEFAULT 'N/A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `other_name` varchar(100) NOT NULL,
  `job_title` varchar(200) NOT NULL,
  `job_description` longtext NOT NULL,
  `current_degree` varchar(200) NOT NULL,
  `graduation_year` varchar(100) NOT NULL,
  `years_of_experience` varchar(100) NOT NULL,
  `joining_date` varchar(100) NOT NULL,
  `nationality` varchar(200) NOT NULL,
  `location` varchar(200) NOT NULL,
  `nid` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `telephone` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `first_name`, `other_name`, `job_title`, `job_description`, `current_degree`, `graduation_year`, `years_of_experience`, `joining_date`, `nationality`, `location`, `nid`, `email`, `telephone`, `username`, `password`) VALUES
(14, 'UWASE', 'Brenda', 'Accountant', 'manages all account operations', 'CPA', '2023', '2', '2024-06-12', 'Rwanda', 'gikondo', '12345678907654', 'Brenda@gmail.com', '0787452014', 'brenda', '$2y$10$36Aa3FCQ4MIr3Q4vOHRPkOAi2jSuygNp6Dw6Mby9yD5HUhQtr7tzW'),
(16, 'ABIZEYIMANA', 'Pascal', 'Marketing', 'advertize and promotes all business operations', 'A0', '2023', '2', '2002-06-19', 'Rwanda', 'gikondo', '1200280243574150', 'abizeyimanapascal0@gmail.com', '0785806630', 'pascal', '$2y$10$kxj9v68jpOg1iGFadhJK3uhbAJhSloSLrqZ.6Ds.1eszsMdRY98jW');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `day_off_requests`
--
ALTER TABLE `day_off_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `hr`
--
ALTER TABLE `hr`
  ADD PRIMARY KEY (`hr_id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `day_off_requests`
--
ALTER TABLE `day_off_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `hr`
--
ALTER TABLE `hr`
  MODIFY `hr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `day_off_requests`
--
ALTER TABLE `day_off_requests`
  ADD CONSTRAINT `day_off_requests_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `history_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
