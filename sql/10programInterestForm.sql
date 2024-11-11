--
-- Drop all tables below if any exist
--
DROP TABLE IF EXISTS `dbProgramInterestsForm_ProgramInterests`;
DROP TABLE IF EXISTS `dbProgramInterests`;
DROP TABLE IF EXISTS `dbProgramInterestsForm_TopicInterests`;
DROP TABLE IF EXISTS `dbTopicInterests`;
DROP TABLE IF EXISTS `dbAvailability`;
DROP TABLE IF EXISTS `dbProgramInterestForm`;

--
-- Table structure for table `dbProgramInterestForm`
--
CREATE TABLE `dbProgramInterestForm` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `family_id` int(11) NOT NULL,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `address` varchar(256) NOT NULL,
  `city` varchar(256) NOT NULL,
  `neighborhood` varchar(256) NOT NULL,
  `state` varchar(2) NOT NULL,
  `zip` varchar(256) NOT NULL,
  `cell_phone` varchar(10) NOT NULL,
  `home_phone` varchar(10) NOT NULL,
  `email` varchar(256) NOT NULL,
  `child_num` int NOT NULL,
  `child_ages` varchar(256),
  `adult_num` int NOT NULL,
  CONSTRAINT `FKprogramInterestForm_Family` FOREIGN KEY (`family_id`) REFERENCES `dbFamily` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `dbProgramInterests`
--
CREATE TABLE `dbProgramInterests` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `interest` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `dbProgramInterests` (interest) VALUES ('Brain Builders');
INSERT INTO `dbProgramInterests` (interest) VALUES ('Camp Junction');
INSERT INTO `dbProgramInterests` (interest) VALUES ('Stafford County Sheriff’s Office Sports Camp');
INSERT INTO `dbProgramInterests` (interest) VALUES ('STEAM');
INSERT INTO `dbProgramInterests` (interest) VALUES ('YMCA');
INSERT INTO `dbProgramInterests` (interest) VALUES ('Tide Me Over Bags');
INSERT INTO `dbProgramInterests` (interest) VALUES ('English Language Conversation Classes');

--
-- Table structure for junction table `dbProgramInterestsForm_ProgramInterests`
--
CREATE TABLE `dbProgramInterestsForm_ProgramInterests` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `form_id` int(11) NOT NULL,
  `interest_id` int(11) NOT NULL,
  CONSTRAINT `FKprogramInterestForm_ProgramInterest` FOREIGN KEY (`form_id`) REFERENCES `dbProgramInterestForm` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FKprogramInterest_programInterestForm` FOREIGN KEY (`interest_id`) REFERENCES `dbProgramInterests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Table structure for table `dbTopicInterests`
--
CREATE TABLE `dbTopicInterests` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `interest` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `dbTopicInterests` (interest) VALUES ('Legal Services');
INSERT INTO `dbTopicInterests` (interest) VALUES ('Finances');
INSERT INTO `dbTopicInterests` (interest) VALUES ('Tenant Rights');
INSERT INTO `dbTopicInterests` (interest) VALUES ('Computer Skills/Literacy');
INSERT INTO `dbTopicInterests` (interest) VALUES ('Health/Wellness/Nutrition');
INSERT INTO `dbTopicInterests` (interest) VALUES ('Continuing Education');
INSERT INTO `dbTopicInterests` (interest) VALUES ('Parenting');
INSERT INTO `dbTopicInterests` (interest) VALUES ('Mental Health');
INSERT INTO `dbTopicInterests` (interest) VALUES ('Job/Career Guidance');
INSERT INTO `dbTopicInterests` (interest) VALUES ('Citizenship Classes');

--
-- Table structure for junction table `dbProgramInterestsForm_TopicInterests`
--
CREATE TABLE `dbProgramInterestsForm_TopicInterests` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `form_id` int(11) NOT NULL,
  `interest_id` int(11) NOT NULL,
  CONSTRAINT `FKprogramInterestForm_topicInterest` FOREIGN KEY (`form_id`) REFERENCES `dbProgramInterestForm` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FKtopicInterest_programInterestForm` FOREIGN KEY (`interest_id`) REFERENCES `dbTopicInterests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Table structure for table `dbAvailability`
--
CREATE TABLE `dbAvailability` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `form_id` int(11) NOT NULL,
  `day` varchar(256) NOT NULL,
  `morning` boolean NOT NULL,
  `afternoon` boolean NOT NULL,
  `evening` boolean NOT NULL,
  `specific_time` varchar(256) NOT NULL,
  CONSTRAINT `FKprogramInterestForm_Availability` FOREIGN KEY (`form_id`) REFERENCES `dbProgramInterestForm` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
