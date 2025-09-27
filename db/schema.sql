CREATE DATABASE IF NOT EXISTS CONTACT_MANAGER;

USE CONTACT_MANAGER;

CREATE TABLE IF NOT EXISTS `Users` (
    `ID`        INT NOT NULL AUTO_INCREMENT,
    `FirstName` VARCHAR(50) NOT NULL,
    `LastName`  VARCHAR(50) NOT NULL,
    `Login`     VARCHAR(50) NOT NULL,
    `Password`  VARCHAR(50) NOT NULL,
    PRIMARY KEY(`ID`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `Contacts` (
    `ID`            INT NOT NULL AUTO_INCREMENT,
    `FirstName`     VARCHAR(50) NOT NULL,
    `LastName`      VARCHAR(50) NOT NULL,
    `PhoneNumber`   VARCHAR(50) NOT NULL,
    `Email`         VARCHAR(50) NOT NULL,
    `UserID`        INT NOT NULL DEFAULT '0',
    PRIMARY KEY(`ID`)
) ENGINE = InnoDB;

/* modify user username rules. database can only have a unique 
 * username */
ALTER TABLE `Users` ADD UNIQUE (`Login`); 

/* add foreign key constraint to user id on Contacts table. */
ALTER TABLE `Contacts` ADD CONSTRAINT `fk_contacts_user_id`
FOREIGN KEY (`UserID`) REFERENCES `Users`(`ID`);