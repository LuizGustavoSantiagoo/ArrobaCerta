SET foreign_key_checks = 0;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(50) NOT NULL,
    encrypted_password VARCHAR(255) NOT NULL,
    role enum('manager', 'employee') NOT NULL,
    status enum('active', 'inactive') NOT NULL,
    profile_photo_path VARCHAR(255) NULL,
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB;

SET foreign_key_checks = 1;