# linux install :   $  mysql -u root -p < ddl.sql
# ou bien passer par phpmyadmin

CREATE DATABASE IF NOT EXISTS DavidBotton;
USE DavidBotton;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS rendezvous (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    date DATE NOT NULL,
    start_hour TIME NOT NULL,
    end_hour TIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
