<?php
    include_once 'product.php';
    include_once 'cart.php';
    include_once 'category.php';
    include_once 'order.php';

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

    function getAllCategories() {
        try {
            $db = getDBConnection();
            $query = "select * from category order by DESCRIPTION";
            $statement = $db->prepare($query);
            $statement->execute();
            $results = $statement->fetchAll();
            $statement->closeCursor();
            $categories = array();
            foreach($results as $CategoryRow)
            {
                array_push($categories,new category($CategoryRow['CATEGORYID'],$CategoryRow['DESCRIPTION']));
            }
            return $categories;           // Assoc Array of Rows
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();
            include '../view/errorPage.php';
            die;
        }
    }

        function getAllProducts() {
            try {
                $db = getDBConnection();
                $query = "select * from productview where QTYONHAND > 0 order by NAME";
                $statement = $db->prepare($query);
                $statement->execute();
                $results = $statement->fetchAll();
                $statement->closeCursor();
                $products = array();
                foreach($results as $ProductRow)
                {
                    array_push($products,new product($ProductRow['PRODUCTID'],$ProductRow['NAME'],$ProductRow['DESCRIPTION'],$ProductRow['QTYONHAND'],
                        $ProductRow['MAXORDERQTY'],$ProductRow['ORDERLIMIT'],$ProductRow['GOALSTOCK'],$ProductRow['QTYONORDER'],$ProductRow['QTYAVAILABLE']));
                }
                return $products;
            } catch (PDOException $e) {
                $errorMessage = $e->getMessage();
                include '../view/errorPage.php';
                die;
            }
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
                    array_push($products,new cartItem(new product($CartRow['PRODUCTID'],$CartRow['NAME'],$CartRow['DESCRIPTION'],$CartRow['QTYONHAND'],
                        $CartRow['MAXORDERQTY'],$CartRow['ORDERLIMIT'],$CartRow['GOALSTOCK'],$CartRow['QTYONORDER'],$CartRow['QTYAVAILABLE']),$CartRow['QTYREQUESTED'],$CartRow['MOSTRECENTDATE']));
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
        function getOrderIDsByUSERID($USERID){
            try{
                $db = getDBConnection();
                $query = "SELECT * FROM orders where orders.USERID = :USERID";
                $statement = $db->prepare($query);
                $statement->bindValue(":USERID", $USERID);
                $statement->execute();
                $result = $statement->fetchAll();
                $statement->closeCursor();
                $orderIDs = array();
                foreach($result as $order)
                {
                    array_push($orderIDs,$order['ORDERID']);
                }
                return $orderIDs;
            }
            catch (Exception $ex)
            {
                $errorMessage = $ex->getMessage();
                include '../view/errorPage.php';
                die;
            }
        }


        function getOrdersByUserIDOrderID($USERID,$ORDERID)
        {
            try{
                $db = getDBConnection();
                $query = "SELECT * FROM orders inner join orderdetails on orders.ORDERID = orderdetails.ORDERID 
                            inner join productview on orderdetails.PRODUCTID = productview.PRODUCTID where orders.USERID = :USERID and orders.ORDERID =:ORDERID ORDER BY orders.DATEORDERED ASC";
                $statement = $db->prepare($query);
                $statement->bindValue(":USERID", $USERID);
                $statement->bindValue(":ORDERID", $ORDERID);
                $statement->execute();
                $result = $statement->fetchAll();
                $statement->closeCursor();
                $orderDetails = array();
                foreach($result as $orderRow)
                {
                    array_push($orderDetails,new orderDetail($orderRow['ORDERID'],new product($orderRow['PRODUCTID'],$orderRow['NAME'],$orderRow['DESCRIPTION'],$orderRow['QTYONHAND'],
                        $orderRow['MAXORDERQTY'],$orderRow['ORDERLIMIT'],$orderRow['GOALSTOCK'],$orderRow['QTYONORDER'],$orderRow['QTYAVAILABLE']),$orderRow['QTYREQUESTED'],$orderRow['QTYFILLED']));
                }
                return new order($ORDERID, $USERID, $result[0]['STATUS'],$result[0]['DATEORDERED'],$result[0]['DATEFILLED'],$result[0]['DATECOMPLETED'],$result[0]['COMMENT'],$orderDetails);
            }
            catch (Exception $ex)
            {
                $errorMessage = $ex->getMessage();
                include '../view/errorPage.php';
                die;
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

        function getFilteredProducts($QtyLessThan, $QtyLessThanStatus, $InactiveItems, $StockedItems) {
            try {
                console_log($QtyLessThanStatus);
                $db = getDBConnection();
                if($QtyLessThanStatus == true && $InactiveItems == false && $StockedItems == false)
                {
                    $query = "select *
                               from productview
                               where QTYONHAND > 0 and QTYONHAND < :QTYONHAND
                               order by NAME";
                }
                else if($InactiveItems == true && $QtyLessThanStatus == false && $StockedItems == false)
                {
                    $query = "select *
                               from productview
                               where QTYONHAND > 0
                               union
                               select *
                               from productview
                               where GOALSTOCK = 0 and QTYONHAND = 0
                               order by NAME";
                }
                else if($StockedItems == true && $QtyLessThanStatus == false && $InactiveItems == false)
                {
                    console_log('im in the else if 1');
                    $query = "select *
                               from productview
                               where GOALSTOCK > 0
                               order by NAME";
                }
                else if($InactiveItems == true && $StockedItems == true && $QtyLessThanStatus == false)
                {
                    console_log('im in the else if 2');
                    $query = "select *
                               from productview
                               where GOALSTOCK > 0
                               union
                               select *
                               from productview
                               where GOALSTOCK = 0 and QTYONHAND = 0
                               order by NAME";
                }
                else if ($InactiveItems == true && $QtyLessThanStatus == true && $StockedItems == false)
                {
                    console_log('im in the else if 3');
                    $query = "select *
                               from productview
                               where QTYONHAND > 0 and QTYONHAND < :QTYONHAND
                               union
                               select *
                               from productview
                               where GOALSTOCK = 0 and QTYONHAND = 0
                               order by NAME";
                }
                else if ($StockedItems == true && $QtyLessThanStatus == true && $InactiveItems == false)
                {
                    console_log('im in the else if 4');
                    $query = "select *
                                from productview
                                where QTYONHAND > 0 and QTYONHAND < :QTYONHAND and GOALSTOCK > 0
                                order by NAME";
                }
                else
                {
                    $query = "select *
                               from productview
                               where QTYONHAND > 0
                               order by NAME";
                }

                $statement = $db->prepare($query);
                $statement->bindValue(":QTYONHAND", $QtyLessThan);
                $statement->execute();
                $results = $statement->fetchAll();
                $statement->closeCursor();
                $products = array();
                foreach($results as $ProductRow)
                {
                    array_push($products,new product($ProductRow['PRODUCTID'],$ProductRow['NAME'],$ProductRow['DESCRIPTION'],$ProductRow['QTYONHAND'],
                        $ProductRow['MAXORDERQTY'],$ProductRow['ORDERLIMIT'],$ProductRow['GOALSTOCK'],$ProductRow['QTYONORDER'],$ProductRow['QTYAVAILABLE']));
                }
                return $products;
            } catch (PDOException $e) {
                $errorMessage = $e->getMessage();
                include '../view/errorPage.php';
                die;
            }
        }

        function getCategory($CATEGORYID)
        {
            try{
                $db = getDBConnection();
                $query = "select *
                          from productview
                          inner join productcategories on productview.PRODUCTID = productcategories.PRODUCTID
                          where QTYONHAND > 0 and productcategories.CATEGORYID = :CATEGORYID ";
                $statement = $db->prepare($query);
                $statement->bindValue(":CATEGORYID", $CATEGORYID);
                $statement->execute();
                $result = $statement->fetchAll();
                $statement->closeCursor();
                $products = array();
                foreach($result as $ProductRow)
                {
                    array_push($products,new product($ProductRow['PRODUCTID'],$ProductRow['NAME'],$ProductRow['DESCRIPTION'],$ProductRow['QTYONHAND'],
                        $ProductRow['MAXORDERQTY'],$ProductRow['ORDERLIMIT'],$ProductRow['GOALSTOCK'],$ProductRow['QTYONORDER'],$ProductRow['QTYAVAILABLE']));
                }
                return $products;
            }
            catch (Exception $ex)
            {
                $errorMessage = $ex->getMessage();
                include '../view/errorPage.php';
                die;
            }
        }

        function getFilteredCategory($CATEGORYID, $QTYONHAND)
                {
                    try{
                        $db = getDBConnection();
                        $query = "select *
                                  from productview
                                  inner join productcategories on productview.PRODUCTID = productcategories.PRODUCTID
                                  where productcategories.CATEGORYID = :CATEGORYID AND QTYONHAND < :QTYONHAND";
                        $statement = $db->prepare($query);
                        $statement->bindValue(":CATEGORYID", $CATEGORYID);
                        $statement->bindValue(":QTYONHAND", $QTYONHAND);
                        $statement->execute();
                        $results = $statement->fetchAll();
                        $statement->closeCursor();
                        $products = array();
                        foreach($results as $ProductRow)
                        {
                            array_push($products,new product($ProductRow['PRODUCTID'],$ProductRow['NAME'],$ProductRow['DESCRIPTION'],$ProductRow['QTYONHAND'],
                                $ProductRow['MAXORDERQTY'],$ProductRow['ORDERLIMIT'],$ProductRow['GOALSTOCK'],$ProductRow['QTYONORDER'],$ProductRow['QTYAVAILABLE']));
                        }
                        return $products;
                    }
                    catch (Exception $ex)
                    {
                        $errorMessage = $ex->getMessage();
                        include '../view/errorPage.php';
                        die;
                    }
                }

        function getFilterResults($QtyLessThan) {
            try{
                $db = getDBConnection();
                $query = "select *
                          from productview
                          where QTYONHAND < :QtyLessThan";
                $statement = $db->prepare($query);
                $statement->bindValue(":QtyLessThan", $QtyLessThan);
                $statement->execute();
                $results = $statement->fetchAll();
                $statement->closeCursor();
                $products = array();
                foreach($results as $ProductRow)
                {
                    array_push($products,new product($ProductRow['PRODUCTID'],$ProductRow['NAME'],$ProductRow['DESCRIPTION'],$ProductRow['QTYONHAND'],
                        $ProductRow['MAXORDERQTY'],$ProductRow['ORDERLIMIT'],$ProductRow['GOALSTOCK'],$ProductRow['QTYONORDER'],$ProductRow['QTYAVAILABLE']));
                }
                return $products;
            }
            catch (Exception $ex)
            {
                $errorMessage = $ex->getMessage();
                include '../view/errorPage.php';
                die;
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
                    return $statement->rowCount();
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


        function clearCart($USERID){
            $db = getDBConnection();
            $query = 'DELETE FROM cart WHERE (USERID = :USERID)';
            $statement = $db->prepare($query);
            $statement->bindValue(':USERID', $USERID);
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
        function updateQTY($PRODUCTID, $QTYONHAND, $INCOMINGAMT)
       {
           $chars = preg_split('//', $INCOMINGAMT, -1, PREG_SPLIT_NO_EMPTY);
           if($chars[0] === '-')
           {
                $QTYONHAND = $QTYONHAND - $chars[1];
           }
           else
           {
                $QTYONHAND = $QTYONHAND + $INCOMINGAMT;
           }
           $db = getDBConnection();
           $query = "update productview set QTYONHAND = :QTYONHAND where PRODUCTID = :PRODUCTID";
           $statement = $db->prepare($query);
           $statement->bindValue(':PRODUCTID', $PRODUCTID);
           $statement->bindValue(':QTYONHAND', $QTYONHAND);
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

        function addProduct($ProductName, $QtyOnHand, $MaxOrderQty, $GoalStock, $ProductDescription)
        {
           $db = getDBConnection();
           $query = 'INSERT INTO product (NAME, QTYONHAND, MAXORDERQTY, GOALSTOCK, DESCRIPTION)
                                            VALUES (:NAME, :QTYONHAND, :MAXORDERQTY, :GOALSTOCK, :DESCRIPTION)';
           $statement = $db->prepare($query);
           $statement->bindValue(':NAME', $ProductName);
           $statement->bindValue(':QTYONHAND', $QtyOnHand);
           $statement->bindValue(':MAXORDERQTY', $MaxOrderQty);
           $statement->bindValue(':GOALSTOCK', $GoalStock);
           $statement->bindValue(':DESCRIPTION', $ProductDescription);
           $success = $statement->execute();
           $statement->closeCursor();

           if($success)
           {
               //savePriceImageFile($db->lastInsertId());
               return $db->lastInsertId();
           }
           else
           {
               logSQLError($statement->errorInfo());
           }
        }

        function updateProduct($ProductID, $ProductName, $QtyOnHand, $MaxOrderQty, $GoalStock, $ProductDescription)
        {
            $db = getDBConnection();
           $query = 'UPDATE product SET NAME = :NAME, QTYONHAND = :QTYONHAND, MAXORDERQTY = :MAXORDERQTY, GOALSTOCK = :GOALSTOCK, DESCRIPTION = :DESCRIPTION WHERE PRODUCTID = :PRODUCTID';
           $statement = $db->prepare($query);
           $statement->bindValue(':PRODUCTID', $ProductID);
           $statement->bindValue(':NAME', $ProductName);
           $statement->bindValue(':QTYONHAND', $QtyOnHand);
           $statement->bindValue(':MAXORDERQTY', $MaxOrderQty);
           $statement->bindValue(':GOALSTOCK', $GoalStock);
           $statement->bindValue(':DESCRIPTION', $ProductDescription);
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

        function getUserID(){
            return $_SESSION["UserID"];
}

        function getByGeneralSearch($criteria) {
                try {
                    $db = getDBConnection();
                    $query = 'SELECT *
                                    FROM productview
                                    WHERE NAME LIKE :criteria OR
                                    DESCRIPTION LIKE :criteria
                                    ORDER BY NAME';
                    $statement = $db->prepare($query);
                    $statement->bindValue(':criteria', "%$criteria%");
                    $statement->execute();
                    $results = $statement->fetchAll();
                    $statement->closeCursor();
                    $products = array();
                    foreach($results as $ProductRow)
                    {
                        array_push($products,new product($ProductRow['PRODUCTID'],$ProductRow['NAME'],$ProductRow['DESCRIPTION'],$ProductRow['QTYONHAND'],
                            $ProductRow['MAXORDERQTY'],$ProductRow['ORDERLIMIT'],$ProductRow['GOALSTOCK'],$ProductRow['ONORDER'],$ProductRow['QTYAVAILABLE']));
                    }
                    return $products;
                } catch (PDOException $e) {
                    $errorMessage = $e->getMessage();
                    include '../view/errorPage.php';
                    die;
                }
            }

?>