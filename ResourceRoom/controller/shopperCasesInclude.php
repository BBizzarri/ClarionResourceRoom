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
        $USERID = getUserID();
        $orderIDs = getOrderIDsByUSERID($USERID);

        if(sizeof($orderIDs) > 0){
            $orders = array();
            foreach($orderIDs as $ID){
                array_push($orders,getOrdersByUserIDOrderID($USERID,$ID));
            }
        }
        include '../view/shopperOrders.php';
    }


    function displayProducts()
    {
        $listType = filter_input(INPUT_GET, 'ListType');
        $CategoryArray = getAllCategories();
        if($listType =='GeneralSearch'){
            $ProductArray = getByGeneralSearch($_GET['Criteria']);
            $CurrentCategory = "Search: " . $_GET['Criteria'];
        }else if (isset($_GET['CATEGORYID'])) {
            $shopperCategoryID = $_GET['CATEGORYID'];
            $CurrentCategory = $_GET['DESCRIPTION'];
            $ProductArray = getCategory($shopperCategoryID);
        }else{
            $CurrentCategory = $CategoryArray[0]->getCategoryDescription();
            $shopperCategoryID = $CategoryArray[0]->getCategoryID();
            $ProductArray = getCategory($shopperCategoryID);
        }
        $CategoryHeader = $CurrentCategory;
            if ($ProductArray == false)
            {
                $errorMessage = 'That category was not found';
                include '../view/errorPage.php';
            }
               else
               {
                   $USERID = getUserID();
                   $cart = getCart($USERID);
                   include '../view/index.php';
               }
    }
    function displayCart()
    {
        $USERID = getUserID();
        $cart = getCart($USERID);
        include '../view/shopperCart.php';
    }

    function processAddToCart()
    {
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
        $USERID = getUserID();
        $invalidRequests = validateCart($USERID);
        $COMMENT = $_POST["cartComment"];
        if(sizeof($invalidRequests) == 0){
            submitOrder($USERID,getCart($USERID),$COMMENT);
        }
        header("Location: {$_SERVER['HTTP_REFERER']}");
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

