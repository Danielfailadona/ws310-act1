CREATE DATABASE IF NOT EXISTS ws310_mini_db;
USE ws310_mini_db;

-- Main Applicants Table (Simplified - only 2 data columns)
CREATE TABLE IF NOT EXISTS applicants (
    applicant_id INT AUTO_INCREMENT PRIMARY KEY,
    ssnum VARCHAR(50) NOT NULL,
    lname VARCHAR(100) NOT NULL,
    fname VARCHAR(100) NOT NULL,
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Addresses Table (Simplified - only 2 data columns)
CREATE TABLE IF NOT EXISTS applicant_addresses (
    address_id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT NOT NULL,
    address_6 VARCHAR(100) NOT NULL, -- City
    address_7 VARCHAR(100) NOT NULL, -- Province
    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (applicant_id) REFERENCES applicants(applicant_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;