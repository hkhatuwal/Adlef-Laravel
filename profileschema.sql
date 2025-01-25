CREATE TABLE user_profile (
    -- Personal Information
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    last_name VARCHAR(100) NOT NULL,
    alias VARCHAR(100),
    date_of_birth DATE,
    place_of_birth VARCHAR(100),
    gender ENUM('male', 'female'),
    marital_status ENUM('Single', 'Married', 'Divorced', 'Widowed'),
    citizenship_id INT,
    has_dual_citizenship BOOLEAN DEFAULT FALSE,

    -- Economic Profile
    current_occupation VARCHAR(200),
    annual_income_range ENUM(
        'Under US$250k', 
        'US$250k - US$500k', 
        'US$500k - US$1mil', 
        'US$1mil - US$5mil', 
        'Over US$5mil'
    ),
    account_purpose SET(
        'Custody', 
        'Asset Servicing', 
        'Escrow', 
        'Investments', 
        'Treasury Services', 
        'Other'
    ),
    funds_source SET(
        'Salary', 
        'Inheritance', 
        'Divorce Settlement', 
        'Pension/SavingsFromEmployment', 
        'Sale Of Property', 
        'Interest Income', 
        'Capital Gain/Dividends', 
        'Gambling', 
        'Gift', 
        'Other'
    ),
    wealth_source SET(
        'Salary', 
        'Inheritance', 
        'Divorce Settlement', 
        'Pension/SavingsFromEmployment', 
        'Sale Of Property', 
        'Interest Income', 
        'Capital Gain/Dividends', 
        'Gambling', 
        'Gift', 
        'Other'
    ),
    anticipated_asset_class ENUM(
        'Custody Asset Servicing', 
        'Escrow', 
        'Investments', 
        'Business Transactions', 
        'Other'
    ),
    third_party_contributions BOOLEAN,

    -- Contact Information
    country_code VARCHAR(10),
    phone_number VARCHAR(20),
    phone_type ENUM('Mobile', 'Home', 'Office'),
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,

    -- Residential Address
    street_address_1 VARCHAR(255),
    street_address_2 VARCHAR(255),
    city VARCHAR(100),
    state_province VARCHAR(100),
    postal_code VARCHAR(20),
    country_id INT,

    -- Tax Residency
    is_hong_kong_tax_resident BOOLEAN,
    tax_identification_number VARCHAR(50),
    secondary_tax_country_id INT,

    -- Consent and Tracking
    agreement_accepted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);