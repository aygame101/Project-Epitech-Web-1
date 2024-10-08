CREATE DATABASE	web_project

CREATE TABLE peopleincharge (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    mail VARCHAR(100) NOT NULL
);

CREATE TABLE applayers (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name VARCHAR(100) NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    mail VARCHAR(255) NOT NULL
);


CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name VARCHAR(100) NOT NULL,
    address VARCHAR(100) NOT NULL,
    people_in_charge INT NOT NULL,
    UNIQUE(name),  -- Ensures the company name is unique
    FOREIGN KEY (people_in_charge) REFERENCES peopleincharge(id)
);

CREATE TABLE advertising (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    company_name VARCHAR(100) NOT NULL,  -- Reference to Companies
    poste VARCHAR(255) NOT NULL,
    salaire DECIMAL(10, 2) NOT NULL,
    contrat VARCHAR(100) NOT NULL,
    lieu VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    FOREIGN KEY (company_name) REFERENCES Companies(name)  -- Foreign key to Companies table
);

CREATE TABLE requeststorage (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    id_annonce_postule INT NOT NULL,  -- Reference to an ad from the Advertising table
    applayer_info INT NOT NULL,       -- Reference to an applicant from the Applayers table
    id_company INT NOT NULL,          -- Reference to a company from the Companies table
    FOREIGN KEY (id_annonce_postule) REFERENCES Advertising(id),
    FOREIGN KEY (applayer_info) REFERENCES Applayers(id),
    FOREIGN KEY (id_company) REFERENCES Companies(id)
);
