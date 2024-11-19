DROP TABLE IF EXISTS dbBrainBuildersHolidayPartyForm;

CREATE TABLE dbBrainBuildersHolidayPartyForm (
    id INT PRIMARY KEY AUTO_INCREMENT,
    family_id INT NOT NULL,
    email VARCHAR(100) NOT NULL,
    child_first_name VARCHAR(50) NOT NULL,
    child_last_name VARCHAR(50) NOT NULL,
    isAttending BOOLEAN NOT NULL, 
    transportation VARCHAR(50) NOT NULL,
    neighborhood VARCHAR(50) NOT NULL,
    comments TEXT,
    CONSTRAINT FK_brainbuilders_family_id
        FOREIGN KEY (family_id) REFERENCES dbFamily(id)
        ON DELETE CASCADE 
        ON UPDATE CASCADE
);
