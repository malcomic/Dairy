-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2025 at 12:18 AM
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
(10, 'Ankole'),
(4, 'Ayrshire'),
(7, 'Borana'),
(5, 'Brown Swiss'),
(9, 'Dexter'),
(3, 'Guernsey'),
(1, 'Holstein Friesian'),
(2, 'Jersey'),
(8, 'Red Poll'),
(6, 'Sahiwal');

-- --------------------------------------------------------

--
-- Table structure for table `cows`
--

CREATE TABLE `cows` (
  `id` int(11) NOT NULL,
  `cow_id` varchar(255) DEFAULT NULL,
  `breed` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `health_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cows`
--

INSERT INTO `cows` (`id`, `cow_id`, `breed`, `age`, `health_status`) VALUES
(1, '1', 'Holstein Friesian', 5, 'healthy'),
(2, '2', 'Ankole', 12, 'Healthy'),
(3, '3', 'Ayrshire', 23, 'Healthy'),
(4, '4', 'Borana', 12, 'Healthy');

-- --------------------------------------------------------

--
-- Table structure for table `feed_records`
--

CREATE TABLE `feed_records` (
  `id` int(11) NOT NULL,
  `cow_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `feed_type` varchar(255) DEFAULT NULL,
  `quantity` decimal(10,2) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feed_records`
--

INSERT INTO `feed_records` (`id`, `cow_id`, `date`, `feed_type`, `quantity`, `unit`) VALUES
(1, 1, '2025-03-14', 'Hay', 1.26, 'kg'),
(2, 1, '2025-03-19', 'Hay', 10.00, 'litres'),
(3, 1, '2025-03-19', 'Hay', 10.00, 'litres'),
(4, 1, '2025-03-19', 'silage', 0.17, 'kg'),
(5, 2, '2025-04-01', 'Grain', 12.00, 'litres');

-- --------------------------------------------------------

--
-- Table structure for table `health_records`
--

CREATE TABLE `health_records` (
  `id` int(11) NOT NULL,
  `cow_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `diagnosis` varchar(255) NOT NULL,
  `treatment` text NOT NULL,
  `veterinarian` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `health_records`
--

INSERT INTO `health_records` (`id`, `cow_id`, `date`, `diagnosis`, `treatment`, `veterinarian`) VALUES
(1, 2, '2025-04-01', 'ertyu', 'wertyu', 'sdfgh');

-- --------------------------------------------------------

--
-- Table structure for table `milk_production`
--

CREATE TABLE `milk_production` (
  `id` int(11) NOT NULL,
  `cow_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `milk_yield` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `milk_production`
--

INSERT INTO `milk_production` (`id`, `cow_id`, `date`, `milk_yield`, `notes`) VALUES
(1, 1, '2023-10-27', 10.50, 'Test record'),
(2, 1, '2025-03-18', 10.00, 'yes'),
(3, 1, '2025-03-18', 100.00, ''),
(4, 2, '2025-04-01', 34.00, 'ertyu'),
(5, 2, '2025-04-01', 12.00, 'ytrew');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `email`) VALUES
(3, 'admin', '$2y$10$ySJ5zzaMbwMlkI0fW4i.5upCALGcpOL/yOAukS0GmmsUezrlJMA.u', 'admin', 'admin@example.com'),
(4, 'farmer', 'Hashed Password: $2y$10$dB.b1.mlCrX6AzleRskv0.GI8YSB1kg7CGXacYnWSgC3/PSMm3UlK', 'farmer', 'farmer@example.com'),
(5, 'vet', '$2y$10$9RUTqiXzEVUzPjt.TdA9o.TnA2VXAwHm31DARmW9DXXX449Po6JHy', 'veterinarian', 'vet@example.com');

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
-- Indexes for table `cows`
--
ALTER TABLE `cows`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cow_id` (`cow_id`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `breeds`
--
ALTER TABLE `breeds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `cows`
--
ALTER TABLE `cows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feed_records`
--
ALTER TABLE `feed_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `health_records`
--
ALTER TABLE `health_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `milk_production`
--
ALTER TABLE `milk_production`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feed_records`
--
ALTER TABLE `feed_records`
  ADD CONSTRAINT `feed_records_ibfk_1` FOREIGN KEY (`cow_id`) REFERENCES `cows` (`id`);

--
-- Constraints for table `health_records`
--
ALTER TABLE `health_records`
  ADD CONSTRAINT `health_records_ibfk_1` FOREIGN KEY (`cow_id`) REFERENCES `cows` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `milk_production`
--
ALTER TABLE `milk_production`
  ADD CONSTRAINT `milk_production_ibfk_1` FOREIGN KEY (`cow_id`) REFERENCES `cows` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
