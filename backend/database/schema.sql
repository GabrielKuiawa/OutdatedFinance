SET foreign_key_checks = 0;

DROP TABLE IF EXISTS resources;
DROP TABLE IF EXISTS expenses_tags;
DROP TABLE IF EXISTS expenses;
DROP TABLE IF EXISTS tags;
DROP TABLE IF EXISTS group_users;
DROP TABLE IF EXISTS groups;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    encrypted_password VARCHAR(255) NOT NULL,
    avatar VARCHAR(500),
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME
);

CREATE TABLE groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE group_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    group_id INT NOT NULL,
    joined_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE
);

CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    amount FLOAT NOT NULL,
    expense_date DATE NOT NULL,
    register_by_user_id INT NOT NULL,
    group_user_id INT NOT NULL,
    tag_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    register_payment_user_id INT,
    status ENUM('pendente', 'pago', 'atrasado') DEFAULT 'pendente',
    payment ENUM('cartao', 'pix', 'dinheiro'),
    FOREIGN KEY (register_by_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (group_user_id) REFERENCES group_users(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE SET NULL
);

CREATE TABLE expenses_tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tag_id INT NOT NULL,
    expenses_id INT NOT NULL,
    FOREIGNencrypted_password KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE,
    FOREIGN KEY (expenses_id) REFERENCES expenses(id) ON DELETE CASCADE
);

CREATE TABLE resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_path VARCHAR(500),
    expenses_id INT,
    FOREIGN KEY (expenses_id) REFERENCES expenses(id) ON DELETE CASCADE
);

SET foreign_key_checks = 1;
