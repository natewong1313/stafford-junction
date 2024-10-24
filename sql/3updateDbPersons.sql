START TRANSACTION;

/* ------ Updating dbPersons id, we also need to update fk relationships ---------- */
ALTER TABLE `dbEventVolunteers`
    DROP FOREIGN KEY `FKpersonID`;
ALTER TABLE `dbPersons`
    DROP COLUMN `id`;
/* now we modify id to be type of int and also need to update dbEventVolunteers */
ALTER TABLE `dbPersons`
    ADD COLUMN `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `dbEventVolunteers`
    CHANGE COLUMN `userID` `userID` INT NOT NULL;
ALTER TABLE `dbEventVolunteers`
  ADD CONSTRAINT `FKpersonID` FOREIGN KEY (`userID`) REFERENCES `dbPersons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/* now add new columns */
ALTER TABLE `dbPersons`
    ADD COLUMN `security_question` TEXT NOT NULL,
    ADD COLUMN `security_answer` TEXT NOT NULL,
    ADD COLUMN `emergency_first_name` TEXT NOT NULL,
    ADD COLUMN `emergency_last_name` TEXT NOT NULL,
    ADD COLUMN `emergency_phone` TEXT NOT NULL,
    ADD COLUMN `emergency_relation` TEXT NOT NULL,
    ADD COLUMN `is_active` BOOLEAN NOT NULL DEFAULT 1;

COMMIT;