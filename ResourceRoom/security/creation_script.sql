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

CREATE TABLE users ( UserID VARCHAR(20) NOT NULL,
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

CREATE TABLE userroles ( UserID VARCHAR(20) NOT NULL,
                         RoleID INT NOT NULL,
                         PRIMARY KEY (UserID, RoleID),
                         FOREIGN KEY (UserID) REFERENCES users(UserID) ON DELETE CASCADE,
                         FOREIGN KEY (RoleID) REFERENCES roles(RoleID) ON DELETE CASCADE);

CREATE TABLE errorlog (
                          LogID     INT NOT NULL AUTO_INCREMENT,
                          TimeInserted     TIMESTAMP NOT NULL,
                          UserID     VARCHAR(20) NOT NULL,
                          UserName     VARCHAR(32) NOT NULL,
                          ErrorMessage     VARCHAR(1024) NOT NULL,
                          PRIMARY KEY (LogID));

CREATE TABLE orders
(   ORDERID                 INT AUTO_INCREMENT,
    USERID                  VARCHAR(20),
    STATUS                  VARCHAR(30),
    DATEORDERED             DATE,
    DATEFILLED              DATE,
    DATECOMPLETED           DATE,
    COMMENT                 VARCHAR(255),
    CONSTRAINT ORDERS_PK PRIMARY KEY (ORDERID),
    CONSTRAINT USERID_FK FOREIGN KEY (USERID) REFERENCES users (USERID)
    -- CONSTRAINT STATUS_CK CHECK
           -- (STATUS IN ('Completed', 'Ready for Pickup', 'Submitted')) */
);

CREATE TABLE product
(   PRODUCTID               INT AUTO_INCREMENT,
    NAME                    VARCHAR(50),
    PRODUCTDESCRIPTION             VARCHAR(255),
    QTYONHAND               INT,
    MAXORDERQTY             INT,
    GOALSTOCK               INT,
    CONSTRAINT PRODUCT_PK PRIMARY KEY (PRODUCTID)
);

/*INTERSECTION TABLE BETWEEN ORDERS, PRODUCT*/
CREATE TABLE orderdetails
(   ORDERID                 INT,
    PRODUCTID               INT,
    QTYREQUESTED            INT,
    QTYFILLED               INT,
    CONSTRAINT ORDER_DETAILS_PK PRIMARY KEY (ORDERID, PRODUCTID),
    CONSTRAINT ORDERID_FK FOREIGN KEY (ORDERID) REFERENCES orders (ORDERID),
    CONSTRAINT PRODUCTID_FK FOREIGN KEY (PRODUCTID) REFERENCES product (PRODUCTID)
);

CREATE TABLE category
(   CATEGORYID              INT AUTO_INCREMENT,
    CATEGORYDESCRIPTION     VARCHAR(50),
    CONSTRAINT CATEGORY_PK PRIMARY KEY (CATEGORYID)
);

/*INTERSECTION TABLE BETWEEN PRODUCT AND CATEGORY
DETERMINES WHICH PRODUCTS BELONG TO WHICH CATEGORIES*/
CREATE TABLE productcategories
(   PRODUCTID               INT,
    CATEGORYID              INT,
    CONSTRAINT PRODUCT_CATEGORY_PK PRIMARY KEY (CATEGORYID, PRODUCTID),
    CONSTRAINT CATEGORYS_ID_FK FOREIGN KEY (CATEGORYID) REFERENCES category (CATEGORYID),
    CONSTRAINT PRODUCTS_ID_FK FOREIGN KEY (PRODUCTID) REFERENCES product (PRODUCTID)
);

CREATE TABLE cart
(
    USERID                  VARCHAR(20),
    PRODUCTID               INT,
    QTYREQUESTED            INT,
    CONSTRAINT CART_PK PRIMARY KEY (USERID, PRODUCTID),
    CONSTRAINT USER_ID_FK FOREIGN KEY (USERID) REFERENCES users (USERID),
    CONSTRAINT PRODUCT_ID_FK FOREIGN KEY (PRODUCTID) REFERENCES product (PRODUCTID)
);


CREATE TABLE setting
(   SETTINGID               INT,
    EmailOrderReceived      VARCHAR(300),
    EmailOrderFilled        VARCHAR(300),
    EmailOrderReminder      VARCHAR(300),
    EmailOrderCancelled     VARCHAR(300),
    OrderReceivedText       VARCHAR(500),
    OrderFilledText         VARCHAR(500),
    OrderReminderText       VARCHAR(500),
    OrderCancelledText      VARCHAR(500),
    OrderReceivedSubj       VARCHAR(100),
    OrderFilledSubj         VARCHAR(100),
    OrderReminderSubj       VARCHAR(100),
    OrderCancelledSubj      VARCHAR(100),
    FooterText              VARCHAR(200),
    PhotoDir                TEXT,
    CONSTRAINT SETTING_PK PRIMARY KEY (SETTINGID)
);


-- Creates a View that generates the OnOrder amount for each product that is in a ''Submitted'' order
CREATE VIEW onorderview AS
(SELECT OD.PRODUCTID, IFNULL(SUM(QTYREQUESTED),0) AS QTYONORDER
FROM orderdetails OD INNER JOIN orders O ON OD.ORDERID = O.ORDERID AND O.STATUS = 'SUBMITTED'
GROUP BY OD.PRODUCTID);

-- Create a Qty Available View, which includes product id and qty available
CREATE VIEW qtyavailableview AS
(SELECT product.PRODUCTID, IFNULL(product.QTYONHAND - QTYONORDER, product.QTYONHAND) AS QTYAVAILABLE
FROM product LEFT OUTER JOIN onorderview ON product.PRODUCTID = onorderview.PRODUCTID);

-- Create a Product View that includes QtyAvailable, OrderLimit, and OnOrder (Amount of product in orders that are requested but not filled)
CREATE VIEW productview AS
(SELECT product.PRODUCTID, product.NAME, product.PRODUCTDESCRIPTION, IF(product.QTYONHAND<0, 0, product.QTYONHAND) AS QTYONHAND, product.MAXORDERQTY,
        (CASE product.MAXORDERQTY
             WHEN 0 THEN QTYAVAILABLE
             ELSE IF(product.MAXORDERQTY<QTYAVAILABLE, product.MAXORDERQTY, IF(QTYAVAILABLE<0, 0, QTYAVAILABLE))
            END
            ) AS ORDERLIMIT,
        product.GOALSTOCK, IFNULL(QTYONORDER,0) AS QTYONORDER, QTYAVAILABLE
FROM product LEFT OUTER JOIN onorderview ON product.PRODUCTID = onorderview.PRODUCTID
             JOIN qtyavailableview ON product.PRODUCTID = qtyavailableview.PRODUCTID);

-- Create a Cart View, which has the number of products in each users cart
-- QtyItemsInCart = number of unique product ids for each user
CREATE VIEW cartview AS
(SELECT C.USERID, COUNT(DISTINCT C.PRODUCTID) AS QYTITEMSINCART
FROM cart C GROUP BY C.USERID);

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
INSERT INTO functions (Name,Description) VALUES ('adminInventory', 'Inventory page to view inventory');
INSERT INTO functions (Name,Description) VALUES ('adminOrders', 'orders page for admins to fill orders that are submitted');
INSERT INTO functions (Name,Description) VALUES ('adminSecurity', 'security page for the admins to change security settings');
INSERT INTO functions (Name,Description) VALUES ('adminReports', 'reports page for admin to generate and download reports');
INSERT INTO functions (Name,Description) VALUES ('adminShoppingList', 'Shopping list page for admins to view and download their shopping list');
INSERT INTO functions (Name,Description) VALUES ('shopperCart', 'Where shoppers can view what items they have in their cart and submit their order');
INSERT INTO functions (Name,Description) VALUES ('shopperHome', 'where shoppers can select items that they would like to purchase');
INSERT INTO functions (Name,Description) VALUES ('shopperOrders', 'where shoppers can view their current past and pending orders');
INSERT INTO functions (Name,Description) VALUES ('addEditProduct','Creates a new product or edits a product info if product already exists'),
                                                ('adminChangeOrderStatus','Changes the status of an order'),
                                                ('adminFillOrder','Lets the user fill an order'),
                                                ('processStockAdjust','Adjust QtyOnHand for a single product or multiple products on inventory page'),
                                                ('shopperAdjustQTYInCart','Adjust QtyRequested for users in cart table'),
                                                ('processAddToCart','Adds product to an users cart in cart table'),
                                                ('shopperRemoveFromCart','Removes a product from an users cart in cart table'),
                                                ('shopperSubmitOrder','Creates an order for users based off of users cart'),
                                                ('accountSettings','Allows User to view their account settings'),
                                                ('addEditCategory','Allows User to add, edit or delete a category'),
                                                ('updateEmailAnnouncementSettings','Allows user to update email and announcement settings'),
                                                ('mobileAdd','Allows user to add items from their phone');
INSERT INTO functions (Name,Description) VALUES ('ProcessLogin', 'Process SSO Login');
INSERT INTO functions (Name,Description) VALUES ('SecurityChangeUserLevel', 'Change authorization level');



INSERT INTO roles (Name,Description) VALUES  ('Admin','Full privileges.'),
                                             ('Student', 'Shopping, cart, and own orders'),
                                             ('Developer','Security'),
                                             ('Inventory Management','View and edit inventory.  Add/Edit/Delete Products.'),
                                             ('Order Fulfillment','View and fill orders.'),
                                             ('Guest', 'Guest');


INSERT INTO users (UserID,FirstName,LastName,UserName,Password,Email) VALUES ('s_admin','TestAdmin','TestAdmin','admin',SHA1('admin'),'admin@clarion.edu'),
                                                                      ('s_student','TestStudent','TestStudent', 'student', SHA1('student'), 'teststudent@clarion.edu'),
                                                                      ('s_developer','TestDeveloper', 'TestDeveloper', 'developer', SHA1('developer'), 'testdeveloper@clarion.edu'),
                                                                      ('s_inventory','TestInventory', 'TestInventory', 'inventory', SHA1('inventory'), 'testinventory@clarion.edu'),
                                                                      ('s_order','TestOrder', 'TestOrder', 'order', SHA1('order'), 'testorder@clarion.edu');

INSERT INTO userroles (UserID,RoleID) VALUES ('s_admin',1);
INSERT INTO userroles (UserID,RoleID) VALUES ('s_student',2);
INSERT INTO userroles (UserID,RoleID) VALUES ('s_developer',3);
INSERT INTO userroles (UserID,RoleID) VALUES ('s_inventory',4);
INSERT INTO userroles (UserID,RoleID) VALUES ('s_order',5);

INSERT INTO users (UserID,FirstName,LastName,UserName,Password,Email) VALUES ('s_gmbennett','Gina', 'Bennett', 's_gmbennett', SHA1('s_gmbennett'), 'g.m.bennett@eagle.clarion.edu'),
                                                                      ('s_ajrobinso1','Austin', 'Robinson', 's_ajrobinso1', SHA1('s_ajrobinso1'), 'a.j.robinson1@eagle.clarion.edu'),
                                                                      ('mlkarg','Meredith', 'Karg', 'mlkarg', SHA1('mlkarg'), 'mlkarg@clarion.edu'),
                                                                      ('tcrissman','Tom', 'Crissman', 'tcrissman', SHA1('tcrissman'), 'tcrissman@clarion.edu'),
                                                                      ('s_skcuster','Sara', 'Custer', 's_skcuster', SHA1('s_skcuster'), 's.k.custer@eagle.clarion.edu'),
                                                                      ('s_nalacoe','Natalie', 'LaCoe', 's_nalacoe', SHA1('s_nalacoe'), 'n.a.lacoe@eagle.clarion.edu'),
                                                                      ('s_srsmith','Samuel', 'Smith', 's_srsmith', SHA1('s_srsmith'), 's.r.smith@eagle.clarion.edu'),
                                                                      ('s_bmbizzarri','Brady', 'Bizzarri', 's_bmbizzarri', SHA1('s_bmbizzarri'), 'b.m.bizzarri@eagle.clarion.edu');

INSERT INTO userroles (UserID,RoleID) VALUES ('s_gmbennett',2),  -- Gina as Student
                                             ('s_ajrobinso1',2),  -- Austin as Student
                                             ('mlkarg',1),  -- Meredith as Admin
                                             ('tcrissman',1),  -- Tom as Admin
                                             ('s_skcuster',2), -- Sara as Student
                                             ('s_skcuster',4), -- Sara as Inventory Management
                                             ('s_nalacoe',4), -- Nat as Order Fulfillment
                                             ('s_nalacoe',2), -- Nat as Student
                                             ('s_srsmith',2), -- Sam as Student
                                             ('s_bmbizzarri',1); -- Brady as developer



INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,1);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,12);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,13);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,14);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,15);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,21);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,22);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,23);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,24);
INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,29);

INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (1,30),
                                                     (1,31),
                                                     (1,19),
                                                     (1,16),
                                                     (1,17),
                                                     (1,28),
                                                     (1,20),
                                                     (1,36),
                                                     (1,37),
                                                     (1,38),
                                                     (1,39);

INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (2,25),
                                                     (2,26),
                                                     (2,27),
                                                     (2,32),
                                                     (2,33),
                                                     (2,34),
                                                     (2,35),
                                                     (2,36);

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
                                                     (3,20),
                                                     (3,21),
                                                     (3,22),
                                                     (3,23),
                                                     (3,24),
                                                     (3,25),
                                                     (3,26),
                                                     (3,27),
                                                     (3,28),
                                                     (3,29),
                                                     (3,30),
                                                     (3,31),
                                                     (3,32),
                                                     (3,33),
                                                     (3,34),
                                                     (3,35),
                                                     (3,36),
                                                     (3,37),
                                                     (3,38),
                                                     (3,39),
                                                     (3,40),
                                                     (3,41);

INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (4,20),
                                                     (4,24),
                                                     (4,28),
                                                     (4,31),
                                                     (4,36);

INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (5,21),
                                                     (5,29),
                                                     (5,30),
                                                     (5,36);

INSERT INTO rolefunctions (RoleID,FunctionID) VALUES (6,16),
                                                     (6,17);

