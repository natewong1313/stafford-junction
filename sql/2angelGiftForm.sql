--
-- Table structure for table `dbAngelGiftForm`
--

CREATE TABLE `dbAngelGiftForm` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `child_id` int(11) NOT NULL,
  `email` varchar(256) NOT NULL,
  `parent_name` varchar(256) NOT NULL,
  `phone` int(12) NOT NULL,
  `child_name` varchar(256) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `age` int(5) NOT NULL,
  `pants_size` varchar(5),
  `shirt_size` varchar(5),
  `shoe_size` int(2),
  `coat_size` varchar(5),
  `underwear_size` varchar(5),
  `sock_size` int(2),
  `wants` text NOT NULL,
  `interests` text NOT NULL,
  `photo_release` boolean NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Constraints for table `dbAngelGiftForm`
--
ALTER TABLE `dbAngelGiftForm`
  ADD CONSTRAINT `FKangelgift` FOREIGN KEY (`child_id`) REFERENCES `dbChildren` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;