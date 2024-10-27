CREATE TABLE `dbFamily` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `firstName` varchar(256) NOT NULL,
    `lastName` varchar(256) NOT NULL,
    `birthdate` varchar(256) NOT NULL,
    `address` varchar(256) NOT NULL,
    `city` varchar(256) NOT NULL,
    `state` varchar(256) NOT NULL,
    `zip` varchar(256) NOT NULL,
    `email` varchar(256) NOT NULL,
    `phone` varchar(256) NOT NULL,
    `phoneType` varchar(256) NOT NULL,
    `secondaryPhone` varchar(256) NOT NULL,
    `secondaryPhoneType` varchar(256) NOT NULL,
    `firstName2` varchar(256),
    `lastName2` varchar(256),
    `birthdate2` varchar(256),
    `address2` varchar(256),
    `city2` varchar(256),
    `state2` varchar(256),
    `zip2` varchar(256),
    `email2` varchar(256),
    `phone2` varchar(256),
    `phoneType2` varchar(256),
    `secondaryPhone2` varchar(256),
    `secondaryPhoneType2` varchar(256),
    `econtactFirstName` varchar(256) NOT NULL,
    `econtactLastName` varchar(256) NOT NULL,
    `econtactPhone` varchar(256) NOT NULL,
    `econtactRelation` varchar(256),
    `password` text NOT NULL,
    `securityQuestion` text NOT NULL,
    `securityAnswer` text NOT NULL,
    `accountType` varchar(256) NOT NULL,
    `isArchived` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;