INSERT INTO `category` (`CategoryID`, `CategoryDescription`) VALUES
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

INSERT INTO `product` (`ProductID`, `Name`, `ProductDescription`, `QtyOnHand`, `MaxOrderQty`, `GoalStock`) VALUES
(1, 'Strawberry Shampoo', '', 0, 0, 0),
(2, 'Ocean Breeze Shampoo', '', 14, 0, 5),
(3, 'Ocean Breeze Conditioner', '', 6, 0, 5),
(4, 'Tropical Coconut Shampoo', '', 4, 0, 5),
(5, 'Tropical Coconut Conditioner', '', 14, 0, 5),
(6, 'Dry Shampoo Spray', '', 3, 0, 5),
(7, 'Curling Cream', '', 0, 0, 3),
(8, 'Olive Oil Styling Gel', '', 0, 0, 3),
(9, 'Hair Pick', '', 12, 0, 4),
(10, 'Lift and Style Comb', '', 2, 0, 3),
(11, 'Wide Tooth Comb', '', 0, 0, 0),
(12, 'Regular Comb', '', 10, 0, 3),
(13, 'Do Rag', '', 7, 0, 5),
(14, 'Night Bonnet', '', 5, 0, 5),
(15, 'Original Bar Soap', '', 40, 0, 8),
(16, 'Sensitive Skin Bar Soap', '', 11, 0, 6),
(17, 'Liquid Hand Soap', '', 10, 0, 8),
(18, 'Regular Body Wash (neutral scent)', '', 13, 0, 5),
(19, 'Sensitive Skin Body Wash', '', 5, 0, 5),
(20, 'Men\'s Regular Body Wash', '', 9, 0, 0),
(21, 'Loofah/Shower Puff', '', 15, 0, 6),
(22, 'Medicated Body Powder', '', 4, 0, 3),
(23, 'Creamy Body Lotion with Cocoa Butter and Shea', '', 10, 0, 5),
(24, 'Vitamin E Skin Care Cream', '', 49, 0, 4),
(25, 'Cocoa Butter Skin Care Cream', '', 2, 0, 4),
(26, 'Petrolium Jelly', '', 12, 0, 3),
(27, 'Women\'s Deodorant', '', 37, 0, 10),
(28, 'Men\'s Deodorant', '', 12, 0, 10),
(29, 'Hand Sanitizer', '', 43, 0, 6),
(30, 'Men\'s Razors', '', 56, 0, 10),
(31, 'Women\'s Razors', '', 25, 0, 10),
(32, 'Men\'s Shaving Cream', '', 20, 0, 6),
(33, 'Women\'s Shaving Cream', '', 13, 0, 6),
(34, 'Baby Powder', '', 0, 0, 0),
(35, 'Bandaids', '', 3, 0, 5),
(36, 'Q Tips', '', 17, 0, 8),
(37, 'Baby Wipes', '', 3, 0, 0),
(38, 'Cream Style Face Wash', '', 7, 0, 5),
(39, 'Facial Cleanser', '', 5, 0, 5),
(40, 'Face/makeup wipes', '', 9, 0, 8),
(41, 'Lip Balm', '', 12, 0, 5),
(42, 'Dental Floss', '', 47, 0, 5),
(43, 'Mouthwash', '', 6, 0, 5),
(44, 'Toothbrush single', '', 10, 0, 10),
(45, 'Toothbrush (2 pack)', '', 14, 0, 0),
(46, 'Toothbrush (6 pack)', '', 0, 0, 0),
(47, 'Toothpaste', '', 7, 0, 5),
(48, 'Makeup Kit', '', 0, 0, 0),
(49, 'Eye makeup kit', '', 0, 0, 0),
(50, 'Tampons - Variety Pack', '', 5, 0, 5),
(51, 'Feminine Pads with Wings (box of 3)', '', 23, 0, 5),
(52, 'Plastic Plate', '', 8, 0, 8),
(53, 'Plastic Cup', '', 8, 0, 8),
(54, 'Plastic Bowl', '', 9, 0, 8),
(55, 'Reusable Water Bottle', '', 18, 0, 4),
(56, 'Metal Fork', '', 10, 0, 8),
(57, 'Metal Knife', '', 9, 0, 8),
(58, 'Metal Spoon', '', 10, 0, 8),
(59, 'Can Opener', '', 8, 0, 3),
(60, 'Microfiber Cleaning towel', '', 0, 0, 0),
(61, '5 piece kitchen towel set', '', 0, 0, 0),
(62, 'Dish Soap', '', 67, 0, 8),
(63, 'Cleaning Wipes', '', 20, 0, 8),
(64, 'All Purpose Cleaner', '', 0, 0, 5),
(65, 'Toilet Bowl Cleaner', '', 5, 0, 5),
(66, 'Disinfecting Spray', '', 0, 0, 0),
(67, '13 Gallon Garbage Bags', '', 1, 0, 5),
(68, 'Travel Tissue pack', '', 1, 0, 5),
(69, 'Tissue Box', '', 18, 0, 8),
(70, 'Paper Towel Single Roll', '', 33, 0, 20),
(71, 'Toilet Paper (4 pack)', '', 16, 0, 20),
(72, 'Laundry soap', '', 5, 0, 5),
(73, 'Dryer Sheets', '', 6, 0, 5),
(74, 'Mini Plastic Cups with lids', '', 0, 0, 0),
(75, 'Tropical Nector Candle: 2 pack', '', 0, 0, 0),
(76, '60W light bulb', '', 28, 0, 0),
(77, 'Wash Cloth', '', 9, 0, 8),
(78, 'Hand Towel', '', 7, 0, 8),
(79, 'Body Towel', '', 8, 0, 8),
(80, 'Twin 3-piece Sheet Set', '', 6, 0, 0),
(81, 'Twin Quilt', '', 1, 0, 0),
(82, 'Full/Queen comforter', '', 0, 0, 0),
(83, 'Pink Fleece Blanket', '', 0, 0, 0),
(84, '3 piece bath towel set', '', 7, 0, 0),
(85, 'Shower Curtain Set (pink)', '', 3, 0, 0),
(86, 'Nutrigrain Bar: Mixed Berry Box', '', 6, 0, 5),
(87, 'Nutrigrain Bar: Apple Cinnamon Box', '', 6, 0, 5),
(88, 'Nutrigrain Bar: Strawberry Box', '', 7, 0, 5),
(89, 'Nutrigrain Bar: Blueberry Box', '', 0, 0, 0),
(90, 'Nutrigrain Bar: Apple Cinnamon Single', '', 0, 0, 0),
(91, 'Oatmeal: Fruit and Cream Variety Box', '', 4, 0, 3),
(92, 'Oatmeal: Low Sugar Variety Box', '', 0, 0, 0),
(93, 'Instant Cream of Wheat: Maple Brown Sugar box', '', 0, 0, 0),
(94, 'Oatmeal: Maple and Brown Sugar Microwave Cup', '', 4, 0, 5),
(95, 'Oatmeal: Strawberries and Cream Cup', '', 0, 0, 5),
(96, 'Froasted Flakes Cereal Box', '', 2, 0, 3),
(97, 'Honey Nut O\'s Cereal Box', '', 3, 0, 3),
(98, 'Toasted O\'s Cereal Box', '', 1, 0, 0),
(99, 'Strawberry Awake Cereal Box', '', 3, 0, 3),
(100, 'Oatmeal Cream Pie Cereal Box', '', 0, 0, 0),
(101, 'Cereal: Honey Nut Cheerios ? Single Serve', '', 0, 0, 0),
(102, 'Cereal: Fruit Loops - Single Serve', '', 0, 0, 0),
(103, 'Cereal: Cinnamon Toast Crunch - Single Serve', '', 0, 0, 0),
(104, 'Toaster Pasteries: Frosted Brown Sugar Box', '', 4, 0, 5),
(105, 'Toaster Pasteries: Frosted Strawberry Box', '', 5, 0, 5),
(106, 'Pop Tarts: Frosted Cookies and Creme: Individual packs', '', 2, 0, 0),
(107, 'Original Syrup: Bottle', '', 1, 0, 0),
(108, 'Pancake Mix: Box', '', 0, 0, 0),
(109, 'Belvita Breakfast Biscuits: Cinnamon & Sugar - single pack', '', 0, 0, 0),
(110, 'Nature Valley Biscuits: Cinnamon Almond Butter - Single', '', 0, 0, 0),
(111, 'Bottle Water', '', 19, 0, 0),
(112, 'Box of Hot Cocoa', '', 0, 0, 0),
(113, 'Body Armour Super Drink - Fruit Punch', '', 0, 0, 0),
(114, 'Pepsi: 2L Bottle', '', 0, 0, 0),
(115, 'Carmel Apple Black Tea: Box', '', 0, 0, 0),
(116, 'Gatorade: Glacier Freeze', '', 0, 0, 0),
(117, 'Salted Caramel Light Roast Coffee: Bag', '', 1, 0, 0),
(118, 'Quest Salted Caramel Protein drink: 4 pack', '',0, 0, 0),
(119, 'V8 Engery: Peach Mango can', '', 0, 0, 0),
(120, 'Sunkist Grape Drink Mix Singles: Box', '', 0, 0, 0),
(121, 'Sparkling Water-Black Raspberry: Bottle', '', 0, 0, 0),
(122, 'Sparkling Water - Orange Mango: Bottle', '', 0, 0, 0),
(123, 'Sunkist Pineapple Drink Mix Singles: Box', '', 0, 0, 0),
(124, 'Sparkling Water-Grapefruit: Can', '', 6, 0, 0),
(125, 'Sparkling Water-Black Cherry: Can', '', 0, 0, 0),
(126, 'Sparkling Water-Watermelon: Can', '', 0, 0, 0),
(127, 'Green Tea with Pomegranate: single packet', '', 0, 0, 0),
(128, 'Electrolyte', '', 0, 0, 3),
(129, 'Compleats Meals: Turkey and Dressing', '', 7, 0, 5),
(130, 'Compleats Meals: Tender Beef with Mashed Potatoes & gravy', '', 0, 0, 5),
(131, 'Compleats Meals: Beef Pot Roast', '', 3, 0, 5),
(132, 'Compleats Meals: Chicken & Noodles', '', 2, 0, 5),
(133, 'Compleats Meals: Rice & Chicken', '', 0, 0, 0),
(134, 'Compleats Meals: Dumplings & Chicken', '', 2, 0, 0),
(135, 'Compleats Meals: Stroganoff', '', 0, 0, 0),
(136, 'Compleats Meals: Chicken Alfredo', '', 0, 0, 0),
(137, 'Hamburger Helper: Cheesburger Macaroni', '', 0, 0, 0),
(138, 'Pasta: Spaghetti', '', 19, 0, 10),
(139, 'Pasta: Rotini', '', 11, 0, 10),
(140, 'Pasta: Egg Noodles', '', 0, 0, 0),
(141, 'Pasta: Lasgna Noodles ', '', 2, 0, 0),
(142, 'Tomato Sauce with Meat', '', 1, 0, 6),
(143, 'Tomato Sauce no Meat', '', 9, 0, 6),
(144, 'Pizza Sauce', '', 0, 0, 0),
(145, 'Alfredo Sauce', '', 0, 0, 0),
(146, 'Spaghettio\'s Original', '', 10, 0, 8),
(147, 'Spaghettio\'s with Meatballs', '', 8, 0, 8),
(148, 'Canned Beef Ravioli', '', 17, 0, 0),
(149, 'Macaroni and Cheese Box', '', 10, 0, 10),
(150, 'Shells & Cheese Box', '', 0, 0, 0),
(151, 'Instant White Rice Box', '', 5, 0, 5),
(152, 'Instant Brown Rice Box', '', 5, 0, 5),
(153, 'Jasmine Rice: Bag', '', 0, 0, 0),
(154, 'Yellow Rice: Bag', '', 0, 0, 0),
(155, 'White Rice: Micrwaveable Cup - 2 pack', '', 6, 0, 5),
(156, 'Brown Rice: Microwaveable Cup - 2 pack', '', 5, 0, 5),
(157, 'Rice a Roni Cheddar Broccoli: Microwaveable Cup', '', 6, 0, 6),
(158, 'Microwaveable Cauliflower Rice', '', 0, 0, 0),
(159, 'Microwaveable Ready Pasta', '', 1, 0, 0),
(160, 'Beef Ravioli Microwaveable Cups', '', 2, 0, 8),
(161, 'Beefaroni Microwaveable Cups', '', 0, 0, 0),
(162, 'Macaroni & Cheese: Microwaveable Cups', '', 26, 0, 24),
(163, 'White Cheddar Macaroni & Cheese cups', '', 0, 0, 0),
(164, 'Shells & Cheese: Microwaveable Cups', '', 3, 0, 0),
(165, 'Rice Sides: Chicken Flavor', '', 3, 0, 5),
(166, 'Rice Sides: Creamy Chicken', '', 8, 0, 0),
(167, 'Rice Sides: Chicken with Broccoli', '', 2, 0, 5),
(168, 'Rice Sides: Herb and Butter', '', 5, 0, 5),
(169, 'Pasta Sides: Creamy Chicken', '', 10, 0, 5),
(170, 'Pasta Sides: Butter and Herb', '', 9, 0, 5),
(171, 'Pasta Sides: Alfredo', '', 0, 0, 0),
(172, 'Pasta Sides: Cheddar Broccoli', '', 10, 0, 0),
(173, 'Italian Sides: 4 Cheese Pasta', '', 14, 0, 5),
(174, 'Fiesta Sides: Mexican Rice', '', 5, 0, 5),
(175, 'Fiesta Sides: Spanish Rice ', '', 0, 0, 0),
(176, 'Instant Mashed Potatoes: Butter', '', 3, 0, 5),
(177, 'Instant Mashed Potatoes: Four Cheese', '', 4, 0, 5),
(178, 'Mashed Potatoes: Box', '', 0, 0, 0),
(179, 'Au Gratin Potatoes: Box', '', 0, 0, 0),
(180, 'Suddenly Pasta Salad: Creamy Macaroni', '', 0, 0, 0),
(181, 'Stuffing: Chicken flavor - Box', '', 0, 0, 0),
(182, 'Butter Mashed Potato: Microwaveable Cup', '', 0, 0, 0),
(183, 'Beef Vegetable Soup', '', 9, 0, 10),
(184, 'Broccoli Cheese Soup', '', 8, 0, 10),
(185, 'Chicken Noodle Soup', '', 17, 0, 10),
(186, 'Creamy Chicken Noodle Soup', '', 0, 0, 0),
(187, 'Chicken and Rice Soup', '', 8, 0, 10),
(188, 'Clam Chowder Soup', '', 4, 0, 5),
(189, 'Bean with Bacon Soup', '', 0, 0, 0),
(190, 'Cream of Potato Soup', '', 8, 0, 10),
(191, 'Tomato Soup', '', 13, 0, 10),
(192, 'Vegetable Soup', '', 10, 0, 10),
(193, 'Beef Barley Soup', '', 1, 0, 0),
(194, 'Cream of Chicken Soup', '', 0, 0, 0),
(195, 'Cream of Mushroom Soup', '', 0, 0, 0),
(196, 'Bean and Ham Soup', '', 1, 0, 0),
(197, 'Chicken Broccoli Cheese w/Potato', '', 0, 0, 0),
(198, 'Cheddar Cheese Soup', '', 1, 0, 0),
(199, 'Chicken and Dumpling Soup', '', 0, 0, 0),
(200, 'Beef Raman packets', '', 14, 0, 24),
(201, 'Chicken Raman packets', '', 28, 0, 24),
(202, 'Chicken Stock', '', 0, 0, 0),
(203, 'Vegetable Stock', '', 1, 0, 0),
(204, 'Beef Broth', '', 0, 0, 0),
(205, 'Italian Style Meatball soup', '', 0, 0, 0),
(206, 'Chicken Noodle Soup: Microwaveable Cup', '', 11, 0, 10),
(207, 'Tomato Soup: Microwaveable Cup', '', 5, 0, 5),
(208, 'Beef Raman: Microwaveable Cup', '', 0, 0, 0),
(209, 'Chicken Raman: Microwaveable Cup', '', 10, 0, 10),
(210, 'Cheddar Cheese Raman: Microwaveable Cup', '', 1, 0, 0),
(211, 'Hot & Spicy Shrimp Raman: Microwaveable Cup', '', 1, 0, 0),
(212, 'Chicken and Stars Soup: Microwaveable Cup', '', 1, 0, 0),
(213, 'Applesauce cups 6 Pack: original', '', 14, 0, 5),
(214, 'Applesauce cups 6 Pack: Cinnamon', '', 6, 0, 5),
(215, 'Applesauce Cinnamon: large jar', '', 0, 0, 0),
(216, 'Mandarin Orange Cups: 4 pack', '', 0, 0, 5),
(217, 'Peach Cups: 4 pack', '', 4, 0, 5),
(218, 'Pear Cups: 4 pack', '', 5, 0, 5),
(219, 'Pineapple Cups: 4 pack', '', 2, 0, 5),
(220, 'Raisins: Individual ', '', 0, 0, 0),
(221, 'Canned Mandarin Oranges', '', 2, 0, 0),
(222, 'Canned Pineapple ', '', 0, 0, 0),
(223, 'Canned Fruit Cocktail ', '', 0, 0, 0),
(224, 'Canned Pears', '', 0, 0, 0),
(225, 'Mixed Fruit: Single Cup', '', 0, 0, 0),
(226, 'Applesauce Original: individual Cup', '', 0, 0, 0),
(227, 'Potato Crisps: Original', '', 0, 0, 3),
(228, 'Potato Crisps: Cheddar Cheese', '', 1, 0, 3),
(229, 'Potato Crisps: Barbecue', '', 1, 0, 3),
(230, 'Butter Crackers: Box', '', 3, 0, 3),
(231, 'Wheat Crackers: Box', '', 2, 0, 3),
(232, 'White Cheddar Cheese Crackers: Box', '', 0, 0, 3),
(233, 'Multigrain Crackers: Box', '', 0, 0, 0),
(234, 'Peanut Butter Sandwich Crackers: Box of 8 packs', '', 3, 0, 4),
(235, 'Cheese Sandwhich Crackers: Box of 8 packs', '', 4, 0, 4),
(236, 'Microwave popcorn: box of 3 packs', '', 5, 0, 5),
(237, 'Individaul gummy packs', '', 0, 0, 0),
(238, 'Animal Crackers Snack Size Bag', '', 0, 0, 0),
(239, 'Green Pea Snack Crisps', '', 0, 0, 0),
(240, 'Girl Scout Cookies: Lemonades pack', '', 0, 0, 0),
(241, 'Crispy Rice Treats Box', '', 0, 0, 0),
(242, 'Seasoned Croutons', '', 0, 0, 0),
(243, 'Smokehouse Almonds', '', 0, 0, 0),
(244, 'Nature Valley Crunchy Oats & Honey: single bar', '', 0, 0, 0),
(245, 'Salted Caramel Protein Bar: Single', '', 11, 0, 0),
(246, 'Creamy Peanut Butter Protein Bar: Single', '', 0, 0, 0),
(247, 'Smores Protein Bar: Single', '', 0, 0, 0),
(248, 'Granola Bar: Chewy Choloclate chunk Single', '', 0, 0, 0),
(249, 'Strawberry Geletin Cup', '', 0, 0, 0),
(250, 'Cheese Sandwhich Crackers: Individual', '', 0, 0, 0),
(251, 'Sliced Carrots', '', 8, 0, 8),
(252, 'Corn', '', 7, 0, 8),
(253, 'Cream Style Corn', '', 1, 0, 0),
(254, 'Green Beans', '', 26, 0, 8),
(255, 'Peas', '', 6, 0, 8),
(256, 'Sliced Potatoes', '', 7, 0, 8),
(257, 'Chick Peas/Garbanzo Beans', '', 0, 0, 0),
(258, 'Diced Tomatoes', '', 2, 0, 0),
(259, 'Kidney Beans', '', 1, 0, 0),
(260, 'Baked Beans', '', 0, 0, 0),
(261, 'Sauerkraut', '', 2, 0, 0),
(262, 'Mixed Vegetables ', '', 0, 0, 0),
(263, 'Refried Beans', '', 2, 0, 0),
(264, 'Collard Greens', '', 1, 0, 0),
(265, 'Canned Tuna', '', 17, 0, 10),
(266, 'Canned Chicken', '', 5, 0, 10),
(267, 'Tuna Packet: no flavor', '', 33, 0, 10),
(268, 'Chicken Packet: no flavor', '', 1, 0, 0),
(269, 'Chicken Packet: Buffalo flavor', '', 0, 0, 0),
(270, 'Ketchup', '', 4, 0, 5),
(271, 'Mustard', '', 4, 0, 5),
(272, 'Mayonnaise', '', 2, 0, 5),
(273, 'Peanut Butter', '', 3, 0, 5),
(274, 'Grape Jelly', '', 3, 0, 3),
(275, 'Peanut Butter & Grape Jelly Stripes', '', 0, 0, 0),
(276, 'Sugar Free Grape Jam', '', 0, 0, 0),
(277, 'Srawberry Jelly', '', 2, 0, 3),
(278, 'Large 26 OZ salt', '', 1, 0, 0),
(279, 'Salt & Pepper combo pack', '', 4, 0, 5),
(280, 'Tomato Paste', '', 1, 0, 0),
(281, 'Chicken Gravy Packet', '', 1, 0, 0),
(282, 'Brown Gravy Packet', '', 1, 0, 0),
(283, 'Savory Beef Gravy: Jar', '', 0, 0, 0),
(284, 'Chipotle Sauce', '', 0, 0, 0),
(285, 'Taco Seasoning mix: packet', '', 1, 0, 0),
(286, 'Salad Dressing: French', '', 2, 0, 0),
(287, 'Season Salt: Bottle', '', 0, 0, 0),
(288, 'Teriyaki Sauce: Bottle', '', 0, 0, 0),
(289, 'Minced Onion seasoning', '', 0, 0, 0),
(290, 'Guacamole Dip Mix: packet', '', 2, 0, 0),
(291, 'Ranch seasoning: packet', '', 0, 0, 0),
(292, 'Garlic Parmesean mix: packet', '', 0, 0, 0),
(293, 'Sloppy Joe mix: packet', '', 1, 0, 0),
(294, 'Cinnamon', '', 0, 0, 0),
(295, 'Manwich Original Sloppy Joe Sauce', '', 3, 0, 0),
(296, 'Chocolate Cake Mix: Box', '', 0, 0, 0),
(297, 'All purpose flour', '', 0, 0, 0),
(298, 'Plain Bread Crumbs', '', 1, 0, 0),
(299, 'Corn Muffin Mix: Box', '', 1, 0, 0),
(300, 'Fudge Brownie mix: Box', '', 0, 0, 0),
(301, 'Toothpicks: pack', '', 0, 0, 0),
(302, 'Disposable Cupcack Liners: pack', '', 0, 0, 0),
(303, 'Food Coloring: pack of 4', '', 0, 0, 0),
(304, 'Milk chocolate brownie Mix: Box', '', 0, 0, 0),
(305, 'Baking Powder', '', 0, 0, 0),
(306, 'Cheddar Bay Biscuit Mix', '', 0, 0, 0),
(307, 'Angel Food cake mix: Box', '', 0, 0, 0),
(308, 'Baby Shampoo', '', 1, 0, 0),
(309, 'Pack of 10 Emery Boards', '', 1, 0, 0),
(310, 'Baseball hats', '', 4, 0, 0),
(311, 'Reuseable Face Mask', '', 60, 0, 0),
(312, 'Compleats Meals: Salisbury Steak', '', 2, 0, 0),
(313, 'White Rice: Bag', '', 1, 0, 0),
(314, 'Rice with Chicken and Vegetables: Microwaveable', '', 2, 0, 0),
(315, 'Chicken Broth', '', 2, 0, 0),
(316, 'Lasgna Style Soup', '', 1, 0, 0),
(317, 'Chili with Beans & Beef', '', 1, 0, 0),
(318, 'Savory Chicken with Brown Rice', '', 5, 0, 0),
(319, 'Creamy Chicken Noodle', '', 2, 0, 0),
(320, 'Italian Style Meatball', '', 1, 0, 0),
(321, 'Unsweetened Applesauce Can', '', 1, 0, 0),
(322, 'Diced Mango: 3 pack', '', 1, 0, 0),
(323, 'Rasin snack box: 6 pack', '', 7, 0, 0),
(324, 'Banana creme sandwich cookie: 6 pack', '', 2, 0, 0),
(325, 'Vanilla Creme sandwich cookie: 6 pack', '', 2, 0, 0),
(326, 'Vienna Sausage: BBQ flavor', '', 5, 0, 0);
COMMIT;

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
(86, 4),
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
(308,1),
(309,1),
(310,3),
(311,3),
(312,6),
(313,7),
(314,7),
(315,9),
(316,9),
(317,9),
(318,9),
(319,9),
(320,9),
(321,10),
(322,10),
(323,11),
(324,11),
(325,11),
(326,12),
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

