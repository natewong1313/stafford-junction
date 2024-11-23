--
-- Alter table `dbBrainBuildersRegistrationForm`
--
ALTER TABLE `dbBrainBuildersRegistrationForm`
  ADD COLUMN `child_id` INT NOT NULL;
  CHANGE COLUMN `gender` `child_gender` VARCHAR(6) NOT NULL,
  CHANGE COLUMN `school_name` `child_school_name` VARCHAR(100) NOT NULL,
  CHANGE COLUMN `grade` `child_grade` VARCHAR(20) NOT NULL,
  CHANGE COLUMN `birthdate` `child_dob` DATE NOT NULL,
  CHANGE COLUMN `needs_transportation` `transportation` ENUM('needs_transportation', 'transports_themselves') NOT NULL;

--
-- Constraints for table `dbBrainBuildersRegistrationForm`
--
ALTER TABLE `dbBrainBuildersRegistrationForm`
  ADD CONSTRAINT `FKBrainBuilderRegistration` FOREIGN KEY (`child_id`) REFERENCES `dbChildren` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;