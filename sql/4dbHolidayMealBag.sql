--
-- Table structure for table 'dbHolidayMealBagForm'
--
CREATE TABLE `dbHolidayMealBagForm` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `family_id` INT NOT NULL,
    `email` VARCHAR(256) NOT NULL,
    `household_size` INT NOT NULL,
    `meal_bag` VARCHAR(25) NOT NULL,
    `name` VARCHAR(256) NOT NULL,
    `address` VARCHAR(256) NOT NULL,
    `phone` CHAR(10) NOT NULL,
    `photo_release` TINYINT NOT NULL,
    CONSTRAINT `FKfamily_id`
    FOREIGN KEY (`family_id`) REFERENCES `dbFamily` (`id`) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE,
    CHECK (`phone` REGEXP '^[0-9]{10}$')  -- Ensures exactly 10 numeric digits
);

