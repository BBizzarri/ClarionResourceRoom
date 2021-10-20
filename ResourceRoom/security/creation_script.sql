-- To recreate the database
DROP DATABASE IF EXISTS resourceroom;
CREATE DATABASE resourceroom;
USE resourceroom;

-- To create a user account for the database, run this script on your localhost/phpmyadmin in an SQL tab
-- CREATE USER cis411 IDENTIFIED BY 'cis411';
GRANT USAGE ON *.* TO cis411@localhost IDENTIFIED BY 'cis411';
GRANT ALL PRIVILEGES ON resourceroom.* TO 'cis411'@'localhost';
-- --------------------------------------------------------

CREATE TABLE functions ( FunctionID INT NOT NULL AUTO_INCREMENT,
                         Name VARCHAR(64) NOT NULL,
                         Description TEXT,
                         PRIMARY KEY (FunctionID) );

CREATE TABLE roles ( RoleID INT NOT NULL AUTO_INCREMENT,
                     Name VARCHAR(32) NOT NULL,
                     Description TEXT,
                     PRIMARY KEY (RoleID) );

CREATE TABLE users ( UserID INT NOT NULL AUTO_INCREMENT,
                     FirstName VARCHAR(32) NOT NULL,
                     LastName VARCHAR(32) NOT NULL,
                     UserName VARCHAR(32) NOT NULL,
                     Password VARCHAR(40) NOT NULL,
                     Email VARCHAR(32) NOT NULL,
                     PRIMARY KEY (UserID) );

CREATE TABLE rolefunctions ( RoleID INT NOT NULL,
                             FunctionID INT NOT NULL,
                             PRIMARY KEY (FunctionID, RoleID),
                             FOREIGN KEY (RoleID) REFERENCES roles(RoleID) ON DELETE CASCADE,
                             FOREIGN KEY (FunctionID) REFERENCES functions(FunctionID) ON DELETE CASCADE );

CREATE TABLE userroles ( UserID INT NOT NULL,
                         RoleID INT NOT NULL,
                         PRIMARY KEY (UserID, RoleID),
                         FOREIGN KEY (UserID) REFERENCES users(UserID) ON DELETE CASCADE,
                         FOREIGN KEY (RoleID) REFERENCES roles(RoleID) ON DELETE CASCADE);

CREATE TABLE errorlog (
                          LogID     INT NOT NULL AUTO_INCREMENT,
                          TimeInserted     TIMESTAMP NOT NULL,
                          UserID     INT NOT NULL,
                          UserName     VARCHAR(32) NOT NULL,
                          ErrorMessage     VARCHAR(1024) NOT NULL,
                          PRIMARY KEY (LogID));

CREATE TABLE ORDERS
(   ORDERID                 INT AUTO_INCREMENT UNIQUE,
    USERID                  INT,
    STATUS                  VARCHAR(30),
    DATEORDERED             DATE,
    DATEFILLED              DATE,
    DATECOMPLETED           DATE,
    COMMENT                 TEXT,
    CONSTRAINT ORDERS_PK PRIMARY KEY (ORDERID),
    CONSTRAINT USERID_FK FOREIGN KEY (USERID) REFERENCES USERS (USERID)
    /*CONSTRAINT STATUS_CK CHECK
            (STATUS IN ('Completed', 'Ready for Pickup', 'Submitted')) */
);

CREATE TABLE PRODUCT
(   PRODUCTID               INT AUTO_INCREMENT UNIQUE NOT NULL,
    NAME                    VARCHAR(50),
    DESCRIPTION             TEXT,
    QTYONHAND               INT,
    MAXORDERQTY             INT,
    GOALSTOCK               INT,
    CONSTRAINT PRODUCT_PK PRIMARY KEY (PRODUCTID)
);

/*INTERSECTION TABLE BETWEEN ORDERS, PRODUCT*/
CREATE TABLE ORDERDETAILS
(   ORDERID                 INT,
    PRODUCTID               INT,
    QTYREQUESTED            INT,
    QTYFILLED               INT,
    CONSTRAINT ORDER_DETAILS_PK PRIMARY KEY (ORDERID, PRODUCTID),
    CONSTRAINT ORDERID_FK FOREIGN KEY (ORDERID) REFERENCES ORDERS (ORDERID),
    CONSTRAINT PRODUCTID_FK FOREIGN KEY (PRODUCTID) REFERENCES PRODUCT (PRODUCTID)
);

CREATE TABLE CATEGORY
(   CATEGORYID              INT AUTO_INCREMENT UNIQUE NOT NULL,
    DESCRIPTION             VARCHAR(50),
    CONSTRAINT CATEGORY_PK PRIMARY KEY (CATEGORYID)
);

/*INTERSECTION TABLE BETWEEN PRODUCT AND CATEGORY
DETERMINES WHICH PRODUCTS BELONG TO WHICH CATEGORIES*/
CREATE TABLE PRODUCTCATEGORIES
(   PRODUCTID               INT,
    CATEGORYID              INT,
    CONSTRAINT PRODUCT_CATEGORY_PK PRIMARY KEY (CATEGORYID, PRODUCTID),
    CONSTRAINT CATEGORYS_ID_FK FOREIGN KEY (CATEGORYID) REFERENCES CATEGORY (CATEGORYID),
    CONSTRAINT PRODUCTS_ID_FK FOREIGN KEY (PRODUCTID) REFERENCES PRODUCT (PRODUCTID)
);

CREATE TABLE CART
(
    USERID                  INT,
    PRODUCTID               INT,
    QTYREQUESTED            INT,
    CONSTRAINT CART_PK PRIMARY KEY (USERID, PRODUCTID),
    CONSTRAINT USER_ID_FK FOREIGN KEY (USERID) REFERENCES USERS (USERID),
    CONSTRAINT PRODUCT_ID_FK FOREIGN KEY (PRODUCTID) REFERENCES PRODUCT (PRODUCTID)
);


CREATE TABLE SETTING
(   SETTINGID               INT,
    EmailAddresses          TEXT,
    OrderReceivedText       TEXT,
    OrderFilledText         TEXT,
    PhotoDir                TEXT,
    CONSTRAINT SETTING_PK PRIMARY KEY (SETTINGID)
);


-- Creates a View that generates the OnOrder amount for each product that is in a ''Submitted'' order
CREATE VIEW ONORDERVIEW AS
(SELECT OD.PRODUCTID, IFNULL(SUM(QTYREQUESTED),0) AS QTYONORDER
FROM ORDERDETAILS OD INNER JOIN ORDERS O ON OD.ORDERID = O.ORDERID AND O.STATUS = 'SUBMITTED'
GROUP BY OD.PRODUCTID);

