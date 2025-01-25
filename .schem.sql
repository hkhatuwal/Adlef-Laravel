-- User Table
CREATE TABLE User
(
    id           INT AUTO_INCREMENT PRIMARY KEY,
    username     VARCHAR(255)                  NOT NULL,
    password     VARCHAR(255)                  NOT NULL,
    account_type ENUM ('Bank', 'CryptoWallet') NOT NULL
);

-- BusinessDetails Table
CREATE TABLE BusinessDetails
(
    id                INT AUTO_INCREMENT PRIMARY KEY,
    registration_no   VARCHAR(255) NOT NULL,
    registration_date DATE         NOT NULL,
    country           VARCHAR(100) NOT NULL,
    user_id           INT          NOT NULL,
    FOREIGN KEY (user_id) REFERENCES User (id)
);

-- Contact Table
CREATE TABLE Contact
(
    id      INT AUTO_INCREMENT PRIMARY KEY,
    email   VARCHAR(255) NOT NULL,
    phone   VARCHAR(20)  NOT NULL,
    user_id INT          NOT NULL,
    FOREIGN KEY (user_id) REFERENCES User (id)
);

-- Address Table
CREATE TABLE Address
(
    id            INT AUTO_INCREMENT PRIMARY KEY,
    country       VARCHAR(100) NOT NULL,
    state         VARCHAR(100),
    postal_code   VARCHAR(20),
    city          VARCHAR(100),
    address_line1 VARCHAR(255),
    address_line2 VARCHAR(255),
    user_id       INT,
    individual_id INT,
    company_id    INT,
    FOREIGN KEY (user_id) REFERENCES User (id),
    FOREIGN KEY (individual_id) REFERENCES Individual (id),
    FOREIGN KEY (company_id) REFERENCES Company (id)
);

-- BankAccount Table
CREATE TABLE BankAccount
(
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    account_type        ENUM ('Own', 'ThirdParty') NOT NULL,
    account_holder_name VARCHAR(255)               NOT NULL,
    bank_name           VARCHAR(255)               NOT NULL,
    swift               VARCHAR(255),
    account_number      VARCHAR(50)                NOT NULL,
    shortcode           VARCHAR(20),
    branch_code         VARCHAR(20),
    user_id             INT                        NOT NULL,
    is_verified         TINYINT                    NOT NULL DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES User (id)
);

-- ThirdPartyAccount Table
CREATE TABLE ThirdPartyAccount
(
    id               INT AUTO_INCREMENT PRIMARY KEY,
    third_party_type ENUM ('Individual', 'Company') NOT NULL,
    bank_account_id  INT UNIQUE                     NOT NULL,
    individual_id    INT,
    company_id       INT,
    FOREIGN KEY (bank_account_id) REFERENCES BankAccount (id),
    FOREIGN KEY (individual_id) REFERENCES Individual (id),
    FOREIGN KEY (company_id) REFERENCES Company (id)
);

-- Individual Table
CREATE TABLE Individual
(
    id                      INT AUTO_INCREMENT PRIMARY KEY,
    fname                   VARCHAR(100) NOT NULL,
    lname                   VARCHAR(100) NOT NULL,
    dob                     DATE         NOT NULL,
    gender                  ENUM ('Male', 'Female', 'Other'),
    email                   VARCHAR(255) NOT NULL,
    contact                 VARCHAR(20),
    country_of_origin       VARCHAR(100),
    relationship            ENUM ('Vendor', 'Client', 'Supplier', 'Consultant', 'Professional Service Provider'),
    document_id_number      VARCHAR(255),
    document_issued_country VARCHAR(100),
    document_url            VARCHAR(255),
    third_party_account_id  INT UNIQUE,
    FOREIGN KEY (third_party_account_id) REFERENCES ThirdPartyAccount (id)
);

-- Company Table
CREATE TABLE Company
(
    id                     INT AUTO_INCREMENT PRIMARY KEY,
    company_name           VARCHAR(255) NOT NULL,
    country                VARCHAR(100) NOT NULL,
    registration_date      DATE         NOT NULL,
    registration_number    VARCHAR(255),
    email                  VARCHAR(255),
    contact                VARCHAR(20),
    relationship           ENUM ('Vendor', 'Client', 'Supplier', 'Consultant', 'Professional Service Provider'),
    registration_proof     VARCHAR(255),
    third_party_account_id INT UNIQUE,
    FOREIGN KEY (third_party_account_id) REFERENCES ThirdPartyAccount (id)
);

-- CryptoWallet Table
CREATE TABLE CryptoWallet
(
    id              INT AUTO_INCREMENT PRIMARY KEY,
    crypto_currency VARCHAR(50)  NOT NULL,
    wallet_address  VARCHAR(255) NOT NULL,
    alias           VARCHAR(100),
    user_id         INT          NOT NULL,
    is_verified         TINYINT                    NOT NULL DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES User (id)
);
