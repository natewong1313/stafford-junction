-- Table for the dbSpringBreakCampForm
CREATE TABLE dbSpringBreakCampForm (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email TEXT NOT NULL,
    student_name VARCHAR(256) NOT NULL,
    school_choice TEXT NOT NULL,
    isAttending BOOLEAN NOT NULL,
    waiver_completed BOOLEAN NOT NULL,
    notes TEXT,
    
    family_id INT,
    
    CONSTRAINT FK_family_id
        FOREIGN KEY (family_id) REFERENCES dbFamily(id)
        ON DELETE CASCADE 
        ON UPDATE CASCADE
);