-- Create a Qty Available View, which includes product id and qty available
CREATE VIEW QTYAVAILABLEVIEW AS
(SELECT PRODUCT.PRODUCTID, IFNULL(PRODUCT.QTYONHAND - QTYONORDER, PRODUCT.QTYONHAND) AS QTYAVAILABLE
FROM PRODUCT LEFT OUTER JOIN ONORDERVIEW ON product.PRODUCTID = onorderview.PRODUCTID);

-- Create a Product View that includes QtyAvailable, OrderLimit, and OnOrder (Amount of product in orders that are requested but not filled)
CREATE VIEW PRODUCTVIEW AS
(SELECT PRODUCT.PRODUCTID, PRODUCT.NAME, PRODUCT.DESCRIPTION, IF(PRODUCT.QTYONHAND<0, 0, PRODUCT.QTYONHAND) AS QTYONHAND, PRODUCT.MAXORDERQTY,
        (CASE PRODUCT.MAXORDERQTY
             WHEN 0 THEN QTYAVAILABLE
             ELSE IF(PRODUCT.MAXORDERQTY<QTYAVAILABLE, PRODUCT.MAXORDERQTY, IF(QTYAVAILABLE<0, 0, QTYAVAILABLE))
            END
            ) AS ORDERLIMIT,
        PRODUCT.GOALSTOCK, IFNULL(QTYONORDER,0) AS QTYONORDER, QTYAVAILABLE
FROM PRODUCT LEFT OUTER JOIN ONORDERVIEW ON product.PRODUCTID = onorderview.PRODUCTID
             JOIN QTYAVAILABLEVIEW ON product.PRODUCTID = QTYAVAILABLEVIEW.PRODUCTID);

-- Create a Cart View, which has the number of products in each users cart
-- QtyItemsInCart = number of unique product ids for each user
CREATE VIEW CARTVIEW AS
(SELECT C.USERID, COUNT(DISTINCT C.PRODUCTID) AS QYTITEMSINCART
FROM CART C GROUP BY C.USERID);

INSERT INTO functions (Name,Description) VALUES ('SecurityManageUsers','Allows for reading users and interface to add, change, and delete.');
INSERT INTO functions (Name,Description) VALUES ('SecurityUserAdd','Allows for adding of users by enabling link on manage form.');
INSERT INTO functions (Name,Description) VALUES ('SecurityUserEdit','Allows for editing of users by enabling link on manage form.');
INSERT INTO functions (Name,Description) VALUES ('SecurityUserDelete','Allows for deleting of users by enabling checkbox on manage form.');
INSERT INTO functions (Name,Description) VALUES ('SecurityProcessUserAddEdit','Required to process an add, change, or delete of users.');
INSERT INTO functions (Name,Description) VALUES ('SecurityManageFunctions','Allows for reading functions and interface to add, change, and delete.');
INSERT INTO functions (Name,Description) VALUES ('SecurityFunctionAdd','Allows for adding of functions by enabling link on manage form.');
INSERT INTO functions (Name,Description) VALUES ('SecurityFunctionEdit','Allows for editing of functions by enabling link on manage form.');
INSERT INTO functions (Name,Description) VALUES ('SecurityFunctionDelete','Allows for deleting of functions by enabling checkbox on manage form.');
INSERT INTO functions (Name,Description) VALUES ('SecurityProcessFunctionAddEdit','Required to process an add, change, or delete of functions.');
INSERT INTO functions (Name,Description) VALUES ('SecurityManageRoles','Allows for reading roles and interface to add, change, and delete.');
INSERT INTO functions (Name,Description) VALUES ('SecurityRoleAdd','Allows for adding of roles by enabling link on manage form.');
INSERT INTO functions (Name,Description) VALUES ('SecurityRoleEdit','Allows for editing of roles by enabling link on manage form.');
INSERT INTO functions (Name,Description) VALUES ('SecurityRoleDelete','Allows for deleting of roles by enabling checkbox on manage form.');
INSERT INTO functions (Name,Description) VALUES ('SecurityProcessRoleAddEdit','Required to process an add, change, or delete of roles.');
INSERT INTO functions (Name,Description) VALUES ('SecurityLogin', 'Provide Username and Password');
INSERT INTO functions (Name,Description) VALUES ('SecurityLogOut', 'Exit the application.');
INSERT INTO functions (Name,Description) VALUES ('SecurityProcessLogin', 'Try to authorize a user login.');
INSERT INTO functions (Name,Description) VALUES ('SecurityHome', 'Default security page with login button.');
INSERT INTO functions (Name,Description) VALUES ('Home', 'Default home page with guest access and login button.');
INSERT INTO functions (Name,Description) VALUES ('adminInventory', 'Inventory page to view inventory');
INSERT INTO functions (Name,Description) VALUES ('adminOrders', 'orders page for admins to fill orders that are submitted');
INSERT INTO functions (Name,Description) VALUES ('adminSecurity', 'security page for the admins to change security settings');
INSERT INTO functions (Name,Description) VALUES ('adminReports', 'reports page for admin to generate and download reports');
INSERT INTO functions (Name,Description) VALUES ('adminShoppingList', 'Shopping list page for admins to view and download their shopping list');
INSERT INTO functions (Name,Description) VALUES ('shopperCart', 'Where shoppers can view what items they have in their cart and submit their order');
INSERT INTO functions (Name,Description) VALUES ('shopperHome', 'where shoppers can select items that they would like to purchase');
INSERT INTO functions (Name,Description) VALUES ('shopperOrders', 'where shoppers can view their current past and pending orders');
INSERT INTO functions (Name,Description) VALUES ('displaySelectedCategory', 'Allows the admin to select different categories to only display certain ones in the admin inventory.');
INSERT INTO functions (Name, Description) VALUES ('addEditProduct','Creates a new product or edits a product info if product already exists'),
                                                 ('applyFilter','Allows for use of filters on inventory page'),
                                                 ('displaySelectedCategory','REPEAT!!  REPLACE WHEN ANOTHER FUNCTION IS ADDED!!!!!'),
                                                 ('getProductInfo','Returns Products Infromation'),
                                                 ('processBulkStockAdjust','Adjust QtyOnHand for multiple products on inventory page'),
                                                 ('processSingleStockAdjust','Adjust QtyOnHand for a single product on inventory page'),
                                                 ('shopperAdjustQTYInCart','Adjust QtyRequested for users in cart table'),
                                                 ('processAddToCart','Adds product to an users cart in cart table'),
                                                 ('shopperRemoveFromCart','Removes a product from an users cart in cart table'),
                                                 ('shopperSubmitOrder','Creates an order for users based off of users cart');



INSERT INTO roles (Name,Description) VALUES  ('Admin','Full privileges.'),
                                             ('Student', 'Shopping, cart, and own orders'),
                                             ('Developer','Security'),
                                             ('Inventory Management','View and edit inventory.  Add/Edit/Delete Products.'),
                                             ('Order Fulfillment','View and fill orders.'),
                                             ('Guest', 'Guest');

