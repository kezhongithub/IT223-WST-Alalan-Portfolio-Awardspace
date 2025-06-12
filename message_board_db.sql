-- Create the database
CREATE DATABASE IF NOT EXISTS message_board_db;
USE message_board_db;

-- Create the message table
CREATE TABLE IF NOT EXISTS message_tbl (
    Message_ID INT AUTO_INCREMENT PRIMARY KEY,
    Full_Name VARCHAR(100) NOT NULL,
    Email VARCHAR(50) NOT NULL,
    Message_Content TEXT NOT NULL,
    Date_posted DATE NOT NULL
); 