DROP TABLE IF EXISTS dbSpringBreakCampForm;

CREATE TABLE dbSpringBreakCampForm (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email TEXT NOT NULL,
    student_name VARCHAR(256) NOT NULL,
    school_choice TEXT NOT NULL,
    isAttending TINYINT(1) NOT NULL, -- BOOLEAN is an alias for TINYINT(1)
    waiver_completed TINYINT(1) NOT NULL, -- BOOLEAN is an alias for TINYINT(1)
    notes TEXT,
    child_id INT,
    CONSTRAINT FK_dbspringbreakcampform_child_id
        FOREIGN KEY (child_id) REFERENCES dbChildren(id)
        ON DELETE CASCADE 
        ON UPDATE CASCADE
);
