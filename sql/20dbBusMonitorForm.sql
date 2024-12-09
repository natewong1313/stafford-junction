-- Drop the tables if they already exist to avoid conflicts
DROP TABLE IF EXISTS `dbRouteVolunteers`;
DROP TABLE IF EXISTS `dbRoute`;
DROP TABLE IF EXISTS `dbAttendees`;
DROP TABLE IF EXISTS `dbAttendance`;

-- Create the dbRoute table
CREATE TABLE `dbRoute` (
    `route_id` INT NOT NULL AUTO_INCREMENT,   
    `route_direction` VARCHAR(25) NOT NULL,  
    `route_name` VARCHAR(25) NOT NULL,       
    PRIMARY KEY (`route_id`)                 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create the dbAttendees table
CREATE TABLE `dbAttendees` (
    `attendee_id` INT NOT NULL AUTO_INCREMENT, 
    `name` VARCHAR(256) NOT NULL,              
    `route_id` INT NOT NULL,                   
    `child_id` INT NOT NULL,                   
    PRIMARY KEY (`attendee_id`),               
    FOREIGN KEY (`route_id`) REFERENCES `dbRoute`(`route_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`child_id`) REFERENCES `dbChildren`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create the dbAttendance table
CREATE TABLE `dbAttendance` (
    `id` INT NOT NULL AUTO_INCREMENT,         
    `route_id` INT NOT NULL,                  
    `participant_id` INT NOT NULL,            
    `participant_type` ENUM('volunteer', 'attendee') NOT NULL, 
    `attendance_date` DATE NOT NULL,          
    `is_present` BOOLEAN NOT NULL DEFAULT 1,  
    PRIMARY KEY (`id`),                       
    FOREIGN KEY (`route_id`) REFERENCES `dbRoute` (`route_id`) ON DELETE CASCADE ON UPDATE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- These are the route directions/names from the form
INSERT INTO dbRoute (route_direction, route_name) VALUES 
('North', 'Foxwood'),
('South', 'Meadows'),
('South', 'Jefferson Place'),
('South', 'Olde Forge'),
('South', 'England Run');

-- Create the dbRouteVolunteers table (Associative Table for Route and Volunteers)
CREATE TABLE `dbRouteVolunteers` (
    `id` INT NOT NULL AUTO_INCREMENT,         
    `route_id` INT NOT NULL,                  
    `volunteer_id` INT NOT NULL,              
    PRIMARY KEY (`id`),                       
    FOREIGN KEY (`route_id`) REFERENCES `dbRoute` (`route_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`volunteer_id`) REFERENCES `dbVolunteers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

