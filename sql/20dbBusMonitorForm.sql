-- Drop the tables if they already exist to avoid conflicts
DROP TABLE IF EXISTS `dbBusMonitorAttendanceForm`;
DROP TABLE IF EXISTS `dbRoute`;
DROP TABLE IF EXISTS `dbAttendees`;

-- Create the dbAttendees table
CREATE TABLE `dbAttendees` (
    `attendee_id` INT NOT NULL AUTO_INCREMENT, -- Primary key for attendees
    `name` VARCHAR(256) NOT NULL,             -- Name of the attendee
    `isPresent` BOOLEAN NOT NULL,             -- Indicates if the attendee is present
    PRIMARY KEY (`attendee_id`)               -- Primary key constraint
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create the dbRoute table
CREATE TABLE `dbRoute` (
    `route_id` INT NOT NULL AUTO_INCREMENT,   -- Primary key for routes
    `volunteer_id` INT NOT NULL,              -- Foreign key referencing volunteer_id
    `attendee_id` INT NOT NULL,               -- Foreign key referencing attendee_id
    `route_direction` VARCHAR(25) NOT NULL,  -- Direction of the route
    `route_name` VARCHAR(25) NOT NULL,       -- Name of the route
    PRIMARY KEY (`route_id`),                -- Primary key constraint
    -- Constraints for table `dbRoute`
    CONSTRAINT `FK_route_attendee_id`
        FOREIGN KEY (`attendee_id`) REFERENCES `dbAttendees` (`attendee_id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create the dbBusMonitorAttendanceForm table
CREATE TABLE `dbBusMonitorAttendanceForm` (
    `id` INT NOT NULL AUTO_INCREMENT,         -- Primary key for attendance forms
    `route_id` INT NOT NULL,                  -- Foreign key referencing route_id
    PRIMARY KEY (`id`),                       -- Primary key constraint
    -- Constraints for table `dbBusMonitorAttendanceForm`
    CONSTRAINT `FK_bus_monitor_route_id`
        FOREIGN KEY (`route_id`) REFERENCES `dbRoute` (`route_id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
