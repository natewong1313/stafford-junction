
ALTER TABLE `dbFamily`
    -- nitpick
    DROP COLUMN `accountType`,
    MODIFY COLUMN `isArchived` BOOLEAN NOT NULL DEFAULT FALSE;

-- add dummy data into dbFamily for our vmsroot user
INSERT INTO `dbFamily` (
    `firstName`, `lastName`, `birthdate`, `address`, `city`, `state`, `zip`, `email`, `phone`, `phoneType`,
    `secondaryPhone`, `secondaryPhoneType`, `firstName2`, `lastName2`, `birthdate2`, `address2`, `city2`, `state2`,
    `zip2`, `email2`, `phone2`, `phoneType2`, `secondaryPhone2`, `secondaryPhoneType2`, `econtactFirstName`,
    `econtactLastName`, `econtactPhone`, `econtactRelation`, `password`, `securityQuestion`, `securityAnswer`
) 
VALUES (
    'VMS', 'ROOT', '2003-01-01', '1234 road st', 'Fredericksburg', 'VA', '22401', 'vmsroot@gmail.com', '1231231234', 'Mobile',
    '3213214321', 'Mobile', 'John', 'Smith', '2003-02-02', '1234 road st', 'Fredericksburg', 'VA', '22401', 'johnsmith@gmail.com',
    '5675675678', 'Mobile', '7897897891', 'Mobile', 'Jane', 'Smith', '3453453456', 'friend', '$2y$10$.3p8xvmUqmxNztEzMJQRBesLDwdiRU3xnt/HOcJtsglwsbUk88VTO',
    'Whats 9+10?', '$2y$10$RGQ3P7KOXfR2m1a2z6Tr7ekssfMzboKrt7TsmLjaalfeHEpKX0GUG'
)
