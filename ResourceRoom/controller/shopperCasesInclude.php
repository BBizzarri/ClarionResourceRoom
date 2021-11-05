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
        $orders = getOrderIDsByUSERID($USERID);
        include '../view/shopperOrders.php';
    }

    function displayProducts()
    {
        $listType = filter_input(INPUT_GET, 'ListType');
        $CategoryArray = getAllCategories();
        if($listType =='GeneralSearch'){
            $info = getProducts([],'',$IncludeInactiveItems = false ,$HideUnstockedItems = false,$ShoppingList = false,$_POST['searchCriteria']);
            $ProductArray = $info[0];
            $CurrentCategory = "Related To: " . $_POST['searchCriteria'];
        }else if (isset($_GET['CATEGORYID'])) {
            $shopperCategoryID = $_GET['CATEGORYID'];
            $CurrentCategory = $_GET['DESCRIPTION'];
            $info = getProducts([$shopperCategoryID],'',$IncludeInactiveItems = false ,$HideUnstockedItems = false,$ShoppingList = false,'');
            $ProductArray = $info[0];
        }else{
            $CurrentCategory = $CategoryArray[0]->getCategoryDescription();
            $shopperCategoryID = $CategoryArray[0]->getCategoryID();
            $info = getProducts([$shopperCategoryID],'',$IncludeInactiveItems = false ,$HideUnstockedItems = false,$ShoppingList = false,'');
            $ProductArray = $info[0];
        }
        $CategoryHeader = $CurrentCategory;
            if ($ProductArray == false)
            {
                $errorMessage = 'No items relevent to: ' . $_GET['Criteria'];
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
        $USERID = getUserID();
        $cart = getCart($USERID);
        $_SESSION['itemsInCart'] = $cart->getNumberOfItemsInCart();
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
        if(isset($_POST["cartComment"])){
            $COMMENT = $_POST["cartComment"];
        } else{
            $COMMENT = "";
        }
        if(sizeof($invalidRequests) == 0){
            submitOrder($USERID,getCart($USERID),$COMMENT);
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

