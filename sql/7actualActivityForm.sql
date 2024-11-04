--
-- Table structure for table `dbActualActivityForm`
--

CREATE TABLE `dbActualActivityForm` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `activity` INT NOT NULL,
  `date` VARCHAR(256) NOT NULL,
  `program` VARCHAR(256) NOT NULL,
  `start_time` VARCHAR(15) NOT NULL,
  `end_time` VARCHAR(15) NOT NULL,
  `start_mile` VARCHAR(15) NOT NULL,
  `end_mile` VARCHAR(15) NOT NULL,
  `address` VARCHAR(256) NOT NULL,
  `attend_num` INT NOT NULL,
  `volstaff_num` INT NOT NULL,
  `materials_used` TEXT NOT NULL,
  `mealinfo` BOOLEAN NOT NULL,
  `act_costs` TEXT NOT NULL,
  `act_benefits` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `Attendees`
--
CREATE TABLE `Attendees` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(256) NOT NULL,
    `activity_id` INT NOT NULL,
    FOREIGN KEY (`activity_id`) REFERENCES `dbActualActivityForm`(`id`) ON DELETE CASCADE
);
