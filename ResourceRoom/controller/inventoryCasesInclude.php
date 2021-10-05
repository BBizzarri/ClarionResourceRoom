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
        case 'displaySelectedCategory':
            displayCategories();
            break;
        case 'processSingleStockAdjust':
            processSingleStockAdjust();
            break;
    }

    function displayCategories()
    {
        $Display = $_GET['Display'];
        console_log($Display);
        if($Display === 'All')
        {
            $CategoryHeader = 'All';
            $CategoryResults = getAllCategories();
            $ProductArray = getAllProducts();
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
    }

    function displayStartingInventoryView()
    {
        $CategoryHeader = 'All';
        $CategoryResults = getAllCategories();
        $ProductArray = getAllProducts();
        if (count($CategoryResults) == 0) {
            $errorMessage = "No Categories found.";
            include '../view/errorPage.php';
        } else {
            include '../view/adminInventory.php';
        }
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