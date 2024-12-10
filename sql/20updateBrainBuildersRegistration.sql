ALTER TABLE `dbBrainBuildersRegistrationForm`
ADD COLUMN child_id INT NOT NULL AFTER id;

ALTER TABLE `dbBrainBuildersRegistrationForm`
ADD CONSTRAINT `fk_form_child` FOREIGN KEY (`child_id`) REFERENCES `dbChildren` (`id`);