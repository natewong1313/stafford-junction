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
-- Add new colums to dbFamily
--
ALTER TABLE dbFamily
ADD `neighborhood` varchar(256) NOT NULL,
ADD `isHispanic` BOOLEAN NOT NULL,
ADD `race` varchar(256) NOT NULL,
ADD `income` varchar(256) NOT NULL,
ADD `neighborhood2` varchar(256) NOT NULL,
ADD `isHispanic2` BOOLEAN NOT NULL,
ADD `race2` varchar(256) NOT NULL;

--
-- Add new colums to dbChildren
--
ALTER TABLE dbChildren
ADD `address` varchar(256) NOT NULL,
ADD `neighborhood` varchar(256) NOT NULL,
ADD `city` varchar(256) NOT NULL,
ADD `state` varchar(256) NOT NULL,
ADD `zip` varchar(256) NOT NULL,
ADD `school` varchar(256) NOT NULL,
ADD `grade` varchar(25) NOT NULL,
ADD `is_hispanic` BOOLEAN NOT NULL,
ADD `race` varchar(256) NOT NULL;

--
-- Inset data into dbFamily to be used for testing`
-- Passwords and Security Answers are all "a"
--
INSERT INTO `dbFamily` (`id`, `firstName`, `lastName`, `birthdate`, `address`, `city`, `state`, `zip`, `email`, `phone`, `phoneType`, `secondaryPhone`, `secondaryPhoneType`, `firstName2`, `lastName2`, `birthdate2`, `address2`, `city2`, `state2`, `zip2`, `email2`, `phone2`, `phoneType2`, `secondaryPhone2`, `secondaryPhoneType2`, `econtactFirstName`, `econtactLastName`, `econtactPhone`, `econtactRelation`, `password`, `securityQuestion`, `securityAnswer`, `isArchived`, `neighborhood`, `isHispanic`, `race`, `income`, `neighborhood2`, `isHispanic2`, `race2`) VALUES
(4, 'John', 'Smith', '1970-10-06', '12343 Test Rd', 'Fredericksburg', 'VA', '22405', 'test@email.com', '(540) 456-7890', 'cellphone', '(540) 654-0987', 'home', 'Mary', 'Smith', '1970-02-01', '12343 Test Rd', 'Fredericksburg', '--', '22405', 'a@email.com', '(540) 342-4826', 'cellphone', '', '', 'Sam', 'Smith', '(540) 431-1134', 'Mother', '$2y$10$2fF/.k6unIjmLLhKSE3lbOLS4jFwC7J9yWm3AmEAYEH5EBqtqENDW', 'a', '$2y$10$2KwKjMOolrxhDx5f/A5Cgud.oweNFqPo9MgbC7RiMSc7emM35F4i2', 0, 'Apple Creek', '0', 'Caucasian', '$15,000 - $24,999', 'Test Neighborhood', '0', 'Caucasian'),
(5, ' Lucy', 'Anderson', '1996-01-13', '7465 Orchard Drive', 'Stafford', 'VA', '22554', 'test2@email.com', '(540) 836-2826', 'cellphone', '(540) 766-9872', 'home', 'John', 'Anderson', '1995-09-04', '7465 Orchard Drive', 'Stafford', 'VA', '22554', '', '', '', '', '', 'Mary', 'Nelson', '(540) 836-2826', 'Sister', '$2y$10$r85rkJgl0NEj70fW7eqNFOmsfKRTIAsTDQ/OjQmq7dEe852BAlHk2', 'a', '$2y$10$o6GD2exAi7TVaqLhrkW5IeDfNqMIl9F2RSiumGuIhPbdbR5o3F6B6', 0, 'Orchard Run', '0', 'Multiracial', '$25,000 - $34,999', 'Orchard Run', '0', 'Caucasian'),
(6, ' Henry', 'Johnson', '1984-11-11', '125 Fox Drive', 'Stafford', 'VA', '22554', 'test3@email.com', '(540) 472-2826', 'home', '(540) 272-2222', 'cellphone', 'Sarah', 'Johnson', '1985-03-07', '125 Fox Drive', 'Stafford', 'VA', '22554', '', '', '', '', '', 'Ellie', 'Johnson', '(540) 837-2827', 'Mother', '$2y$10$DMbCinoOD8nh4LZjUZy4aex0lOCvJBxsMauipPCw7SrtAJO4t0Zfm', 'a', '$2y$10$AihOMEcCZIwBJHnL8J1OP.lWkEbluPp90DtMppM/PTuMKnPEl8hQ.', 0, 'Fox Woods', '0', 'Caucasian', '$15,000 - $24,999', 'Fox Woods', '0', 'Caucasian'),
(7, ' Mary', 'Ramirez', '1985-06-28', '123 Oakland Drive', 'Stafford', 'VA', '22554', 'test4@email.com', '(540) 826-2826', 'home', '(540) 382-2892', 'cellphone', '', '', '', '', '', '', '--', '', '', '', '', '', 'Carmen', 'Ramirez', '(540) 872-2828', 'Sister', '$2y$10$3ZZDNc8a8xaOWOTjzolRBuI4PeZbdcp9kz6IsigvJ4VoxhiDf3Mpa', 'a', '$2y$10$LfzjuA98LW1fnLwg6JxqI.IvnmBYnbwzD7kfSuvlgV3OyLQi.9cmK', 0, 'Oakland Plaza', '1', 'Multiracial', '$15,000 - $24,999', '', '', ''),
(8, ' Amy', 'Garcia', '1990-11-19', '123 Creek Road', 'Fredericksburg', 'VA', '22405', 'test5@email.com', '(540) 836-2826', 'cellphone', '(540) 736-2828', 'home', 'James', 'Garcia', '1990-11-11', '123 Creek Road', 'Fredericksburg', 'VA', '22405', '', '', '', '', '', 'Lucas', 'Garcia', '(540) 872-2828', 'Brother', '$2y$10$sGzKlh04MQvgMpLUFB/SAeTFJTzjswYdxvS9DRTX8ukYcgoGTeUBO', 'a', '$2y$10$aqKzKx9h2opN/NHM4IUIw.s19XsjTN0Fr0yjOKOiWdzPvwKZCBxUK', 0, 'Apple Creek', '1', 'Multiracial', '$15,000 - $24,999', 'Apple Creek', '1', 'Multiracial'),
(9, ' David', 'Parker', '1989-09-03', '456 Buffalo Road', 'Fredericksburg', 'VA', '22401', 'test6@email.com', '(540) 736-8262', 'cellphone', '(540) 372-2873', 'home', 'Olivia', 'Parker', '1989-06-12', '456 Buffalo Road', 'Fredericksburg', 'VA', '22401', '', '', '', '', '', 'Nathan', 'Johnson', '(540) 583-2827', 'Friend', '$2y$10$zLlP7y5ZJDvXaucw8FSDm.cerMGKN6gbStYYRJiko0AtR6o3CXGC2', 'a', '$2y$10$60I8CN9wnwHuIOODacJ/muVoGa4d2dAZNARQacyhYH0Q8kdKLde3i', 0, 'Buffalo Hills', '0', 'Black/African American', '$25,000 - $34,999', 'Buffalo Hills', '0', 'Black/African American'),
(10, ' Arthur', 'Martin', '1988-05-05', '7001 Orchard Drive', 'Stafford', 'VA', '22554', 'test7@email.com', '(540) 473-2872', 'cellphone', '(540) 836-8287', 'home', 'Victoria', 'Martin', '1985-09-28', '7001 Orchard Drive', 'Stafford', 'VA', '22554', '', '', '', '', '', 'John', 'Gold', '(540) 398-2828', 'Friend', '$2y$10$OxtEtoGNanHiIj/KtGaioOHX0MZ45MhExzryxveWfwOM9WCTOL.Cq', 'a', '$2y$10$FBsuj/EXPWUIOgISSBZOzOEdKIrcPrPptC/0..uMAwIIr0ovm4NZ6', 0, 'Orchard Run', '0', 'Caucasian', '$35,000 - $49,999', 'Orchard Run', '0', 'Caucasian');

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

--
-- Insert children into dbChildren which will be used for testing
--
INSERT INTO `dbChildren` (`id`, `family_id`, `first_name`, `last_name`, `dob`, `gender`, `medical_notes`, `notes`, `neighborhood`, `address`, `city`, `state`, `zip`, `school`, `grade`, `is_hispanic`, `race`) VALUES
(1, 4, 'Henry', 'Smith', '2008-05-20', 'male', 'N/A', 'N/A', 'Apple Creek', '12343 Test Road', 'Fredericksburg', 'VA', '22405', 'Smith High School', '10', '0', 'Caucasian'),
(2, 4, 'Jane', 'Smith', '2012-11-01', 'female', 'Peanut Allergy', 'N/A', 'Apple Creek', '12343 Test Road', 'Fredericksburg', 'VA', '22405', 'Smith Middle School', '7', '0', 'Caucasian'),
(3, 4, 'Jack', 'Smith', '2016-09-13', 'male', 'Caucasian', 'N/A', 'N/A', 'Apple Creek', '12343 Test Road', 'Fredericksburg', 'VA', '22405', 'Smith Elementary School', '3', '0'),
(4, 5, 'Mathew', 'Anderson', '2018-05-05', 'male', 'N/A', 'N/A', 'Orchard Run', '7465 Orchard Drive', 'Stafford', 'VA', '22554', 'Anderson High School', 'Kindergarten', '0', 'Multiracial'),
(5, 6, 'Thomas', 'Johnson', '2008-04-05', 'male', 'None', 'No', 'Fox Woods', '125 Fox Drive', 'Stafford', 'VA', '22554', 'Johnson High School', '10', '0', 'Caucasian'),
(6, 7, 'Lucy', 'Ramirez', '2009-05-19', 'female', 'n/a', 'n/a', 'Oakland Plaza', '123 Oakland Drive', 'Stafford', 'VA', '22555', 'Johnson High School', '11', '1', 'Multiracial'),
(7, 7, 'Adrian', 'Ramirez', '2007-02-02', 'male', 'n/a', 'n/a', 'Oakland Plaza', '123 Oakland Drive', 'Stafford', 'VA', '22554', 'Johnson High School', '9', '1', 'Multiracial'),
(8, 8, 'Daniel', 'Garcia', '2013-05-29', 'male', 'n/a', 'n/a', 'Apple Creek', '123 Creek Road', 'Fredericksburg', 'VA', '22405', 'Smith Middle School', '7', '1', 'Multiracial'),
(9, 9, 'William', 'Parker', '2013-01-23', 'male', 'n/a', 'n/a', 'Buffalo Hills', '456 Buffalo Road', 'Fredericksburg', 'VA', '22401', 'Buffalo Middle School', '7', '0', 'Black/African American'),
(10, 9, 'Natalie', 'Parker', '2014-02-27', 'female', 'n/a', 'n/a', 'Buffalo Hills', '456 Buffalo Road', 'Fredericksburg', 'VA', '22401', 'Buffalo Middle School', '6', '0', 'Black/African American'),
(11, 10, 'Louise', 'Martin', '2012-01-02', 'female', 'none', 'none', 'Orchard Run', '7001 Orchard Drive', 'Stafford', 'VA', '22554', 'Anderson High School', '7', '0', 'Caucasian');
