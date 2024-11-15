DROP TABLE IF EXISTS `dbChildren`;
--
-- Table structure for table `dbChildren`
--

CREATE TABLE `dbChildren` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `family_id` int(11) NOT NULL,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `dob` date NOT NULL,
  `address` varchar(256) NOT NULL,
  `neighborhood` varchar(256) NOT NULL,
  `city` varchar(256) NOT NULL,
  `state` varchar(256) NOT NULL,
  `zip` varchar(256) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `school` varchar(256) NOT NULL,
  `grade` varchar(25) NOT NULL,
  `is_hispanic` varchar(256) NOT NULL,
  `race` varchar(256) NOT NULL,
  `medical_notes` text,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Constraints for table `dbChildren`
--
ALTER TABLE `dbChildren`
  ADD CONSTRAINT `dbChildren_family_id_FK` FOREIGN KEY (`family_id`) REFERENCES `dbFamily` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


--
-- Insert children into dbChildren which will be used for testing
--
INSERT INTO `dbChildren` (`id`, `family_id`, `first_name`, `last_name`, `dob`, `address`, `neighborhood`, `city`, `state`, `zip`, `gender`, `school`, `grade`, `is_hispanic`, `race`, `medical_notes`, `notes`) VALUES
(1, 4, 'Henry', 'Smith', '2008-05-20', '12343 Test Road', 'Apple Creek', 'Fredericksburg', 'VA', '22405', 'male', 'Smith High School', '10', '0', 'Caucasian', 'N/A', 'N/A'),
(2, 4, 'Jane', 'Smith', '2012-11-01', '12343 Test Road', 'Apple Creek', 'Fredericksburg', 'VA', '22405', 'female', 'Smith Middle School', '7', '0', 'Caucasian', 'Peanut Allergy', 'N/A'),
(3, 4, 'Jack', 'Smith', '2016-09-13', '12343 Test Road', 'Apple Creek', 'Fredericksburg', 'VA', '22405', 'male', 'Smith Elementary School', '3', '0', 'Caucasian', 'N/A', 'N/A'),
(4, 5, 'Mathew', 'Anderson', '2018-05-05', '7465 Orchard Drive', 'Orchard Run', 'Stafford', 'VA', '22554', 'male', 'Anderson High School', 'Kindergarten', '0', 'Multiracial', 'N/A', 'N/A'),
(5, 6, 'Thomas', 'Johnson', '2008-04-05', '125 Fox Drive', 'Fox Woods', 'Stafford', 'VA', '22554', 'male', 'Johnson High School', '10', '0', 'Caucasian', 'None', 'No'),
(6, 7, 'Lucy', 'Ramirez', '2009-05-19', '123 Oakland Drive', 'Oakland Plaza', 'Stafford', 'VA', '22555', 'female', 'Johnson High School', '11', '1', 'Multiracial', 'n/a', 'n/a'),
(7, 7, 'Adrian', 'Ramirez', '2007-02-02', '123 Oakland Drive', 'Oakland Plaza', 'Stafford', 'VA', '22554', 'male', 'Johnson High School', '9', '1', 'Multiracial', 'n/a', 'n/a'),
(8, 8, 'Daniel', 'Garcia', '2013-05-29', '123 Creek Road', 'Apple Creek', 'Fredericksburg', 'VA', '22405', 'male', 'Smith Middle School', '7', '1', 'Multiracial', 'n/a', 'n/a'),
(9, 9, 'William', 'Parker', '2013-01-23', '456 Buffalo Road', 'Buffalo Hills', 'Fredericksburg', 'VA', '22401', 'male', 'Buffalo Middle School', '7', '0', 'Black/African American', 'n/a', 'n/a'),
(10, 9, 'Natalie', 'Parker', '2014-02-27', '456 Buffalo Road', 'Buffalo Hills', 'Fredericksburg', 'VA', '22401', 'female', 'Buffalo Middle School', '6', '0', 'Black/African American', 'n/a', 'n/a'),
(11, 10, 'Louise', 'Martin', '2012-01-02', '7001 Orchard Drive', 'Orchard Run', 'Stafford', 'VA', '22554', 'female', 'Anderson High School', '7', '0', 'Caucasian', 'none', 'none');

