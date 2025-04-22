
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(32) NOT NULL,
    confirm_password VARCHAR(32) NOT NULL,
    type INT NOT NULL DEFAULT 0
);
