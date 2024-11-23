-- Drop the existing `Attendees` foreign key constraints
ALTER TABLE `Attendees` DROP FOREIGN KEY `Attendees_ibfk_1`;

-- Drop the existing 'dbActualActivityForm' foreign key constraints
ALTER TABLE `dbActualActivityForm` DROP FOREIGN KEY `dbActivityAttendees_ibfk_1`;
ALTER TABLE `dbActualActivityForm` DROP FOREIGN KEY `dbActivityAttendees_ibfk_2`;

-- Alter the `activity` column from INT to VARCHAR(256)
ALTER TABLE `dbActualActivityForm` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `activity` VARCHAR(256) NOT NULL,
  `date` DATE NOT NULL,
  `program` VARCHAR(256) NOT NULL,
  `start_time` VARCHAR(15) NOT NULL,
  `end_time` VARCHAR(15) NOT NULL,
  `start_mile` INT(11) NOT NULL,
  `end_mile` INT(11) NOT NULL,
  `address` VARCHAR(256) NOT NULL,
  `attend_num` INT NOT NULL,
  `volstaff_num` INT NOT NULL,
  `materials_used` TEXT NOT NULL,
  `meal_info` ENUM('meal_provided', 'meal_paid', 'no_meal') NOT NULL,
  `act_costs` TEXT NOT NULL,
  `act_benefits` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `dbActualActivityAttendees`
--
CREATE TABLE `dbActualActivityAttendees` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for junction table `dbActivityAttendees`
--
CREATE TABLE `dbActivityAttendees` (
    `activityID` INT NOT NULL,
    `attendeeID` INT NOT NULL,
    PRIMARY KEY (`activityID`, `attendeeID`),
    FOREIGN KEY (`activityID`) REFERENCES `dbActualActivityForm`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`attendeeID`) REFERENCES `dbActualActivityAttendees`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;