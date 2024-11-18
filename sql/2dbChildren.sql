--
-- Table structure for table `dbChildren`
--
CREATE TABLE `dbChildren` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `family_id` int(11) NOT NULL,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(6) NOT NULL,
  `medical_notes` text,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Constraints for table `dbChildren`
--
ALTER TABLE `dbChildren`
  ADD CONSTRAINT `dbChildren_family_id_FK` FOREIGN KEY (`family_id`) REFERENCES `dbFamily` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;