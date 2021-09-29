<?php

    // This file is included in the main controller as a series of cases to check the $action querystring parameter.
    // The purpose is to separate the shopper actions from the back-end inventory actions to help version control.
    switch ($action) {
        case 'shopperCart':
            include '../view/shopperCart.php';
            break;
        case 'shopperHome':
            displayProducts();
            break;
        case 'shopperOrders':
            include '../view/shopperOrders.php';
            break;
    }

    function displayProducts()
    {
        $CategoryResults = getAllCategories();
        $CurrentCategory = $CategoryResults[0]['DESCRIPTION'];
        $shopperCategoryID = $CategoryResults[0]['CATEGORYID'];
        if(isset($_GET['CATEGORYID'])) {
            $shopperCategoryID = $_GET['CATEGORYID'];
            $CurrentCategory = $_GET['DESCRIPTION'];
        }
        $CategoryHeader = $CurrentCategory;
        $ProductResults = getCategory($shopperCategoryID);
            if ($ProductResults == false)
            {
                $errorMessage = 'That category was not found';
                include '../view/errorPage.php';
            }
               else
               {
                   include '../view/index.php';
               }
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