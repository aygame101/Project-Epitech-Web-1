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
    salt VARCHAR (255)
);

CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    salt VARCHAR (255) NOT NULL,
    UNIQUE(name)  -- Ensures the company name is unique
);

CREATE TABLE job_ads (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    company_name VARCHAR(100) NOT NULL,
    city VARCHAR(255) NOT NULL,
    job_title VARCHAR(255) NOT NULL,
    contract_type VARCHAR(100) NOT NULL,
    wage VARCHAR(30) NOT NULL,
    mail_in_charge VARCHAR(30) NOT NULL,
    description_job TEXT NOT NULL
);

CREATE TABLE requeststorage (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    id_annonce_postule INT NOT NULL,  -- Reference to an ad from the Advertising table
    applayer_info INT NOT NULL,       -- Reference to an applicant from the Applayers table
    id_company INT NOT NULL,          -- Reference to a company from the Companies table
    FOREIGN KEY (id_annonce_postule) REFERENCES job_ads(id),
    FOREIGN KEY (applayer_info) REFERENCES people(id),
    FOREIGN KEY (id_company) REFERENCES companies(id)
);