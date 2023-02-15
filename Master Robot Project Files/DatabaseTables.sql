CREATE TABLE StoreItem (
ItemID varchar(10) PRIMARY KEY,
Price integer,
NoOfCopies integer CHECK(NoOfCopies >=0)
);

CREATE TABLE ComicBooks (
ItemID varchar(10) UNIQUE,
Title varchar(50),
PublishingDate date,
CONSTRAINT ItemID FOREIGN KEY (ItemID) REFERENCES StoreItem(ItemID)
);

CREATE TABLE CartoonMovies (
ItemID varchar(10) UNIQUE,
Title varchar(50),
StudioName varchar(50),
Description varchar(500),
CONSTRAINT ItemIDM FOREIGN KEY (ItemID) REFERENCES StoreItem(ItemID)
);

CREATE TABLE Customer (
CustID integer PRIMARY KEY,
CustType varchar(7) CHECK(CustType IN ('GOLD', 'REGULAR')),
Name varchar(20),
Phone integer UNIQUE NOT NULL,
Email varchar(30) UNIQUE NOT NULL,
DateJoined date,
Coupon varchar(10),
Street varchar(10),
City varchar(20),
ZipCode integer
);

CREATE TABLE CustOrder (
OrderID integer PRIMARY KEY,
CustID integer,
DateOfOrder date,
ShippedDate date,
ShippingFee float,
OrderCost float,
FinalCost float,
Tax float,
Discount float
CONSTRAINT CustID FOREIGN KEY (CustID) REFERENCES Customer(CustID)
);

CREATE TABLE OrderLineItem (
OrderID integer,
ItemID varchar(10),
Quantity integer,
LineID integer,
CONSTRAINT ORDERLINEITEM PRIMARY KEY (OrderID, ItemID, LineID),
CONSTRAINT OrderID FOREIGN KEY (OrderID) REFERENCES CustOrder(OrderID),
CONSTRAINT ItemIDO FOREIGN KEY (ItemID) REFERENCES StoreItem(ItemID)
);