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
--  LEGACY database tables, these are only here to preserve existing functionality
--  When adding new SQL statements do not add them in this section
-- 

CREATE TABLE `dbAnimals` (
  `id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `odhs_id` varchar(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `breed` varchar(256) DEFAULT NULL,
  `age` int(5) NOT NULL,
  `gender` varchar(6) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `spay_neuter_done` varchar(3) NOT NULL,
  `spay_neuter_date` date DEFAULT NULL,
  `rabies_given_date` date NOT NULL,
  `rabies_due_date` date DEFAULT NULL,
  `heartworm_given_date` date NOT NULL,
  `heartworm_due_date` date DEFAULT NULL,
  `distemper1_given_date` date NOT NULL,
  `distemper1_due_date` date DEFAULT NULL,
  `distemper2_given_date` date NOT NULL,
  `distemper2_due_date` date DEFAULT NULL,
  `distemper3_given_date` date NOT NULL,
  `distemper3_due_date` date DEFAULT NULL,
  `microchip_done` varchar(3) NOT NULL,
  `archived` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `dbEventMedia` (
  `id` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `url` text NOT NULL,
  `type` text NOT NULL,
  `format` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `dbEvents` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `abbrevName` text NOT NULL,
  `date` char(10) NOT NULL,
  `startTime` char(5) NOT NULL,
  `endTime` char(5) NOT NULL,
  `description` text NOT NULL,
  `locationID` int(11) NOT NULL,
  `capacity` int(11) NOT NULL,
  `animalID` int(11) NOT NULL,
  `completed` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `dbEventsServices` (
  `eventID` int(11) NOT NULL,
  `serviceID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `dbEventVolunteers` (
  `eventID` int(11) NOT NULL,
  `userID` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `dbLocations` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `address` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `dbLocationsServices` (
  `locationID` int(11) NOT NULL,
  `serviceID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `dbMessages` (
  `id` int(11) NOT NULL,
  `senderID` varchar(256) NOT NULL,
  `recipientID` varchar(256) NOT NULL,
  `title` varchar(256) NOT NULL,
  `body` text NOT NULL,
  `time` varchar(16) NOT NULL,
  `wasRead` tinyint(1) NOT NULL DEFAULT 0,
  `prioritylevel` tinyint(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `dbPersons` (
  `id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `start_date` text DEFAULT NULL,
  `venue` text DEFAULT NULL,
  `first_name` text NOT NULL,
  `last_name` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` text DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `zip` text DEFAULT NULL,
  `phone1` varchar(12) NOT NULL,
  `phone1type` text DEFAULT NULL,
  `phone2` varchar(12) DEFAULT NULL,
  `phone2type` text DEFAULT NULL,
  `birthday` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `contact_name` text NOT NULL,
  `contact_num` varchar(12) NOT NULL,
  `relation` text NOT NULL,
  `contact_time` text NOT NULL,
  `cMethod` text DEFAULT NULL,
  `type` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `availability` text DEFAULT NULL,
  `schedule` text DEFAULT NULL,
  `hours` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `sundays_start` char(5) DEFAULT NULL,
  `sundays_end` char(5) DEFAULT NULL,
  `mondays_start` char(5) DEFAULT NULL,
  `mondays_end` char(5) DEFAULT NULL,
  `tuesdays_start` char(5) DEFAULT NULL,
  `tuesdays_end` char(5) DEFAULT NULL,
  `wednesdays_start` char(5) DEFAULT NULL,
  `wednesdays_end` char(5) DEFAULT NULL,
  `thursdays_start` char(5) DEFAULT NULL,
  `thursdays_end` char(5) DEFAULT NULL,
  `fridays_start` char(5) DEFAULT NULL,
  `fridays_end` char(5) DEFAULT NULL,
  `saturdays_start` char(5) DEFAULT NULL,
  `saturdays_end` char(5) DEFAULT NULL,
  `profile_pic` text NOT NULL,
  `force_password_change` tinyint(1) NOT NULL,
  `gender` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `dbPersons` (`id`, `start_date`, `venue`, `first_name`, `last_name`, `address`, `city`, `state`, `zip`, `phone1`, `phone1type`, `phone2`, `phone2type`, `birthday`, `email`, `contact_name`, `contact_num`, `relation`, `contact_time`, `cMethod`, `type`, `status`, `availability`, `schedule`, `hours`, `notes`, `password`, `sundays_start`, `sundays_end`, `mondays_start`, `mondays_end`, `tuesdays_start`, `tuesdays_end`, `wednesdays_start`, `wednesdays_end`, `thursdays_start`, `thursdays_end`, `fridays_start`, `fridays_end`, `saturdays_start`, `saturdays_end`, `profile_pic`, `force_password_change`, `gender`) VALUES
('brianna@gmail.com', '2024-01-22', 'portland', 'Brianna', 'Wahl', '212 Latham Road', 'Mineola', 'VA', '11501', '1234567890', 'cellphone', '', '', '2004-04-04', 'brianna@gmail.com', 'Mom', '1234567890', 'Mother', 'Days', 'text', 'admin', 'Active', '', '', '', '', '$2y$10$jNbMmZwq.1r/5/oy61IRkOSX4PY6sxpYEdWfu9tLRZA6m1NgsxD6m', '00:00', '10:00', '', '', '', '', '02:00', '16:00', '', '', '', '', '', '', '', 0, 'Female'),
('bum@gmail.com', '2024-01-24', 'portland', 'bum', 'bum', '1345 Strattford St.', 'Mineola', 'VA', '22401', '1234567890', 'home', '', '', '1111-11-11', 'bum@gmail.com', 'Mom', '1234567890', 'Mom', 'Mornings', 'text', 'admin', 'Active', '', '', '', '', '$2y$10$Ps8FnZXT7d4uiU/R5YFnRecIRbRakyVtbXP9TVqp7vVpuB3yTXFIO', '', '', '15:00', '18:00', '', '', '', '', '', '', '', '', '', '', '', 0, 'Male'),
('mom@gmail.com', '2024-01-22', 'portland', 'Lorraine', 'Egan', '212 Latham Road', 'Mineola', 'NY', '11501', '5167423832', 'home', '', '', '1910-10-10', 'mom@gmail.com', 'Mom', '5167423832', 'Dead', 'Never', 'phone', 'admin', 'Active', '', '', '', '', '$2y$10$of1CkoNXZwyhAMS5GQ.aYuAW1SHptF6z31ONahnF2qK4Y/W9Ty2h2', '00:00', '10:00', '18:00', '19:00', '06:00', '14:00', '02:00', '12:00', '02:00', '16:00', '12:00', '18:00', '08:00', '17:00', '', 0, 'Male'),
('oliver@gmail.com', '2024-01-22', 'portland', 'Oliver', 'Wahl', '1345 Strattford St.', 'Fredericksburg', 'VA', '22401', '1234567890', 'home', '', '', '2011-11-11', 'oliver@gmail.com', 'Mom', '1234567890', 'Mother', 'Middle of the Night', 'text', 'admin', 'Active', '', '', '', '', '$2y$10$tgIjMkXhPzdmgGhUgbfPRuXLJVZHLiC0pWQQwOYKx8p8H8XY3eHw6', '06:00', '14:00', '', '', '', '', '', '', '', '', '', '', '04:00', '18:00', '', 0, 'Other'),
('peter@gmail.com', '2024-01-22', 'portland', 'Peter', 'Polack', '1345 Strattford St.', 'Mineola', 'VA', '12345', '1234567890', 'cellphone', '', '', '1968-09-09', 'peter@gmail.com', 'Mom', '1234567890', 'Mom', 'Don&amp;amp;#039;t Call or Text or Email', 'email', 'admin', 'Active', '', '', '', '', '$2y$10$j5xJ6GWaBhnb45aktS.kruk05u./TsAhEoCI3VRlNs0SRGrIqz.B6', '00:00', '19:00', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 'Male'),
('polack@um.edu', '2024-01-22', 'portland', 'Jennifer', 'Polack', '15 Wallace Farms Lane', 'Fredericksburg', 'VA', '22406', '1234567890', 'cellphone', '', '', '1970-05-01', 'polack@um.edu', 'Mom', '1234567890', 'Mom', 'Days', 'email', 'admin', 'Active', '', '', '', '', '$2y$10$mp18j4WqhlQo7MTeS/9kt.i08n7nbt0YMuRoAxtAy52BlinqPUE4C', '00:00', '12:00', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 'Female'),
('tom@gmail.com', '2024-01-22', 'portland', 'tom', 'tom', '1345 Strattford St.', 'Mineola', 'NY', '12345', '1234567890', 'home', '', '', '1920-02-02', 'tom@gmail.com', 'Dad', '9876543210', 'Father', 'Mornings', 'phone', 'admin', 'Active', '', '', '', '', '$2y$10$1Zcj7n/prdkNxZjxTK1zUOF7391byZvsXkJcN8S8aZL57sz/OfxP.', '11:00', '17:00', '', '', '11:00', '14:00', '', '', '09:00', '14:00', '', '', '', '', '', 0, 'Male'),
('vmsroot', 'N/A', 'portland', 'vmsroot', '', 'N/A', 'N/A', 'VA', 'N/A', '', 'N/A', 'N/A', 'N/A', 'N/A', 'vmsroot', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '$2y$10$.3p8xvmUqmxNztEzMJQRBesLDwdiRU3xnt/HOcJtsglwsbUk88VTO', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '');


CREATE TABLE `dbServices` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `type` varchar(256) NOT NULL,
  `duration_years` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `dbAnimals`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `dbEventMedia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKeventID2` (`eventID`);

ALTER TABLE `dbEvents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKanimalID` (`animalID`),
  ADD KEY `FKlocationID` (`locationID`);

ALTER TABLE `dbEventsServices`
  ADD PRIMARY KEY (`eventID`,`serviceID`),
  ADD KEY `FKserviceID3` (`serviceID`);

ALTER TABLE `dbEventVolunteers`
  ADD KEY `FKeventID` (`eventID`),
  ADD KEY `FKpersonID` (`userID`);

ALTER TABLE `dbLocations`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `dbLocationsServices`
  ADD PRIMARY KEY (`locationID`,`serviceID`),
  ADD KEY `FKserviceID2` (`serviceID`);

ALTER TABLE `dbMessages`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `dbPersons`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `dbServices`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `dbAnimals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `dbEventMedia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `dbEvents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `dbLocations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `dbMessages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2747;

ALTER TABLE `dbServices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `dbEventMedia`
  ADD CONSTRAINT `FKeventID2` FOREIGN KEY (`eventID`) REFERENCES `dbEvents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `dbEvents`
  ADD CONSTRAINT `FKanimalID` FOREIGN KEY (`animalID`) REFERENCES `dbAnimals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FKlocationID` FOREIGN KEY (`locationID`) REFERENCES `dbLocations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `dbEventsServices`
  ADD CONSTRAINT `FKEventID3` FOREIGN KEY (`eventID`) REFERENCES `dbEvents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FKserviceID3` FOREIGN KEY (`serviceID`) REFERENCES `dbServices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `dbEventVolunteers`
  ADD CONSTRAINT `FKeventID` FOREIGN KEY (`eventID`) REFERENCES `dbEvents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FKpersonID` FOREIGN KEY (`userID`) REFERENCES `dbPersons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `dbLocationsServices`
  ADD CONSTRAINT `FKlocationID2` FOREIGN KEY (`locationID`) REFERENCES `dbLocations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FKserviceID2` FOREIGN KEY (`serviceID`) REFERENCES `dbServices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

-- --------------------------------------------------------

--
--  NEW database tables, these will need to have functionality in follow-up tickets
--


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

CREATE TABLE `dbServicesNEW` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(256) NOT NULL,
  `location` int NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Table structure for table `dbLocations`
--

CREATE TABLE `dbLocationsNEW` (
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

CREATE TABLE `dbPersonsNEW` (
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

INSERT INTO `dbPersonsNEW` (`id`, `first_name`, `last_name`, `address`, `city`, `state`, `zip`, `phone1`, `phone1_type`, `phone2`, `phone2_type`, `birthday`, `email`, `contact_name`, `contact_num`, `relation`, `contact_time`, `password`, `is_active`) VALUES
(NULL, 'test', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vmsroot', NULL, NULL, NULL, NULL, '$2y$10$.3p8xvmUqmxNztEzMJQRBesLDwdiRU3xnt/HOcJtsglwsbUk88VTO', '1');

-- --------------------------------------------------------
--
-- Junction tables!!!!!!
--

--
-- Table structure for table `dbLocationsServices`
--

CREATE TABLE `dbLocationsServicesNEW` (
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
-- Constraints for table `dbPrograms`
--
ALTER TABLE `dbPrograms`
  ADD CONSTRAINT `dbPrograms_location_id_FK` FOREIGN KEY (`location_id`) REFERENCES `dbLocationsNEW` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dbLocationsServices`
--
ALTER TABLE `dbLocationsServicesNEW`
  ADD CONSTRAINT `dbLocationsServices_location_id_FK` FOREIGN KEY (`location_id`) REFERENCES `dbLocationsNEW` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dbLocationsServices_service_id_FK` FOREIGN KEY (`service_id`) REFERENCES `dbServicesNEW` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dbProgramsServices`
--
ALTER TABLE `dbProgramsServices`
  ADD CONSTRAINT `dbProgramsServices_program_id_FK` FOREIGN KEY (`program_id`) REFERENCES `dbPrograms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dbProgramsServices_service_id_FK` FOREIGN KEY (`service_id`) REFERENCES `dbServicesNEW` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dbProgramsVolunteers`
--
ALTER TABLE `dbProgramsVolunteers`
  ADD CONSTRAINT `dbProgramsVolunteers_program_id_FK` FOREIGN KEY (`program_id`) REFERENCES `dbPrograms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dbProgramsVolunteers_user_id_FK` FOREIGN KEY (`user_id`) REFERENCES `dbPersonsNEW` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dbProgramsParticipants`
--
ALTER TABLE `dbProgramsParticipants`
  ADD CONSTRAINT `dbProgramsParticipants_program_id_FK` FOREIGN KEY (`program_id`) REFERENCES `dbPrograms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dbProgramsParticipants_user_id_FK` FOREIGN KEY (`user_id`) REFERENCES `dbPersonsNEW` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
