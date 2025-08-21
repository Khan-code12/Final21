
CREATE DATABASE IF NOT EXISTS electricity_billing;
USE electricity_billing;

DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS complaints;
DROP TABLE IF EXISTS bills;
DROP TABLE IF EXISTS meters;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin','biller','consumer','manager') NOT NULL,
    fullname VARCHAR(100)
);

CREATE TABLE meters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    meter_no VARCHAR(50),
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE bills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    units INT,
    month VARCHAR(20),
    amount DECIMAL(10,2),
    status ENUM('unpaid','paid') DEFAULT 'unpaid',
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bill_id INT,
    amount DECIMAL(10,2),
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(bill_id) REFERENCES bills(id) ON DELETE CASCADE
);

CREATE TABLE complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    message TEXT,
    reply TEXT,
    status ENUM('open','closed') DEFAULT 'open',
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Seed users (plain password for demo)
INSERT INTO users(fullname,username,password,role) VALUES
('Super Admin','admin','admin123','admin'),
('Primary Biller','biller','biller123','biller'),
('Area Manager','manager','manager123','manager'),
('John Consumer','john','john123','consumer');

INSERT INTO meters(user_id,meter_no) VALUES ((SELECT id FROM users WHERE username='john'),'M000001');
