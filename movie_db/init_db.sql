-- Run this SQL in phpMyAdmin or MySQL CLI

CREATE DATABASE IF NOT EXISTS movie_db;
USE movie_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    genre VARCHAR(100) NOT NULL,
    director VARCHAR(150) NOT NULL,
    release_year YEAR NOT NULL,
    description TEXT NOT NULL,
    image_path VARCHAR(255) DEFAULT 'default.jpg'
);
