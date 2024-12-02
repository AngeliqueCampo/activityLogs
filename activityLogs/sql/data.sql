CREATE TABLE `activity_logs` (
  `logID` INT(11) NOT NULL,
  `userID` INT(11) NOT NULL,
  `action_type` ENUM('INSERT', 'UPDATE', 'DELETE', 'SEARCH', 'LOGIN', 'LOGOUT') NOT NULL,
  `description` TEXT NOT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `search_keyword` VARCHAR(255) DEFAULT NULL,
  `username` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`logID`)
);

CREATE TABLE `applications` (
  `applicationID` INT(11) NOT NULL,
  `userID` INT(11) NOT NULL,
  `cause` VARCHAR(100) NOT NULL,
  `skills` TEXT NOT NULL,
  `experience` TEXT NOT NULL,
  `submitted_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `firstName` VARCHAR(50) NOT NULL,
  `lastName` VARCHAR(50) NOT NULL,
  `added_by` INT(11) NOT NULL,
  `last_updated_by` INT(11) NOT NULL,
  PRIMARY KEY (`applicationID`)
);

CREATE TABLE `users` (
  `userID` INT(11) NOT NULL,
  `firstName` VARCHAR(50) NOT NULL,
  `lastName` VARCHAR(50) NOT NULL,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (`userID`)
);