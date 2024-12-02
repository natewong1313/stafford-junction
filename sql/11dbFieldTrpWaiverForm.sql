--
-- Drop table if it already exist
--
DROP TABLE IF EXISTS `dbFieldTripWaiverForm`;

--
-- Table for the `dbFieldTripWaiverForm`
--
CREATE TABLE `dbFieldTripWaiverForm` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `child_id` INT NOT NULL,
    `child_name` varchar(256) NOT NULL,
    `gender` VARCHAR(6) NOT NULL,
    `birth_date` DATE NOT NULL,
    `neighborhood` VARCHAR(256) NOT NULL,
    `school` VARCHAR(256) NOT NULL,
    `child_address` VARCHAR(256) NOT NULL,
    `child_city` VARCHAR(100) NOT NULL,
    `child_state` VARCHAR(100) NOT NULL,
    `child_zip` VARCHAR(10) NOT NULL, 
    `parent_email` VARCHAR(256) NOT NULL,

    `emgcy_contact_name_1` VARCHAR(256) NOT NULL,
    `emgcy_contact1_rship` VARCHAR(100) NOT NULL,
    `emgcy_contact1_phone` VARCHAR(15) NOT NULL,

    `emgcy_contact_name_2` VARCHAR(256) NOT NULL,
    `emgcy_contact2_rship` VARCHAR(100) NOT NULL,
    `emgcy_contact2_phone` VARCHAR(15) NOT NULL,
 
    `medical_insurance_company` VARCHAR(256) NOT NULL,
    `policy_number` VARCHAR(50) NOT NULL,
    `photo_waiver_signature` VARCHAR(256) NOT NULL,
    `photo_waiver_date` DATE NOT NULL,

--
-- Constraints for table `dbFieldTripWaiverForm`
--
    CONSTRAINT FK_field_trip_child_id
        FOREIGN KEY (`child_id`) REFERENCES `dbChildren` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