INSERT INTO `product` (`ProductID`, `Name`, `ProductDescription`, `QtyOnHand`, `MaxOrderQty`, `GoalStock`) VALUES
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

INSERT INTO `orders` (`ORDERID`, `USERID`, `STATUS`, `DATEORDERED`, `DATEFILLED`, `DATECOMPLETED`, `COMMENT`) VALUES
    (1, 's_gmbennett', 'COMPLETED', '2021-08-29', '2021-09-01', '2021-09-05', 'I am allergic to Nuts'),
    (2, 's_ajrobinso1', 'READY FOR PICKUP', '2021-09-16', '2021-09-17', '', ' '),
    (3, 's_gmbennett', 'SUBMITTED', '2021-09-18',  '', '', 'I live off campus'),
    (4, 's_ajrobinso1', 'SUBMITTED', '2021-09-19', '', '', 'Size Xtra Large For the Tank Top');
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

INSERT INTO `orderdetails` (`ORDERID`, `PRODUCTID`, `QTYREQUESTED`, `QTYFILLED`) VALUES
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

INSERT INTO `cart` (`USERID`, `PRODUCTID`, `QTYREQUESTED`) VALUES
    ('s_order', 1001, 6),      -- QtyRequested > MaxOrderQty, can't be ordered as in cart
    ('s_order', 1002, 1),      -- No issues
    ('s_order', 1003, 2),      -- No issues
    ('s_order', 1006, 1),      -- Date is from 2020
    ('s_order', 1009, 10),     -- QtyRequested > QtyAvailable, can't be ordered as in cart
    ('s_order', 1012, 4);      -- QtyAvailable = 0, Item is out of stock
