-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 25, 2023 at 05:39 PM
-- Server version: 5.7.39-42-log
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db3dpzjdij5qds`
--

-- --------------------------------------------------------

--
-- Table structure for table `dbEventMedia`
--

CREATE TABLE `dbEventMedia` (
  `id` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `url` text NOT NULL,
  `type` text NOT NULL,
  `format` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `dbEvents`
--

CREATE TABLE `dbEvents` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `abbrevName` text NOT NULL,
  `date` char(10) NOT NULL,
  `startTime` char(5) NOT NULL,
  `endTime` char(5) NOT NULL,
  `description` text NOT NULL,
  `locationID` int NOT NULL,
  `capacity` int(11) NOT NULL,
  `animalID` int(11) NOT NULL,
  `completed` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `dbEventVolunteers`
--

CREATE TABLE `dbEventVolunteers` (
  `eventID` int(11) NOT NULL,
  `userID` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `dbMessages`
--

CREATE TABLE `dbMessages` (
  `id` int(11) NOT NULL,
  `senderID` varchar(256) NOT NULL,
  `recipientID` varchar(256) NOT NULL,
  `title` varchar(256) NOT NULL,
  `body` text NOT NULL,
  `time` varchar(16) NOT NULL,
  `wasRead` tinyint(1) NOT NULL DEFAULT '0',
  `prioritylevel` tinyint(5) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `dbPersons`
--

CREATE TABLE `dbPersons` (
  `id` varchar(256) CHARACTER SET utf8mb4 NOT NULL,
  `start_date` text,
  `venue` text,
  `first_name` text NOT NULL,
  `last_name` text,
  `address` text,
  `city` text,
  `state` varchar(2) DEFAULT NULL,
  `zip` text,
  `phone1` varchar(12) NOT NULL,
  `phone1type` text,
  `phone2` varchar(12) DEFAULT NULL,
  `phone2type` text,
  `birthday` text,
  `email` text,
  `shirt_size` varchar(3) DEFAULT NULL,
  `computer` varchar(3) DEFAULT NULL,
  `camera` varchar(3) NOT NULL,
  `transportation` varchar(3) NOT NULL,
  `contact_name` text NOT NULL,
  `contact_num` varchar(12) NOT NULL,
  `relation` text NOT NULL,
  `contact_time` text NOT NULL,
  `cMethod` text,
  `position` text,
  `credithours` text,
  `howdidyouhear` text,
  `commitment` text,
  `motivation` text,
  `specialties` text,
  `convictions` text,
  `type` text,
  `status` text,
  `availability` text,
  `schedule` text,
  `hours` text,
  `notes` text,
  `password` text,
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for 'dbAnimals'
--

-- note that odhs_id is just the id assigned and used by ODHS, not the primary key for the table.
CREATE TABLE `dbAnimals` (
  `id` int(11) NOT NULL,
  `odhs_id` varchar(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `breed` varchar(256),
  `age` int(5) NOT NULL,
  `gender` varchar(6),
  `notes` text,
  `spay_neuter_done` varchar(3) NOT NULL,
  `spay_neuter_date` date,
  `rabies_given_date` date NOT NULL,
  `rabies_due_date` date,
  `heartworm_given_date` date NOT NULL,
  `heartworm_due_date` date,
  `distemper1_given_date` date NOT NULL,
  `distemper1_due_date` date,
  `distemper2_given_date` date NOT NULL,
  `distemper2_due_date` date,
  `distemper3_given_date` date NOT NULL,
  `distemper3_due_date` date,
  `microchip_done` varchar(3) NOT NULL,
  `archived` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
--
-- Table structure for table `dbLocations`
--

CREATE TABLE `dbLocations` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `address` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
--
-- Table structure for table `dbServices`
--

CREATE TABLE `dbServices` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `type` varchar(256) NOT NULL,
  `duration_years` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
--
-- Table structure for junction table `dbLocationsServices`
--

CREATE TABLE `dbLocationsServices` (
  `locationID` int(11) NOT NULL,
  `serviceID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
--
-- Table structure for junction table `dbEventsServices`
--

