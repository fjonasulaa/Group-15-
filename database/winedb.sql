CREATE TABLE customer (
  customerID INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  firstName VARCHAR(100) NOT NULL,
  surname VARCHAR(100) NOT NULL,
  dateOfBirth DATE,
  addressLine VARCHAR(255),
  postcode VARCHAR(20),
  email VARCHAR(255) NOT NULL,
  phoneNumber VARCHAR(20),
  passwordHash VARCHAR(255) NOT NULL
);

CREATE TABLE wines (
  wineId INT NOT NULL PRIMARY KEY,
  wineName VARCHAR(100) NOT NULL,
  wineRegion VARCHAR(100),
  ingredients TEXT,
  country VARCHAR(100),
  category VARCHAR(100),
  price DECIMAL(7,2) NOT NULL,
  description TEXT,
  imageUrl VARCHAR(500)
);

CREATE TABLE orders (
  orderId INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  customerId INT NOT NULL,
  orderDate DATE NOT NULL DEFAULT CURRENT_DATE,
  totalAmount DECIMAL(7,2) NOT NULL,
  FOREIGN KEY (customerId) REFERENCES customer(customerID)
);

CREATE TABLE orderswines (
  ordersWinesId INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  orderId INT NOT NULL,
  wineId INT NOT NULL,
  quantity INT NOT NULL,
  FOREIGN KEY (orderId) REFERENCES orders(orderId),
  FOREIGN KEY (wineId) REFERENCES wines(wineId)
);

CREATE TABLE payment (
  paymentId INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  orderId INT NOT NULL,
  method VARCHAR(100) NOT NULL,
  amount DECIMAL(7,2) NOT NULL,
  paymentStatus VARCHAR(100) NOT NULL,
  transactionTimestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (orderId) REFERENCES orders(orderId)
);

CREATE TABLE reviews (
  reviewId INT NOT NULL PRIMARY KEY,
  customerId INT NOT NULL,
  wineId INT NOT NULL,
  stars INT NOT NULL,
  reviewText TEXT,
  FOREIGN KEY (customerId) REFERENCES customer(customerID),
  FOREIGN KEY (wineId) REFERENCES wines(wineId)
);

CREATE TABLE shipping (
  shippingId INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  orderId INT NOT NULL,
  deliveryType VARCHAR(100) NOT NULL,
  carrier VARCHAR(100),
  trackingNumber VARCHAR(100),
  shippingStatus VARCHAR(100) NOT NULL,
  shippingDate DATE,
  estimatedDelivery DATE,
  FOREIGN KEY (orderId) REFERENCES orders(orderId)
);