-- Table for the dbChildCareWaiverForm
CREATE TABLE dbChildCareWaiverForm (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    child_id INT NOT NULL,
    child_first_name VARCHAR(256),
    child_last_name VARCHAR(256),
    birth_date DATE,
    gender VARCHAR(6),
    child_address VARCHAR(256),
    child_city VARCHAR(100),
    child_state VARCHAR(100),
    child_zip VARCHAR(10),
    
    parent_1 INT NOT NULL,
    parent1_first_name VARCHAR(256),
    parent1_last_name VARCHAR(256),
    parent1_address VARCHAR(256),
    parent1_city VARCHAR(100),
    parent1_state VARCHAR(100),
    parent1_zip_code VARCHAR(10),
    parent1_email VARCHAR(256),
    parent1_cell_phone VARCHAR(15),
    parent1_home_phone VARCHAR(15),
    parent1_work_phone VARCHAR(15),
    
    parent_2 INT NOT NULL,
    parent2_first_name VARCHAR(256),
    parent2_last_name VARCHAR(256),
    parent2_address VARCHAR(256),
    parent2_city VARCHAR(100),
    parent2_state VARCHAR(100),
    parent2_zip_code VARCHAR(10),
    parent2_email VARCHAR(256),
    parent2_cell_phone VARCHAR(15),
    parent2_home_phone VARCHAR(15),
    parent2_work_phone VARCHAR(15),
    
    parent_guardian_signature VARCHAR(256),
    signature_date DATE,
    
    child_id INT,
    
    CONSTRAINT FK_child_id
        FOREIGN KEY (child_id) REFERENCES dbChildren(id)
        ON DELETE CASCADE 
        ON UPDATE CASCADE
);
