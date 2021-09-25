<?php

    // This file is included in the main controller as a series of cases to check the $action querystring parameter.
    // The purpose is to separate the shopper actions from the back-end inventory actions to help version control.

    switch ($action) {
        case 'adminInventory':
            displayCategories();
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
    }

    function displayCategories()
    {
        $CategoryResults = getAllCategories();
        if (count($CategoryResults) == 0) {
            $errorMessage = "No Categories found.";
            include '../view/errorPage.php';
        } else {
            include '../view/adminInventory.php';
        }
    }

?>