COMMIT;

INSERT INTO `cart` (`USERID`, `PRODUCTID`, `QTYREQUESTED`) VALUES
    ('s_gmbennett', 1001, 6),      -- QtyRequested > MaxOrderQty, can't be ordered as in cart
    ('s_gmbennett', 1002, 1),      -- No issues
    ('s_gmbennett', 1003, 2),      -- No issues
    ('s_gmbennett', 1009, 10),     -- QtyRequested > QtyAvailable, can't be ordered as in cart
    ('s_gmbennett', 1012, 4);      -- QtyAvailable = 0, Item is out of stock
COMMIT;

INSERT INTO `setting` (SettingID, EmailOrderReceived, EmailOrderFilled, EmailOrderReminder, EmailOrderCancelled, OrderReceivedText,
                       OrderFilledText, OrderReminderText, OrderCancelledText, OrderReceivedSubj, OrderFilledSubj, OrderReminderSubj,
                       OrderCancelledSubj, FooterText, PhotoDir) VALUES
     (1,
     'A.J.Robinson1@eagle.clarion.edu, B.J.Lindermuth@eagle.clarion.edu',
     'A.J.Robinson1@eagle.clarion.edu, B.J.Lindermuth@eagle.clarion.edu',
     'A.J.Robinson1@eagle.clarion.edu, B.J.Lindermuth@eagle.clarion.edu, g.m.bennett@eagle.clarion.edu',
     'A.J.Robinson1@eagle.clarion.edu, B.J.Lindermuth@eagle.clarion.edu, g.m.bennett@eagle.clarion.edu',
     'Hello!  We have received your order and it will be filled as soon as possible.  You will receive another email when it is ready for pickup.  Thank you!',
     'Hello!  Your order has been filled and is ready for pick up at the Gemmell Info Desk.
     Info desk hours are Monday-Friday 9AM -10PM and Noon-10PM on Saturday and Sunday. Please bring a photo ID with you when picking up your order.  Thank you!',
     'Hello!  Reminder that your order is ready to be picked up!  Please pick it up at your earliest convenience at the Gemmell info desk.
     Info desk hours are Monday-Friday 9AM -10PM and Noon-10PM on Saturday and Sunday. Please bring a photo ID with you when picking up your order.  Thank you!',
     'Hello!  Your order has been cancelled.  To place another order, visit: Clarion.edu/hungry',
     'Your Order Has Been Received!', 'Your Order is Ready For Pickup', 'Reminder: Your Order is Ready For Pickup','Your Order has been Cancelled',
     'The last day to order from the resource room will be on Friday, November 19th','');
