<?php

    // This file is included in the main controller as a series of cases to check the $action querystring parameter.
    // The purpose is to separate the shopper actions from the back-end inventory actions to help version control.

    switch ($action) {
        case 'adminInventory':
             displayStartingInventoryView();
            break;
        case 'adminOrders':
            include '../view/adminOrders.php';
            break;
        case 'adminReports':
            include '../view/adminReports.php';
            break;
        case 'adminSecurity':
            include '../security/index.php';
            break;
        case 'adminShoppingList':
            include '../view/adminShoppingList.php';
            break;
        case 'applyFilter':
            displayCategories();
            break;
        case 'displaySelectedCategory':
            displayCategories();
            break;
        case editProduct();
            editProduct();
            break;
        case 'getProductInfo':
            getProductInfo();
            break;
        case 'processBulkStockAdjust':
            processBulkStockAdjust();
            break;
        case 'processSingleStockAdjust':
            processSingleStockAdjust();
            break;
    }

    function applyFilter($ProductResults)
    {
        $QTYLESSTHAN = $_POST['qtyLessThan'];
        if($QTYLESSTHAN !== '')
        {
            $ProductResults = getFilterResults($QTYLESSTHAN, $ProductResults);
        }
        if(isset($_POST['inactiveItems']))
        {
            console_log('inactive items is checked');
        }
        $CategoryArray = getAllCategories();
        include '../view/adminInventory.php';
    }

    function displayCategories()
    {
       if (isset($_POST['action']))
        {
            $action = $_POST['action'];
        }
        else if (isset($_GET['action']))
        {
            $action = $_GET['action'];
        }
        //$Display = $_GET['Display'];
        if(!isset($_GET['CATEGORYID']))
        {
            $CategoryHeader = 'All';
            $CategoryArray = getAllCategories();
            if($action === 'applyFilter')
            {
                $QTYLESSTHAN = $_POST['qtyLessThan'];
                $ProductArray = getFilteredProducts($QTYLESSTHAN);
            }
            else
            {
                $ProductArray = getAllProducts();
            }
            if ($CategoryArray == false) {
                $errorMessage = "No Categories found.";
                include '../view/errorPage.php';
            } else {
                include '../view/adminInventory.php';
            }
        }
        else if (isset($_GET['CATEGORYID'])) {
            $CATEGORYID = $_GET['CATEGORYID'];
            $DESCRIPTION = $_GET['DESCRIPTION'];
            if (!isset($CATEGORYID))
            {
                $errorMessage = 'You must provide a category ID to display';
                include '../view/errorPage.php';
            }
            else
            {
                $CategoryHeader = $DESCRIPTION;
                $CategoryArray = getAllCategories();
                if($action === 'applyFilter')
                {
                    $QTYLESSTHAN = $_POST['qtyLessThan'];
                    $ProductArray = getFilteredCategory($CATEGORYID, $QTYLESSTHAN);
                }
                else
                {
                    $ProductArray = getCategory($CATEGORYID);
                }
                if ($ProductArray == false)
                {
                    $errorMessage = 'That category was not found';
                    include '../view/errorPage.php';
                }
                else
                {
                    include '../view/adminInventory.php';
                }
            }
        }
    }

    function displayStartingInventoryView()
    {
        $listType = filter_input(INPUT_GET, 'ListType');
        $CategoryResults = getAllCategories();
        if($listType =='GeneralSearch'){
            $CategoryHeader = 'All';
            $ProductArray = getByGeneralSearch($_GET['Criteria']);
            $CurrentCategory = "Search: " . $_GET['Criteria'];
        }
        else {

            $CategoryHeader = 'All';
            $CategoryArray = getAllCategories();
            $ProductArray = getAllProducts();
        }
        if ($ProductArray == false) {
            $errorMessage = "No Categories found.";
            include '../view/errorPage.php';
        } else {
            include '../view/adminInventory.php';
        }
    }

    function getProductInfo()
    {
        console_log('here');
        $PRODUCTID = $_GET['ProductID'];
        console_log($PRODUCTID);
    }

    function processBulkStockAdjust()
    {
        console_log('action triggered');
        $PRODUCTID = $_GET['PRODUCTID'];

    }
    function processSingleStockAdjust()
        {
            $PRODUCTID = $_GET['ProductID'];
            $QTYONHAND = $_GET['QTYOnHand'];
            $INCOMINGAMT = $_POST['incomingAmt'];
            //Validations
            $errors = "";
            if($errors != "")
            {
                include '../view/adminInventory.php';
            }
            else
            {
                $rowsAffected = updateQTY($PRODUCTID, $QTYONHAND, $INCOMINGAMT);
            }
            header("Location: {$_SERVER['HTTP_REFERER']}");
        }

    function retrieveCategory()
    {
        $CATEGORYID = $_GET['CATEGORYID'];
        $DESCRIPTION = $_GET['DESCRIPTION'];
        if (!isset($CATEGORYID))
        {
            $errorMessage = 'You must provide a category ID to display';
            include '../view/errorPage.php';
        }
        else
        {
            $CategoryHeader = $DESCRIPTION;
            $CategoryArray = getAllCategories();
            $ProductArray = getCategory($CATEGORYID);
            if ($ProductArray == false)
            {
                $errorMessage = 'That category was not found';
                include '../view/errorPage.php';
            }
            else
            {
                include '../view/adminInventory.php';
            }
        }
    }
?>