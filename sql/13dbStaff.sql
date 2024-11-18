--
-- Drop all tables below if any exist
--
DROP TABLE IF EXISTS `dbStaff`;

--
-- Table structure for table `dbStaff`
--

CREATE TABLE `dbStaff` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `firstName` VARCHAR(256) NOT NULL,
    `lastName` VARCHAR(256) NOT NULL,
    `birthdate` VARCHAR(256) NOT NULL,
    `address` VARCHAR(256) NOT NULL,
    `email` VARCHAR(256) NOT NULL,
    `phone` VARCHAR(256) NOT NULL,
    `econtactName` VARCHAR(256) NOT NULL,
    `econtactPhone` VARCHAR(256) NOT NULL,
    `jobTitle` VARCHAR(256) NOT NULL,
    `password` VARCHAR(256) NOT NULL,
    `securityQuestion` VARCHAR(256) NOT NULL,
    `securityAnswer` VARCHAR(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `dbStaff` (`firstName`, `lastName`, `birthdate`, `address`, `email`, `phone`, `econtactName`, `econtactPhone`, `jobTitle`, `password`, `securityQuestion`, `securityAnswer`) VALUES
    ('John', 'Doe', '10-13-24', '12 Little Oak Road', 'jdoe@gmail.com', '(555)555-5555', 'Jane Doe', '555-555-5555', 'Teacher', '123', 'question', 'answer');