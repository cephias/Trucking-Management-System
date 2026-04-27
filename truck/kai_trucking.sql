CREATE DATABASE IF NOT EXISTS kai_trucking;
USE kai_trucking;

-- Users table for authentication
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customerer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Trucks table
CREATE TABLE trucks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registration_number VARCHAR(20) UNIQUE NOT NULL,
    model VARCHAR(50) NOT NULL,
    capacity DECIMAL(10,2) NOT NULL, 
    status ENUM('available', 'in_use', 'maintenance') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Drivers table
CREATE TABLE drivers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    license_number VARCHAR(20) UNIQUE NOT NULL,
    contact VARCHAR(15) NOT NULL,
    assigned_truck_id INT,
    status ENUM('active', 'inactive', 'suspended', 'offduty') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_truck_id) REFERENCES trucks(id) ON DELETE SET NULL
);

-- Customers table
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    contact VARCHAR(15) NOT NULL,
    company_name VARCHAR(100),
    balance DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Trips table
CREATE TABLE trips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    origin VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    cargo_details TEXT,
    trip_cost DECIMAL(10,2) NOT NULL,
    driver_id INT,
    truck_id INT,
    customer_id INT,
    status ENUM('pending', 'ongoing', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (driver_id) REFERENCES drivers(id) ON DELETE SET NULL,
    FOREIGN KEY (truck_id) REFERENCES trucks(id) ON DELETE SET NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
);

INSERT INTO users (username, password, role) VALUES ('admin', '$2y$10$examplehashedpassword', 'admin'); 

INSERT INTO trucks (registration_number, model, capacity) VALUES
('KAA 123A', 'Scania', 20.5),
('KAB 456B', 'Volvo', 15.0);

INSERT INTO drivers (name, license_number, contact) VALUES
('John James', 'DL123456', '0712345678'),
('Jane Mwai', 'DL654321', '0723456789');

INSERT INTO customers (name, contact, company_name) VALUES
('ABC Logistics', '0734567890', 'ABC Ltd'),
('XYZ Transport', '0745678901', 'XYZ Corp');

INSERT INTO trips (origin, destination, cargo_details, trip_cost, driver_id, truck_id, customer_id) VALUES
('Nairobi', 'Mombasa', 'Electronics', 50000.00, 1, 1, 1);
