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
    `satEnd` varchar(5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `dbVolunteers` (
    `email`, `password`, `securityQuestion`, `securityAnswer`, `firstName`, `lastName`, `phone`,
    `phoneType`, `sunStart`, `sunEnd`, `monStart`, `monEnd`, `tueStart`, `tueEnd`, `wedStart`,
    `wedEnd`, `thurStart`, `thurEnd`, `friStart`, `friEnd`, `satStart`, `satEnd`
)
VALUES (
        'volunteer@mail.com', '$2y$10$EZCNkQflMinx5sgoMbJwN.sqGKOlL8fnHiGGhQ3wXKU3uGMkjOx6a', 'Whats 9+10',
        '$2y$10$RGQ3P7KOXfR2m1a2z6Tr7ekssfMzboKrt7TsmLjaalfeHEpKX0GUG', 'Mr', 'Volunteer', '1112223333',
        'Mobile', '', '', '', '', '', '', '', '', '', '', '', '', '', ''
       )