CREATE TABLE `dbEventsServices` (
  `eventID` int(11) NOT NULL,
  `serviceID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbPersons`
--

INSERT INTO `dbPersons` (`id`, `start_date`, `venue`, `first_name`, `last_name`, `address`, `city`, `state`, `zip`, `phone1`, `phone1type`, `phone2`, `phone2type`, `birthday`, `email`, `shirt_size`, `computer`, `camera`, `transportation`, `contact_name`, `contact_num`, `relation`, `contact_time`, `cMethod`, `position`, `credithours`, `howdidyouhear`, `commitment`, `motivation`, `specialties`, `convictions`, `type`, `status`, `availability`, `schedule`, `hours`, `notes`, `password`, `sundays_start`, `sundays_end`, `mondays_start`, `mondays_end`, `tuesdays_start`, `tuesdays_end`, `wednesdays_start`, `wednesdays_end`, `thursdays_start`, `thursdays_end`, `fridays_start`, `fridays_end`, `saturdays_start`, `saturdays_end`, `profile_pic`, `force_password_change`, `gender`) VALUES
('vmsroot', 'N/A', 'portland', 'vmsroot', '', 'N/A', 'N/A', 'VA', 'N/A', '', 'N/A', 'N/A', 'N/A', 'N/A', 'vmsroot', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '$2y$10$G58bb/./aiqRQt1vR1QWhuXM3gNKOwqCfb.q7yfp0e.RKmLRvY3py', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dbEventMedia`
--
ALTER TABLE `dbEventMedia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKeventID2` (`eventID`);

--
-- Indexes for table `dbEvents`
--
ALTER TABLE `dbEvents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKanimalID` (`animalID`),
  ADD KEY `FKlocationID` (`locationID`);

--
-- Indexes for table `dbLocations`
--
ALTER TABLE `dbLocations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbServices`
--
ALTER TABLE `dbServices`
  ADD PRIMARY KEY (`id`);


--
-- Indexes for table `dbEventVolunteers`
--
ALTER TABLE `dbEventVolunteers`
  ADD KEY `FKeventID` (`eventID`),
  ADD KEY `FKpersonID` (`userID`);

--
-- Indexes for table `dbMessages`
--
ALTER TABLE `dbMessages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbPersons`
--
ALTER TABLE `dbPersons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbAnimals`
--
ALTER TABLE `dbAnimals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbLocationsServices`
--
ALTER TABLE `dbLocationsServices`
  ADD PRIMARY KEY (`locationID`, `serviceID`);

--
-- Indexes for table `dbEventsServices`
--
ALTER TABLE `dbEventsServices`
  ADD PRIMARY KEY (`eventID`, `serviceID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dbEventMedia`
--
ALTER TABLE `dbEventMedia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dbLocations`
--
ALTER TABLE `dbLocations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dbServices`
--
ALTER TABLE `dbServices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dbEvents`
--
ALTER TABLE `dbEvents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dbMessages`
--
ALTER TABLE `dbMessages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dbAnimals`
--
ALTER TABLE `dbAnimals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dbEventMedia`
--
ALTER TABLE `dbEventMedia`
  ADD CONSTRAINT `FKeventID2` FOREIGN KEY (`eventID`) REFERENCES `dbEvents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dbEvents`
--
ALTER TABLE `dbEvents`
  ADD CONSTRAINT `FKanimalID` FOREIGN KEY (`animalID`) REFERENCES `dbAnimals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FKlocationID` FOREIGN KEY (`locationID`) REFERENCES `dbLocations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dbLocationsServices`
--
ALTER TABLE `dbLocationsServices`
  ADD CONSTRAINT `FKlocationID2` FOREIGN KEY (`locationID`) REFERENCES `dbLocations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FKserviceID2` FOREIGN KEY (`serviceID`) REFERENCES `dbServices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dbEventsServices`
--
ALTER TABLE `dbEventsServices`
  ADD CONSTRAINT `FKEventID3` FOREIGN KEY (`eventID`) REFERENCES `dbEvents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FKserviceID3` FOREIGN KEY (`serviceID`) REFERENCES `dbServices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dbEventVolunteers`
--
ALTER TABLE `dbEventVolunteers`
  ADD CONSTRAINT `FKeventID` FOREIGN KEY (`eventID`) REFERENCES `dbEvents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FKpersonID` FOREIGN KEY (`userID`) REFERENCES `dbPersons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
