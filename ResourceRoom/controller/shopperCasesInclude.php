<?php

    // This file is included in the main controller as a series of cases to check the $action querystring parameter.
    // The purpose is to separate the shopper actions from the back-end inventory actions to help version control.
    switch ($action) {
        case 'shopperCart':
            include '../view/shopperCart.php';
            break;
        case 'shopperHome':
            include '../view/index.php';
            break;
        case 'shopperOrders':
            include '../view/shopperOrders.php';
            break;
    }
?>