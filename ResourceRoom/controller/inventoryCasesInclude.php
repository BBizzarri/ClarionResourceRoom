<?php

    // This file is included in the main controller as a series of cases to check the $action querystring parameter.
    // The purpose is to separate the shopper actions from the back-end inventory actions to help version control.

    switch ($action) {
        case 'adminInventory':
            include '../view/adminInventory.php';
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
        case 'displayCategory':
            displayCategory();
            break;
    }

    function displayCategory()
    {
        $categoryID = $_GET['CategoryID'];
        if (!isset($categoryID))
        {
            $errorMessage = 'You must provide a CategoryID to display';
            include '../view/errorPage.php';
        }
        else
        {
            $row = getCategory($categoryID);
            if ($row == false)
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