-- INSERT INTO roles (Name,Description) VALUES ('updater','Update/Read privileges.');
-- INSERT INTO roles (Name,Description) VALUES ('reader','Read-only privileges.');
-- INSERT INTO roles (Name,Description) VALUES ('guest','Features available to all visitors without logging in.');




INSERT INTO users (FirstName,LastName,UserName,Password,Email) VALUES ('TestAdmin','TestAdmin','admin',SHA1('admin'),'admin@clarion.edu'),
                                                                      ('TestStudent','TestStudent', 'student', SHA1('student'), 'teststudent@clarion.edu'),
                                                                      ('TestDeveloper', 'TestDeveloper', 'developer', SHA1('developer'), 'testdeveloper@clarion.edu'),
                                                                      ('TestInventory', 'TestInventory', 'inventory', SHA1('inventory'), 'testinventory@clarion.edu'),
                                                                      ('TestOrder', 'TestOrder', 'order', SHA1('order'), 'testorder@clarion.edu');

INSERT INTO userroles (UserID,RoleID) VALUES (1,1);
INSERT INTO userroles (UserID,RoleID) VALUES (2,2);
INSERT INTO userroles (UserID,RoleID) VALUES (3,3);
INSERT INTO userroles (UserID,RoleID) VALUES (4,4);
INSERT INTO userroles (UserID,RoleID) VALUES (5,5);

INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,1);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,2);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,3);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,4);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,5);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,6);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,7);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,8);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,9);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,10);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,11);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,12);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,13);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,14);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,15);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,21);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,22);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,23);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,24);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,25);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,29);

INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,20),
                                                     (1,30),
                                                     (1,31),
                                                     (1,33),
                                                     (1,34),
                                                     (1,35),
                                                     (1,19),
                                                     (1,16),
                                                     (1,17),
                                                     (1,36),
                                                     (1,26),
                                                     (1,27),
                                                     (1,28),
                                                     (1,37),
                                                     (1,38),
                                                     (1,39);

INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (2,20),
                                                     (2,26),
                                                     (2,27),
                                                     (2,28),
                                                     (2,36),
                                                     (2,37),
                                                     (2,38),
                                                     (2,39);

INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (3,1),
                                                     (3,2),
                                                     (3,3),
                                                     (3,4),
                                                     (3,5),
                                                     (3,6),
                                                     (3,7),
                                                     (3,8),
                                                     (3,9),
                                                     (3,10),
                                                     (3,11),
                                                     (3,12),
                                                     (3,13),
                                                     (3,14),
                                                     (3,15),
                                                     (3,16),
                                                     (3,17),
                                                     (3,18),
                                                     (3,19),
                                                     (3,20);

INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (4,20),
                                                     (4,21),
                                                     (4,25),
                                                     (4,29),
                                                     (4,30),
                                                     (4,31),
                                                     (4,33),
                                                     (4,34),
                                                     (4,35);


INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (5,20),
                                                     (5,22);

INSERT INTO `category` (`CategoryID`, `Description`) VALUES
(1, 'Hygiene & Personal Care Items'),
(2, 'Household Supplies'),
(3, 'Linens'),
(4, 'Breakfast Foods'),
(5, 'Beverages'),
(6, 'Meal Items'),
(7, 'Pasta & Rice'),
(8, 'Side Dishes'),
(9, 'Soups'),
(10, 'Fruits'),
(11, 'Snack Items'),
(12, 'Canned Vegetables, Beans & Meats'),
(13, 'Condiments & Seasonings'),
(14, 'Baking Supplies'),
(15, 'Hair Care Products'),
(16, 'Body Products'),
(17, 'Face & Oral Hygiene Products'),
(18, 'Feminine Hygiene Products'),
(19, 'Clothing Items');
COMMIT;

