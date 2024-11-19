--
-- Drop table if it already exist
--
DROP TABLE IF EXISTS `dbFieldTrpWaiverForm`;

--
-- Table for the `dbFieldTripWaiverForm`
--
CREATE TABLE `dbFieldTrpWaiverForm` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `child_id` INT NOT NULL,
    `child_name` varchar(256) NOT NULL,
    `gender` VARCHAR(6),
    `birth_date` DATE,
    `neighborhood` VARCHAR(256),
    `school` VARCHAR(256),
    `child_address` VARCHAR(256),
    `child_city` VARCHAR(100),
    `child_state` VARCHAR(100),
    `child_zip` VARCHAR(10), 
    `parent_email` VARCHAR(256),

    `contact_1` INT NOT NULL,
    `emgcy_contact1_first_name` VARCHAR(256),
    `emgcy_contact1_last_name` VARCHAR(256),
    `emgcy_contact1_rship` VARCHAR(100),
    `emgcy_contact1_phone` VARCHAR(15),
   
    `contact_2` INT, -- Made nullable in case a second emergency contact is not provided
    `emgcy_contact2_first_name` VARCHAR(256),
    `emgcy_contact2_last_name` VARCHAR(256),
    `emgcy_contact2_rship` VARCHAR(100),
    `emgcy_contact2_phone` VARCHAR(15),
 
    `medical_insurance_company` VARCHAR(256),
    `policy_number` VARCHAR(50),
    `photo_waiver_signature` VARCHAR(256),
    `photo_waiver_date` DATE,

--
-- Constraints for table `dbFieldTripWaiverForm`
--
    CONSTRAINT FK_field_trip_child_id
        FOREIGN KEY (`child_id`) REFERENCES `dbChildren` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

