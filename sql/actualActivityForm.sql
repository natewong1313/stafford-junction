--
-- Table structure for table `dbActualActivityForm`
--

CREATE TABLE `dbActualActivityForm` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `activity` int(256) NOT NULL,
  `date` varchar(256) NOT NULL,
  `program` varchar(256) NOT NULL,
  `start_time` varchar(15) NOT NULL,
  `end_time` varchar(15) NOT NULL,
  `start_mile` varchar(15) NOT NULL,
  `end_mile` varchar(15) NOT NULL,
  `address` varchar(256) NOT NULL,
  `attend_num` int(5) NOT NULL,
  `volstaff_num` int(5) NOT NULL,
  `materials_used` text NOT NULL,
  `mealinfo` boolean NOT NULL,
  `act_costs` text NOT NULL
  `act_benefits` text NOT NULL
  'attendance' text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;