--
-- Alter table `dbBrainBuildersRegistrationForm`
--
ALTER TABLE `dbBrainBuildersRegistrationForm`
  ADD COLUMN `child_id` INT NOT NULL;

--
-- Constraints for table `dbBrainBuildersRegistrationForm`
--
ALTER TABLE `dbBrainBuildersRegistrationForm`
  ADD CONSTRAINT `FKBrainBuilderRegistration` FOREIGN KEY (`child_id`) REFERENCES `dbChildren` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;