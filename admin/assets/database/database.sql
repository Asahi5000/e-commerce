CREATE DATABASE car_ecommerce;
USE car_ecommerce;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,        
    name VARCHAR(100) NOT NULL,                   
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    profile_image VARCHAR(255) DEFAULT 'default.jpg',  
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



CREATE TABLE car_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE cars (
    car_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(100) NOT NULL,
    brand VARCHAR(100),
    model_year YEAR,
    price DECIMAL(12,2) NOT NULL,
    mileage INT,
    transmission ENUM('manual', 'automatic') NOT NULL,
    fuel_type ENUM('petrol', 'diesel', 'electric', 'hybrid'),
    description TEXT,
    image VARCHAR(255),
    stock INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES car_categories(category_id) ON DELETE SET NULL
);


CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(12,2) NOT NULL,
    status ENUM('pending','confirmed','shipped','delivered','cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);


CREATE TABLE messages (
  message_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  subject VARCHAR(150),
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


INSERT INTO users (username, name, email, password, phone, address, profile_image, role) 
VALUES (
  'admin',
  'John Owen Obias',
  'joobias29@gmail.com',
  'admin', 
  '09171234567',
  'Purok 3 Brgy. Del Rosario, Mercedes, Camarines Norte',
  'default.jpg',
  'admin'
);


INSERT INTO users (username, name, email, password, phone, address, profile_image, role) 
VALUES (
  'johndoe',
  'John Doe',
  'johndoe@example.com',
  '1234', 
  '09991234567',
  '456 Customer Avenue, Cebu, PH',
  'john.png',
  'customer'
);

-- Insert sample categories into car_categories
INSERT INTO car_categories (category_name) VALUES
('Sedan'),
('SUV'),
('Pickup'),
('Hatchback'),
('Convertible'),
('Van'),
('Sports Car'),
('Electric'),
('Hybrid'),
('Luxury');

CREATE TABLE bank_cards (
    card_id INT AUTO_INCREMENT PRIMARY KEY,
    card_holder VARCHAR(100) NOT NULL,
    card_number VARCHAR(16) NOT NULL UNIQUE,
    expiry_date VARCHAR(7) NOT NULL,  -- e.g., "12/28"
    cvv VARCHAR(4) NOT NULL,
    balance DECIMAL(12,2) DEFAULT 500000.00  -- initial demo balance
);

-- Insert demo card (for testing purchases)
INSERT INTO bank_cards (card_holder, card_number, expiry_date, cvv, balance)
VALUES ('try1', '123456789', '12/28', '123', 500000.00);


ALTER TABLE orders
ADD COLUMN car_id INT,
ADD CONSTRAINT fk_orders_car
    FOREIGN KEY (car_id) REFERENCES cars(car_id)
    ON DELETE SET NULL;

