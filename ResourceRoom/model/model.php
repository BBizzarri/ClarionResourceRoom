<?php
    include_once 'product.php';
    include_once 'cart.php';
    include_once 'category.php';
    include_once 'order.php';
    include_once 'user.php';


    function getDBConnection() {
            $dsn = 'mysql:host=localhost;dbname=resourceroom';
            $username = 'cis411';
            $password = 'cis411';

            try {
                    $db = new PDO($dsn, $username, $password);
            } catch (PDOException $e) {
                    $errorMessage = $e->getMessage();
                    include '../view/errorPage.php';
                    die;
            }
            return $db;
    }

    function logSQLError($errorInfo) {
        $errorMessage = $errorInfo[2];
        if (isset($_SESSION["UserName"])) {
            $username = $_SESSION["UserName"];
            $userID = $_SESSION["UserID"];
        } else {
            $userID = 0;
            $username = "unknown";
        }

        $db = getDBConnection();
        $query = 'INSERT INTO errorlog (UserID, UserName, ErrorMessage)
                VALUES (:UserID, :UserName, :ErrorMessage)';
        $statement = $db->prepare($query);
        $statement->bindValue(':UserName', $username);
        $statement->bindValue(':UserID', $userID);
        $statement->bindValue(':ErrorMessage', $errorMessage);
        $success = $statement->execute();
        $statement->closeCursor();
        include '../view/errorPage.php';
    }



    function AdjustCart($PRODUCTID, $QTYREQUESTED)
    {
        $USERID = getUserID();
        $db = getDBConnection();
        $query = "update cart set QTYREQUESTED = :QTYREQUESTED where PRODUCTID = :PRODUCTID and USERID = :USERID";
        $statement = $db->prepare($query);
        $statement->bindValue(':USERID', $USERID);
        $statement->bindValue(':PRODUCTID', $PRODUCTID);
        $statement->bindValue(':QTYREQUESTED', $QTYREQUESTED);
        $success = $statement->execute();
        $statement->closeCursor();
        if($success)
        {

            return $statement->rowCount();
        }
        else
        {
            logSQLError($statement->errorInfo());
        }
    }

    function addCategory($CategoryName)
    {
       $db = getDBConnection();
       $query = 'INSERT INTO category (CATEGORYDESCRIPTION)
                                        VALUES (:CATEGORYDESCRIPTION)';
       $statement = $db->prepare($query);
       $statement->bindValue(':CATEGORYDESCRIPTION', $CategoryName);
       $success = $statement->execute();
       $statement->closeCursor();
       if($success)
       {
           return $db->lastInsertId();;
       }
       else
       {
           logSQLError($statement->errorInfo());
       }
    }

    function addProduct($ProductName, $QtyOnHand, $MaxOrderQty, $GoalStock, $ProductDescription, $ProductCategories)
    {
       $db = getDBConnection();
       $query = 'INSERT INTO product (NAME, QTYONHAND, MAXORDERQTY, GOALSTOCK, PRODUCTDESCRIPTION)
                                        VALUES (:NAME, :QTYONHAND, :MAXORDERQTY, :GOALSTOCK, :PRODUCTDESCRIPTION)';
       $statement = $db->prepare($query);
       $statement->bindValue(':NAME', $ProductName);
       $statement->bindValue(':QTYONHAND', $QtyOnHand);
       $statement->bindValue(':MAXORDERQTY', $MaxOrderQty);
       $statement->bindValue(':GOALSTOCK', $GoalStock);
       $statement->bindValue(':PRODUCTDESCRIPTION', $ProductDescription);
       $success = $statement->execute();
       $statement->closeCursor();
       addProductCategories($ProductCategories, $db->lastInsertId());
       if($success)
       {
           return $db->lastInsertId();;
       }
       else
       {
           logSQLError($statement->errorInfo());
       }
    }

    function addProductCategories($ProductCategories, $ProductID) {
        $db = getDBConnection();
        clearCategories($ProductID);
        foreach($ProductCategories as $IndividualCategory)
        {
           $query = 'INSERT INTO productcategories (PRODUCTID, CATEGORYID)
                                            VALUES (:PRODUCTID, :CATEGORYID)';
           $statement = $db->prepare($query);
           $statement->bindValue(':PRODUCTID', $ProductID);
           $statement->bindValue(':CATEGORYID', $IndividualCategory);
           $success = $statement->execute();
           $statement->closeCursor();

           if($success)
           {
               //savePriceImageFile($db->lastInsertId());
           }
           else
           {
               logSQLError($statement->errorInfo());
           }
        }
    }

    function addToCart($PRODUCTID, $QTYREQUESTED)
    {
        $USERID = getUserID();
        $db = getDBConnection();
        $query = 'INSERT INTO cart (UserID, PRODUCTID, QTYREQUESTED)
            VALUES (:USERID, :PRODUCTID, :QTYREQUESTED)';
        $statement = $db->prepare($query);
        $statement->bindValue(':USERID', $USERID);
        $statement->bindValue(':PRODUCTID', $PRODUCTID);
        $statement->bindValue(':QTYREQUESTED', $QTYREQUESTED);
        $success = $statement->execute();
        $statement->closeCursor();
        if($success)
        {

            return $statement->rowCount();
        }
        else
        {
            logSQLError($statement->errorInfo());
        }
    }

    function changeOrderStatus($orderID,$newStatus){
            if($newStatus == "READY FOR PICKUP"){
                $DATECOLUM = "DATEFILLED";
            }else {
                $DATECOLUM = "DATECOMPLETED";
            }
            try {
                $db = getDBConnection();
                $query = "update orders set STATUS = :newStatus, $DATECOLUM = :DATE where ORDERID = :ORDERID";
                $statement = $db->prepare($query);
                $statement->bindValue(':ORDERID', $orderID);
                $statement->bindValue(':newStatus', $newStatus);
                $statement->bindValue(':DATE', date("Y-m-d"));
                $statement->execute();
                $results = $statement->fetchAll();
                $statement->closeCursor();
            } catch (PDOException $e) {
                $errorMessage = $e->getMessage();
                include '../view/errorPage.php';
                die;
            }
    }

    function clearCart($USERID){
            $db = getDBConnection();
            $query = 'DELETE FROM cart WHERE (USERID = :USERID)';
            $statement = $db->prepare($query);
            $statement->bindValue(':USERID', $USERID);
            $success = $statement->execute();
            $statement->closeCursor();
            if($success)
            {
                $_SESSION['itemsInCart'] = 0;
                return $statement->rowCount();
            }
            else
            {
                logSQLError($statement->errorInfo());
            }
    }

    function clearCategories($ProductID)
    {
       $db = getDBConnection();
       $query = 'DELETE FROM productcategories WHERE PRODUCTID = :PRODUCTID';
       $statement = $db->prepare($query);
       $statement->bindValue(':PRODUCTID', $ProductID);
       $success = $statement->execute();
       $statement->closeCursor();

       if($success)
       {
           //savePriceImageFile($db->lastInsertId());
       }
       else
       {
           logSQLError($statement->errorInfo());
       }
    }

    function deactivateCategory($categoryID){
        try{
            $db = getDBConnection();
            $query = "SELECT * FROM productcategories INNER JOIN productview on productcategories.PRODUCTID = productview.PRODUCTID";
            $statement = $db->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll( PDO::FETCH_GROUP| PDO::FETCH_ASSOC);
            $statement->closeCursor();
            $products = array();

            foreach($result as $product)
            {
                $isInCategory = False;
                foreach($product as $categoryArray){
                    if($categoryArray['CATEGORYID'] == $categoryID)
                    {
                        $isInCategory = True;
                    }
                }
                if($isInCategory)
                {
                    if(count($product) == 1)
                    {
                        if($product[0]['GOALSTOCK'] != 0 OR $product[0]['QTYONHAND'] != 0)
                        {
                            array_push($products, $product);
                        }
                    }
                }
            }



            $productArray = array();
            if(count($products) > 0)
            {
                foreach($products as $product)
                {
                    array_push($productArray, new product($product[0]['PRODUCTID'],$product[0]['NAME'],$product[0]['PRODUCTDESCRIPTION'],$product[0]['QTYONHAND'],
                        $product[0]['MAXORDERQTY'],$product[0]['ORDERLIMIT'],$product[0]['GOALSTOCK'],$product[0]['QTYONORDER'],$product[0]['QTYAVAILABLE']));
                }
            }
            else
            {
                $db = getDBConnection();
                $query = "update category set CATEGORYACTIVE = :CATEGORYACTIVE  where category.CATEGORYID = :CATEGORYID";
                $statement = $db->prepare($query);
                $statement->bindValue(':CATEGORYACTIVE', 'N');
                $statement->bindValue(':CATEGORYID', $categoryID);
                $statement->execute();
                $results = $statement->fetchAll();
                $statement->closeCursor();
            }
            return $productArray;
        }
        catch (Exception $ex)
        {
            $errorMessage = $ex->getMessage();
            include '../view/errorPage.php';
            die;
        }
    }


    function fillOrder($order){
        try {
            $db = getDBConnection();
            foreach($order->getOrderDetails() as $orderDetail){
                $query = "update product set QTYONHAND = QTYONHAND - :QTYFILLED where PRODUCTID = :PRODUCTID";
                $statement = $db->prepare($query);
                $statement->bindValue(':PRODUCTID', $orderDetail->getProduct()->getProductID());
                $statement->bindValue(':QTYFILLED', $orderDetail->getQTYFilled());
                $statement->execute();
                $results = $statement->fetchAll();
                $statement->closeCursor();
            }
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();
            include '../view/errorPage.php';
            die;
        }
    }


    function fillOrderDetails($order){
        try {
            $db = getDBConnection();
            foreach($order->getOrderDetails() as $orderDetail){
                $query = "update orderdetails set QTYFILLED = :QTYFILLED where ORDERID = :ORDERID and PRODUCTID = :PRODUCTID";
                $statement = $db->prepare($query);
                $statement->bindValue(':ORDERID', $order->getOrderID());
                $statement->bindValue(':PRODUCTID', $orderDetail->getProduct()->getProductID());
                $statement->bindValue(':QTYFILLED', $orderDetail->getQTYFilled());
                $statement->execute();
                $results = $statement->fetchAll();
                $statement->closeCursor();
            }
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();
            include '../view/errorPage.php';
            die;
        }
    }

    function getAdminOrders(){
            try{
                $db = getDBConnection();
                $query = "SELECT orders.ORDERID, orders.*, orderdetails.QTYREQUESTED, orderdetails.QTYFILLED, productview.*, concat(users.FirstName, ' ', users.LastName) as USERSNAME FROM orders inner join orderdetails on orders.ORDERID = orderdetails.ORDERID
                                                                                                          inner join productview on orderdetails.PRODUCTID = productview.PRODUCTID
                                                                                                          inner join users on orders.USERID = users.UserID ORDER BY orders.DATEORDERED DESC";
                $statement = $db->prepare($query);
                $statement->execute();
                $result = $statement->fetchAll( PDO::FETCH_GROUP| PDO::FETCH_ASSOC);
                $statement->closeCursor();
                $AllOrders = array();
                foreach($result as $order) {
                    $orderDetails = array();
                    foreach($order as $orderItem){
                        array_push($orderDetails, new orderDetail($orderItem['ORDERID'],
                            new product($orderItem['PRODUCTID'],$orderItem['NAME'],$orderItem['PRODUCTDESCRIPTION'],$orderItem['QTYONHAND'],
                                $orderItem['MAXORDERQTY'],$orderItem['ORDERLIMIT'],$orderItem['GOALSTOCK'],$orderItem['QTYONORDER'],$orderItem['QTYAVAILABLE']),
                            $orderItem['QTYREQUESTED'],$orderItem['QTYFILLED']));

                    }
                    array_push($AllOrders, new order($order[0]['ORDERID'],$order[0]['USERID'],$order[0]['STATUS'],$order[0]['DATEORDERED'],$order[0]['DATEFILLED'],$order[0]['DATECOMPLETED'],$order[0]['COMMENT'],$orderDetails, $order[0]['USERSNAME']));
                }
                return $AllOrders;
            }
            catch (Exception $ex)
            {
                $errorMessage = $ex->getMessage();
                include '../view/errorPage.php';
                die;
            }
    }

    function getAllCategories() {
            try {
                $db = getDBConnection();
                $query = "select * from category WHERE category.CATEGORYACTIVE = :CATEGORYACTIVE order by CATEGORYDESCRIPTION";
                $statement = $db->prepare($query);
                $statement->bindValue(':CATEGORYACTIVE', 'Y');
                $statement->execute();
                $results = $statement->fetchAll();
                $statement->closeCursor();
                $categories = array();
                foreach($results as $CategoryRow)
                {
                    array_push($categories,new category($CategoryRow['CATEGORYID'],$CategoryRow['CATEGORYDESCRIPTION']));
                }
                return $categories;           // Assoc Array of Rows
            } catch (PDOException $e) {
            } catch (PDOException $e) {
                $errorMessage = $e->getMessage();
                include '../view/errorPage.php';
                die;
            }
    }

    function getAllSettingsInfo()
    {
        $db = getDBConnection();
        $query = "SELECT * from setting";
        $statement = $db->prepare($query);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    }

    function getCart($USERID)
    {
        try{
            $db = getDBConnection();
            $query = "select *
                      from productview
                      inner join cart on productview.PRODUCTID = cart.PRODUCTID
                      where cart.USERID = :USERID";
            $statement = $db->prepare($query);
            $statement->bindValue(":USERID", $USERID);
            $statement->execute();
            $result = $statement->fetchAll();
            $statement->closeCursor();
            $products = array();
            foreach($result as $CartRow)
            {
                array_push($products,new cartItem(new product($CartRow['PRODUCTID'],$CartRow['NAME'],$CartRow['PRODUCTDESCRIPTION'],$CartRow['QTYONHAND'],
                    $CartRow['MAXORDERQTY'],$CartRow['ORDERLIMIT'],$CartRow['GOALSTOCK'],$CartRow['QTYONORDER'],$CartRow['QTYAVAILABLE']),$CartRow['QTYREQUESTED']));
            }
            return new cart($USERID, $products);
        }
        catch (Exception $ex)
        {
            $errorMessage = $ex->getMessage();
            include '../view/errorPage.php';
            die;
        }
    }

    function getCategoryHeader($CategoryID)
    {

        $AllProductsCategoriesArray = [];
        $AllProductsCategories = '';
        $results = False;
        foreach($CategoryID as $SingleCategoryID)
        {
            $db = getDBConnection();
            $query = "SELECT CATEGORYDESCRIPTION from category where CATEGORYID = :CATEGORYID";
            $statement = $db->prepare($query);
            $statement->bindValue(':CATEGORYID', $SingleCategoryID);
            $statement->execute();
            $results = $statement->fetch();
            $statement->closeCursor();
            if ($results != False){
                array_push($AllProductsCategoriesArray, $results[0]);
            }
        }
        if($results != False){
            foreach($AllProductsCategoriesArray as $SingleCategory)
            {
                if(end($AllProductsCategoriesArray) == $SingleCategory)
                {
                    $AllProductsCategories = $AllProductsCategories  . $SingleCategory;
                }
                else
                {
                    $AllProductsCategories = $AllProductsCategories  . $SingleCategory . ',' . ' ';
                }
            }
            if(strlen($AllProductsCategories) >= 45)
            {
                return substr($AllProductsCategories, 0, 45)."...";
            }
            else
            {
                return $AllProductsCategories;
            }
        }
        else
        {

            return "All";
        }

    }

    function checkCategory(&$categoryArray){
        $arrayPos = 0;
        foreach($categoryArray as $category)
        {
            $info = getProducts([$category->getCategoryID()],'',$IncludeInactiveItems = false ,$HideUnstockedItems = false,$ShoppingList = false,'');
            $ProductArray = $info[0];
            if(count($ProductArray) == 0)
            {
                unset($categoryArray[$arrayPos]);
            }
            $arrayPos = $arrayPos + 1;
        }
        $categoryArray = array_values($categoryArray);
    }
    function getEmailNameToOrder($orderID)
    {
        $db = getDBConnection();
        $query = "select users.Email, concat(users.FirstName, ' ', users.LastName) as FullName
                 from users
                 inner join orders on users.UserID = orders.USERID
                 where orders.ORDERID = :ORDERID";
        $statement = $db->prepare($query);
        $statement->bindValue(':ORDERID', $orderID);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    }
    function getProducts($CategoryID,$QTYLessThan,$IncludeInactiveItems,$HideUnstockedItems,$ShoppingList,$SearchTerm){
        try{
            $queryText = "SELECT productview.PRODUCTID,productview.*,productcategories.CATEGORYID,category.CATEGORYDESCRIPTION FROM productview left join productcategories on productview.PRODUCTID = productcategories.PRODUCTID
                            left join category on productcategories.CATEGORYID = category.CATEGORYID";
            if($ShoppingList){
                $HideUnstockedItems = true;
                $IncludeInactiveItems = false;
            }
            if($HideUnstockedItems){
                $queryText .= " WHERE productview.GOALSTOCK > 0";
            }else{
                $queryText .= " WHERE productview.GOALSTOCK > -1";
            }
            if($IncludeInactiveItems){
                $queryText .= " and productview.QTYAVAILABLE > -1";
            }else{
                if($ShoppingList){
                    $queryText .= " and productview.QTYAVAILABLE < productview.GOALSTOCK";
                }else{
                    $queryText .= " and (productview.QTYAVAILABLE > 0 or productview.GOALSTOCK > 0)";

                }
            }
            if($QTYLessThan != ""){
                $queryText .= " and productview.QTYAVAILABLE < :QTYLessThan";
            }
            if($SearchTerm != ""){
                $queryText .=" and (productview.NAME LIKE :SearchTerm OR productview.PRODUCTDESCRIPTION LIKE :SearchTerm)";
            }
            $queryText .= " order by productview.NAME";
            $query = $queryText;
            $db = getDBConnection();
            $statement = $db->prepare($query);
            if($QTYLessThan != ""){
                $statement->bindValue(':QTYLessThan', $QTYLessThan);
            }
            if($SearchTerm != ""){
                $statement->bindValue(':SearchTerm', "%$SearchTerm%");
            }
            $statement->execute();
            $result = $statement->fetchAll( PDO::FETCH_GROUP | PDO::FETCH_ASSOC);
            $statement->closeCursor();
            $AllProducts = array();
            foreach($result as $products) {
                $categoryInfo = array();
                foreach($products as $product){
                    array_push($categoryInfo,new category($product["CATEGORYID"],$product["CATEGORYDESCRIPTION"]));
                }
                if($CategoryID != []) {
                    $isCategory = FALSE;
                    foreach ($categoryInfo as $category) {
                        if (in_array($category->getCategoryID(),$CategoryID)) {
                            $isCategory = True;
                        }
                    }
                    if ($isCategory) {
                        array_push($AllProducts, new product($products[0]['PRODUCTID'], $products[0]['NAME'], $products[0]['PRODUCTDESCRIPTION'], $products[0]['QTYONHAND'],
                            $products[0]['MAXORDERQTY'], $products[0]['ORDERLIMIT'], $products[0]['GOALSTOCK'], $products[0]['QTYONORDER'], $products[0]['QTYAVAILABLE'], $categoryInfo));
                    }
                } else{
                    array_push($AllProducts, new product($products[0]['PRODUCTID'], $products[0]['NAME'], $products[0]['PRODUCTDESCRIPTION'], $products[0]['QTYONHAND'],
                        $products[0]['MAXORDERQTY'], $products[0]['ORDERLIMIT'], $products[0]['GOALSTOCK'], $products[0]['QTYONORDER'], $products[0]['QTYAVAILABLE'], $categoryInfo));
                }
            }
            return [$AllProducts,$CategoryID,$QTYLessThan,$IncludeInactiveItems,$HideUnstockedItems,$ShoppingList,$SearchTerm];
        }
        catch (Exception $ex)
        {
            $errorMessage = $ex->getMessage();
            include '../view/errorPage.php';
            die;
        }
    }

    function getOrderIDsByUSERID($USERID){
        try{
            $db = getDBConnection();
            $query = "SELECT orders.ORDERID, orders.*, orderdetails.QTYREQUESTED, orderdetails.QTYFILLED, productview.*, concat(users.FirstName, ' ', users.LastName) as USERSNAME FROM orders inner join orderdetails on orders.ORDERID = orderdetails.ORDERID
                                                 inner join productview on orderdetails.PRODUCTID = productview.PRODUCTID
                                                 inner join users on orders.USERID = users.UserID WHERE orders.USERID = :USERID ORDER BY orders.DATEORDERED DESC";
            $statement = $db->prepare($query);
            $statement->bindValue(":USERID", $USERID);
            $statement->execute();
            $result = $statement->fetchAll( PDO::FETCH_GROUP| PDO::FETCH_ASSOC);
            $statement->closeCursor();
            $AllOrders = array();
            foreach($result as $order) {
                $orderDetails = array();
                foreach($order as $orderItem){
                    array_push($orderDetails, new orderDetail($orderItem['ORDERID'],
                        new product($orderItem['PRODUCTID'],$orderItem['NAME'],$orderItem['PRODUCTDESCRIPTION'],$orderItem['QTYONHAND'],
                            $orderItem['MAXORDERQTY'],$orderItem['ORDERLIMIT'],$orderItem['GOALSTOCK'],$orderItem['QTYONORDER'],$orderItem['QTYAVAILABLE']),
                        $orderItem['QTYREQUESTED'],$orderItem['QTYFILLED']));

                }
                array_push($AllOrders, new order($order[0]['ORDERID'],$order[0]['USERID'],$order[0]['STATUS'],$order[0]['DATEORDERED'],$order[0]['DATEFILLED'],$order[0]['DATECOMPLETED'],$order[0]['COMMENT'],$orderDetails, $order[0]['USERSNAME']));
            }
            return $AllOrders;

        }
        catch (Exception $ex)
        {
            $errorMessage = $ex->getMessage();
            include '../view/errorPage.php';
            die;
        }
    }

    function getOrder($OrderID){
        try{
            $db = getDBConnection();
            $query = "SELECT orders.ORDERID, orders.*, orderdetails.QTYREQUESTED, orderdetails.QTYFILLED, productview.*, concat(users.FirstName, ' ', users.LastName) as USERSNAME FROM orders inner join orderdetails on orders.ORDERID = orderdetails.ORDERID
                                                     inner join productview on orderdetails.PRODUCTID = productview.PRODUCTID
                                                     inner join users on orders.USERID = users.UserID WHERE orders.ORDERID = :ORDERID ORDER BY orders.DATEORDERED DESC";
            $statement = $db->prepare($query);
            $statement->bindValue(":ORDERID", $OrderID);
            $statement->execute();
            $result = $statement->fetchAll( PDO::FETCH_GROUP| PDO::FETCH_ASSOC);
            $statement->closeCursor();
            $AllOrders = array();
            foreach($result as $order) {
                $orderDetails = array();
                foreach($order as $orderItem){
                    array_push($orderDetails, new orderDetail($orderItem['ORDERID'],
                        new product($orderItem['PRODUCTID'],$orderItem['NAME'],$orderItem['PRODUCTDESCRIPTION'],$orderItem['QTYONHAND'],
                            $orderItem['MAXORDERQTY'],$orderItem['ORDERLIMIT'],$orderItem['GOALSTOCK'],$orderItem['QTYONORDER'],$orderItem['QTYAVAILABLE']),
                        $orderItem['QTYREQUESTED'],$orderItem['QTYFILLED']));

                }
                array_push($AllOrders, new order($order[0]['ORDERID'],$order[0]['USERID'],$order[0]['STATUS'],$order[0]['DATEORDERED'],$order[0]['DATEFILLED'],$order[0]['DATECOMPLETED'],$order[0]['COMMENT'],$orderDetails, $order[0]['USERSNAME']));
            }
            return $AllOrders;
        }
        catch (Exception $ex)
        {
            $errorMessage = $ex->getMessage();
            include '../view/errorPage.php';
            die;
        }
    }

    function getUserEmail($userID)
    {
        $db = getDBConnection();
        $query = "select Email
                    from users
                    where USERID = :USERID";
        $statement = $db->prepare($query);
        $statement->bindValue(':USERID', $userID);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    }

    function getUserID(){
        if(isset($_SESSION["UserID"]))
        {
            return $_SESSION["UserID"];
        }
    }

    function getUserInfo($UserID)
    {
        $db = getDBConnection();
        $query = "select UserID, FirstName, LastName, Email
                    from users
                    where UserID = :UserID";
        $statement = $db->prepare($query);
        $statement->bindValue(':UserID', $UserID);
        $statement->execute();
        $result = $statement->fetchAll();
        $statement->closeCursor();
        $user = new user($result[0]['UserID'], $result[0]['FirstName'],$result[0]['LastName'],$result[0]['Email']);
        return $user;
    }

    function getUserName($userID)
    {
        $db = getDBConnection();
        $query = "select  concat(FirstName, ' ', LastName) as Name
                    from users
                    where USERID = :USERID";
        $statement = $db->prepare($query);
        $statement->bindValue(':USERID', $userID);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    }

    function getReport($ReportType, $StartDate, $EndDate, $OnlyOrderedProducts, $CategoryList, $OrderStatuses)
    {
        if(isset($ReportType))
        {
            switch ($ReportType) {
                case 'Users':
                    $report = getUserReport($StartDate, $EndDate);
                    break;
                case 'Orders':
                    $report = getOrdersReport($StartDate, $EndDate,$OrderStatuses);
                    break;
                case 'OrderTotals':
                    $report = getOrderTotalsReport($StartDate, $EndDate);
                    break;
                case 'Products':
                    $report = getProductReport($StartDate, $EndDate, $OnlyOrderedProducts,$CategoryList);
                    break;
            }
        }
        else
        {
            $report = getUserReport($StartDate, $EndDate);
        }
        return $report;
    }

     function getUserReport($StartDate, $EndDate)
    {
        $db = getDBConnection();
        $query = "select users.UserID, users.FirstName, users.LastName, users.Email,
                    (Select COUNT(O.ORDERID) from orders O 
                    where O.USERID = users.UserID AND O.STATUS = :COMPLETED AND(O.DATECOMPLETED between :STARTDATE and :ENDDATE)) as 'COMPLETED ORDERS',
                    (Select COUNT(O1.ORDERID) from orders O1 
                    where O1.USERID = users.UserID AND O1.STATUS = :ADMINCANCELLED AND(O1.DATEORDERED between :STARTDATE and :ENDDATE)) as 'ADMIN CANCELLED ORDERS',
                    (Select COUNT(O2.ORDERID) from orders O2 
                    where O2.USERID = users.UserID AND O2.STATUS = :USERCANCELLED AND(O2.DATEORDERED between :STARTDATE and :ENDDATE)) as 'USER CANCELLED ORDERS'
                    from users
                    group by users.UserID";
        $statement = $db->prepare($query);
        $statement->bindValue(':STARTDATE', $StartDate);
        $statement->bindValue(':ENDDATE', $EndDate);
        $statement->bindValue(':COMPLETED', 'COMPLETED');
        $statement->bindValue(':ADMINCANCELLED', 'ADMINCANCELLED');
        $statement->bindValue(':USERCANCELLED', 'USERCANCELLED');
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    }

    function getOrdersReport($StartDate, $EndDate,$OrderStatuses)
    {
        $db = getDBConnection();
        $query = "select users.UserID, users.FirstName, users.Lastname, users.Email, orders.ORDERID, orders.STATUS, orders.DATEORDERED, orders.DATEFILLED, orders.DATECOMPLETED, orders.COMMENT, product.NAME, orderdetails.QTYREQUESTED,
                        orderdetails.QTYFILLED, product.PRODUCTDESCRIPTION, GROUP_CONCAT(category.CATEGORYDESCRIPTION ORDER BY category.CATEGORYDESCRIPTION ASC SEPARATOR ', ') as CATEGORY
                    from users
                    inner join orders on users.UserID = orders.USERID
                    inner join orderdetails on orders.ORDERID = orderdetails.ORDERID
                    inner join product on orderdetails.PRODUCTID = product.PRODUCTID    
                    inner join productcategories on product.PRODUCTID = productcategories.PRODUCTID
                    inner join category on productcategories.CATEGORYID = category.CATEGORYID";

        if(in_array('ALL', $OrderStatuses))
        {
            $query .= " where (orders.DATEORDERED between :STARTDATE and :ENDDATE)";
        }
        else if(in_array('SUBMITTED', $OrderStatuses))
        {
            $query .= " where (orders.DATEORDERED between :STARTDATE and :ENDDATE)";
        }
        else if(in_array('READYFORPICKUP', $OrderStatuses))
        {
            $query .= " where (orders.DATEFILLED between :STARTDATE and :ENDDATE)";
        }
        else if(in_array('COMPLETED', $OrderStatuses))
        {
            $query .= " where (orders.DATECOMPLETED between :STARTDATE and :ENDDATE)";
        }
        else
        {
            $query .= " where (orders.DATEORDERED between :STARTDATE and :ENDDATE)";
        }

        if(!in_array('ALL', $OrderStatuses))
        {
            $query .= " and (";
            foreach($OrderStatuses as $OrderStatus)
            {
                $query .= "orders.STATUS = :$OrderStatus OR ";
            }

            $query .= "false)";
        }
        $query .= " group by orderdetails.ORDERID,orderdetails.PRODUCTID";
        $statement = $db->prepare($query);
        if(!in_array('ALL', $OrderStatuses))
        {
            foreach($OrderStatuses as $OrderStatus)
            {
                console_log(":$OrderStatus");
                $statement->bindValue(":$OrderStatus", $OrderStatus);
            }
        }
        $statement->bindValue(':STARTDATE', $StartDate);
        $statement->bindValue(':ENDDATE', $EndDate);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    }

    function getOrderTotalsReport($StartDate, $EndDate)
    {
        $db = getDBConnection();
        $query = "select COUNT(DISTINCT(orders.ORDERID)) as 'TOTAL ORDERS', COUNT(DISTINCT(orders.USERID)) as 'UNIQUE USERS',
                    SUM(orderdetails.QTYFILLED) as 'TOTAL PRODUCTS', COUNT(DISTINCT(orderdetails.PRODUCTID)) as 'UNIQUE PRODUCTS'
                    from orders
                    inner join users on orders.UserID = users.USERID
                    inner join orderdetails on orders.ORDERID = orderdetails.ORDERID
                    inner join product on orderdetails.PRODUCTID = product.PRODUCTID    
                    where (orders.DATECOMPLETED between :STARTDATE and :ENDDATE) and orders.STATUS = :STATUS";
        $statement = $db->prepare($query);
        $statement->bindValue(':STARTDATE', $StartDate);
        $statement->bindValue(':ENDDATE', $EndDate);
        $statement->bindValue(':STATUS', 'COMPLETED');
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    }

    function getProductReport($StartDate, $EndDate, $OnlyOrderedProducts, $CategoryIDs)
    {
        $db = getDBConnection();
        $query = "select product.*,
                    (select GROUP_CONCAT(c.CATEGORYDESCRIPTION ORDER BY c.CATEGORYDESCRIPTION ASC SEPARATOR ', ') 
                    from category c inner join productcategories p on c.CATEGORYID = p.CATEGORYID where p.PRODUCTID = product.PRODUCTID) as CATEGORY,
                    (Select IFNULL(sum(qtyfilled),0) from orderdetails od where od.productid = product.productid) as 'TOTALORDERED',
                        COUNT(DISTINCT(orderdetails.ORDERID)) as 'NUMBER OF ORDERS', 
                        COUNT(DISTINCT orders.USERID) as 'UNIQUE USERS' 
                          
                    from product left join orderdetails on product.PRODUCTID = orderdetails.PRODUCTID
                    inner join productcategories on product.PRODUCTID = productcategories.PRODUCTID
                    inner join category on productcategories.CATEGORYID = category.CATEGORYID
                    left join orders on orderdetails.ORDERID = orders.ORDERID";

        if($OnlyOrderedProducts)
        {
            $query .= " where (orders.DATECOMPLETED between :STARTDATE and :ENDDATE)";
        }
        else
        {
            $query .= " where ((orders.DATECOMPLETED between :STARTDATE and :ENDDATE) or ISNULL(orders.DATECOMPLETED))";
        }
        if(!in_array(0, $CategoryIDs))
        {
            $query .= " and (";
            foreach($CategoryIDs as $categoryID)
            {
                $query .= "category.CATEGORYID = :$categoryID or ";
            }

            $query .= "false)";
        }

        $query .= " GROUP BY product.PRODUCTID
                    ORDER BY product.PRODUCTID ASC";
        $statement = $db->prepare($query);
        if(!in_array(0, $CategoryIDs))
        {
            foreach($CategoryIDs as $categoryID)
            {
                $statement->bindValue(":$categoryID", $categoryID);
            }
        }
        $statement->bindValue(':STARTDATE', $StartDate);
        $statement->bindValue(':ENDDATE', $EndDate);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;

    }


    function removeFromCart($PRODUCTID)
    {
        $USERID = getUserID();
        $db = getDBConnection();
        $query = 'DELETE FROM cart WHERE (USERID = :USERID) AND (PRODUCTID = :PRODUCTID)';
        $statement = $db->prepare($query);
        $statement->bindValue(':USERID', $USERID);
        $statement->bindValue(':PRODUCTID', $PRODUCTID);
        $success = $statement->execute();
        $statement->closeCursor();
        if($success)
        {

            return $statement->rowCount();
        }
        else
        {
            logSQLError($statement->errorInfo());
        }
    }

    function submitOrder($USERID,$CART,$COMMENT){
        $db = getDBConnection();
        $query = 'INSERT INTO orders (UserID, STATUS, DATEORDERED, COMMENT)
            VALUES (:USERID, :STATUS, :DATEORDERED,:COMMENT)';
        $statement = $db->prepare($query);
        $statement->bindValue(':USERID', $USERID);
        $statement->bindValue(':STATUS', 'SUBMITTED');
        $statement->bindValue(':DATEORDERED', date("Y-m-d"));
        $statement->bindValue(':COMMENT', $COMMENT);
        $success = $statement->execute();
        $statement->closeCursor();
        if($success)
        {
            $ORDERID = $db -> lastInsertId();
            foreach($CART->getProductsInCart() as $cartItem){
                $query = 'INSERT INTO orderdetails (ORDERID, PRODUCTID, QTYREQUESTED, QTYFILLED)
                VALUES (:ORDERID, :PRODUCTID, :QTYREQUESTED,:QTYFILLED)';
                $statement = $db->prepare($query);
                $statement->bindValue(':ORDERID', $ORDERID);
                $statement->bindValue(':PRODUCTID',$cartItem->getProductObject()->getProductID());
                $statement->bindValue(':QTYREQUESTED', $cartItem->getQTYRequested());
                $statement->bindValue(':QTYFILLED', '0');
                $success = $statement->execute();
                $statement->closeCursor();
            }
            if($success){
                clearCart($USERID);
                return $ORDERID;
            }
            else {
                logSQLError($statement->errorInfo());
            }

        }
        else
        {
            logSQLError($statement->errorInfo());
        }
    }

    function deleteOrder($OrderID){
        $AllOrders = getOrder($OrderID);
        $UserID = getUserID();
        $order = $AllOrders[0];
        $orderOwner = $order->getUserID();
        if(userIsAuthorized("adminChangeOrderStatus") or ($UserID == $orderOwner))
        {
            if($order->getOrderStatus() == 'SUBMITTED')
            {
                $db = getDBConnection();
                $query = 'UPDATE orders SET STATUS = :STATUS WHERE ORDERID = :ORDERID';
                $statement = $db->prepare($query);
                if($UserID == $orderOwner)
                {
                    $statement->bindValue(':STATUS', 'USERCANCELLED');
                }
                else
                {
                    $statement->bindValue(':STATUS', 'ADMINCANCELLED');
                }
                $statement->bindValue(':ORDERID', $order->getOrderID());
                $success = $statement->execute();
                $statement->closeCursor();
                if($success)
                {
                    return True;
                }
                else
                {
                    logSQLError($statement->errorInfo());
                }


            }
            else if($order->getOrderStatus() == 'READY FOR PICKUP')
            {
                foreach ($order->getOrderDetails() as $orderDetail)
                {
                    $rowCount = updateQTY($orderDetail->getProductID(),$orderDetail->getQTYFilled());
                }
                $db = getDBConnection();
                $query = 'UPDATE orderdetails SET QTYFILLED = :ZERO WHERE (ORDERID = :ORDERID)';
                $statement = $db->prepare($query);
                $statement->bindValue(':ORDERID', $order->getOrderID());
                $statement->bindValue(':ZERO', 0);
                $success = $statement->execute();
                $statement->closeCursor();
                if($success)
                {
                    $db = getDBConnection();
                    $query = 'UPDATE orders SET STATUS = :STATUS WHERE ORDERID = :ORDERID';
                    $statement = $db->prepare($query);
                    if($UserID == $orderOwner)
                    {
                        $statement->bindValue(':STATUS', 'USERCANCELLED');
                    }
                    else
                    {
                        $statement->bindValue(':STATUS', 'ADMINCANCELLED');
                    }
                    $statement->bindValue(':ORDERID', $order->getOrderID());
                    $success = $statement->execute();
                    $statement->closeCursor();
                    if($success)
                    {
                        return True;
                    }
                    else
                    {
                        logSQLError($statement->errorInfo());
                    }
                }
                else
                {
                    logSQLError($statement->errorInfo());
                }
            }
        }
        else
        {
            return False;
        }
    }

    function sendEmail($to, $cc, $bcc, $subject, $message)
    {
        require_once 'Mail.php';
        $options = array();
        $options['host'] = 'serversmtp.clarion.edu';
        $options['port'] = '2500';
        $options['auth'] = false;
        $Mailer = Mail::factory('smtp', $options);
        $recipients = $to.", ".$cc.", ".$bcc;
        $headers = array();
        $headers['Cc'] = $cc;
        $headers['Bcc'] = $bcc;
        $headers['Subject'] = $subject;
        $headers['From'] = 'resourceroom@clarion.edu';
        $headers['To'] = $to;
        $headers['Content-type'] = 'text/html';
        $htmlContent = $message;
        $result = $Mailer->send($recipients, $headers, $htmlContent);
        if(PEAR::isError($result))
        {
            echo 'Error sending email ' . $result;
        }
        else
        {
            //echo 'Email sent successfully';
        }
    }

    function setMessage($fillerComments, $TextType, $tableBody, $EmailType)
    {
        if($EmailType == 'placed')
        {
//             console_log("in placed order if");
            $message = $TextType . PHP_EOL . PHP_EOL . "
                                                                                                                    <html>
                                                                                                                    <head>
                                                                                                                    <title>HTML email</title>
                                                                                                                    </head>
                                                                                                                    <body>
                                                                                                                    <table>
                                                                                                                    <thead>
                                                                                                                        <th>Product Name</th>
                                                                                                                        <th>Quantity Requested</th>
                                                                                                                    </thead>
                                                                                                                    <tbody>" .
                                                                                                                        $tableBody .
                                                                                                                    "</tbody>
                                                                                                                     </table>
                                                                                                                     </body>
                                                                                                                     </html>";
        }
        else if($EmailType == 'filled')
        {
            console_log("in filled order if");
            $message = $fillerComments . PHP_EOL . PHP_EOL . $TextType . PHP_EOL . PHP_EOL . "
                                                                                                                    <html>
                                                                                                                    <head>
                                                                                                                    <title>HTML email</title>
                                                                                                                    </head>
                                                                                                                    <body>
                                                                                                                    <table>
                                                                                                                    <thead>
                                                                                                                        <th>Product Name</th>
                                                                                                                        <th>Quantity Requested</th>
                                                                                                                        <th>Quantity Filled</th>
                                                                                                                    </thead>
                                                                                                                    <tbody>" .
                                                                                                                        $tableBody .
                                                                                                                    "</tbody>
                                                                                                                     </table>
                                                                                                                     </body>
                                                                                                                     </html>";
        }
        else if($EmailType == 'renotify')
        {
//             console_log("In renotify order if");
            $message = $TextType . PHP_EOL . PHP_EOL . "
                                                                                <html>
                                                                                <head>
                                                                                <title>HTML email</title>
                                                                                </head>
                                                                                <body>
                                                                                <table>
                                                                                <thead>
                                                                                    <th>Product Name</th>
                                                                                    <th>Quantity Requested</th>
                                                                                    <th>Quantity Filled</th>
                                                                                </thead>
                                                                                <tbody>" .
                                                                                    $tableBody .
                                                                                "</tbody>
                                                                                 </table>
                                                                                 </body>
                                                                                 </html>";
    }
        else if($EmailType == 'cancelled')
        {
//             console_log('in cancelled order if');
            $message = $TextType . PHP_EOL . PHP_EOL . "
                                                                                <html>
                                                                                <head>
                                                                                <title>HTML email</title>
                                                                                </head>
                                                                                <body>
                                                                                <table>
                                                                                <thead>
                                                                                    <th>Product Name</th>
                                                                                    <th>Quantity Requested</th>
                                                                                    <th>Quantity Filled</th>
                                                                                </thead>
                                                                                <tbody>" .
                                                                                    $tableBody .
                                                                                "</tbody>
                                                                                 </table>
                                                                                 </body>
                                                                                 </html>";
        }
        else
        {
//             console_log('this is broken');
        }

    }

    function updateCategory($CategoryID, $CategoryName)
    {
       $db = getDBConnection();
       $query = 'UPDATE category SET CATEGORYDESCRIPTION = :CATEGORYDESCRIPTION WHERE CATEGORYID = :CATEGORYID';
       $statement = $db->prepare($query);
       $statement->bindValue(':CATEGORYID', $CategoryID);
       $statement->bindValue(':CATEGORYDESCRIPTION', $CategoryName);
       $success = $statement->execute();
       $statement->closeCursor();
       if($success)
       {
           return $statement->rowCount();
       }
       else
       {
           logSQLError($statement->errorInfo());
       }
    }

    function updateProduct($ProductID, $ProductName, $QtyOnHand, $MaxOrderQty, $GoalStock, $ProductDescription, $ProductCategories)
    {
       $db = getDBConnection();
       $query = 'UPDATE product SET NAME = :NAME, QTYONHAND = :QTYONHAND, MAXORDERQTY = :MAXORDERQTY, GOALSTOCK = :GOALSTOCK, PRODUCTDESCRIPTION = :PRODUCTDESCRIPTION WHERE PRODUCTID = :PRODUCTID';
       $statement = $db->prepare($query);
       $statement->bindValue(':PRODUCTID', $ProductID);
       $statement->bindValue(':NAME', $ProductName);
       $statement->bindValue(':QTYONHAND', $QtyOnHand);
       $statement->bindValue(':MAXORDERQTY', $MaxOrderQty);
       $statement->bindValue(':GOALSTOCK', $GoalStock);
       $statement->bindValue(':PRODUCTDESCRIPTION', $ProductDescription);
       $success = $statement->execute();
       $statement->closeCursor();
       if($success)
       {
           addProductCategories($ProductCategories, $ProductID);
           return $statement->rowCount();
       }
       else
       {
           logSQLError($statement->errorInfo());
       }
    }

    function updateQTY($ProductID, $IncomingAmt)
   {
       $db = getDBConnection();
       if(substr($IncomingAmt, -strlen($IncomingAmt), 1) == '-')
       {
            $Operation = '-';
            $IncomingAmtSep = explode('-', $IncomingAmt);
            $IncomingAmt = $IncomingAmtSep[1];
            $query = "update product set QTYONHAND = QTYONHAND - :QTYONHAND where PRODUCTID = :PRODUCTID";
       }
       else
       {
            $Operation = '+';
            $query = "update product set QTYONHAND = QTYONHAND + :QTYONHAND where PRODUCTID = :PRODUCTID";
       }
       $statement = $db->prepare($query);
       $statement->bindValue(':PRODUCTID', $ProductID);
       $statement->bindValue(':QTYONHAND', $IncomingAmt);
       $success = $statement->execute();
       $statement->closeCursor();
       if($success)
       {


           return $statement->rowCount();
       }
       else
       {
           logSQLError($statement->errorInfo());
       }
   }

   function UpdateSettings($PlacedCC, $FilledCC, $ReNotifyCC, $CancelledCC, $PlacedBCC, $FilledBCC, $ReNotifyBCC, $CancelledBCC, $PlacedSubject, $FilledSubject, $ReNotifySubject, $CancelledSubject, $PlacedText, $FilledText, $ReNotifyText, $CancelledText, $FooterLeftAnnouncement, $FooterRightAnnouncement)
   {
       $db = getDBConnection();
       $query = 'UPDATE setting SET EmailOrderReceived = :EmailOrderReceived, EmailOrderFilled = :EmailOrderFilled, EmailOrderReminder = :EmailOrderReminder, EmailOrderCancelled = :EmailOrderCancelled,
                                    BCCOrderReceived = :BCCOrderReceived, BCCOrderFilled = :BCCOrderFilled, BCCOrderReminder = :BCCOrderReminder, BCCOrderCanceled = :BCCOrderCanceled,  OrderReceivedSubj = :OrderReceivedSubj, OrderFilledSubj = :OrderFilledSubj, OrderReminderSubj = :OrderReminderSubj, OrderCancelledSubj = :OrderCancelledSubj,
                                     OrderReceivedText = :OrderReceivedText, OrderFilledText = :OrderFilledText, OrderReminderText = :OrderReminderText, OrderCancelledText = :OrderCancelledText, FooterTextLeft = :FooterTextLeft, FooterTextRight = :FooterTextRight WHERE SETTINGID = 1';
       $statement = $db->prepare($query);
       $statement->bindValue(':EmailOrderReceived', $PlacedCC);
       $statement->bindValue(':EmailOrderFilled', $FilledCC);
       $statement->bindValue(':EmailOrderReminder', $ReNotifyCC);
       $statement->bindValue(':EmailOrderCancelled', $CancelledCC);
       $statement->bindValue(':BCCOrderReceived', $PlacedBCC);
       $statement->bindValue(':BCCOrderFilled', $FilledBCC);
       $statement->bindValue(':BCCOrderReminder', $ReNotifyBCC);
       $statement->bindValue(':BCCOrderCanceled', $CancelledBCC);
       $statement->bindValue(':OrderReceivedSubj', $PlacedSubject);
       $statement->bindValue(':OrderFilledSubj', $FilledSubject);
       $statement->bindValue(':OrderReminderSubj', $ReNotifySubject);
       $statement->bindValue(':OrderCancelledSubj', $CancelledSubject);
       $statement->bindValue(':OrderReceivedText', $PlacedText);
       $statement->bindValue(':OrderFilledText', $FilledText);
       $statement->bindValue(':OrderReminderText', $ReNotifyText);
       $statement->bindValue(':OrderCancelledText', $CancelledText);
       $statement->bindValue(':FooterTextLeft', $FooterLeftAnnouncement);
       $statement->bindValue(':FooterTextRight', $FooterRightAnnouncement);
       $success = $statement->execute();
       $statement->closeCursor();
       if($success)
       {
           return $statement->rowCount();
       }
       else
       {
           logSQLError($statement->errorInfo());
       }
   }

    function console_log( $data ){
      echo '<script>';
      echo 'console.log('. json_encode( $data ) .')';
      echo '</script>';
    }
?>