CREATE DATABASE IF NOT EXISTS db_pulokalapa;
USE db_pulokalapa;

CREATE TABLE IF NOT EXISTS admin (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

-- Password: admin123 (hash dengan PASSWORD_DEFAULT)
INSERT INTO admin (username, password) VALUES (
  'admin',
  'admin123' 
);