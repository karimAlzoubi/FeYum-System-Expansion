/*
  This schema (database) is designed to manage HR operations, including:
  - Job postings and applications
  - HR staff information
  - Employee records
  - Communication and audit logs
*/
CREATE DATABASE hr_management_system;

USE hr_management_system;

-- TABLE: roles
CREATE TABLE roles (
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL UNIQUE
);

-- TABLE: hr
CREATE TABLE hr (
    hr_id INT PRIMARY KEY AUTO_INCREMENT,
    role_id INT NOT NULL,
    password VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    CONSTRAINT fk_hr_role
        FOREIGN KEY (role_id) 
        REFERENCES roles(role_id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

-- TABLE: documents
CREATE TABLE documents (
    document_id INT PRIMARY KEY AUTO_INCREMENT,
    file_path VARCHAR(255) NOT NULL,
    type ENUM('Resume', 'Certificate', 'Profile Picture') NOT NULL,
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- TABLE: jobs
CREATE TABLE jobs (
    job_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    location VARCHAR(100),
    salary DECIMAL(10, 2),
    deadline DATE,
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    posted_by INT NOT NULL,
    max_applications INT NOT NULL DEFAULT -1,
    education TEXT,
    experience TEXT,
    skills TEXT,
    languages TEXT,
    CONSTRAINT chk_jobs_max_applications
        CHECK (max_applications >= -1),
    CONSTRAINT fk_jobs_hr
        FOREIGN KEY (posted_by)
        REFERENCES hr(hr_id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

-- TABLE: applications
CREATE TABLE applications (
    application_id INT PRIMARY KEY AUTO_INCREMENT,
    job_id INT NOT NULL,            
    full_name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    mobile_number VARCHAR(10) NOT NULL,
    cv_document_id INT NOT NULL,
    experience_summary TEXT,
    languages VARCHAR(100) NOT NULL,
    skills TEXT,
    education_summary TEXT,
    linkedin_profile VARCHAR(200),
    application_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Under Review', 'Accepted', 'Rejected') DEFAULT 'Under Review',
    Id_number INT NOT NULL,
    nationality TEXT,
    CONSTRAINT fk_applications_documents
        FOREIGN KEY (cv_document_id)
        REFERENCES documents(document_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_applications_jobs
        FOREIGN KEY (job_id)
        REFERENCES jobs(job_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- TABLE: employee_info
CREATE TABLE employee_info (
    employee_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(20) NOT NULL,
    last_name VARCHAR(20) NOT NULL,
    Id_number VARCHAR(20) UNIQUE NOT NULL,
    nationality VARCHAR(50),
    job_title VARCHAR(30) NOT NULL,
    job_number VARCHAR(20),
    work_mobile VARCHAR(10),
    salary DECIMAL(10, 2),
    work_email VARCHAR(40) UNIQUE NOT NULL,
    branch VARCHAR(50),
    CONSTRAINT fk_employeeinfo_applications
        FOREIGN KEY (employee_id)
        REFERENCES applications(application_id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

-- TABLE: communication (created after employee_info to resolve FK dependency)
CREATE TABLE communication (
    message_id INT PRIMARY KEY AUTO_INCREMENT,
    hr_id INT NOT NULL,                              
    employee_id INT NOT NULL,                        
    sender_type ENUM('HR', 'Employee') NOT NULL,     
    send_message_content TEXT NOT NULL,              
    send_title VARCHAR(100) NOT NULL,               
    respond_message_content TEXT,
    respond_title VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_communication_hr
        FOREIGN KEY (hr_id)
        REFERENCES hr(hr_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_communication_employee
        FOREIGN KEY (employee_id)
        REFERENCES employee_info(employee_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- TABLE: audit_log
CREATE TABLE audit_log (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    table_name VARCHAR(50) NOT NULL,
    record_id INT NOT NULL,
    action ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
    action_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    details TEXT,
    CONSTRAINT fk_audit_log_hr
        FOREIGN KEY (user_id)
        REFERENCES hr(hr_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

-- Example Indexes (Fixed employee_number issue)
CREATE INDEX idx_applications_jobid ON applications (job_id);
CREATE INDEX idx_applications_cvdid ON applications (cv_document_id);
CREATE INDEX idx_jobs_postedby ON jobs (posted_by);
CREATE INDEX idx_employeeinfo_jobnum ON employee_info (job_number);
