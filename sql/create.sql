# Data Definition

# linux install :   $     mysql -u root -p < create_database.sql
# ou bien passer par phpmyadmin

CREATE DATABASE IF NOT EXISTS agenda_db;
USE agenda_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
