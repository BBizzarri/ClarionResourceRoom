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
        $listType = filter_input(INPUT_GET, 'ListType');
        $CategoryResults = getAllCategories();
        if($listType =='GeneralSearch'){
            $ProductResults = getByGeneralSearch($_GET['Criteria']);
            $CurrentCategory = "Search: " . $_GET['Criteria'];
        }else if (isset($_GET['CATEGORYID'])) {
            $shopperCategoryID = $_GET['CATEGORYID'];
            $CurrentCategory = $_GET['DESCRIPTION'];
            $ProductResults = getCategory($shopperCategoryID);
        }else{
            $CurrentCategory = $CategoryResults[0]['DESCRIPTION'];
            $shopperCategoryID = $CategoryResults[0]['CATEGORYID'];
            $ProductResults = getCategory($shopperCategoryID);
        }
        $CategoryHeader = $CurrentCategory;
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
