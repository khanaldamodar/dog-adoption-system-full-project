-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
-- Host: 127.0.0.1
-- Generation Time: Feb 19, 2025 at 06:11 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Ensure UTF-8 Encoding
/*!40101 SET NAMES utf8mb4 */;

-- ===================================
-- Database: `dog-adoption-project`
-- ===================================

-- =====================
-- Table: `admins`
-- =====================
CREATE TABLE `admins` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `username` VARCHAR(20) NOT NULL UNIQUE,
  `email` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL, -- Increased size for hashed passwords
  `role` ENUM('admin', 'moderator') NOT NULL DEFAULT 'admin',
  `status` TINYINT(1) DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admins` (`id`, `name`, `username`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'manoj', 'manoj', 'manoj123@gmail.com', 'manoj123', 'admin', 1, '2024-12-10 17:55:21', NULL);

-- =====================
-- Table: `adoption`
-- =====================
CREATE TABLE `adoption` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `name` VARCHAR(100) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `number` VARCHAR(15) DEFAULT NULL,
  `address` VARCHAR(255) DEFAULT NULL,
  `breed` VARCHAR(100) DEFAULT NULL,
  `dog_id` INT(11) DEFAULT NULL,
  `adoption_date` DATE DEFAULT NULL,
  `message` TEXT DEFAULT NULL,
  `status` ENUM('Pending', 'Accepted', 'Rejected') DEFAULT 'Pending',
  `admin_comment` TEXT DEFAULT NULL,
  `rejection_reason` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`dog_id`) REFERENCES `dogs` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================
-- Table: `contact`
-- =====================
CREATE TABLE `contact` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(15) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================
-- Table: `dogs`
-- =====================
CREATE TABLE `dogs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `age` INT(3) NOT NULL CHECK (`age` >= 0),
  `breed` VARCHAR(255) NOT NULL,
  `color` VARCHAR(255) NOT NULL,
  `size` ENUM('small','medium','large') NOT NULL,
  `gender` ENUM('male','female') NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 0,
  `message` TEXT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` INT(11) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================
-- Table: `donation`
-- =====================
CREATE TABLE `donation` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(50) NOT NULL,
  `lastname` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `number` VARCHAR(10) NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `donation_amount` DECIMAL(10,2) NOT NULL CHECK (`donation_amount` > 0),
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================


-- =====================
-- Table: `users`
-- =====================
CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL, -- Increased for hashed passwords
  `phone` VARCHAR(15) DEFAULT NULL,
  `profile_image` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================
-- Table Indexes & Constraints
-- =====================
ALTER TABLE `admins`
  ADD UNIQUE (`username`),
  ADD UNIQUE (`email`);

ALTER TABLE `adoption`
  ADD INDEX `fk_dog` (`dog_id`);

ALTER TABLE `users`
  ADD UNIQUE (`email`);

-- =====================
-- Table Auto Increments
-- =====================
ALTER TABLE `admins` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `adoption` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `contact` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `dogs` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `donation` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `users` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

COMMIT;
