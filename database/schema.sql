CREATE DATABASE IF NOT EXISTS ws310_db;
USE ws310_db;

-- Main Applicants Table (Personal Information)
CREATE TABLE IF NOT EXISTS applicants (
    applicant_id INT AUTO_INCREMENT PRIMARY KEY,

    -- Personal Data
    ssnum VARCHAR(50) NOT NULL,
    lname VARCHAR(100) NOT NULL,
    fname VARCHAR(100) NOT NULL,
    mname VARCHAR(100),
    sfx VARCHAR(10),
    dbirth DATE NOT NULL,
    sex ENUM('Male', 'Female') NOT NULL,
    cvstatus VARCHAR(50) NOT NULL,
    cvstatus_other VARCHAR(100),
    taxid VARCHAR(50),
    nation VARCHAR(100) NOT NULL,
    religion VARCHAR(100),
    pbirth TEXT NOT NULL,

    -- Contact Information
    cphone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    tphone VARCHAR(50),

    -- Certification
    printed_name VARCHAR(255),
    cert_date DATE,

    -- System fields
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Indexes
    INDEX idx_ssnum (ssnum),
    INDEX idx_lname (lname),
    INDEX idx_fname (fname),
    INDEX idx_email (email),
    INDEX idx_cphone (cphone),
    INDEX idx_submission_date (submission_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Addresses Table
CREATE TABLE IF NOT EXISTS applicant_addresses (
    address_id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT NOT NULL,

    -- Home Address
    address_1 VARCHAR(100), -- Unit/Room/Building
    address_2 VARCHAR(100), -- House/Lot/Block
    address_3 VARCHAR(100), -- Street
    address_4 VARCHAR(100), -- Subdivision
    address_5 VARCHAR(100), -- Barangay/District
    address_6 VARCHAR(100) NOT NULL, -- City/Municipality
    address_7 VARCHAR(100) NOT NULL, -- Province
    address_8 VARCHAR(100) NOT NULL DEFAULT 'PHILIPPINES', -- Country
    address_9 VARCHAR(20) NOT NULL, -- ZIP Code
    same_as_pbirth BOOLEAN DEFAULT FALSE,

    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (applicant_id) REFERENCES applicants(applicant_id) ON DELETE CASCADE,
    INDEX idx_applicant_id (applicant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Parents Information Table
CREATE TABLE IF NOT EXISTS applicant_parents (
    parent_id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT NOT NULL,

    -- Father Information
    lfather VARCHAR(100),
    ffather VARCHAR(100),
    mfather VARCHAR(100),
    sfxfather VARCHAR(10),
    fbirth DATE,

    -- Mother Information
    lmother VARCHAR(100),
    fmother VARCHAR(100),
    mmother VARCHAR(100),
    sfxmother VARCHAR(10),
    mbirth DATE,

    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (applicant_id) REFERENCES applicants(applicant_id) ON DELETE CASCADE,
    INDEX idx_applicant_id (applicant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Spouse Information Table
CREATE TABLE IF NOT EXISTS applicant_spouse (
    spouse_id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT NOT NULL,

    lspouse VARCHAR(100),
    fspouse VARCHAR(100),
    mspouse VARCHAR(100),
    sfxspouse VARCHAR(10),
    sbirth DATE,

    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (applicant_id) REFERENCES applicants(applicant_id) ON DELETE CASCADE,
    INDEX idx_applicant_id (applicant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Children/Dependents Table
CREATE TABLE IF NOT EXISTS applicant_children (
    child_id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT NOT NULL,

    lname VARCHAR(100),
    fname VARCHAR(100),
    mname VARCHAR(100),
    sfx VARCHAR(10),
    dbirth DATE,

    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (applicant_id) REFERENCES applicants(applicant_id) ON DELETE CASCADE,
    INDEX idx_applicant_id (applicant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Employment Information Table
CREATE TABLE IF NOT EXISTS applicant_employment (
    employment_id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT NOT NULL,
    employment_type VARCHAR(50), -- 'Self-Employed', 'OFW', 'Non-Working Spouse'

    -- Self-Employed (SE)
    profession VARCHAR(100),
    ystart YEAR,
    mearning DECIMAL(12,2),

    -- Overseas Filipino Worker (OFW)
    faddress TEXT,
    ofw_monthly_earnings DECIMAL(12,2),

    -- Non-Working Spouse (NWS)
    spouse_ssnum VARCHAR(20),
    ffprogram DECIMAL(12,2),
    ffp VARCHAR(10),

    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (applicant_id) REFERENCES applicants(applicant_id) ON DELETE CASCADE,
    INDEX idx_applicant_id (applicant_id),
    INDEX idx_employment_type (employment_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