INSERT INTO `product` (`ProductID`, `Name`, `Description`, `QtyOnHand`, `MaxOrderQty`, `GoalStock`) VALUES
(1, 'Strawberry Shampoo', '', 1, 0, 0),
(2, 'Ocean Breeze Shampoo', '', 4, 0, 5),
(3, 'Ocean Breeze Conditioner', '', 5, 0, 5),
(4, 'Tropical Coconut Shampoo', '', 5, 0, 5),
(5, 'Tropical Coconut Conditioner', '', 4, 0, 5),
(6, 'Dry Shampoo Spray', '', 6, 0, 5),
(7, 'Curling Cream', '', 0, 0, 3),
(8, 'Olive Oil Styling Gel', '', 3, 0, 3),
(9, 'Hair Pick', '', 3, 0, 4),
(10, 'Lift and Style Comb', '', 4, 0, 3),
(11, 'Wide Tooth Comb', '', 0, 0, 3),
(12, 'Regular Comb', '', 2, 0, 3),
(13, 'Do Rag', '', 1, 0, 5),
(14, 'Night Bonnet', '', 3, 0, 5),
(15, 'Original Bar Soap', '', 15, 0, 8),
(16, 'Sensitive Skin Bar Soap', '', 6, 0, 6),
(17, 'Liquid Hand Soap', '', 6, 0, 8),
(18, 'Regular Body Wash (neutral scent)', '', 6, 0, 5),
(19, 'Sensitive Skin Body Wash', '', 10, 0, 5),
(20, 'Men\'s Regular Body Wash', '', 14, 0, 0),
(21, 'Loofah/Shower Puff', '', 4, 0, 6),
(22, 'Medicated Body Powder', '', 3, 0, 3),
(23, 'Creamy Body Lotion with Cocoa Butter and Shea', '', 2, 0, 5),
(24, 'Vitamin E Skin Care Cream', '', 3, 0, 4),
(25, 'Cocoa Butter Skin Care Cream', '', 3, 0, 4),
(26, 'Petrolium Jelly', '', 1, 0, 3),
(27, 'Women\'s Deodorant', '', 12, 0, 10),
(28, 'Men\'s Deodorant', '', 13, 0, 10),
(29, 'Hand Sanitizer', '', 52, 0, 6),
(30, 'Men\'s Razors', '', 13, 0, 10),
(31, 'Women\'s Razors', '', 13, 0, 10),
(32, 'Men\'s Shaving Cream', '', 8, 0, 6),
(33, 'Women\'s Shaving Cream', '', 6, 0, 6),
(34, 'Baby Powder', '', 3, 0, 3),
(35, 'Bandaids', '', 8, 0, 5),
(36, 'Q Tips', '', 10, 0, 8),
(37, 'Baby Wipes', '', 4, 0, 5),
(38, 'Cream Style Face Wash', '', 9, 0, 5),
(39, 'Facial Cleanser', '', 9, 0, 5),
(40, 'Face/makeup wipes', '', 15, 0, 8),
(41, 'Lip Balm', '', 2, 0, 5),
(42, 'Dental Floss', '', 4, 0, 5),
(43, 'Mouthwash', '', 8, 0, 5),
(44, 'Toothbrush single', '', 16, 0, 10),
(45, 'Toothbrush (2 pack)', '', 20, 0, 0),
(46, 'Toothbrush (6 pack)', '', 4, 0, 0),
(47, 'Toothpaste', '', 11, 0, 5),
(48, 'Makeup Kit', '', 2, 0, 0),
(49, 'Eye makeup kit', '', 1, 0, 0),
(50, 'Tampons - Variety Pack', '', 10, 0, 5),
(51, 'Feminine Pads with Wings (box of 3)', '', 58, 0, 5),
(52, 'Plastic Plate', '', 10, 0, 8),
(53, 'Plastic Cup', '', 10, 0, 8),
(54, 'Plastic Bowl', '', 10, 0, 8),
(55, 'Reusable Water Bottle', '', 28, 0, 4),
(56, 'Metal Fork', '', 9, 0, 8),
(57, 'Metal Knife', '', 11, 0, 8),
(58, 'Metal Spoon', '', 9, 0, 8),
(59, 'Can Opener', '', 13, 0, 3),
(60, 'Microfiber Cleaning towel', '', 2, 0, 0),
(61, '5 piece kitchen towel set', '', 4, 0, 0),
(62, 'Dish Soap', '', 7, 0, 8),
(63, 'Cleaning Wipes', '', 37, 0, 8),
(64, 'All Purpose Cleaner', '', 5, 0, 5),
(65, 'Toilet Bowl Cleaner', '', 8, 0, 5),
(66, 'Disinfecting Spray', '', 2, 0, 0),
(67, '13 Gallon Garbage Bags', '', 9, 0, 5),
(68, 'Travel Tissue pack', '', 6, 0, 5),
(69, 'Tissue Box', '', 5, 0, 8),
(70, 'Paper Towel Single Roll', '', 13, 0, 20),
(71, 'Toilet Paper (4 pack)', '', 27, 0, 20),
(72, 'Laundry soap', '', 7, 0, 5),
(73, 'Dryer Sheets', '', 9, 0, 5),
(74, 'Mini Plastic Cups with lids', '', 1, 0, 0),
(75, 'Tropical Nector Candle: 2 pack', '', 1, 0, 0),
(76, '60W light bulb', '', 32, 0, 0),
(77, 'Wash Cloth', '', 8, 0, 8),
(78, 'Hand Towel', '', 8, 0, 8),
(79, 'Body Towel', '', 8, 0, 8),
(80, 'Twin 3-piece Sheet Set', '', 8, 0, 0),
(81, 'Twin Quilt', '', 1, 0, 0),
(82, 'Full/Queen comforter', '', 1, 0, 0),
(83, 'Pink Fleece Blanket', '', 1, 0, 0),
(84, '3 piece bath towel set', '', 6, 0, 0),
(85, 'Shower Curtain Set (pink)', '', 5, 0, 0),
(86, 'Nutrigrain Bar: Mixed Berry Box', '', 5, 0, 5),
(87, 'Nutrigrain Bar: Apple Cinnamon Box', '', 5, 0, 5),
(88, 'Nutrigrain Bar: Strawberry Box', '', 5, 0, 5),
(89, 'Nutrigrain Bar: Blueberry Box', '', 1, 0, 0),
(90, 'Nutrigrain Bar: Apple Cinnamon Single', '', 2, 0, 0),
(91, 'Oatmeal: Fruit and Cream Variety Box', '', 3, 0, 3),
(92, 'Oatmeal: Low Sugar Variety Box', '', 2, 0, 0),
(93, 'Instant Cream of Wheat: Maple Brown Sugar box', '', 1, 0, 0),
(94, 'Oatmeal: Maple and Brown Sugar Microwave Cup', '', 6, 0, 5),
(95, 'Oatmeal: Strawberries and Cream Cup', '', 5, 0, 5),
(96, 'Froasted Flakes Cereal Box', '', 1, 0, 3),
(97, 'Honey Nut O\'s Cereal Box', '', 3, 0, 3),
(98, 'Toasted O\'s Cereal Box', '', 2, 0, 0),
(99, 'Strawberry Awake Cereal Box', '', 2, 0, 3),
(100, 'Oatmeal Cream Pie Cereal Box', '', 1, 0, 0),
(101, 'Cereal: Honey Nut Cheerios ? Single Serve', '', 1, 0, 0),
(102, 'Cereal: Fruit Loops - Single Serve', '', 2, 0, 0),
(103, 'Cereal: Cinnamon Toast Crunch - Single Serve', '', 1, 0, 0),
(104, 'Toaster Pasteries: Frosted Brown Sugar Box', '', 5, 0, 5),
(105, 'Toaster Pasteries: Frosted Strawberry Box', '', 7, 0, 5),
(106, 'Pop Tarts: Frosted Cookies and Creme: Individual packs', '', 7, 0, 0),
(107, 'Original Syrup: Bottle', '', 3, 0, 0),
(108, 'Pancake Mix: Box', '', 1, 0, 0),
(109, 'Belvita Breakfast Biscuits: Cinnamon & Sugar - single pack', '', 1, 0, 0),
(110, 'Nature Valley Biscuits: Cinnamon Almond Butter - Single', '', 2, 0, 0),
(111, 'Bottle Water', '', 62, 0, 0),
(112, 'Box of Hot Cocoa', '', 3, 0, 0),
(113, 'Body Armour Super Drink - Fruit Punch', '', 4, 0, 0),
(114, 'Pepsi: 2L Bottle', '', 3, 0, 0),
(115, 'Carmel Apple Black Tea: Box', '', 1, 0, 0),
(116, 'Gatorade: Glacier Freeze', '', 7, 0, 0),
(117, 'Salted Caramel Light Roast Coffee: Bag', '', 1, 0, 0),
(118, 'Quest Salted Caramel Protein drink: 4 pack', '', 1, 0, 0),
(119, 'V8 Engery: Peach Mango can', '', 2, 0, 0),
(120, 'Sunkist Grape Drink Mix Singles: Box', '', 1, 0, 0),
(121, 'Sparkling Water-Black Raspberry: Bottle', '', 3, 0, 0),
(122, 'Sparkling Water - Orange Mango: Bottle', '', 1, 0, 0),
(123, 'Sunkist Pineapple Drink Mix Singles: Box', '', 1, 0, 0),
(124, 'Sparkling Water-Grapefruit: Can', '', 6, 0, 0),
(125, 'Sparkling Water-Black Cherry: Can', '', 3, 0, 0),
(126, 'Sparkling Water-Watermelon: Can', '', 4, 0, 0),
(127, 'Green Tea with Pomegranate: single packet', '', 9, 0, 0),
(128, 'Electrolyte', '', 3, 0, 3),
(129, 'Compleats Meals: Turkey and Dressing', '', 2, 0, 5),
(130, 'Compleats Meals: Tender Beef with Mashed Potatoes & gravy', '', 0, 0, 5),
(131, 'Compleats Meals: Beef Pot Roast', '', 4, 0, 5),
(132, 'Compleats Meals: Chicken & Noodles', '', 3, 0, 5),
(133, 'Compleats Meals: Rice & Chicken', '', 1, 0, 0),
(134, 'Compleats Meals: Dumplings & Chicken', '', 3, 0, 0),
(135, 'Compleats Meals: Stroganoff', '', 1, 0, 0),
(136, 'Compleats Meals: Chicken Alfredo', '', 0, 0, 0),
(137, 'Hamburger Helper: Cheesburger Macaroni', '', 1, 0, 0),
(138, 'Pasta: Spaghetti', '', 25, 0, 10),
(139, 'Pasta: Rotini', '', 10, 0, 10),
(140, 'Pasta: Egg Noodles', '', 2, 0, 0),
(141, 'Pasta: Lasgna Noodles ', '', 3, 0, 0),
(142, 'Tomato Sauce with Meat', '', 6, 0, 6),
(143, 'Tomato Sauce no Meat', '', 12, 0, 6),
(144, 'Pizza Sauce', '', 2, 0, 0),
(145, 'Alfredo Sauce', '', 1, 0, 0),
(146, 'Spaghettio\'s Original', '', 16, 0, 8),
(147, 'Spaghettio\'s with Meatballs', '', 8, 0, 8),
(148, 'Canned Beef Ravioli', '', 16, 0, 0),
(149, 'Macaroni and Cheese Box', '', 18, 0, 10),
(150, 'Shells & Cheese Box', '', 4, 0, 0),
(151, 'Instant White Rice Box', '', 5, 0, 5),
(152, 'Instant Brown Rice Box', '', 4, 0, 5),
(153, 'Jasmine Rice: Bag', '', 1, 0, 0),
(154, 'Yellow Rice: Bag', '', 1, 0, 0),
(155, 'White Rice: Micrwaveable Cup - 2 pack', '', 5, 0, 5),
(156, 'Brown Rice: Microwaveable Cup - 2 pack', '', 5, 0, 5),
(157, 'Rice a Roni Cheddar Broccoli: Microwaveable Cup', '', 0, 0, 6),
(158, 'Microwaveable Cauliflower Rice', '', 1, 0, 0),
(159, 'Microwaveable Ready Pasta', '', 3, 0, 0),
(160, 'Beef Ravioli Microwaveable Cups', '', 7, 0, 8),
(161, 'Beefaroni Microwaveable Cups', '', 2, 0, 0),
(162, 'Macaroni & Cheese: Microwaveable Cups', '', 26, 0, 24),
(163, 'White Cheddar Macaroni & Cheese cups', '', 2, 0, 0),
(164, 'Shells & Cheese: Microwaveable Cups', '', 11, 0, 0),
(165, 'Rice Sides: Chicken Flavor', '', 3, 0, 5),
(166, 'Rice Sides: Creamy Chicken', '', 1, 0, 0),
(167, 'Rice Sides: Chicken with Broccoli', '', 3, 0, 5),
(168, 'Rice Sides: Herb and Butter', '', 2, 0, 5),
(169, 'Pasta Sides: Creamy Chicken', '', 2, 0, 5),
(170, 'Pasta Sides: Butter and Herb', '', 2, 0, 5),
(171, 'Pasta Sides: Alfredo', '', 2, 0, 0),
(172, 'Pasta Sides: Cheddar Broccoli', '', 3, 0, 0),
(173, 'Italian Sides: 4 Cheese Pasta', '', 3, 0, 5),
(174, 'Fiesta Sides: Mexican Rice', '', 6, 0, 5),
(175, 'Fiesta Sides: Spanish Rice ', '', 2, 0, 0),
(176, 'Instant Mashed Potatoes: Butter', '', 5, 0, 5),
(177, 'Instant Mashed Potatoes: Four Cheese', '', 4, 0, 5),
(178, 'Mashed Potatoes: Box', '', 1, 0, 0),
(179, 'Au Gratin Potatoes: Box', '', 2, 0, 0),
(180, 'Suddenly Pasta Salad: Creamy Macaroni', '', 1, 0, 0),
(181, 'Stuffing: Chicken flavor - Box', '', 1, 0, 0),
(182, 'Butter Mashed Potato: Microwaveable Cup', '', 2, 0, 0),
(183, 'Beef Vegetable Soup', '', 10, 0, 10),
(184, 'Broccoli Cheese Soup', '', 7, 0, 10),
(185, 'Chicken Noodle Soup', '', 31, 0, 10),
(186, 'Creamy Chicken Noodle Soup', '', 2, 0, 0),
(187, 'Chicken and Rice Soup', '', 12, 0, 10),
(188, 'Clam Chowder Soup', '', 0, 0, 5),
(189, 'Bean with Bacon Soup', '', 1, 0, 0),
(190, 'Cream of Potato Soup', '', 7, 0, 10),
(191, 'Tomato Soup', '', 21, 0, 10),
(192, 'Vegetable Soup', '', 10, 0, 10),
(193, 'Beef Barley Soup', '', 1, 0, 0),
(194, 'Cream of Chicken Soup', '', 5, 0, 0),
(195, 'Cream of Mushroom Soup', '', 3, 0, 0),
(196, 'Bean and Ham Soup', '', 1, 0, 0),
(197, 'Chicken Broccoli Cheese w/Potato', '', 1, 0, 0),
(198, 'Cheddar Cheese Soup', '', 2, 0, 0),
(199, 'Chicken and Dumpling Soup', '', 2, 0, 0),
(200, 'Beef Raman packets', '', 24, 0, 24),
(201, 'Chicken Raman packets', '', 61, 0, 24),
(202, 'Chicken Stock', '', 3, 0, 0),
(203, 'Vegetable Stock', '', 1, 0, 0),
(204, 'Beef Broth', '', 1, 0, 0),
(205, 'Italian Style Meatball soup', '', 1, 0, 0),
(206, 'Chicken Noodle Soup: Microwaveable Cup', '', 3, 0, 10),
(207, 'Tomato Soup: Microwaveable Cup', '', 19, 0, 8),
(208, 'Beef Raman: Microwaveable Cup', '', 0, 0, 10),
(209, 'Chicken Raman: Microwaveable Cup', '', 4, 0, 10),
(210, 'Cheddar Cheese Raman: Microwaveable Cup', '', 1, 0, 0),
(211, 'Hot & Spicy Shrimp Raman: Microwaveable Cup', '', 1, 0, 0),
(212, 'Chicken and Stars Soup: Microwaveable Cup', '', 4, 0, 10),
(213, 'Applesauce cups 6 Pack: original', '', 4, 0, 5),
(214, 'Applesauce cups 6 Pack: Cinnamon', '', 6, 0, 5),
(215, 'Applesauce Cinnamon: large jar', '', 1, 0, 0),
(216, 'Mandarin Orange Cups: 4 pack', '', 5, 0, 5),
(217, 'Peach Cups: 4 pack', '', 4, 0, 5),
(218, 'Pear Cups: 4 pack', '', 5, 0, 5),
(219, 'Pineapple Cups: 4 pack', '', 5, 0, 5),
(220, 'Raisins: Individual ', '', 1, 0, 0),
(221, 'Canned Mandarin Oranges', '', 2, 0, 0),
(222, 'Canned Pineapple ', '', 1, 0, 0),
(223, 'Canned Fruit Cocktail ', '', 2, 0, 0),
(224, 'Canned Pears', '', 1, 0, 0),
(225, 'Mixed Fruit: Single Cup', '', 2, 0, 0),
(226, 'Applesauce Original: individual Cup', '', 0, 0, 0),
(227, 'Potato Crisps: Original', '', 5, 0, 3),
(228, 'Potato Crisps: Cheddar Cheese', '', 4, 0, 3),
(229, 'Potato Crisps: Barbecue', '', 5, 0, 3),
(230, 'Butter Crackers: Box', '', 4, 0, 3),
(231, 'Wheat Crackers: Box', '', 3, 0, 3),
(232, 'White Cheddar Cheese Crackers: Box', '', 4, 0, 3),
(233, 'Multigrain Crackers: Box', '', 1, 0, 0),
(234, 'Peanut Butter Sandwich Crackers: Box of 8 packs', '', 4, 0, 4),
(235, 'Cheese Sandwhich Crackers: Box of 8 packs', '', 4, 0, 4),
(236, 'Microwave popcorn: box of 3 packs', '', 9, 0, 5),
(237, 'Individaul gummy packs', '', 21, 0, 0),
(238, 'Animal Crackers Snack Size Bag', '', 12, 0, 0),
(239, 'Green Pea Snack Crisps', '', 1, 0, 0),
(240, 'Girl Scout Cookies: Lemonades pack', '', 1, 0, 0),
(241, 'Crispy Rice Treats Box', '', 1, 0, 0),
(242, 'Seasoned Croutons', '', 1, 0, 0),
(243, 'Smokehouse Almonds', '', 1, 0, 0),
(244, 'Nature Valley Crunchy Oats & Honey: single bar', '', 2, 0, 0),
(245, 'Salted Caramel Protein Bar: Single', '', 13, 0, 0),
(246, 'Creamy Peanut Butter Protein Bar: Single', '', 4, 0, 0),
(247, 'Smores Protein Bar: Single', '', 2, 0, 0),
(248, 'Granola Bar: Chewy Choloclate chunk Single', '', 0, 0, 0),
(249, 'Strawberry Geletin Cup', '', 2, 0, 0),
(250, 'Cheese Sandwhich Crackers: Individual', '', 5, 0, 0),
(251, 'Sliced Carrots', '', 5, 0, 8),
(252, 'Corn', '', 11, 0, 8),
(253, 'Cream Style Corn', '', 1, 0, 0),
(254, 'Green Beans', '', 31, 0, 8),
(255, 'Peas', '', 8, 0, 8),
(256, 'Sliced Potatoes', '', 8, 0, 8),
(257, 'Chick Peas/Garbanzo Beans', '', 1, 0, 0),
(258, 'Diced Tomatoes', '', 2, 0, 0),
(259, 'Kidney Beans', '', 2, 0, 0),
(260, 'Baked Beans', '', 1, 0, 0),
(261, 'Sauerkraut', '', 2, 0, 0),
(262, 'Mixed Vegetables ', '', 3, 0, 0),
(263, 'Refried Beans', '', 2, 0, 0),
(264, 'Collard Greens', '', 1, 0, 0),
(265, 'Canned Tuna', '', 32, 0, 10),
(266, 'Canned Chicken', '', 14, 0, 10),
(267, 'Tuna Packet: no flavor', '', 34, 0, 10),
(268, 'Chicken Packet: no flavor', '', 1, 0, 0),
(269, 'Chicken Packet: Buffalo flavor', '', 1, 0, 0),
(270, 'Ketchup', '', 5, 0, 5),
(271, 'Mustard', '', 5, 0, 5),
(272, 'Mayonnaise', '', 5, 0, 5),
(273, 'Peanut Butter', '', 6, 0, 5),
(274, 'Grape Jelly', '', 3, 0, 3),
(275, 'Peanut Butter & Grape Jelly Stripes', '', 1, 0, 0),
(276, 'Sugar Free Grape Jam', '', 0, 0, 0),
(277, 'Srawberry Jelly', '', 3, 0, 3),
(278, 'Large 26 OZ salt', '', 1, 0, 0),
(279, 'Salt & Pepper combo pack', '', 5, 0, 5),
(280, 'Tomato Paste', '', 1, 0, 0),
(281, 'Chicken Gravy Packet', '', 2, 0, 0),
(282, 'Brown Gravy Packet', '', 3, 0, 0),
(283, 'Savory Beef Gravy: Jar', '', 1, 0, 0),
(284, 'Chipotle Sauce', '', 1, 0, 0),
(285, 'Taco Seasoning mix: packet', '', 5, 0, 0),
(286, 'Salad Dressing: French', '', 2, 0, 0),
(287, 'Season Salt: Bottle', '', 1, 0, 0),
(288, 'Teriyaki Sauce: Bottle', '', 1, 0, 0),
(289, 'Minced Onion seasoning', '', 1, 0, 0),
(290, 'Guacamole Dip Mix: packet', '', 2, 0, 0),
(291, 'Ranch seasoning: packet', '', 1, 0, 0),
(292, 'Garlic Parmesean mix: packet', '', 1, 0, 0),
(293, 'Sloppy Joe mix: packet', '', 1, 0, 0),
(294, 'Cinnamon', '', 1, 0, 0),
(295, 'Manwich Original Sloppy Joe Sauce', '', 4, 0, 0),
(296, 'Chocolate Cake Mix: Box', '', 1, 0, 0),
(297, 'All purpose flour', '', 2, 0, 0),
(298, 'Plain Bread Crumbs', '', 1, 0, 0),
(299, 'Corn Muffin Mix: Box', '', 4, 0, 0),
(300, 'Fudge Brownie mix: Box', '', 1, 0, 0),
(301, 'Toothpicks: pack', '', 1, 0, 0),
(302, 'Disposable Cupcack Liners: pack', '', 1, 0, 0),
(303, 'Food Coloring: pack of 4', '', 1, 0, 0),
(304, 'Milk chocolate brownie Mix: Box', '', 1, 0, 0),
(305, 'Baking Powder', '', 1, 0, 0),
(306, 'Cheddar Bay Biscuit Mix', '', 1, 0, 0),
(307, 'Angel Food cake mix: Box', '', 1, 0, 0);

