DROP TABLE IF EXISTS `dbFamily_languages`;
DROP TABLE IF EXISTS `dbFamily_Assistance`;
DROP TABLE IF EXISTS `dbLanguages`;
DROP TABLE IF EXISTS `dbAssistance`;
DROP TABLE IF EXISTS `dbFamily`;

CREATE TABLE `dbFamily` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `firstName` varchar(256) NOT NULL,
    `lastName` varchar(256) NOT NULL,
    `birthdate` varchar(256) NOT NULL,
    `address` varchar(256) NOT NULL,
    `neighborhood` varchar(256) NOT NULL,
    `city` varchar(256) NOT NULL,
    `state` varchar(256) NOT NULL,
    `zip` varchar(256) NOT NULL,
    `email` varchar(256) NOT NULL,
    `phone` varchar(256) NOT NULL,
    `phoneType` varchar(256) NOT NULL,
    `secondaryPhone` varchar(256) NOT NULL,
    `secondaryPhoneType` varchar(256) NOT NULL,
    `isHispanic` varchar(256) NOT NULL,
    `race` varchar(256) NOT NULL,
    `income` varchar(256) NOT NULL,
    `firstName2` varchar(256),
    `lastName2` varchar(256),
    `birthdate2` varchar(256),
    `address2` varchar(256),
    `neighborhood2` varchar(256),
    `city2` varchar(256),
    `state2` varchar(256),
    `zip2` varchar(256),
    `email2` varchar(256),
    `phone2` varchar(256),
    `phoneType2` varchar(256),
    `secondaryPhone2` varchar(256),
    `secondaryPhoneType2` varchar(256),
    `isHispanic2` varchar(256),
    `race2` varchar(256),
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

--
-- Table structure for table `dbLanguages`
--
CREATE TABLE `dbLanguages` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `language` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for junction table `dbFamily_Languages`
--
CREATE TABLE `dbFamily_Languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `family_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  CONSTRAINT `FKFamily_Language` FOREIGN KEY (`family_id`) REFERENCES `dbFamily` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FKLanguage_Family` FOREIGN KEY (`language_id`) REFERENCES `dbLanguages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `dbLanguages`
--
CREATE TABLE `dbAssistance` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `assistance` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for junction table `dbFamily_Assistance`
--
CREATE TABLE `dbFamily_Assistance` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `family_id` int(11) NOT NULL,
  `assistance_id` int(11) NOT NULL,
  CONSTRAINT `FKFamily_Assistance` FOREIGN KEY (`family_id`) REFERENCES `dbFamily` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FKAssistance_Family` FOREIGN KEY (`assistance_id`) REFERENCES `dbAssistance` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Inset data into dbFamily to be used for testing`
