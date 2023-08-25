-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 12, 2023 at 03:26 AM
-- Server version: 10.5.20-MariaDB-cll-lve-log
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `appydeveloper_ardbili`
--

-- --------------------------------------------------------

--
-- Table structure for table `asa_client_data`
--

CREATE TABLE `asa_client_data` (
  `id` int(11) NOT NULL,
  `asa_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `po_number` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zipcode` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `start_date` varchar(20) DEFAULT NULL,
  `valid_date` varchar(20) DEFAULT NULL,
  `budget` decimal(20,2) NOT NULL DEFAULT 0.00,
  `internal_notes` text DEFAULT NULL,
  `pricing_mode` int(11) NOT NULL DEFAULT 1 COMMENT '1= calculated, 2= fixed',
  `markup_service` int(11) NOT NULL DEFAULT 0,
  `selected_tasks` varchar(255) DEFAULT NULL,
  `selected_costs` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asa_cost_data`
--

CREATE TABLE `asa_cost_data` (
  `id` int(11) NOT NULL,
  `asa_id` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `rate_type` varchar(255) DEFAULT NULL,
  `task_time` int(11) NOT NULL,
  `price` decimal(20,2) NOT NULL DEFAULT 0.00,
  `cost` decimal(20,2) NOT NULL DEFAULT 0.00,
  `tax` varchar(255) DEFAULT NULL,
  `single_price` decimal(20,2) NOT NULL DEFAULT 0.00,
  `markup_type` int(11) NOT NULL DEFAULT 0,
  `markup_rate` decimal(20,2) NOT NULL DEFAULT 0.00,
  `total` decimal(20,2) NOT NULL,
  `task_description` text DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `task_pos` int(11) NOT NULL DEFAULT 1,
  `deleteable` int(11) NOT NULL DEFAULT 0,
  `quote` int(11) NOT NULL DEFAULT 0,
  `quote_number` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asa_disciplines_data`
--

CREATE TABLE `asa_disciplines_data` (
  `id` int(11) NOT NULL,
  `dis_id` int(11) NOT NULL,
  `asa_id` int(11) NOT NULL,
  `hour` int(11) NOT NULL,
  `tasks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asa_quote`
--

CREATE TABLE `asa_quote` (
  `id` int(11) NOT NULL,
  `uID` int(11) NOT NULL,
  `quote_number` varchar(255) NOT NULL,
  `quote_count` int(11) NOT NULL DEFAULT 0,
  `revision_number` int(11) NOT NULL DEFAULT 0,
  `projectnumber` varchar(255) DEFAULT NULL,
  `projectname` varchar(255) DEFAULT NULL,
  `client` varchar(255) DEFAULT NULL,
  `clientcontact` varchar(255) DEFAULT NULL,
  `contactemails` text DEFAULT NULL,
  `clientprojectnumber` varchar(255) DEFAULT NULL,
  `client_address` text DEFAULT NULL,
  `template_id` int(11) NOT NULL DEFAULT 0,
  `po_number` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zipcode` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `start_date` varchar(20) DEFAULT NULL,
  `valid_date` varchar(20) DEFAULT NULL,
  `budget` decimal(20,2) NOT NULL DEFAULT 0.00,
  `internal_notes` text DEFAULT NULL,
  `pricing_mode` int(11) NOT NULL DEFAULT 1 COMMENT '1= calculated, 2= fixed',
  `markup_service` int(11) NOT NULL DEFAULT 0,
  `selected_tasks` varchar(255) DEFAULT NULL,
  `selected_costs` varchar(255) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT 0 COMMENT '1=proposal, 2=asa',
  `completed_discipline` int(11) NOT NULL DEFAULT 0,
  `send_to_client` int(11) NOT NULL DEFAULT 0,
  `asa_urgency_work` int(11) NOT NULL DEFAULT 0,
  `created_at` varchar(10) DEFAULT NULL,
  `tasks_array` text DEFAULT NULL,
  `costs_array` text DEFAULT NULL,
  `am_id` int(11) NOT NULL DEFAULT 0,
  `pdf_name` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asa_requests`
--

CREATE TABLE `asa_requests` (
  `req_id` int(10) NOT NULL,
  `type` int(11) NOT NULL DEFAULT 1 COMMENT '1= ASA, 2=Proposal',
  `pNumber` varchar(20) DEFAULT NULL,
  `asa_request_date` varchar(255) DEFAULT NULL,
  `asa_full_name` varchar(255) DEFAULT NULL,
  `asa_your_email` varchar(100) DEFAULT NULL,
  `asa_project_no` varchar(255) DEFAULT NULL,
  `pro_version` int(11) NOT NULL DEFAULT 0,
  `asa_project_name` varchar(255) DEFAULT NULL,
  `client_project_number` varchar(100) DEFAULT NULL,
  `asa_company_name` varchar(255) DEFAULT NULL,
  `company_contact` varchar(20) DEFAULT NULL,
  `asa_email` varchar(255) DEFAULT NULL,
  `client_address` text DEFAULT NULL,
  `asa_urgency_work` varchar(50) DEFAULT NULL COMMENT '1 = standard, 2 = urgent',
  `standard_services` text DEFAULT NULL,
  `standard_services_id` varchar(100) DEFAULT NULL,
  `service_description` longtext DEFAULT NULL,
  `mechanical_hours` varchar(255) DEFAULT NULL,
  `mechanical_description` longtext DEFAULT NULL,
  `asa_plumbing_hours` varchar(255) DEFAULT NULL,
  `asa_plumbing_scope` longtext DEFAULT NULL,
  `electrical_hours_required` varchar(255) DEFAULT NULL,
  `electrical_scope_description` longtext DEFAULT NULL,
  `dateofcompletion` int(11) NOT NULL DEFAULT 1,
  `request_due_date` varchar(255) DEFAULT NULL,
  `send_notification` text DEFAULT NULL,
  `discipline` varchar(255) DEFAULT NULL,
  `completed_discipline` varchar(255) DEFAULT NULL,
  `request_code` varchar(20) DEFAULT NULL COMMENT '0=pending,1=complete',
  `request_status` int(5) NOT NULL DEFAULT 0,
  `archive_status` int(5) NOT NULL DEFAULT 0,
  `archive_reason` text DEFAULT NULL,
  `send_to_client` int(5) NOT NULL DEFAULT 0 COMMENT '0=no, 1=yes',
  `additional_pm` varchar(100) DEFAULT NULL,
  `new_asa` int(11) NOT NULL DEFAULT 0,
  `am_id` int(11) NOT NULL DEFAULT 0,
  `pdf_name` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asa_tasks_data`
--

CREATE TABLE `asa_tasks_data` (
  `id` int(11) NOT NULL,
  `asa_id` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `rate_type` varchar(255) DEFAULT NULL,
  `task_time` int(11) NOT NULL,
  `price` decimal(20,2) NOT NULL DEFAULT 0.00,
  `cost` decimal(20,2) NOT NULL DEFAULT 0.00,
  `tax` varchar(255) DEFAULT NULL,
  `single_price` decimal(20,2) NOT NULL DEFAULT 0.00,
  `markup_rate` decimal(20,2) NOT NULL DEFAULT 0.00,
  `markup_type` int(11) NOT NULL DEFAULT 0,
  `total` decimal(20,2) NOT NULL,
  `task_description` text DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `task_pos` int(11) NOT NULL DEFAULT 1,
  `deleteable` int(11) NOT NULL DEFAULT 0,
  `quote` int(11) NOT NULL DEFAULT 0,
  `quote_number` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `costs`
--

CREATE TABLE `costs` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(20,2) NOT NULL,
  `rate_type` varchar(255) DEFAULT NULL,
  `est_hrs` int(11) NOT NULL DEFAULT 0,
  `unit_price` decimal(20,2) NOT NULL DEFAULT 0.00,
  `markup` int(11) NOT NULL DEFAULT 0,
  `markup_type` int(11) NOT NULL DEFAULT 0,
  `markup_price` decimal(20,2) NOT NULL DEFAULT 0.00,
  `markup_cost` decimal(20,2) NOT NULL DEFAULT 0.00,
  `tax` varchar(255) DEFAULT NULL,
  `single_price` decimal(20,2) NOT NULL DEFAULT 0.00,
  `desription` text DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `internal_notes` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `costs`
--

INSERT INTO `costs` (`id`, `name`, `price`, `rate_type`, `est_hrs`, `unit_price`, `markup`, `markup_type`, `markup_price`, `markup_cost`, `tax`, `single_price`, `desription`, `label`, `internal_notes`, `status`) VALUES
(5, 'new cost 2', 366.00, 'Hourly', 2, 387.96, 1, 1, 0.00, 6.00, NULL, 183.00, 'mmm', NULL, NULL, 1),
(6, 'new cost 2', 33.00, 'Hourly', 2, 33.00, 0, 0, 0.00, 0.00, NULL, 16.50, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `did` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `department_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`did`, `email`, `department_name`) VALUES
(1, 'shahabsafdar01@gmail.com', 'Admin'),
(5, 'Ali@Ardebilieng.com', 'Account Manager'),
(7, 'Babak@ArdebiliEng.com', 'Account Manager'),
(8, 'matthew@ardebilieng.com', 'Account Manager'),
(10, 'Babak@ArdebiliEng.com', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `disciplines`
--

CREATE TABLE `disciplines` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(20,2) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `disciplines`
--

INSERT INTO `disciplines` (`id`, `name`, `price`, `status`) VALUES
(3, 'Electrical Engineering Design/CDs', 180.00, 1),
(5, 'Mechanical', 250.00, 1),
(7, 'Plumbing', 175.00, 1),
(10, 'Mechanical/Plumbing', 200.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `project_complete_detail`
--

CREATE TABLE `project_complete_detail` (
  `id` int(11) NOT NULL,
  `description` longtext DEFAULT NULL,
  `p_number` int(100) DEFAULT NULL,
  `p_name` varchar(50) DEFAULT NULL,
  `p_email` varchar(150) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `text_area` varchar(255) DEFAULT NULL,
  `reviews` varchar(255) DEFAULT 'N/A',
  `comments` longtext DEFAULT 'N/A',
  `status` int(2) DEFAULT NULL COMMENT 'Feedback Added=1, \r\nFeedback Null=0',
  `created_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_emails`
--

CREATE TABLE `project_emails` (
  `id` int(11) NOT NULL,
  `project_id` int(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_emails`
--

INSERT INTO `project_emails` (`id`, `project_id`, `email`) VALUES
(3, 11, 'bniroomand9@gmail.com'),
(4, 11, 'nirohairstudio@gmail.com'),
(5, 11, 'info@niromedia.com');

-- --------------------------------------------------------

--
-- Table structure for table `proposal_templates`
--

CREATE TABLE `proposal_templates` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proposal_templates`
--

INSERT INTO `proposal_templates` (`id`, `name`, `description`, `date_created`) VALUES
(6, 'New Template for Ground up Office', '<p>ground up project for Office services include MPE&nbsp;</p><ul><li>one</li><li>two</li><li>three</li></ul><p>then paragraph</p><p>service exclude</p><ul><li>one ex</li><li>two ex</li><li>three ex</li></ul><p>thank you, come again</p>', '2023-07-07 00:16:50'),
(14, 'New Template for TI Multifamily', '<p>Information about MPE services for a TI of multifamily is here</p><ul><li>one</li><li>two</li><li>three</li></ul><p>service exclusions</p><ul><li>one</li><li>two</li><li>three</li></ul><p>then we give you the CDs and you will like them</p><p>You\'re welcome.</p>', '2023-07-07 00:27:24');

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `qid` int(10) NOT NULL,
  `asa_id` int(11) NOT NULL,
  `template_id` varchar(20) DEFAULT NULL,
  `name` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `budget` varchar(10) DEFAULT NULL,
  `date` varchar(25) DEFAULT NULL,
  `validity` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `explanation` longtext DEFAULT NULL,
  `created_date` varchar(25) DEFAULT NULL,
  `is_signed` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotation_services`
--

CREATE TABLE `quotation_services` (
  `qsid` int(10) NOT NULL,
  `is_checked` varchar(11) NOT NULL DEFAULT '0',
  `quotation_id` varchar(20) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `time` varchar(50) DEFAULT NULL,
  `cost` varchar(50) DEFAULT NULL,
  `calculatedPrice` int(11) NOT NULL,
  `created_date` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `role` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `role`) VALUES
(2, 'E'),
(3, 'Admin'),
(4, 'Account Manager'),
(5, 'Management'),
(6, 'MP'),
(8, 'Finance'),
(9, 'HR'),
(10, 'Site Manager'),
(11, 'Architecture');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(20,2) NOT NULL,
  `rate_type` varchar(255) DEFAULT NULL,
  `est_hrs` int(11) NOT NULL DEFAULT 0,
  `unit_price` decimal(20,2) NOT NULL DEFAULT 0.00,
  `markup` int(11) NOT NULL DEFAULT 0,
  `markup_type` int(11) NOT NULL DEFAULT 0,
  `markup_price` decimal(20,2) NOT NULL DEFAULT 0.00,
  `markup_cost` decimal(20,2) NOT NULL DEFAULT 0.00,
  `tax` varchar(255) DEFAULT NULL,
  `single_price` decimal(20,2) NOT NULL DEFAULT 0.00,
  `desription` text DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `internal_notes` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `price`, `rate_type`, `est_hrs`, `unit_price`, `markup`, `markup_type`, `markup_price`, `markup_cost`, `tax`, `single_price`, `desription`, `label`, `internal_notes`, `status`) VALUES
(11, 'Submittal Reviews HVAC', 360.00, 'Hourly', 1, 442.80, 1, 2, 50.00, 0.00, '8', 360.00, NULL, NULL, NULL, 1),
(12, 'Submittal Reviews Plumbing Fixtures', 350.00, 'Hourly', 1, 378.00, 0, 0, 0.00, 0.00, '8', 350.00, NULL, NULL, NULL, 1),
(18, 'test', 39.00, 'Hourly', 33, 80.34, 1, 1, 100.00, 2.00, '3', 1.18, NULL, NULL, NULL, 1),
(19, 'new new', 350.00, 'Hourly', 1, 364.00, 0, 0, 0.00, 0.00, '4', 350.00, NULL, NULL, NULL, 1),
(20, 'service', 500.00, 'Hourly', 1, 600.00, 1, 1, 20.00, 100.00, NULL, 500.00, NULL, NULL, NULL, 1),
(21, 'Submittals HVAC', 350.00, 'Hourly', 1, 350.00, 0, 0, 0.00, 0.00, NULL, 350.00, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sign_asa_quote`
--

CREATE TABLE `sign_asa_quote` (
  `id` int(11) NOT NULL,
  `asa_id` int(11) NOT NULL,
  `asa_quote_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `sign_path` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `action_password` varchar(255) DEFAULT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `mechanical_price_hr` varchar(5) DEFAULT NULL,
  `plumbing_price_hr` varchar(5) DEFAULT NULL,
  `electrical_price_hr` varchar(5) DEFAULT NULL,
  `review_cost` varchar(8) DEFAULT NULL,
  `standard_service_cost` varchar(20) DEFAULT NULL,
  `apikey` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `action_password`, `site_name`, `logo_path`, `mechanical_price_hr`, `plumbing_price_hr`, `electrical_price_hr`, `review_cost`, `standard_service_cost`, `apikey`) VALUES
(1, 'Ardebili1', NULL, NULL, '175', '175', '175', '100', '350', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` varchar(30) DEFAULT NULL,
  `lname` varchar(30) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `password` varchar(150) DEFAULT NULL,
  `user_type` int(1) DEFAULT 2,
  `is_active` int(1) NOT NULL DEFAULT 0,
  `is_event_approved` int(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `img_path` varchar(100) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `email`, `department`, `password`, `user_type`, `is_active`, `is_event_approved`, `created_at`, `img_path`, `token`) VALUES
(51, 'Babak', 'Super Admin', 'babak@admin.com', NULL, '63157ec36ea28fc3c65ddcd4067e6b11', 1, 1, 0, '2023-05-31 04:00:00', NULL, 'add4723307ddacd9bea22e11dc22db40'),
(52, 'Admin', 'Admin', 'admin@admin.com', NULL, '25d55ad283aa400af464c76d713c07ad', 1, 1, 0, '2023-06-23 04:00:00', NULL, 'be384840e5fb3639ee6565a562651870'),
(53, 'Admin 2', 'Khan', 'admin2@admin.com', NULL, '25d55ad283aa400af464c76d713c07ad', 1, 1, 0, '2023-05-21 04:00:00', NULL, '067d53a3fdef19a89eb48bef234d2df3'),
(55, 'Haris', 'Khan', 'haris@novaeno.com', NULL, '25d55ad283aa400af464c76d713c07ad', 1, 1, 0, '2023-05-21 04:00:00', NULL, '3ebec2274acbe19f5dc2466c1562392f'),
(56, 'ammartest', 'afzal', 'test@gmail.com', NULL, '25d55ad283aa400af464c76d713c07ad', 1, 1, 0, '2023-06-25 04:00:00', NULL, '675e7980ce4d0652317e832400a65929'),
(58, 'Babak', 'testin', 'babak2@admin.com', NULL, '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, '2023-07-06 04:00:00', NULL, 'cfc09dc437968985deed76fc2a694808'),
(59, 'Babak', 'Testing 2', 'testing@admin.com', NULL, '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, '2023-07-06 04:00:00', NULL, 'c7bd87529e39c2b8f82c5691564cc01b'),
(60, 'Babak', 'Niroomand', 'Babak@ArdebiliEng.com', NULL, '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, '2023-07-07 04:00:00', NULL, '1618a0b6b57ae9e5800824bb2cd492d6'),
(61, 'Electrical', 'Babak', 'italy2000@gmail.com', NULL, 'e10adc3949ba59abbe56e057f20f883e', 1, 1, 0, '2023-07-11 04:00:00', NULL, NULL),
(62, 'Mechanical', 'Babak', 'bniroomand9@gmail.com', NULL, 'e10adc3949ba59abbe56e057f20f883e', 1, 1, 0, '2023-07-11 04:00:00', NULL, NULL),
(63, 'Matt', 'Fabros', 'Matthew@Ardebilieng.com', NULL, '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, '2023-07-12 04:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_departments`
--

CREATE TABLE `user_departments` (
  `id` int(11) NOT NULL,
  `user_id` int(50) DEFAULT NULL,
  `departement` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_departments`
--

INSERT INTO `user_departments` (`id`, `user_id`, `departement`) VALUES
(90, 16, '5'),
(91, 16, '3'),
(92, 16, '4'),
(105, 47, '4'),
(106, 47, '5'),
(107, 1, '3'),
(111, 43, '5'),
(112, 44, '2'),
(120, 18, '2'),
(122, 11, '2'),
(123, 14, '6'),
(124, 12, '6'),
(125, 15, '2'),
(126, 19, '2'),
(127, 21, '6'),
(128, 22, '6'),
(129, 23, '2'),
(130, 27, '6'),
(132, 38, '2'),
(133, 38, '5'),
(138, 42, '5'),
(139, 41, '8'),
(140, 45, '11'),
(141, 46, '10'),
(145, 48, '4'),
(146, 48, '5'),
(147, 17, '2'),
(149, 39, '5'),
(150, 39, '3'),
(151, 50, '3'),
(154, 32, '3'),
(155, 32, '4'),
(156, 32, '5'),
(158, 37, '5'),
(159, 37, '6'),
(160, 53, '10'),
(161, 53, '4'),
(162, 53, '3'),
(163, 54, '6'),
(164, 55, '6'),
(169, 57, '3'),
(170, 51, '3'),
(171, 40, '5'),
(172, 40, '3'),
(173, 29, '4'),
(177, 52, '3'),
(187, 56, '6'),
(189, 59, '6'),
(190, 58, '2'),
(191, 58, '4'),
(192, 60, '3'),
(193, 61, '2'),
(194, 62, '6'),
(195, 63, '4');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `asa_client_data`
--
ALTER TABLE `asa_client_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `asa_cost_data`
--
ALTER TABLE `asa_cost_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `asa_disciplines_data`
--
ALTER TABLE `asa_disciplines_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `asa_quote`
--
ALTER TABLE `asa_quote`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `asa_requests`
--
ALTER TABLE `asa_requests`
  ADD PRIMARY KEY (`req_id`);

--
-- Indexes for table `asa_tasks_data`
--
ALTER TABLE `asa_tasks_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `costs`
--
ALTER TABLE `costs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`did`);

--
-- Indexes for table `disciplines`
--
ALTER TABLE `disciplines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_complete_detail`
--
ALTER TABLE `project_complete_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_emails`
--
ALTER TABLE `project_emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proposal_templates`
--
ALTER TABLE `proposal_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`qid`);

--
-- Indexes for table `quotation_services`
--
ALTER TABLE `quotation_services`
  ADD PRIMARY KEY (`qsid`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sign_asa_quote`
--
ALTER TABLE `sign_asa_quote`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_departments`
--
ALTER TABLE `user_departments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `asa_client_data`
--
ALTER TABLE `asa_client_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asa_cost_data`
--
ALTER TABLE `asa_cost_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asa_disciplines_data`
--
ALTER TABLE `asa_disciplines_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asa_quote`
--
ALTER TABLE `asa_quote`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asa_requests`
--
ALTER TABLE `asa_requests`
  MODIFY `req_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asa_tasks_data`
--
ALTER TABLE `asa_tasks_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `costs`
--
ALTER TABLE `costs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `did` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `disciplines`
--
ALTER TABLE `disciplines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `project_complete_detail`
--
ALTER TABLE `project_complete_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `project_emails`
--
ALTER TABLE `project_emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `proposal_templates`
--
ALTER TABLE `proposal_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `qid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotation_services`
--
ALTER TABLE `quotation_services`
  MODIFY `qsid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `sign_asa_quote`
--
ALTER TABLE `sign_asa_quote`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `user_departments`
--
ALTER TABLE `user_departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
