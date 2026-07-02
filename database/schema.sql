SET foreign_key_checks = 0;

DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS cattle;
DROP TABLE IF EXISTS cattle_images;
DROP TABLE IF EXISTS cattle_vaccines;
DROP TABLE IF EXISTS vaccines;
DROP TABLE IF EXISTS tags;
DROP TABLE IF EXISTS cattle_tags;

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

CREATE TABLE cattle (
    id INT NOT NULL AUTO_INCREMENT,
    breed VARCHAR(255) NOT NULL,
    purchase_value_in_cents decimal NOT NULL,
    sale_value_in_cents decimal,
    purchase_date DATETIME NOT NULL,
    sale_date DATETIME,
    death_date DATETIME,
    death_reason VARCHAR(255),
    state ENUM('active', 'sold', 'dead') NOT NULL DEFAULT 'active',
    purchase_type VARCHAR(255) NULL,
    registered_by_user_id INT,
    PRIMARY KEY (id),
    CONSTRAINT fk_cattle_users FOREIGN KEY (registered_by_user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cattle_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    type ENUM('none', 'death_photo', 'cattle') NOT NULL DEFAULT 'none',
    cattle_id INT NOT NULL,
    registered_by_id INT NOT NULL,
    CONSTRAINT fk_cattle_images_cattle FOREIGN KEY (cattle_id) REFERENCES cattle(id),
    CONSTRAINT fk_cattle_images_user FOREIGN KEY (registered_by_id) REFERENCES users(id)
);

CREATE TABLE vaccines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL
);

CREATE TABLE cattle_vaccines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cattle_id INT NOT NULL,
    vaccine_id INT NOT NULL,
    application_date DATE NOT NULL,
    
    FOREIGN KEY (cattle_id) REFERENCES cattle(id) ON DELETE CASCADE,
    FOREIGN KEY (vaccine_id) REFERENCES vaccines(id) ON DELETE CASCADE
);

CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL
);

CREATE TABLE cattle_tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cattle_id INT NOT NULL,
    tag_id INT NOT NULL,
    application_date DATE NOT NULL,
    
    FOREIGN KEY (cattle_id) REFERENCES cattle(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

SET foreign_key_checks = 1;