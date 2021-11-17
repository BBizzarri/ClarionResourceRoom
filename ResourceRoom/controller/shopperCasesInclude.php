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
        $CategoryHeader = $CurrentCategory;
            if ($ProductArray == false)
            {
                $errorMessage = 'No items relevent to: ' . htmlspecialchars($_POST['searchCriteria']);
                include '../view/errorPage.php';
            }
               else
               {
                   $USERID = getUserID();
                   $cart = getCart($USERID);
                   $_SESSION['itemsInCart'] = $cart->getNumberOfItemsInCart();
                   include '../view/index.php';
               }
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
        $UsersEmail = getUserEmail($USERID);
        $invalidRequests = validateCart($USERID);
        if(isset($_POST["cartComment"])){
            $COMMENT = $_POST["cartComment"];
        } else{
            $COMMENT = "";
        }
        if(sizeof($invalidRequests) == 0){
            submitOrder($USERID,getCart($USERID),$COMMENT);
        }
        displayShopperOrders();

        $to = $UsersEmail['Email'];
        $cc = $SettingsInfo['EmailOrderReceived'];
        $subject = $SettingsInfo['OrderReceivedSubj'];
        $message = $SettingsInfo['OrderReceivedText'];
        sendEmail($to, $cc, $subject, $message);
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

