CREATE DATABASE IF NOT EXISTS `emrs_db`;
USE `emrs_db`;


CREATE TABLE IF NOT EXISTS `roles` (
    `RoleID` INT AUTO_INCREMENT PRIMARY KEY,
    `RoleName` VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `users` (
    `UserID` INT AUTO_INCREMENT PRIMARY KEY,
    `Name` VARCHAR(100) NOT NULL,
    `Email` VARCHAR(100) NOT NULL UNIQUE,
    `Password` VARCHAR(255) NOT NULL,
    `RoleID` INT NOT NULL,
    CONSTRAINT `fk_users_role` 
        FOREIGN KEY (`RoleID`) REFERENCES `roles` (`RoleID`) 
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `equipment` (
    `EquipmentID` INT AUTO_INCREMENT PRIMARY KEY,
    `ItemName` VARCHAR(100) NOT NULL,
    `Category` VARCHAR(50) NOT NULL,
    `StockQuantity` INT NOT NULL DEFAULT 1,
    `ConditionStatus` VARCHAR(50) NOT NULL DEFAULT 'Good'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `requests` (
    `RequestID` INT AUTO_INCREMENT PRIMARY KEY,
    `EmployeeID` INT NOT NULL,
    `EquipmentID` INT NOT NULL,
    `Description` TEXT NOT NULL,
    `Priority` VARCHAR(20) NOT NULL DEFAULT 'Medium',
    `Status` VARCHAR(50) NOT NULL DEFAULT 'Pending',
    `CreatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_requests_employee` 
        FOREIGN KEY (`EmployeeID`) REFERENCES `users` (`UserID`) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_requests_equipment` 
        FOREIGN KEY (`EquipmentID`) REFERENCES `equipment` (`EquipmentID`) 
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `assignments` (
    `AssignmentID` INT AUTO_INCREMENT PRIMARY KEY,
    `RequestID` INT NOT NULL,
    `ManagerID` INT NOT NULL,
    `TechnicianID` INT NOT NULL,
    `AssignedDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `CompletionDate` DATETIME NULL DEFAULT NULL,
    CONSTRAINT `fk_assignments_request` 
        FOREIGN KEY (`RequestID`) REFERENCES `requests` (`RequestID`) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_assignments_manager` 
        FOREIGN KEY (`ManagerID`) REFERENCES `users` (`UserID`) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_assignments_technician` 
        FOREIGN KEY (`TechnicianID`) REFERENCES `users` (`UserID`) 
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `roles` (`RoleID`, `RoleName`) VALUES
(1, 'Employee'),
(2, 'Manager'),
(3, 'Technician')
ON DUPLICATE KEY UPDATE `RoleName`=`RoleName`;


INSERT INTO `users` (`UserID`, `Name`, `Email`, `Password`, `RoleID`) VALUES
(1, 'Anas Employee', 'anas@domain.com', '123456', 1),
(2, 'Sarah Manager', 'manager@domain.com', '123456', 2),
(3, 'John Tech', 'tech@domain.com', '123456', 3)
ON DUPLICATE KEY UPDATE `Email`=`Email`;


INSERT INTO `equipment` (`EquipmentID`, `ItemName`, `Category`, `StockQuantity`, `ConditionStatus`) VALUES
(1, 'Dell 24" IPS Monitor', 'Display', 12, 'Good'),
(2, 'HP LaserJet Pro MFP', 'Printer', 4, 'Fair'),
(3, 'Logitech MX Master Mouse', 'Peripheral', 25, 'Excellent'),
(4, 'Corsair 650W Power Supply', 'Internal Hardware', 8, 'Good'),
(5, 'Keychron K2 Mechanical Keyboard', 'Peripheral', 15, 'Good')
ON DUPLICATE KEY UPDATE `ItemName`=`ItemName`;