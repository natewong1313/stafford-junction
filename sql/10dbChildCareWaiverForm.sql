DROP TABLE IF EXISTS `dbChildCareWaiverForm`;

CREATE TABLE `dbChildCareWaiverForm` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `child_id` INT NOT NULL,
    `child_name` VARCHAR(256) NOT NULL,
    `birth_date` DATE NOT NULL,
    `gender` VARCHAR(6) NOT NULL,
    `child_address` VARCHAR(256) NOT NULL,
    `child_city` VARCHAR(100) NOT NULL,
    `child_state` VARCHAR(100) NOT NULL,
    `child_zip` VARCHAR(10) NOT NULL,

    `parent1_first_name` VARCHAR(256) NOT NULL,
    `parent1_last_name` VARCHAR(256) NOT NULL,
    `parent1_address` VARCHAR(256) NOT NULL,
    `parent1_city` VARCHAR(100) NOT NULL,
    `parent1_state` VARCHAR(100) NOT NULL,
    `parent1_zip_code` VARCHAR(10) NOT NULL,
    `parent1_email` VARCHAR(256) NOT NULL,
    `parent1_cell_phone` VARCHAR(15) NOT NULL,
    `parent1_home_phone` VARCHAR(15) NOT NULL,
    `parent1_work_phone` VARCHAR(15) NOT NULL,

    `parent2_first_name` VARCHAR(256),
    `parent2_last_name` VARCHAR(256),
    `parent2_address` VARCHAR(256),
    `parent2_city` VARCHAR(100),
    `parent2_state` VARCHAR(100),
    `parent2_zip_code` VARCHAR(10),
    `parent2_email` VARCHAR(256),
    `parent2_cell_phone` VARCHAR(15),
    `parent2_home_phone` VARCHAR(15),
    `parent2_work_phone` VARCHAR(15),

    `parent_guardian_signature` VARCHAR(256) NOT NULL,
    `signature_date` DATE NOT NULL,

    CONSTRAINT `FK_dbcwaiverform_child_id`
        FOREIGN KEY (`child_id`) REFERENCES `dbChildren`(`id`)
        ON DELETE CASCADE 
        ON UPDATE CASCADE
);
 