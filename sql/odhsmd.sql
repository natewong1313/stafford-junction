-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 12, 2024 at 07:39 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `odhsmd`
--

-- --------------------------------------------------------

--
-- Table structure for table `dbChildren`
--

CREATE TABLE `dbChildren` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `person_id` int(11) NOT NULL,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(6) NOT NULL,
  `medical_notes` text,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `dbPrograms`
--

CREATE TABLE `dbPrograms` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text NOT NULL,
  `date` date NOT NULL,
  `start_time` char(5) NOT NULL,
  `start_date` date NOT NULL,
  `end_time` char(5) NOT NULL,
  `end_date` date NOT NULL,
  `description` text NOT NULL,
  `location_id` int(11) NOT NULL,
  `capacity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbServices`
--

CREATE TABLE `dbServices` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(256) NOT NULL,
  `location` int NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `dbLocations`
--

CREATE TABLE `dbLocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(256) NOT NULL,
  `address` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbAllergies`
--

CREATE TABLE `dbAllergies` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbUnallowedFoods`
--

CREATE TABLE `dbUnallowedFoods` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbAuthorizedStatus`
--

CREATE TABLE `dbAuthorizedStatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `authorized` boolean NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbRace`
--

CREATE TABLE `dbRace` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbPersons`
--

CREATE TABLE `dbPersons` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `address` varchar(256) DEFAULT NULL,
  `city` varchar(256) DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `zip` int DEFAULT NULL,
  `phone1` varchar(12) DEFAULT NULL,
  `phone1_type` varchar(256) DEFAULT NULL,
  `phone2` varchar(12) DEFAULT NULL,
  `phone2_type` varchar(256) DEFAULT NULL,
  `birthday` varchar(10) DEFAULT NULL,
  `email` varchar(256) NOT NULL,
  `contact_name` varchar(256) DEFAULT NULL,
  `contact_num` varchar(12) DEFAULT NULL,
  `relation` text DEFAULT NULL,
  `contact_time` text DEFAULT NULL,
  `password` varchar(64) NOT NULL,
  `is_active` boolean NOT NULL DEFAULT 1
);

INSERT INTO `dbPersons` (`id`, `first_name`, `last_name`, `address`, `city`, `state`, `zip`, `phone1`, `phone1_type`, `phone2`, `phone2_type`, `birthday`, `email`, `contact_name`, `contact_num`, `relation`, `contact_time`, `password`, `is_active`) VALUES
(NULL, 'test', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vmsroot', NULL, NULL, NULL, NULL, '$2y$10$.3p8xvmUqmxNztEzMJQRBesLDwdiRU3xnt/HOcJtsglwsbUk88VTO', '1');

-- --------------------------------------------------------
--
-- Junction tables!!!!!!
--

--
-- Table structure for table `dbLocationServices`
--

CREATE TABLE `dbLocationsServices` (
  `location_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `dbProgramServices`
--

CREATE TABLE `dbProgramsServices` (
  `program_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `dbProgramServices`
--

CREATE TABLE `dbProgramsVolunteers` (
  `program_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `dbProgramParticipants`
--

CREATE TABLE `dbProgramsParticipants` (
  `program_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dbChildren`
--
ALTER TABLE `dbChildren`
  ADD CONSTRAINT `dbChildren_person_id_FK` FOREIGN KEY (`person_id`) REFERENCES `dbPersons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dbPrograms`
--
ALTER TABLE `dbPrograms`
  ADD CONSTRAINT `dbPrograms_location_id_FK` FOREIGN KEY (`location_id`) REFERENCES `dbLocations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dbLocationsServices`
--
ALTER TABLE `dbLocationsServices`
  ADD CONSTRAINT `dbLocationsServices_location_id_FK` FOREIGN KEY (`location_id`) REFERENCES `dbLocations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dbLocationsServices_service_id_FK` FOREIGN KEY (`service_id`) REFERENCES `dbServices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dbProgramsServices`
--
ALTER TABLE `dbProgramsServices`
  ADD CONSTRAINT `dbProgramsServices_program_id_FK` FOREIGN KEY (`program_id`) REFERENCES `dbPrograms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dbProgramsServices_service_id_FK` FOREIGN KEY (`service_id`) REFERENCES `dbServices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dbProgramsVolunteers`
--
ALTER TABLE `dbProgramsVolunteers`
  ADD CONSTRAINT `dbProgramsVolunteers_program_id_FK` FOREIGN KEY (`program_id`) REFERENCES `dbPrograms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dbProgramsVolunteers_user_id_FK` FOREIGN KEY (`user_id`) REFERENCES `dbPersons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dbProgramsParticipants`
--
ALTER TABLE `dbProgramsParticipants`
  ADD CONSTRAINT `dbProgramsParticipants_program_id_FK` FOREIGN KEY (`program_id`) REFERENCES `dbPrograms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dbProgramsParticipants_user_id_FK` FOREIGN KEY (`user_id`) REFERENCES `dbPersons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
