
-- these columns should be a part of dbPersons, which is our "account" table
ALTER TABLE `dbFamily`
    DROP COLUMN `password`,
    DROP COLUMN `securityQuestion`,
    DROP COLUMN `securityAnswer`,
    DROP COLUMN `accountType`,
    -- nitpick
    MODIFY COLUMN `isArchived` BOOLEAN NOT NULL DEFAULT FALSE,
    ADD COLUMN `person_id`  varchar(256) NOT NULL,
    ADD CONSTRAINT `FKfamilyPersonID` FOREIGN KEY (`person_id`) REFERENCES `dbPersons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- add dummy data into dbFamily for our vmsroot user
INSERT INTO `dbFamily` (
    `firstName`, `lastName`, `birthdate`, `address`, `city`, `state`, `zip`, `email`, `phone`, `phoneType`,
    `secondaryPhone`, `secondaryPhoneType`, `firstName2`, `lastName2`, `birthdate2`, `address2`, `city2`, `state2`,
    `zip2`, `email2`, `phone2`, `phoneType2`, `secondaryPhone2`, `secondaryPhoneType2`, `econtactFirstName`,
    `econtactLastName`, `econtactPhone`, `econtactRelation`, `person_id`
) 
SELECT 
    'VMS', 'ROOT', '2003-01-01', '1234 road st', 'Fredericksburg', 'VA', '22401', 'vmsroot@gmail.com', '1231231234', 'Mobile',
    '3213214321', 'Mobile', 'John', 'Smith', '2003-02-02', '1234 road st', 'Fredericksburg', 'VA', '22401', 'johnsmith@gmail.com',
    '5675675678', 'Mobile', '7897897891', 'Mobile', 'Jane', 'Smith', '3453453456', 'Person', id
FROM `dbPersons`
WHERE `email` = 'vmsroot';

COMMIT;