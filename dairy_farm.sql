-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2025 at 06:08 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dairy_farm`
--

-- --------------------------------------------------------

--
-- Table structure for table `breeds`
--

CREATE TABLE `breeds` (
  `id` int(11) NOT NULL,
  `breed_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `breeds`
--

INSERT INTO `breeds` (`id`, `breed_name`) VALUES
(5, 'Ayrshire'),
(4, 'Brown Swiss'),
(3, 'Guernsey'),
(1, 'Holstein'),
(2, 'Jersey');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `submitted_at`) VALUES
(1, 'Malcom kipkemoi', 'mrono2637@gmail.com', 'Milk production', '<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">\r\n                            <strong>Success!</strong> Your message has been submitted successfully.\r\n                            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>\r\n                          </div>\'', '2025-04-18 15:50:29');

-- --------------------------------------------------------

--
-- Table structure for table `cows`
--

CREATE TABLE `cows` (
  `id` int(11) NOT NULL,
  `cow_id` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `tag_number` varchar(50) DEFAULT NULL,
  `breed` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `health_status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cows`
--

INSERT INTO `cows` (`id`, `cow_id`, `name`, `tag_number`, `breed`, `date_of_birth`, `age`, `health_status`) VALUES
(16, '1', 'Bessie', 'TAG1001', 'Holstein', '2018-03-15', 6, 'Healthy'),
(17, '2', 'Daisy', 'TAG1002', 'Jersey', '2019-01-20', 5, 'Healthy'),
(18, '3', 'Buttercup', 'TAG1003', 'Guernsey', '2020-05-10', 4, 'Healthy'),
(19, '4', 'Rosie', 'TAG1004', 'Holstein', '2017-11-05', 7, 'Healthy'),
(20, '5', 'Maggie', 'TAG1005', 'Brown Swiss', '2019-08-12', 5, 'Healthy'),
(21, '6', 'Bella', 'TAG1006', 'Ayrshire', '2020-02-28', 4, 'Healthy'),
(22, '7', 'Luna', 'TAG1007', 'Holstein', '2021-04-17', 3, 'Healthy'),
(23, '8', 'Stella', 'TAG1008', 'Jersey', '2020-09-22', 4, 'Healthy'),
(24, '9', 'Molly', 'TAG1009', 'Guernsey', '2019-12-01', 5, 'Healthy'),
(25, '10', 'Coco', 'TAG1010', 'Holstein', '2021-01-30', 3, 'Healthy'),
(26, '11', 'Ginger', 'TAG1011', 'Brown Swiss', '2018-07-14', 6, 'Recovering from mastitis'),
(27, '12', 'Dottie', 'TAG1012', 'Ayrshire', '2019-06-18', 5, 'Lameness - under treatment'),
(28, '13', 'Penny', 'TAG1013', 'Holstein', '2020-03-25', 4, 'Pregnant - due in 2 months'),
(29, '14', 'Mabel', 'TAG1014', 'Jersey', '2015-10-10', 9, 'Senior - reduced production'),
(30, '15', 'Bertha', 'TAG1015', 'Holstein', '2014-05-05', 10, 'Retired - not milking'),
(31, '16', 'Birir', NULL, 'Brown Swiss', NULL, 5, 'Injured'),
(32, '17', 'wanat', NULL, 'Guernsey', NULL, 2, 'Healthy');

-- --------------------------------------------------------

--
-- Table structure for table `feed_records`
--

CREATE TABLE `feed_records` (
  `id` int(11) NOT NULL,
  `cow_id` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `feed_type` varchar(255) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feed_records`
--

INSERT INTO `feed_records` (`id`, `cow_id`, `date`, `feed_type`, `quantity`, `unit`) VALUES
(1, '1', '2024-10-07', 'Alfalfa Hay', 6.00, 'kg'),
(2, '1', '2024-10-07', 'Dairy Grain Mix', 3.00, 'kg'),
(3, '1', '2024-11-06', 'Alfalfa Hay', 5.50, 'kg'),
(4, '1', '2024-11-06', 'Protein Supplement', 1.50, 'kg'),
(5, '1', '2024-12-06', 'Mixed Hay', 7.00, 'kg'),
(6, '1', '2024-12-06', 'Energy Grain', 3.50, 'kg'),
(7, '1', '2025-01-05', 'Alfalfa Hay', 6.50, 'kg'),
(8, '1', '2025-01-05', 'Mineral Mix', 0.50, 'kg'),
(9, '1', '2025-02-04', 'Grass Hay', 5.00, 'kg'),
(10, '1', '2025-02-04', 'Dairy Grain Mix', 2.80, 'kg'),
(11, '1', '2025-03-06', 'Alfalfa Hay', 6.20, 'kg'),
(12, '1', '2025-03-06', 'Protein Supplement', 1.80, 'kg'),
(13, '1', '2025-03-21', 'Mixed Hay', 6.80, 'kg'),
(14, '1', '2025-03-21', 'Energy Grain', 3.20, 'kg'),
(15, '1', '2025-04-05', 'Alfalfa Hay', 5.80, 'kg'),
(16, '1', '2025-04-05', 'Mineral Mix', 0.60, 'kg'),
(17, '2', '2024-10-17', 'Grass Hay', 4.50, 'kg'),
(18, '2', '2024-10-17', 'Dairy Grain Mix', 2.00, 'kg'),
(19, '2', '2024-11-16', 'Alfalfa Hay', 5.00, 'kg'),
(20, '2', '2024-11-16', 'Protein Supplement', 1.00, 'kg'),
(21, '2', '2024-12-16', 'Mixed Hay', 5.50, 'kg'),
(22, '2', '2024-12-16', 'Energy Grain', 2.50, 'kg'),
(23, '2', '2025-01-15', 'Grass Hay', 4.80, 'kg'),
(24, '2', '2025-01-15', 'Mineral Mix', 0.40, 'kg'),
(25, '2', '2025-02-14', 'Alfalfa Hay', 5.20, 'kg'),
(26, '2', '2025-02-14', 'Dairy Grain Mix', 2.20, 'kg'),
(27, '2', '2025-03-16', 'Grass Hay', 4.60, 'kg'),
(28, '2', '2025-03-16', 'Protein Supplement', 1.20, 'kg'),
(29, '2', '2025-03-26', 'Mixed Hay', 5.30, 'kg'),
(30, '2', '2025-03-26', 'Energy Grain', 2.30, 'kg'),
(31, '2', '2025-04-05', 'Grass Hay', 4.70, 'kg'),
(32, '2', '2025-04-05', 'Mineral Mix', 0.50, 'kg'),
(33, '3', '2024-10-27', 'Alfalfa Hay', 5.00, 'kg'),
(34, '3', '2024-10-27', 'Medicated Feed', 2.50, 'kg'),
(35, '3', '2024-11-26', 'Grass Hay', 4.50, 'kg'),
(36, '3', '2024-11-26', 'Dairy Grain Mix', 2.00, 'kg'),
(37, '3', '2024-12-26', 'Alfalfa Hay', 5.50, 'kg'),
(38, '3', '2024-12-26', 'Protein Supplement', 1.50, 'kg'),
(39, '3', '2025-01-25', 'Mixed Hay', 5.00, 'kg'),
(40, '3', '2025-01-25', 'Energy Grain', 2.20, 'kg'),
(41, '3', '2025-02-24', 'Alfalfa Hay', 5.80, 'kg'),
(42, '3', '2025-02-24', 'Mineral Mix', 0.50, 'kg'),
(43, '3', '2025-03-16', 'Grass Hay', 4.80, 'kg'),
(44, '3', '2025-03-16', 'Dairy Grain Mix', 2.30, 'kg'),
(45, '3', '2025-03-26', 'Alfalfa Hay', 5.30, 'kg'),
(46, '3', '2025-03-26', 'Protein Supplement', 1.30, 'kg'),
(47, '3', '2025-04-05', 'Recovery Formula', 3.00, 'kg'),
(48, '3', '2025-04-05', 'Mineral Mix', 0.60, 'kg'),
(54, '16', '2025-04-09', 'Grain', 10.00, 'kg');

-- --------------------------------------------------------

--
-- Table structure for table `health_records`
--

CREATE TABLE `health_records` (
  `id` int(11) NOT NULL,
  `cow_id` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `diagnosis` text NOT NULL,
  `treatment` text DEFAULT NULL,
  `veterinarian` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `health_records`
--

INSERT INTO `health_records` (`id`, `cow_id`, `date`, `diagnosis`, `treatment`, `veterinarian`) VALUES
(35, '1', '2024-10-07', 'Annual checkup', 'Vaccinations updated', 'Dr. Johnson'),
(36, '1', '2024-11-06', 'Hoof trimming', 'Routine hoof care', 'Dr. Smith'),
(37, '1', '2024-12-06', 'Weight check', 'Nutrition plan adjusted', 'Dr. Wilson'),
(38, '1', '2025-01-05', 'Pregnancy check', 'Confirmed pregnant - 2 months', 'Dr. Johnson'),
(39, '1', '2025-02-04', 'Routine checkup', 'All parameters normal', 'Dr. Smith'),
(40, '1', '2025-02-19', 'Mild lameness', 'Anti-inflammatory given', 'Dr. Wilson'),
(41, '1', '2025-03-06', 'Follow-up', 'Lameness resolved', 'Dr. Johnson'),
(42, '1', '2025-03-21', 'Pre-calving check', 'All normal, due in 3 weeks', 'Dr. Smith'),
(43, '2', '2024-10-17', 'Skin irritation', 'Antiseptic spray applied', 'Dr. Wilson'),
(44, '2', '2024-11-16', 'Weight loss', 'Increased feed ration', 'Dr. Johnson'),
(45, '2', '2024-12-16', 'Mild mastitis', 'Intramammary antibiotics', 'Dr. Smith'),
(46, '2', '2024-12-26', 'Mastitis follow-up', 'Cleared, back to normal', 'Dr. Wilson'),
(47, '2', '2025-01-15', 'Hoof abscess', 'Drained and bandaged', 'Dr. Johnson'),
(48, '2', '2025-02-04', 'Vaccination', 'Annual boosters given', 'Dr. Smith'),
(49, '2', '2025-02-24', 'Weight check', 'Gaining appropriately', 'Dr. Wilson'),
(50, '2', '2025-03-16', 'Routine checkup', 'All parameters normal', 'Dr. Johnson'),
(51, '3', '2024-10-27', 'Severe mastitis', 'Aggressive antibiotic treatment', 'Dr. Smith'),
(52, '3', '2024-11-01', 'Mastitis follow-up', 'Improving, continue treatment', 'Dr. Wilson'),
(53, '3', '2024-11-06', 'Mastitis resolved', 'Switch to maintenance care', 'Dr. Johnson'),
(54, '3', '2024-12-06', 'Weight check', 'Nutrition plan adjusted', 'Dr. Smith'),
(55, '3', '2025-01-05', 'Hair loss', 'Skin scrape - fungal treatment', 'Dr. Wilson'),
(56, '3', '2025-01-20', 'Fungal follow-up', 'Improving, continue treatment', 'Dr. Johnson'),
(57, '3', '2025-02-04', 'Full recovery', 'All treatments completed', 'Dr. Smith'),
(58, '3', '2025-03-06', 'Routine checkup', 'All parameters normal', 'Dr. Wilson'),
(59, '3', '2025-03-21', 'Vaccination', 'Annual boosters given', 'Dr. Johnson'),
(60, '3', '2025-04-05', 'General health', 'Excellent condition', 'Dr. Smith'),
(61, '4', '2024-10-12', 'Arthritis', 'Joint supplements prescribed', 'Dr. Wilson'),
(62, '4', '2024-11-06', 'Dental check', 'Teeth floated', 'Dr. Johnson'),
(63, '4', '2024-12-01', 'Weight loss', 'Senior feed formula', 'Dr. Smith'),
(64, '4', '2024-12-26', 'Arthritis follow-up', 'Improved mobility', 'Dr. Wilson'),
(65, '4', '2025-01-20', 'Skin tags', 'Removed 3 benign tags', 'Dr. Johnson'),
(66, '4', '2025-02-14', 'Routine checkup', 'Stable condition', 'Dr. Smith'),
(67, '4', '2025-03-11', 'Vaccination', 'Annual boosters given', 'Dr. Wilson'),
(68, '4', '2025-04-05', 'Geriatric care', 'Comfort measures in place', 'Dr. Johnson'),
(70, '17', '2025-04-09', 'fever', 'not good', 'Amos'),
(71, '9', '2025-04-27', 'Mild cold', 'you know', 'Vincent');

-- --------------------------------------------------------

--
-- Table structure for table `milk_production`
--

CREATE TABLE `milk_production` (
  `id` int(11) NOT NULL,
  `cow_id` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `milk_yield` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `milk_production`
--

INSERT INTO `milk_production` (`id`, `cow_id`, `date`, `milk_yield`, `notes`) VALUES
(101, '1', '2025-03-06', 26.80, 'Morning milking - day 1'),
(102, '1', '2025-03-06', 24.50, 'Evening milking - day 1'),
(103, '1', '2025-03-07', 27.20, 'Morning milking - day 2'),
(104, '1', '2025-03-07', 25.10, 'Evening milking - day 2'),
(105, '1', '2025-03-08', 25.90, 'Morning milking - day 3'),
(106, '1', '2025-03-08', 23.70, 'Evening milking - day 3'),
(107, '1', '2025-03-09', 26.50, 'Morning milking - day 4'),
(108, '1', '2025-03-09', 24.30, 'Evening milking - day 4'),
(109, '1', '2025-03-10', 28.10, 'Morning milking - day 5'),
(110, '1', '2025-03-10', 25.80, 'Evening milking - day 5'),
(111, '1', '2025-03-11', 27.40, 'Morning milking - day 6'),
(112, '1', '2025-03-11', 25.20, 'Evening milking - day 6'),
(113, '1', '2025-03-12', 26.00, 'Morning milking - day 7'),
(114, '1', '2025-03-12', 23.90, 'Evening milking - day 7'),
(115, '1', '2025-03-13', 27.70, 'Morning milking - day 8'),
(116, '1', '2025-03-13', 25.50, 'Evening milking - day 8'),
(117, '1', '2025-03-14', 26.30, 'Morning milking - day 9'),
(118, '1', '2025-03-14', 24.10, 'Evening milking - day 9'),
(119, '1', '2025-03-15', 25.80, 'Morning milking - day 10'),
(120, '1', '2025-03-15', 23.60, 'Evening milking - day 10'),
(121, '2', '2025-03-06', 19.20, 'Morning milking - day 1'),
(122, '2', '2025-03-06', 17.50, 'Evening milking - day 1'),
(123, '2', '2025-03-07', 20.10, 'Morning milking - day 2'),
(124, '2', '2025-03-07', 18.30, 'Evening milking - day 2'),
(125, '2', '2025-03-08', 18.70, 'Morning milking - day 3'),
(126, '2', '2025-03-08', 16.90, 'Evening milking - day 3'),
(127, '2', '2025-03-09', 19.50, 'Morning milking - day 4'),
(128, '2', '2025-03-09', 17.80, 'Evening milking - day 4'),
(129, '2', '2025-03-10', 20.30, 'Morning milking - day 5'),
(130, '2', '2025-03-10', 18.60, 'Evening milking - day 5'),
(131, '2', '2025-03-11', 19.80, 'Morning milking - day 6'),
(132, '2', '2025-03-11', 18.10, 'Evening milking - day 6'),
(133, '2', '2025-03-12', 18.20, 'Morning milking - day 7'),
(134, '2', '2025-03-12', 16.50, 'Evening milking - day 7'),
(135, '2', '2025-03-13', 19.90, 'Morning milking - day 8'),
(136, '2', '2025-03-13', 18.20, 'Evening milking - day 8'),
(137, '2', '2025-03-14', 19.30, 'Morning milking - day 9'),
(138, '2', '2025-03-14', 17.60, 'Evening milking - day 9'),
(139, '2', '2025-03-15', 18.90, 'Morning milking - day 10'),
(140, '2', '2025-03-15', 17.20, 'Evening milking - day 10'),
(141, '3', '2025-03-06', 21.50, 'Morning milking - day 1'),
(142, '3', '2025-03-06', 19.80, 'Evening milking - day 1'),
(143, '3', '2025-03-07', 22.10, 'Morning milking - day 2'),
(144, '3', '2025-03-07', 20.30, 'Evening milking - day 2'),
(145, '3', '2025-03-08', 20.70, 'Morning milking - day 3'),
(146, '3', '2025-03-08', 18.90, 'Evening milking - day 3'),
(147, '3', '2025-03-09', 21.50, 'Morning milking - day 4'),
(148, '3', '2025-03-09', 19.80, 'Evening milking - day 4'),
(149, '3', '2025-03-10', 22.30, 'Morning milking - day 5'),
(150, '3', '2025-03-10', 20.60, 'Evening milking - day 5'),
(151, '3', '2025-03-11', 21.80, 'Morning milking - day 6'),
(152, '3', '2025-03-11', 20.10, 'Evening milking - day 6'),
(153, '3', '2025-03-12', 20.20, 'Morning milking - day 7'),
(154, '3', '2025-03-12', 18.50, 'Evening milking - day 7'),
(155, '3', '2025-03-13', 21.90, 'Morning milking - day 8'),
(156, '3', '2025-03-13', 20.20, 'Evening milking - day 8'),
(157, '3', '2025-03-14', 21.30, 'Morning milking - day 9'),
(158, '3', '2025-03-14', 19.60, 'Evening milking - day 9'),
(159, '3', '2025-03-15', 20.90, 'Morning milking - day 10'),
(160, '3', '2025-03-15', 19.20, 'Evening milking - day 10'),
(161, '4', '2025-03-06', 22.50, 'Morning milking - day 1'),
(162, '4', '2025-03-06', 20.30, 'Evening milking - day 1'),
(163, '4', '2025-03-07', 23.10, 'Morning milking - day 2'),
(164, '4', '2025-03-07', 21.00, 'Evening milking - day 2'),
(165, '4', '2025-03-08', 21.80, 'Morning milking - day 3'),
(166, '4', '2025-03-08', 19.70, 'Evening milking - day 3'),
(167, '4', '2025-03-09', 22.40, 'Morning milking - day 4'),
(168, '4', '2025-03-09', 20.30, 'Evening milking - day 4'),
(169, '4', '2025-03-10', 24.00, 'Morning milking - day 5'),
(170, '4', '2025-03-10', 21.80, 'Evening milking - day 5'),
(171, '4', '2025-03-11', 23.30, 'Morning milking - day 6'),
(172, '4', '2025-03-11', 21.20, 'Evening milking - day 6'),
(173, '4', '2025-03-12', 22.00, 'Morning milking - day 7'),
(174, '4', '2025-03-12', 19.90, 'Evening milking - day 7'),
(175, '4', '2025-03-13', 23.60, 'Morning milking - day 8'),
(176, '4', '2025-03-13', 21.50, 'Evening milking - day 8'),
(177, '4', '2025-03-14', 22.20, 'Morning milking - day 9'),
(178, '4', '2025-03-14', 20.10, 'Evening milking - day 9'),
(179, '4', '2025-03-15', 21.70, 'Morning milking - day 10'),
(180, '4', '2025-03-15', 19.60, 'Evening milking - day 10'),
(181, '5', '2025-03-06', 24.20, 'Morning milking - day 1'),
(182, '5', '2025-03-06', 22.00, 'Evening milking - day 1'),
(183, '5', '2025-03-07', 24.80, 'Morning milking - day 2'),
(184, '5', '2025-03-07', 22.60, 'Evening milking - day 2'),
(185, '5', '2025-03-08', 23.50, 'Morning milking - day 3'),
(186, '5', '2025-03-08', 21.40, 'Evening milking - day 3'),
(187, '5', '2025-03-09', 24.10, 'Morning milking - day 4'),
(188, '5', '2025-03-09', 22.00, 'Evening milking - day 4'),
(189, '5', '2025-03-10', 25.70, 'Morning milking - day 5'),
(190, '5', '2025-03-10', 23.50, 'Evening milking - day 5'),
(191, '5', '2025-03-11', 25.00, 'Morning milking - day 6'),
(192, '5', '2025-03-11', 22.90, 'Evening milking - day 6'),
(193, '5', '2025-03-12', 23.70, 'Morning milking - day 7'),
(194, '5', '2025-03-12', 21.60, 'Evening milking - day 7'),
(195, '5', '2025-03-13', 25.30, 'Morning milking - day 8'),
(196, '5', '2025-03-13', 23.20, 'Evening milking - day 8'),
(197, '5', '2025-03-14', 23.90, 'Morning milking - day 9'),
(198, '5', '2025-03-14', 21.80, 'Evening milking - day 9'),
(199, '5', '2025-03-15', 23.40, 'Morning milking - day 10'),
(200, '5', '2025-03-15', 21.30, 'Evening milking - day 10'),
(205, '17', '2025-04-09', 100.00, 'good'),
(206, '11', '2025-04-09', 50.00, ''),
(207, '14', '2025-04-09', 70.00, 'morning'),
(208, '3', '2025-04-09', 102.00, 'morning'),
(209, '9', '2025-04-09', 104.99, 'morning');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `assigned_to` varchar(255) NOT NULL,
  `priority` enum('urgent','high','medium','low') DEFAULT 'medium',
  `deadline` date NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `message`, `assigned_to`, `priority`, `deadline`, `created_at`) VALUES
(1, 'Complete project presentation', 'john.doe@gmail.com', 'urgent', '2025-04-28', '2025-04-28'),
(2, 'Review team reports', 'jane.smith@gmail.com', 'high', '2025-04-28', '2025-04-28'),
(3, 'Schedule client meeting', 'mike.johnson@gmail.com', 'medium', '2025-04-28', '2025-04-28'),
(4, 'Fix critical bug in login system', 'john.doe@gmail.com', 'urgent', '2025-04-27', '2025-04-27'),
(5, 'Prepare quarterly financial report', 'jane.smith@gmail.com', 'high', '2025-04-27', '2025-04-27'),
(6, 'Update documentation', 'mike.johnson@gmail.com', 'low', '2025-04-27', '2025-04-27'),
(7, 'Implement new API endpoint', 'john.doe@gmail.com', 'high', '2025-04-26', '2025-04-26'),
(8, 'Conduct user interviews', 'jane.smith@gmail.com', 'medium', '2025-04-26', '2025-04-26'),
(9, 'Order office supplies', 'mike.johnson@gmail.com', 'low', '2025-04-26', '2025-04-26'),
(10, 'Deploy to production', 'john.doe@gmail.com', 'urgent', '2025-04-25', '2025-04-25'),
(11, 'Create marketing materials', 'jane.smith@gmail.com', 'medium', '2025-04-25', '2025-04-25'),
(12, 'Clean up database', 'mike.johnson@gmail.com', 'low', '2025-04-25', '2025-04-25'),
(13, 'Performance optimization', 'john.doe@gmail.com', 'high', '2025-04-24', '2025-04-24'),
(14, 'Plan team building event', 'jane.smith@gmail.com', 'medium', '2025-04-24', '2025-04-24'),
(15, 'Respond to customer tickets', 'mike.johnson@gmail.com', 'low', '2025-04-24', '2025-04-24'),
(16, 'Security audit', 'john.doe@gmail.com', 'urgent', '2025-04-23', '2025-04-23'),
(17, 'Update company policies', 'jane.smith@gmail.com', 'medium', '2025-04-23', '2025-04-23'),
(18, 'Organize fileserver', 'mike.johnson@gmail.com', 'low', '2025-04-23', '2025-04-23'),
(19, 'Server maintenance', 'john.doe@gmail.com', 'high', '2025-04-22', '2025-04-22'),
(20, 'Prepare training materials', 'jane.smith@gmail.com', 'medium', '2025-04-22', '2025-04-22'),
(21, 'Archive old projects', 'mike.johnson@gmail.com', 'low', '2025-04-22', '2025-04-22'),
(22, 'Emergency system patch', 'john.doe@gmail.com', 'urgent', '2025-04-21', '2025-04-21'),
(23, 'Budget planning', 'jane.smith@gmail.com', 'high', '2025-04-21', '2025-04-21'),
(24, 'Update contact list', 'mike.johnson@gmail.com', 'low', '2025-04-21', '2025-04-21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `password`, `role`, `email`) VALUES
(1, 'Lami cost', 'lami', '$2y$10$IndoNPf40PkDM7AOM7EeCOYwUZKBVlo/uRY15XVDsG1SyE4plAZhK', 'farmer', 'lamicost1@gmail.com'),
(2, 'Malcom Rono', 'malcom', '$2y$10$kvY4xmOKPhO9.n8sSBjNPOsFgep.07b94TZbxbg1wKA0nVvEfz5rG', 'admin', 'mrono2637@gmail.com'),
(3, 'Dero coli', 'dero', '$2y$10$EIi4cW6nBZyb386YChHvp.vGE7khT/kx771kZ0AKCDGEfcyIG7iDu', 'vet', 'deroc915@gmail.com'),
(4, '', 'John Doe', '', '', 'john.doe@gmail.com'),
(5, '', 'Jane Smith', '', '', 'jane.smith@gmail.com'),
(6, '', 'Mike Johnson', '', '', 'mike.johnson@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `breeds`
--
ALTER TABLE `breeds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `breed_name` (`breed_name`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cows`
--
ALTER TABLE `cows`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cow_id` (`cow_id`),
  ADD UNIQUE KEY `tag_number` (`tag_number`),
  ADD KEY `breed` (`breed`);

--
-- Indexes for table `feed_records`
--
ALTER TABLE `feed_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cow_id` (`cow_id`);

--
-- Indexes for table `health_records`
--
ALTER TABLE `health_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cow_id` (`cow_id`);

--
-- Indexes for table `milk_production`
--
ALTER TABLE `milk_production`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cow_id` (`cow_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `breeds`
--
ALTER TABLE `breeds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cows`
--
ALTER TABLE `cows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `feed_records`
--
ALTER TABLE `feed_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `health_records`
--
ALTER TABLE `health_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `milk_production`
--
ALTER TABLE `milk_production`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cows`
--
ALTER TABLE `cows`
  ADD CONSTRAINT `cows_ibfk_1` FOREIGN KEY (`breed`) REFERENCES `breeds` (`breed_name`) ON DELETE SET NULL;

--
-- Constraints for table `feed_records`
--
ALTER TABLE `feed_records`
  ADD CONSTRAINT `feed_records_ibfk_1` FOREIGN KEY (`cow_id`) REFERENCES `cows` (`cow_id`) ON DELETE CASCADE;

--
-- Constraints for table `health_records`
--
ALTER TABLE `health_records`
  ADD CONSTRAINT `fk_cow` FOREIGN KEY (`cow_id`) REFERENCES `cows` (`cow_id`),
  ADD CONSTRAINT `health_records_ibfk_1` FOREIGN KEY (`cow_id`) REFERENCES `cows` (`cow_id`) ON DELETE CASCADE;

--
-- Constraints for table `milk_production`
--
ALTER TABLE `milk_production`
  ADD CONSTRAINT `milk_production_ibfk_1` FOREIGN KEY (`cow_id`) REFERENCES `cows` (`cow_id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
