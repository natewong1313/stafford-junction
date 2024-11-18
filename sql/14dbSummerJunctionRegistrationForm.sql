--
-- Table structure for table `dbSummerJunctionRegistrationForm`
--

CREATE TABLE dbSummerJunctionRegistrationForm (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `child_id` int(11) NOT NULL,
    `steam` BOOLEAN,
    `summer_camp` BOOLEAN,
    `child_first_name` VARCHAR(50) NOT NULL,
    `child_last_name` VARCHAR(50) NOT NULL,
    `birthdate` DATE NOT NULL,
    `grade_completed` INT NOT NULL,
    `gender` ENUM('male', 'female') NOT NULL,
    `shirt_size` ENUM('child-xs', 'child-s', 'child-m', 'child-l', 'child-xl', 
                    'adult-s', 'adult-m', 'adult-l', 'adult-xl', 'adult-2x') NOT NULL,
    `neighborhood` VARCHAR(100) NOT NULL,
    `child_address` VARCHAR(100) NOT NULL,
    `child_city` VARCHAR(50) NOT NULL,
    `child_state` CHAR(2) NOT NULL,
    `child_zip` CHAR(5) NOT NULL,
    `child_medical_allergies` VARCHAR(255),
    `child_food_avoidances` VARCHAR(255),

    `parent1_first_name` VARCHAR(50) NOT NULL,
    `parent1_last_name` VARCHAR(50) NOT NULL,
    `parent1_address` VARCHAR(100) NOT NULL,
    `parent1_city` VARCHAR(50) NOT NULL,
    `parent1_state` CHAR(2) NOT NULL,
    `parent1_zip` CHAR(5) NOT NULL,
    `parent1_email` VARCHAR(100) NOT NULL,
    `parent1_cell_phone` VARCHAR(15) NOT NULL,
    `parent1_home_phone` VARCHAR(15),
    `parent1_work_phone` VARCHAR(15),

    `parent2_first_name` VARCHAR(50),
    `parent2_last_name` VARCHAR(50),
    `parent2_address` VARCHAR(100),
    `parent2_city` VARCHAR(50),
    `parent2_state` CHAR(2),
    `parent2_zip` CHAR(5),
    `parent2_email` VARCHAR(100),
    `parent2_cell_phone` VARCHAR(15),
    `parent2_home_phone` VARCHAR(15),
    `parent2_work_phone` VARCHAR(15),

    `emergency_contact1_name` VARCHAR(50) NOT NULL,
    `emergency_contact1_relationship` VARCHAR(50) NOT NULL,
    `emergency_contact1_phone` VARCHAR(15) NOT NULL,

    `emergency_contact2_name` VARCHAR(50),
    `emergency_contact2_relationship` VARCHAR(50),
    `emergency_contact2_phone` VARCHAR(15),

    `primary_language` VARCHAR(50) NOT NULL,
    `hispanic_latino_spanish` ENUM('yes', 'no') NOT NULL,
    `race` ENUM('Caucasian', 'Black/African American', 
               'Native Indian/Alaska Native', 'Native Hawaiian/Pacific Islander', 
               'Asian', 'Multiracial', 'Other') NOT NULL,
    `num_unemployed` INT,
    `num_retired` INT,
    `num_unemployed_students` INT,
    `num_employed_fulltime` INT,
    `num_employed_parttime` INT,
    `num_employed_students` INT,
    
    `income` ENUM('Under 20,000', '20,000-40,000', 
                 '40,001-60,000', '60,001-80,000', 'Over 80,000') NOT NULL,
    `other_programs` VARCHAR(255) NOT NULL,
    `lunch` ENUM('free', 'reduced', 'neither') NOT NULL,

    `insurance` VARCHAR(100) NOT NULL,
    `policy_num` VARCHAR(50) NOT NULL,
    `signature` VARCHAR(255) NOT NULL,
    `signature_date` DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Constraints for table `dbSummerJunctionRegistrationForm`
--
ALTER TABLE `dbSummerJunctionRegistrationForm`
  ADD CONSTRAINT `FKSummerJunctionRegistration` FOREIGN KEY (`child_id`) REFERENCES `dbChildren` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
