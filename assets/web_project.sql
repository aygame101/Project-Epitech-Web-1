--create the database first
CREATE DATABASE	web_project

-- Then on the database create the tables
CREATE TABLE people (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    applying INT NOT NULL,
    mail VARCHAR(255) NOT NULL,
    name VARCHAR(100),
    firstname VARCHAR(100),
    phone VARCHAR(20),
    password VARCHAR(255),
    salt VARCHAR (255),
);

CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    salt VARCHAR (255) NOT NULL,
    UNIQUE(name)  -- Ensures the company name is unique
);

CREATE TABLE advertising (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    company_name VARCHAR(100) NOT NULL,  -- Reference to Companies
    poste VARCHAR(255) NOT NULL,
    lieu VARCHAR(255) NOT NULL,
    salaire VARCHAR(20) NOT NULL,
    contrat VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    FOREIGN KEY (company_name) REFERENCES companies(name)  -- Foreign key to Companies table
);

CREATE TABLE requeststorage (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    id_annonce_postule INT NOT NULL,  -- Reference to an ad from the Advertising table
    applayer_info INT NOT NULL,       -- Reference to an applicant from the Applayers table
    id_company INT NOT NULL,          -- Reference to a company from the Companies table
    FOREIGN KEY (id_annonce_postule) REFERENCES advertising(id),
    FOREIGN KEY (applayer_info) REFERENCES people(id),
    FOREIGN KEY (id_company) REFERENCES companies(id)
);

--partie test
```
INSERT INTO people (firstname,mail,name,password,phone)
VALUES 
('mr','mrX@league.lol','x','123456789'),
('Wukong','wukong@king.kong','singe','2024')

INSERT INTO companies(name,address,people_in_charge)
VALUES 
('ubisoft','Paris, 15ème',1),
('Safran','Paris, 2ème',2),
('Firefly Inc','La Défense',3),
('AMD','Corbeilles-Essonne',4),
('','Versailles',5)
```