INSERT INTO `productcategories` (`ProductID`, `CategoryID`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 2),
(53, 2),
(54, 2),
(55, 2),
(56, 2),
(57, 2),
(58, 2),
(59, 2),
(60, 2),
(61, 2),
(62, 2),
(63, 2),
(64, 2),
(65, 2),
(66, 2),
(67, 2),
(68, 2),
(69, 2),
(70, 2),
(71, 2),
(72, 2),
(73, 2),
(74, 2),
(75, 2),
(76, 2),
(77, 3),
(78, 3),
(79, 3),
(80, 3),
(81, 3),
(82, 3),
(83, 3),
(84, 3),
(85, 3),
(86, 3),
(87, 4),
(88, 4),
(89, 4),
(90, 4),
(91, 4),
(92, 4),
(93, 4),
(94, 4),
(95, 4),
(96, 4),
(97, 4),
(98, 4),
(99, 4),
(100, 4),
(101, 4),
(102, 4),
(103, 4),
(104, 4),
(105, 4),
(106, 4),
(107, 4),
(108, 4),
(109, 4),
(110, 4),
(111, 5),
(112, 5),
(113, 5),
(114, 5),
(115, 5),
(116, 5),
(117, 5),
(118, 5),
(119, 5),
(120, 5),
(121, 5),
(122, 5),
(123, 5),
(124, 5),
(125, 5),
(126, 5),
(127, 5),
(128, 5),
(129, 6),
(130, 6),
(131, 6),
(132, 6),
(133, 6),
(134, 6),
(135, 6),
(136, 6),
(137, 6),
(138, 7),
(139, 7),
(140, 7),
(141, 7),
(142, 7),
(143, 7),
(144, 7),
(145, 7),
(146, 7),
(147, 7),
(148, 7),
(149, 7),
(150, 7),
(151, 7),
(152, 7),
(153, 7),
(154, 7),
(155, 7),
(156, 7),
(157, 7),
(158, 7),
(159, 7),
(160, 7),
(161, 7),
(162, 7),
(163, 7),
(164, 7),
(165, 8),
(166, 8),
(167, 8),
(168, 8),
(169, 8),
(170, 8),
(171, 8),
(172, 8),
(173, 8),
(174, 8),
(175, 8),
(176, 8),
(177, 8),
(178, 8),
(179, 8),
(180, 8),
(181, 8),
(182, 8),
(183, 9),
(184, 9),
(185, 9),
(186, 9),
(187, 9),
(188, 9),
(189, 9),
(190, 9),
(191, 9),
(192, 9),
(193, 9),
(194, 9),
(195, 9),
(196, 9),
(197, 9),
(198, 9),
(199, 9),
(200, 9),
(201, 9),
(202, 9),
(203, 9),
(204, 9),
(205, 9),
(206, 9),
(207, 9),
(208, 9),
(209, 9),
(210, 9),
(211, 9),
(212, 9),
(213, 10),
(214, 10),
(215, 10),
(216, 10),
(217, 10),
(218, 10),
(219, 10),
(220, 10),
(221, 10),
(222, 10),
(223, 10),
(224, 10),
(225, 10),
(226, 10),
(227, 11),
(228, 11),
(229, 11),
(230, 11),
(231, 11),
(232, 11),
(233, 11),
(234, 11),
(235, 11),
(236, 11),
(237, 11),
(238, 11),
(239, 11),
(240, 11),
(241, 11),
(242, 11),
(243, 11),
(244, 11),
(245, 11),
(246, 11),
(247, 11),
(248, 11),
(249, 11),
(250, 11),
(251, 12),
(252, 12),
(253, 12),
(254, 12),
(255, 12),
(256, 12),
(257, 12),
(258, 12),
(259, 12),
(260, 12),
(261, 12),
(262, 12),
(263, 12),
(264, 12),
(265, 12),
(266, 12),
(267, 12),
(268, 12),
(269, 12),
(270, 13),
(271, 13),
(272, 13),
(273, 13),
(274, 13),
(275, 13),
(276, 13),
(277, 13),
(278, 13),
(279, 13),
(280, 13),
(281, 13),
(282, 13),
(283, 13),
(284, 13),
(285, 13),
(286, 13),
(287, 13),
(288, 13),
(289, 13),
(290, 13),
(291, 13),
(292, 13),
(293, 13),
(294, 13),
(295, 13),
(296, 14),
(297, 14),
(298, 14),
(299, 14),
(300, 14),
(301, 14),
(302, 14),
(303, 14),
(304, 14),
(305, 14),
(306, 14),
(307, 14),
(1, 15),
(2, 15),
(3, 15),
(4, 15),
(5, 15),
(6, 15),
(7, 15),
(8, 15),
(9, 15),
(10, 15),
(11, 15),
(12, 15),
(13, 15),
(14, 15),
(15, 16),
(16, 16),
(17, 16),
(18, 16),
(19, 16),
(20, 16),
(21, 16),
(22, 16),
(23, 16),
(24, 16),
(25, 16),
(26, 16),
(27, 16),
(28, 16),
(29, 16),
(30, 16),
(31, 16),
(32, 16),
(33, 16),
(34, 16),
(35, 16),
(36, 16),
(37, 16),
(38, 17),
(39, 17),
(40, 17),
(41, 17),
(42, 17),
(43, 17),
(44, 17),
(45, 17),
(46, 17),
(47, 17),
(48, 17),
(49, 17),
(50, 18),
(51, 18);
COMMIT;

