<?php

    // This file is included in the main controller as a series of cases to check the $action querystring parameter.
    // The purpose is to separate the shopper actions from the back-end inventory actions to help version control.
    switch ($action) {
        case 'shopperCart':
            include '../view/shopperCart.php';
            break;
        case 'shopperHome':
            listProducts();
            break;
        case 'shopperOrders':
            include '../view/shopperOrders.php';
            break;
    }



function listProducts() {
        $listType = filter_input(INPUT_GET, 'ListType');
        if($listType =='GeneralSearch'){
            $ProductResults = getByGeneralSearch($_GET['Criteria']);
        } else {
            $ProductResults = getAllProducts();
        }
        $CategoryResults = getAllCategories();
        if (count($ProductResults) == 0) {
            $errorMessage = "No Products found.";
            include '../view/errorPage.php';
        } else {
            include '../view/index.php';
        }
}
?>