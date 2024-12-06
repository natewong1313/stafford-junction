-- Drop the tables if they already exist to avoid conflicts
DROP TABLE IF EXISTS `dbBusMonitorAttendanceForm`;
DROP TABLE IF EXISTS `dbRouteVolunteers`;
DROP TABLE IF EXISTS `dbRoute`;
DROP TABLE IF EXISTS `dbAttendees`;
DROP TABLE IF EXISTS `dbAttendance`;


-- Create the dbAttendees table
CREATE TABLE `dbAttendees` (
    `attendee_id` INT NOT NULL AUTO_INCREMENT, -- Primary key for attendees
    `name` VARCHAR(256) NOT NULL,              -- Name of the attendee
    `route_id` INT NOT NULL,                   -- Foreign key referencing dbRoute
    PRIMARY KEY (`attendee_id`),               -- Primary key constraint
    FOREIGN KEY (`route_id`) REFERENCES `dbRoute`(`route_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create the dbAttendance table
CREATE TABLE `dbAttendance` (
    `id` INT NOT NULL AUTO_INCREMENT,          -- Primary key for the attendance table
    `route_id` INT NOT NULL,                   -- Foreign key referencing route_id from dbRoute
    `volunteer_id` INT NOT NULL,               -- Foreign key referencing id from dbVolunteers
    `attendance_date` DATE NOT NULL,           -- The date of attendance
    `isPresent` BOOLEAN NOT NULL DEFAULT 0,    -- Indicates if the volunteer was present (1 = Yes, 0 = No)
    PRIMARY KEY (`id`),                        -- Primary key constraint
    UNIQUE (`route_id`, `volunteer_id`, `attendance_date`), -- Prevent duplicate records for the same day
    FOREIGN KEY (`route_id`) REFERENCES `dbRoute` (`route_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`volunteer_id`) REFERENCES `dbVolunteers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create the dbRoute table
CREATE TABLE `dbRoute` (
    `route_id` INT NOT NULL AUTO_INCREMENT,   -- Primary key for routes
    `route_direction` VARCHAR(25) NOT NULL,  -- Direction of the route (e.g., North/South)
    `route_name` VARCHAR(25) NOT NULL,       -- Name of the route
    PRIMARY KEY (`route_id`)                 -- Primary key constraint
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
    `id` INT NOT NULL AUTO_INCREMENT,         -- Primary key for the associative table
    `route_id` INT NOT NULL,                  -- Foreign key referencing dbRoute
    `volunteer_id` INT NOT NULL,              -- Foreign key referencing dbVolunteers
    PRIMARY KEY (`id`),                       -- Primary key constraint
    FOREIGN KEY (`route_id`) REFERENCES `dbRoute` (`route_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`volunteer_id`) REFERENCES `dbVolunteers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create the dbBusMonitorAttendanceForm table
CREATE TABLE `dbBusMonitorAttendanceForm` (
    `id` INT NOT NULL AUTO_INCREMENT,         -- Primary key for attendance forms
    `route_id` INT NOT NULL,                  -- Foreign key referencing route_id
    PRIMARY KEY (`id`),                       -- Primary key constraint
    FOREIGN KEY (`route_id`) REFERENCES `dbRoute` (`route_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;