INSERT INTO `PRODUCT` (`ProductID`, `Name`, `Description`, `QtyOnHand`, `MaxOrderQty`, `GoalStock`) VALUES
(1000, 'Blanket', 'Dark blue, fleece.  Approximately 50x50 inches', 1, 1, 0),  -- GoalStock = 0 (Temp item)
(1001, 'Clear American Sparkling Water, Wild Cherry', '1 bottle, 33.8 fl oz', 10, 5, 5),
(1002, 'Basmati Rice', '1 bag, 32 oz', 2, 1, 5),  -- QtyOnHand < GoalStock (On shopping List)
(1003, 'Gluten Free Angel Hair Pasta', '1 box, 1lb', 4, 2, 10), -- QtyOnHand < GoalStock (On shopping List)
(1004, 'Coat', 'Forever 21 Faux Fur Lined Womens Coat, size Xtra Large', 1, 1, 0), -- GoalStock = 0 (Temp item)
(1005, 'Canned Dragon Fruit', '1 can, 12 oz', 9, 3, 5),
(1006, 'Sugar', '1 bag, .5 lb', 6, 2, 8), -- QtyOnHand < GoalStock (On shopping List)
(1007, 'Flour', '1 bag, .5 lb', 8, 1, 3),
(1008, 'Curtains', 'Barbie Pink, Room darkening, 63"', 1, 1, 0), -- GoalStock = 0 (Temp item)
(1009, 'Vienna Sausages', '1 can, 6 oz', 10, 15, 6),
(1010, 'Ruler', '12 inch Ruler', 20, 3, 5),
(1011, 'Black Tank Top', 'Womens Tank Tops, size Small, Medium, and Xtra Large Available.
Please put size in comment box before ordering', 30, 5, 0), -- GoalStock = 0 (Temp item)
(1012, 'Composition Notebooks', '1 Black, regular ruled notebook', 0, 5, 19),  -- QtyOnHand = 0, out of stock
(1013, 'Canned Alfredo Pasta Sauce', '1 can, 24 oz', 0, 3, 10), -- QtyOnHand = 0, out of stock
(1014, 'iPhone 10 case', 'blue with stars design, Otterbox', 0, 1, 0), -- inactive item
(1015, 'Thinx Period Proof Underwear', 'black, size Medium, brief style', 1, 1, 0),  -- GoalStock = 0 (Temp item)
(1016, 'Creamy Italian Wedding Soup', '12 oz can', 5, 8, 0),
(1017, 'Suave 3-1 Shampoo, Body and Face Wash', '16 fl oz bottle, scent: ThunderBird Axe Attack', 3, 10, 15); -- QtyOnHand < GoalStock (On shopping List)
COMMIT;

INSERT INTO `ORDERS` (`ORDERID`, `USERID`, `STATUS`, `DATEORDERED`, `DATEFILLED`, `DATECOMPLETED`, `COMMENT`) VALUES
(1, 1, 'COMPLETED', '2021-08-29', '2021-09-01', '2021-09-05', 'I am allergice to Nuts'),
(2, 2, 'READY FOR PICKUP', '2021-09-16', '2021-09-17', '', ' '),
(3, 1, 'SUBMITTED', '2021-09-18',  '', '', 'I live off campus'),
(4, 3, 'SUBMITTED', '2021-09-19', '', '', 'Size Xtra Large For the Tank Top');
COMMIT;

INSERT INTO `productcategories` (`ProductID`, `CategoryID`) VALUES
(1000, 3),    -- Blanket in linens
(1001, 5),    -- Water in Beverages
(1002, 7),    -- Rice in Pasta & Rice
(1003, 7),    -- Pasta in Pasta & Rice
(1004, 19),   -- Coat in Clothing
(1005, 10),   -- Dragon fruit in Fruit
(1005, 11),   -- Dragon fruit in Snacks
(1006, 14),   -- Sugar in Baking
(1007, 14),   -- Flour in Baking
(1008, 3),    -- Curtains in Linens
(1009, 12),   -- Canned Sausage under Canned Meats
(1010, 2),    -- Ruler in Household Supplies
(1011, 19),   -- Tank Top in Clothing
(1012, 2),    -- Notebook in Household Supplies
(1013, 7),    -- Alfredo Sauce in Pasta & Rice
(1014, 2),    -- Phone Case in Household Supplies
(1015, 18),   -- Underwear in Feminine Hygiene Products
(1015, 19),   -- Underwear in Clothing
(1016, 9),    -- Italian Wedding in Soup
(1017, 15),   -- Suave in Hair Care
(1017, 16),   -- Suave in Body
(1017, 17);   -- Suave in Face
COMMIT;

INSERT INTO `ORDERDETAILS` (`ORDERID`, `PRODUCTID`, `QTYREQUESTED`, `QTYFILLED`) VALUES
-- Order 1, 5 different items, all items filled as requested, Order Complete
(1, 1002, 1, 1),
(1, 1003, 2, 2),
(1, 1005, 2, 2),
(1, 1009, 3, 3),
(1, 1010, 1, 1),
(1, 1012, 1, 1),
-- Order 2, 8 different items, 2 items not filled as requested, Ready for Pickup
(2, 1000, 1, 1),
(2, 1001, 3, 3),
(2, 1003, 1, 1),
(2, 1005, 2, 2),
(2, 1006, 2, 1),  -- Only received 1 bag of sugar
(2, 1007, 1, 1),
(2, 1009, 2, 2),
(2, 1012, 4, 0),  -- Received no notebooks
-- Order 3, 6 different items, Submitted (Not Filled) so QtyFilled = 0 for all items
(3, 1000, 1, 0),
(3, 1004, 1, 0),
(3, 1005, 2, 0),
(3, 1010, 3, 0),
(3, 1016, 8, 0),  -- QtyRequested > Qty Available
(3, 1017, 7, 0),  -- QtyRequested > Qty Available
-- Order 4, 10 different items, Submitted (Not Filled) so QtyFilled = 0 for all items
(4, 1001, 4, 0),
(4, 1002, 1, 0),
(4, 1003, 2, 0),
(4, 1004, 1, 0), -- QtyAvailable < QtyRequested, Will not receive a coat
(4, 1005, 2, 0),
(4, 1006, 1, 0),
(4, 1007, 1, 0),
(4, 1009, 2, 0),
(4, 1010, 1, 0),
(4, 1011, 4, 0);
COMMIT;

INSERT INTO `CART` (`USERID`, `PRODUCTID`, `QTYREQUESTED`) VALUES
(5, 1001, 6),      -- QtyRequested > MaxOrderQty, can't be ordered as in cart
(5, 1002, 1),      -- No issues
(5, 1003, 2),      -- No issues
(5, 1006, 1),      -- Date is from 2020
(5, 1009, 10),     -- QtyRequested > QtyAvailable, can't be ordered as in cart
(5, 1012, 4);      -- QtyAvailable = 0, Item is out of stock
COMMIT;

INSERT INTO `SETTING` (SettingID, EmailAddresses, OrderReceivedText, OrderFilledText, PhotoDir) VALUES
(1, 'mlkarg@clarion.edu, resourceroom@clarion.edu, admin@clarion.edu',
 'Hello!  We have received your order and will fill it as soon as we are able.  Once the order has been filled, another email will be sent to confirm pick up details.',
 'Hello!  Your order has been filled and can be picket up in Ralston Hall, Monday through Friday from 8am to 4pm.  In the entry way is a table.
 Your order will be in a reusable shopping bag on the table. Please bring your order number to ensure you pick up the correct order.','');
