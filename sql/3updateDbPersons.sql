START TRANSACTION;

/* ------ Updating dbPersons id, we also need to update fk relationships ---------- */
ALTER TABLE `dbEventVolunteers`
    DROP FOREIGN KEY `FKpersonID`;
ALTER TABLE `dbPersons`
    DROP COLUMN `id`;
/* now we modify id to be type of int and also need to update dbEventVolunteers */
ALTER TABLE `dbPersons`
    ADD COLUMN `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `dbEventVolunteers`
    CHANGE COLUMN `userID` `userID` int NOT NULL;
ALTER TABLE `dbEventVolunteers`
  ADD CONSTRAINT `FKpersonID` FOREIGN KEY (`userID`) REFERENCES `dbPersons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/* now add new columns */
ALTER TABLE `dbPersons`
    ADD COLUMN `secondary_first_name` text,
    ADD COLUMN `secondary_last_name` text,
    ADD COLUMN `secondary_birthday` varchar(10),
    ADD COLUMN `secondary_address` text,
    ADD COLUMN `secondary_city` text,
    ADD COLUMN `secondary_state` text,
    ADD COLUMN `secondary_zip` int,
    ADD COLUMN `secondary_email` varchar(256),
    ADD COLUMN `secondary_phone1` varchar(12),
    ADD COLUMN `secondary_phone1_type` varchar(256),
    ADD COLUMN `secondary_phone2` varchar(12),
    ADD COLUMN `secondary_phone2_type` varchar(256),
    ADD COLUMN `security_question` text NOT NULL,
    ADD COLUMN `security_answer` text NOT NULL,
    ADD COLUMN `emergency_first_name` text NOT NULL,
    ADD COLUMN `emergency_last_name` text NOT NULL,
    ADD COLUMN `emergency_phone` text NOT NULL,
    ADD COLUMN `emergency_relation` text NOT NULL,
    ADD COLUMN `is_active` boolean NOT NULL DEFAULT 1;

COMMIT;