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
        $CategoryResults = getAllCategories();
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
        $Display = $_GET['Display'];
        if($Display === 'All')
        {
            $CategoryHeader = 'All';
            $CategoryResults = getAllCategories();
            if($action === 'applyFilter')
            {
                $QTYLESSTHAN = $_POST['qtyLessThan'];
                $ProductResults = getFilteredProducts($QTYLESSTHAN);
            }
            else
            {
                $ProductResults = getAllProducts();
            }
            if (count($CategoryResults) == 0) {
                $errorMessage = "No Categories found.";
                include '../view/errorPage.php';
            } else {
                include '../view/adminInventory.php';
            }
        }
        else if ($Display == 'category') {
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
                $CategoryResults = getAllCategories();
                if($action === 'applyFilter')
                {
                    $QTYLESSTHAN = $_POST['qtyLessThan'];
                    $ProductResults = getFilteredCategory($CATEGORYID, $QTYLESSTHAN);
                }
                else
                {
                    $ProductResults = getCategory($CATEGORYID);
                }
                if ($ProductResults == false)
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
        $CategoryHeader = 'All';
        $CategoryResults = getAllCategories();
        $ProductResults = getAllProducts();
        if (count($CategoryResults) == 0) {
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
        $CATEGORYID = $_GET['CATEGORYID'];

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
            $CategoryResults = getAllCategories();
            $ProductResults = getCategory($CATEGORYID);
            if ($ProductResults == false)
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