--
INSERT INTO `dbFamily` (`id`, `firstName`, `lastName`, `birthdate`, `address`, `neighborhood`, `city`, `state`, `zip`, `email`, `phone`, `phoneType`, `secondaryPhone`, `secondaryPhoneType`, `isHispanic`, `race`, `income`, `firstName2`, `lastName2`, `birthdate2`, `address2`, `neighborhood2`, `city2`, `state2`, `zip2`, `email2`, `phone2`, `phoneType2`, `secondaryPhone2`, `secondaryPhoneType2`, `isHispanic2`, `race2`, `econtactFirstName`, `econtactLastName`, `econtactPhone`, `econtactRelation`, `password`, `securityQuestion`, `securityAnswer`, `isArchived`) VALUES
(4, 'John', 'Smith', '1970-10-06', '12343 Test Rd', 'Apple Creek', 'Fredericksburg', 'VA', '22405', 'test@email.com', '(540) 456-7890', 'cellphone', '(540) 654-0987', 'home', '0', 'Caucasian', '$15,000 - $24,999', 'Mary', 'Smith', '1970-02-01', '12343 Test Rd', 'Test Neighborhood', 'Fredericksburg', '--', '22405', 'a@email.com', '(540) 342-4826', 'cellphone', '', '', '0', 'Caucasian', 'Sam', 'Smith', '(540) 431-1134', 'Mother', '$2y$10$2fF/.k6unIjmLLhKSE3lbOLS4jFwC7J9yWm3AmEAYEH5EBqtqENDW', 'a', '$2y$10$2KwKjMOolrxhDx5f/A5Cgud.oweNFqPo9MgbC7RiMSc7emM35F4i2', 0),
(5, ' Lucy', 'Anderson', '1996-01-13', '7465 Orchard Drive', 'Orchard Run', 'Stafford', 'VA', '22554', 'test2@email.com', '(540) 836-2826', 'cellphone', '(540) 766-9872', 'home', '0', 'Multiracial', '$25,000 - $34,999', 'John', 'Anderson', '1995-09-04', '7465 Orchard Drive', 'Orchard Run', 'Stafford', 'VA', '22554', '', '', '', '', '', '0', 'Caucasian', 'Mary', 'Nelson', '(540) 836-2826', 'Sister', '$2y$10$r85rkJgl0NEj70fW7eqNFOmsfKRTIAsTDQ/OjQmq7dEe852BAlHk2', 'a', '$2y$10$o6GD2exAi7TVaqLhrkW5IeDfNqMIl9F2RSiumGuIhPbdbR5o3F6B6', 0),
(6, ' Henry', 'Johnson', '1984-11-11', '125 Fox Drive', 'Fox Woods', 'Stafford', 'VA', '22554', 'test3@email.com', '(540) 472-2826', 'home', '(540) 272-2222', 'cellphone', '0', 'Caucasian', '$15,000 - $24,999', 'Sarah', 'Johnson', '1985-03-07', '125 Fox Drive', 'Fox Woods', 'Stafford', 'VA', '22554', '', '', '', '', '', '0', 'Caucasian', 'Ellie', 'Johnson', '(540) 837-2827', 'Mother', '$2y$10$DMbCinoOD8nh4LZjUZy4aex0lOCvJBxsMauipPCw7SrtAJO4t0Zfm', 'a', '$2y$10$AihOMEcCZIwBJHnL8J1OP.lWkEbluPp90DtMppM/PTuMKnPEl8hQ.', 0),
(7, ' Mary', 'Ramirez', '1985-06-28', '123 Oakland Drive', 'Oakland Plaza', 'Stafford', 'VA', '22554', 'test4@email.com', '(540) 826-2826', 'home', '(540) 382-2892', 'cellphone', '1', 'Multiracial', '$15,000 - $24,999', '', '', '', '', '', '', '--', '', '', '', '', '', '', '', '', 'Carmen', 'Ramirez', '(540) 872-2828', 'Sister', '$2y$10$3ZZDNc8a8xaOWOTjzolRBuI4PeZbdcp9kz6IsigvJ4VoxhiDf3Mpa', 'a', '$2y$10$LfzjuA98LW1fnLwg6JxqI.IvnmBYnbwzD7kfSuvlgV3OyLQi.9cmK', 0),
(8, ' Amy', 'Garcia', '1990-11-19', '123 Creek Road', 'Apple Creek', 'Fredericksburg', 'VA', '22405', 'test5@email.com', '(540) 836-2826', 'cellphone', '(540) 736-2828', 'home', '1', 'Multiracial', '$15,000 - $24,999', 'James', 'Garcia', '1990-11-11', '123 Creek Road', 'Apple Creek', 'Fredericksburg', 'VA', '22405', '', '', '', '', '', '1', 'Multiracial', 'Lucas', 'Garcia', '(540) 872-2828', 'Brother', '$2y$10$sGzKlh04MQvgMpLUFB/SAeTFJTzjswYdxvS9DRTX8ukYcgoGTeUBO', 'a', '$2y$10$aqKzKx9h2opN/NHM4IUIw.s19XsjTN0Fr0yjOKOiWdzPvwKZCBxUK', 0),
(9, ' David', 'Parker', '1989-09-03', '456 Buffalo Road', 'Buffalo Hills', 'Fredericksburg', 'VA', '22401', 'test6@email.com', '(540) 736-8262', 'cellphone', '(540) 372-2873', 'home', '0', 'Black/African American', '$25,000 - $34,999', 'Olivia', 'Parker', '1989-06-12', '456 Buffalo Road', 'Buffalo Hills', 'Fredericksburg', 'VA', '22401', '', '', '', '', '', '0', 'Black/African American', 'Nathan', 'Johnson', '(540) 583-2827', 'Friend', '$2y$10$zLlP7y5ZJDvXaucw8FSDm.cerMGKN6gbStYYRJiko0AtR6o3CXGC2', 'a', '$2y$10$60I8CN9wnwHuIOODacJ/muVoGa4d2dAZNARQacyhYH0Q8kdKLde3i', 0),
(10, ' Arthur', 'Martin', '1988-05-05', '7001 Orchard Drive', 'Orchard Run', 'Stafford', 'VA', '22554', 'test7@email.com', '(540) 473-2872', 'cellphone', '(540) 836-8287', 'home', '0', 'Caucasian', '$35,000 - $49,999', 'Victoria', 'Martin', '1985-09-28', '7001 Orchard Drive', 'Orchard Run', 'Stafford', 'VA', '22554', '', '', '', '', '', '0', 'Caucasian', 'John', 'Gold', '(540) 398-2828', 'Friend', '$2y$10$OxtEtoGNanHiIj/KtGaioOHX0MZ45MhExzryxveWfwOM9WCTOL.Cq', 'a', '$2y$10$FBsuj/EXPWUIOgISSBZOzOEdKIrcPrPptC/0..uMAwIIr0ovm4NZ6', 0);

INSERT INTO `dbLanguages` (`id`, `language`) VALUES
(4, 'English'),
(5, 'Spanish'),
(6, 'French');

INSERT INTO `dbAssistance` (`id`, `assistance`) VALUES
(1, 'SNAP'),
(2, 'SSI');

INSERT INTO `dbFamily_Languages` (`id`, `family_id`, `language_id`) VALUES
(5, 4, 4),
(6, 5, 4),
(7, 6, 4),
(8, 7, 5),
(9, 7, 4),
(10, 8, 4),
(11, 8, 5),
(12, 9, 4),
(13, 10, 4),
(14, 10, 6);

INSERT INTO `dbFamily_Assistance` (`id`, `family_id`, `assistance_id`) VALUES
(1, 4, 1),
(2, 6, 1),
(3, 6, 2),
(4, 8, 1);