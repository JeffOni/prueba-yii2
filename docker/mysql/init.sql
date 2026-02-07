-- Initialization script for MySQL database
-- This script runs when the Docker container is first created

-- Set character encoding
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Create database if not exists (already created by environment variables)
-- USE inventory_system;

-- Grant privileges
GRANT ALL PRIVILEGES ON *.* TO 'yii2user'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
