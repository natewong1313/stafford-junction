-- Drop the existing `Attendees` foreign key constraints
ALTER TABLE `Attendees` DROP FOREIGN KEY `Attendees_ibfk_1`;

-- Drop `Attendees` table if it exists
DROP TABLE IF EXISTS `Attendees`;

-- Alter the `activity` column from INT to VARCHAR(256)
ALTER TABLE `dbActualActivityForm` (
  MODIFY COLUMN `activity` VARCHAR(256) NOT NULL,
  MODIFY COLUMN `date` DATE NOT NULL,
  MODIFY COLUMN `start_mile` INT NOT NULL,
  MODIFY COLUMN `end_mile` INT NOT NULL,
  CHANGE COLUMN `mealinfo` `meal_info` ENUM('meal_provided', 'meal_paid', 'no_meal') NOT NULL
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