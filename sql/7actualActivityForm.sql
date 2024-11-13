--
-- Table structure for table `dbActualActivityForm`
--

CREATE TABLE `dbActualActivityForm` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `activity` varchar(256) NOT NULL,
  `date` varchar(100) NOT NULL,
  `program` varchar(256) NOT NULL,
  `start_time` varchar(100) NOT NULL,
  `end_time` varchar(100) NOT NULL,
  `start_mile` int(11) NOT NULL,
  `end_mile` int(11) NOT NULL,
  `address` varchar(256) NOT NULL,
  `attend_num` int(11) NOT NULL,
  `volstaff_num` int(11) NOT NULL,
  `materials_used` text NOT NULL,
  `meal_info` text NOT NULL,
  `act_costs` text NOT NULL,
  `act_benefits` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `dbActualActivityAttendees`
--
CREATE TABLE `dbActualActivityAttendees` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` varchar(255) NOT NULL
);

--
-- Table structure for junction table `dbActivityAttendees`
--
CREATE TABLE `dbActivityAttendees` (
    `activityID` INT(11) NOT NULL,
    `attendeeID` INT(11) NOT NULL,
    PRIMARY KEY (`activityID`, `attendeeID`),
    FOREIGN KEY (`activityID`) REFERENCES `dbActualActivityForm`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`attendeeID`) REFERENCES `dbActualActivityAttendees`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);