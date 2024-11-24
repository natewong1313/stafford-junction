--
-- Alter table `dbBrainBuildersRegistrationForm`
--
ALTER TABLE `dbBrainBuildersRegistrationForm`
  ADD COLUMN `child_id` INT NOT NULL,
  ADD COLUMN `child_email` VARCHAR(100) NOT NULL,
  CHANGE COLUMN `gender` `child_gender` VARCHAR(6) NOT NULL,
  CHANGE COLUMN `school_name` `child_school_name` VARCHAR(100) NOT NULL,
  CHANGE COLUMN `grade` `child_grade` VARCHAR(20) NOT NULL,
  CHANGE COLUMN `birthdate` `child_dob` DATE NOT NULL,
  MODIFY COLUMN `child_medical_allergies` VARCHAR(255) NOT NULL,
  MODIFY COLUMN `child_food_avoidances` VARCHAR(255) NOT NULL,
  MODIFY COLUMN `parent1_altPhone` CHAR(14),
  MODIFY COLUMN `num_unemployed` INT NOT NULL,
  MODIFY COLUMN `num_retired` INT NOT NULL,
  MODIFY COLUMN `num_unemployed_student` INT NOT NULL,
  MODIFY COLUMN `num_employed_fulltime` INT NOT NULL,
  MODIFY COLUMN `num_employed_parttime` INT NOT NULL,
  MODIFY COLUMN `num_employed_student` INT NOT NULL,
  CHANGE COLUMN `needs_transportation` `transportation` ENUM('needs_transportation', 'transports_themselves') NOT NULL;

--
-- Constraints for table `dbBrainBuildersRegistrationForm`
--
ALTER TABLE `dbBrainBuildersRegistrationForm`
  ADD CONSTRAINT `FKBrainBuilderRegistration` FOREIGN KEY (`child_id`) REFERENCES `dbChildren` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;