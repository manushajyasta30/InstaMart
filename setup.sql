CREATE DATABASE IF NOT EXISTS `project`;

-- Switch to the newly created or existing database
USE `project`;


-- Orders Table
CREATE TABLE `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,        -- Unique order ID (Auto-incremented)
    `productID` VARCHAR(255),                    -- Product ID (assumed to be VARCHAR)
    `totalCounts` VARCHAR(255),                  -- Total counts (should ideally be INT if it's numeric)
    `buyerEmail` VARCHAR(255),                   -- Email of the buyer
    `orderDate` DATE,                            -- Date when the order was placed
    `totalAmount` VARCHAR(255),                  -- Total amount of the order
    `cardNumber` VARCHAR(16),                    -- Credit card number (16 characters max)
    `expiryDate` VARCHAR(5),                     -- Expiration date of the card (MM/YY format)
    `CVV` INT,                                   -- CVV (security code) of the card
    `cardHolderName` VARCHAR(100),               -- Name of the cardholder
    `shippingStatus` VARCHAR(255),               -- Shipping status (e.g., Pending, Shipped)
    `address` TEXT,                              -- Delivery address
    `city` VARCHAR(50),                          -- Delivery city
    `state` VARCHAR(50),                         -- Delivery state
    `pin` INT                                     -- Delivery pin code
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Product TABLE
CREATE TABLE `products` (
    `productid` INT AUTO_INCREMENT PRIMARY KEY,  -- Unique product ID (Auto-incremented)
    `categoryname` VARCHAR(100) NOT NULL,        -- Category of the product
    `productname` VARCHAR(100) NOT NULL,         -- Name of the product
    `merchantname` VARCHAR(100) NOT NULL,        -- Merchant's name
    `merchantemail` VARCHAR(100) NOT NULL,       -- Merchant's email
    `stock` INT,                                 -- Stock available for the product
    `price` INT,                                 -- Price of the product
    `description` VARCHAR(255) NOT NULL,         -- Product description
    `productimage` LONGBLOB,                     -- Product image in binary format
    `gender` VARCHAR(50) NOT NULL                -- Gender specification for the product (if applicable)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- User Table
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,         -- Unique user ID (Auto-incremented)
    `name` VARCHAR(100) NOT NULL,                 -- User's full name
    `email` VARCHAR(100) NOT NULL UNIQUE,         -- User's email (must be unique)
    `password` VARCHAR(255) NOT NULL,             -- Hashed password of the user
    `account_type` VARCHAR(8),                    -- Account type (e.g., buyer, merchant)
    `address` TEXT,                               -- User's address
    `city` VARCHAR(50),                           -- User's city
    `state` VARCHAR(50),                          -- User's state
    `pin` INT,                                    -- User's delivery pin code
    `phone` VARCHAR(15),                          -- User's phone number
    `dob` DATE,                                   -- Date of birth
    `image` LONGBLOB                              -- Profile image in binary format
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Cart Table
CREATE TABLE `cart` (
    `productid` INT(11) NOT NULL,                -- Product ID from the products table
    `count` INT(11) NOT NULL,                    -- Quantity of the product in the cart
    `buyername` VARCHAR(100) NOT NULL,           -- Buyer's name
    `buyeremail` VARCHAR(100) NOT NULL,          -- Buyer's email
    PRIMARY KEY (`productid`, `buyeremail`)      -- Composite primary key
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Submission Table
CREATE TABLE `submissions` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,  -- Unique ID for each submission (Auto-incremented)
    `email` VARCHAR(255) NOT NULL,                      -- Email of the person making the submission
    `name` VARCHAR(255) NOT NULL,                       -- Name of the person making the submission
    `reason` VARCHAR(255) NOT NULL,                     -- Reason for the submission
    `description` TEXT DEFAULT NULL,                    -- Additional description or details
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP -- Time when the submission was created
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
