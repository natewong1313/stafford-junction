CREATE TABLE `dbVolunteers` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `email` varchar(256) NOT NULL,
    `password` varchar(256) NOT NULL,
    `securityQuestion` text NOT NULL,
    `securityAnswer` text NOT NULL,
    `firstName` varchar(256) NOT NULL,
    `middleInitial` varchar(1) NOT NULL,
    `lastName` varchar(256) NOT NULL,
    `address` varchar(256) NOT NULL,
    `city` varchar(256) NOT NULL,
    `state` varchar(2) NOT NULL,
    `zip` int NOT NULL,
    `homePhone` varchar(256) NOT NULL,
    `cellPhone` varchar(256) NOT NULL,
    `age` int NOT NULL,
    `birthDate` date NOT NULL,
    `hasDriversLicense` boolean NOT NULL,
    `transportation` varchar(256),
    `emergencyContact1Name` varchar(256) NOT NULL,
    `emergencyContact1Relation` varchar(256) NOT NULL,
    `emergencyContact1Phone` varchar(256) NOT NULL,
    `emergencyContact2Name` varchar(256),
    `emergencyContact2Relation` varchar(256),
    `emergencyContact2Phone` varchar(256),
    `allergies` varchar(256),
    `sunStart` varchar(5),
    `sunEnd` varchar(5),
    `monStart` varchar(5),
    `monEnd` varchar(5),
    `tueStart` varchar(5),
    `tueEnd` varchar(5),
    `wedStart` varchar(5),
    `wedEnd` varchar(5),
    `thurStart` varchar(5),
    `thurEnd` varchar(5),
    `friStart` varchar(5),
    `friEnd` varchar(5),
    `satStart` varchar(5),
    `satEnd` varchar(5),
    `dateAvailable` date,
    `minHours` int NOT NULL,
    `maxHours` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `dbVolunteers` (
    `email`, `password`, `securityQuestion`, `securityAnswer`, `firstName`, `middleInitial`, `lastName`,
    `address`, `city`, `state`, `zip`, `homePhone`, `cellPhone`, `age`, `birthDate`, `hasDriversLicense`,
    `emergencyContact1Name`, `emergencyContact1Relation`, `emergencyContact1Phone`, `minHours`, `maxHours`
)
VALUES (
        'volunteer@mail.com', '$2y$10$EZCNkQflMinx5sgoMbJwN.sqGKOlL8fnHiGGhQ3wXKU3uGMkjOx6a', 'Whats 9+10',
        '$2y$10$RGQ3P7KOXfR2m1a2z6Tr7ekssfMzboKrt7TsmLjaalfeHEpKX0GUG', 'Mr', 'A', 'Volunteer', '123 road st',
        'Fredericksburg', 'VA', '22401', '1112223333', '2223334444', '20', '1999-01-01', 'true', 'John', 'Smith',
        '1112223333', '10', '20'
       )
