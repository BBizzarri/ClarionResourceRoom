<?php

    // This file is included in the main controller as a series of cases to check the $action querystring parameter.
    // The purpose is to separate the shopper actions from the back-end inventory actions to help version control.
    switch ($action) {
        case 'shopperAdjustQTYInCart':
            processAdjustQTYInCart();
            break;
        case 'shopperCart':
            displayCart();
            break;
        case 'shopperHome':
            displayProducts();
            break;
        case 'shopperOrders':
            displayShopperOrders();
            break;
        case 'processAddToCart':
            processAddToCart();
            break;
        case 'shopperRemoveFromCart':
            processRemoveFromCart();
            break;
        case 'shopperSubmitOrder':
            processSubmitOrder();
            break;
    }
    function displayShopperOrders(){
        $SettingsInfo = getAllSettingsInfo();
        $USERID = getUserID();
        $orders = getOrderIDsByUSERID($USERID);
        console_log(getOrderIDsByUSERID($USERID));
        $UsersEmail = getUserEmail($USERID);
        include '../view/shopperOrders.php';
    }

    function displayProducts()
    {
        $SettingsInfo = getAllSettingsInfo();
        $listType = filter_input(INPUT_GET, 'ListType');
        $CategoryArray = getAllCategories();

        if($listType =='GeneralSearch' && isset($_POST['searchCriteria'])){
            $info = getProducts([],'',$IncludeInactiveItems = false,$HideUnstockedItems = false,$ShoppingList = false,htmlspecialchars($_POST['searchCriteria']));
            $ProductArray = $info[0];
            $CurrentCategory = "Related To: " . htmlspecialchars($_POST['searchCriteria']);
        }else if (isset($_GET['CATEGORYID'])) {
            $shopperCategoryID = $_GET['CATEGORYID'];
            $CurrentCategory = getCategoryHeader([$shopperCategoryID]);
            $info = getProducts([$shopperCategoryID],'',$IncludeInactiveItems = false ,$HideUnstockedItems = false,$ShoppingList = false,'');
            $ProductArray = $info[0];
        }else{
            $shopperCategoryID = $CategoryArray[0]->getCategoryID();
            $CurrentCategory = getCategoryHeader([$shopperCategoryID]);
            $info = getProducts([$shopperCategoryID],'',$IncludeInactiveItems = false, $HideUnstockedItems = false,$ShoppingList = false,'');
            $ProductArray = $info[0];
        }
            if ($ProductArray == false)
            {
                $CategoryHeader = 'No items relevant to: ' . htmlspecialchars($_POST['searchCriteria']);
            }
            else
            {
                $CategoryHeader = $CurrentCategory;
            }
        $USERID = getUserID();
        $cart = getCart($USERID);
        $_SESSION['itemsInCart'] = $cart->getNumberOfItemsInCart();
        include '../view/index.php';
    }
    function displayCart()
    {
        $SettingsInfo = getAllSettingsInfo();
        $USERID = getUserID();
        $cart = getCart($USERID);
        $_SESSION['itemsInCart'] = $cart->getNumberOfItemsInCart();
        include '../view/shopperCart.php';
    }

    function processAddToCart()
    {
        $SettingsInfo = getAllSettingsInfo();
        $PRODUCTID = $_GET['ProductID'];
        $QTYREQUESTED = $_POST['QTYRequested'];
        //Validations
        $errors = "";
        if($errors != "")
        {
            include '../view/errorPage.php';
        }
        else
        {
            $rowsAffected = addToCart($PRODUCTID, $QTYREQUESTED);
        }
        header("Location: {$_SERVER['HTTP_REFERER']}");
    }

    function processAdjustQTYInCart(){
        $SettingsInfo = getAllSettingsInfo();
        $PRODUCTID = $_GET['ProductID'];
        $QTYREQUESTED = $_POST['QTYRequested'];
        //Validations
        $errors = "";
        if($errors != "")
        {
            include '../view/errorPage.php';
        }
        else
        {
            $rowsAffected = AdjustCart($PRODUCTID, $QTYREQUESTED);
        }
        header("Location: {$_SERVER['HTTP_REFERER']}");
    }

    function processRemoveFromCart()
    {
        $SettingsInfo = getAllSettingsInfo();
        $PRODUCTID = $_GET['ProductID'];
        //Validations
        $errors = "";
        if($errors != "")
        {
            include '../view/shopperCart.php';
        }
        else
        {
            $rowsAffected = removeFromCart($PRODUCTID);
        }
        header("Location: {$_SERVER['HTTP_REFERER']}");
    }

    function processSubmitOrder(){
        $SettingsInfo = getAllSettingsInfo();
        $USERID = getUserID();
        $invalidRequests = validateCart($USERID);
        if(isset($_POST["cartComment"])){
            $COMMENT = $_POST["cartComment"];
        } else{
            $COMMENT = "";
        }
        if(sizeof($invalidRequests) == 0){
            $orderID = submitOrder($USERID,getCart($USERID),$COMMENT);
            $currentOrder = getOrder($orderID)[0];
            $SettingsInfo = getAllSettingsInfo();
            $UsersEmail = getUserEmail($USERID);
            $UserInfo = getUserInfo($USERID);
            $to = $UsersEmail['Email'];
            $cc = $SettingsInfo['EmailOrderReceived'];
            $bcc = $SettingsInfo['BCCOrderReceived'];
            $subject = $SettingsInfo['OrderReceivedSubj'];
            $tableBody = "";
            foreach($currentOrder->getOrderDetails() as $orderDetail){
                $ProductName = $orderDetail->getProduct()->getProductName();
                $QtyRequested = $orderDetail->getQTYRequested();
                $tableBody .= "
                <tr>
                <td>$ProductName</td>
                <td style='text-align: center;'>$QtyRequested</td>
                </tr>
                ";
            }
            $message = $SettingsInfo['OrderReceivedText'] . "<br><br>" . "<h3>Order Summary: " . $UserInfo->getFirstName() . " " . $UserInfo->getLastName() . "</h3>" . "
                                                                                <html>
                                                                                <head>
                                                                                <title>HTML email</title>
                                                                                </head>
                                                                                <body>
                                                                                <table>
                                                                                <thead>
                                                                                    <th>Product Name</th>
                                                                                    <th style='padding-left:30px;'>Quantity Requested</th>
                                                                                </thead>
                                                                                <tbody>" .
                                                                                    $tableBody .
                                                                                "</tbody>
                                                                                 </table>
                                                                                 </body>
                                                                                 </html>";
            sendEmail($to, $cc, $bcc, $subject, $message);
        }
        displayShopperOrders();
    }

    function validateCart($USERID){
        $cart = getCart($USERID);
        $invalidRequests = array();
        foreach($cart->getProductsInCart() as $cartItem){
            if($cartItem->getQTYRequested() > $cartItem->getProductObject()->getProductQTYAvailable()){
                array_push($invalidRequests,$cartItem);
            }
        }
        return $invalidRequests;
    }

