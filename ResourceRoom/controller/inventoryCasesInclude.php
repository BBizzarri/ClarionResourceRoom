<?php

    // This file is included in the main controller as a series of cases to check the $action querystring parameter.
    // The purpose is to separate the shopper actions from the back-end inventory actions to help version control.

    switch ($action) {
        case 'adminChangeOrderStatus':
            adminChangeOrderStatus();
            break;
        case 'addEditProduct':
            addEditProduct();
            break;
        case 'adminFillOrder':
            adminFillOrder();
            break;
        case 'adminInventory':
            showInventory();
            break;
        case 'adminOrders':
            showAdminOrders();
            break;
        case 'adminReports':
            include '../view/adminReports.php';
            break;
        case 'adminSecurity':
            include '../security/index.php';
            break;
        case 'adminShoppingList':
            shopperPage();
            break;
        case 'applyFilter':

            break;
        case 'displaySelectedCategory':

            break;
        case 'getProductCategories':
            getProductCategories();
            break;
        case 'getProductInfo':
            getProductInfo();
            break;
        case 'processSingleStockAdjust':
            processSingleStockAdjust();
            break;
    }

    function shopperPage(){
        $IncludeInactiveItems = false;
        $HideUnstockedItems = true;
        $ShoppingList = false;
        $info = getProducts([1,7,9],10,$IncludeInactiveItems,$HideUnstockedItems,$ShoppingList,"");
        include '../view/adminShoppingList.php';
    }
    function adminChangeOrderStatus(){
        $orderID = $_GET['orderID'];
        $status = $_GET['STATUS'];
        if($status == "SUBMITTED"){
            $newStatus = "READY FOR PICKUP";
        }
        else if($status == "READY FOR PICKUP"){
            $newStatus = "COMPLETED";
        }else{
            $newStatus = "COMPLETED";
        }
        changeOrderStatus($orderID,$newStatus);
        header("Location: {$_SERVER['HTTP_REFERER']}");
    }

    function adminFillOrder(){
        $orderID = $_GET['orderID'];
        $status = $_GET['status'];
        $orderDetails = array();
        $QTYRequested = '';
        foreach($_POST as $productID=>$QTYFilled){
            array_push($orderDetails,new orderDetail($orderID,new product((int)$productID,'','','','','','','','',''),$QTYRequested,$QTYFilled));
        }
        $order = new order($orderID,'',$status,'','','','',$orderDetails);
        if($status == "SUBMITTED"){
            $newStatus = "READY FOR PICKUP";
        }
        else if($status == "READY FOR PICKUP"){
            $newStatus = "COMPLETED";
        }else{
            $newStatus = "ERROR";
        }
        changeOrderStatus($orderID,$newStatus);
        fillOrderDetails($order);
        fillOrder($order);
        header("Location: {$_SERVER['HTTP_REFERER']}");
    }
    function applyFilter()
    {
        $QtyLessThan = $_POST['qtyLessThan'];
        if($QtyLessThan !== '')
        {
            $CategoryHeader = 'All';
            $ProductArray = getFilterResults($QtyLessThan);
        }
        if(isset($_POST['inactiveItems']))
        {
            console_log('inactive items is checked');
        }
        $CategoryArray = getAllCategories();
        include '../view/adminInventory.php';
    }

    function showInventory()
    {
        $CategoryArray = getAllCategories();
        $InactiveItems = isset($_POST['inactiveItems']);
        $StockedItems = isset($_POST['stockedItems']);
        $ShoppingList = isset($_POST['shoppingList']);
        if(isset($_POST['CategoryList']))
        {
            $CategoryList = $_POST['CategoryList'];
            $CategoryID = $CategoryList;
            $CategoryHeader = getCategoryHeader($CategoryID);
        }
        else
        {
            $CategoryID = [];
            $CategoryHeader = 'All';
        }
        if(isset($_POST['QtyLessThan']))
        {
            $QtyLessThan = $_POST['QtyLessThan'];
        }
        else
        {
            $QtyLessThan = '';
        }
        if(isset($_POST['adminSearchCriteria']))
        {
            $SearchTerm = $_POST['adminSearchCriteria'];
        }
        else
        {
            $SearchTerm = '';
        }
        $info = getProducts($CategoryID,$QtyLessThan,$InactiveItems,$StockedItems,$ShoppingList,$SearchTerm);
        $ProductArray = $info[0];
        include '../view/adminInventory.php';

//         $Display = $_GET['Display'];
//        if (isset($_POST['action']))
//         {
//             $action = $_POST['action'];
//         }
//         else if (isset($_GET['action']))
//         {
//             $action = $_GET['action'];
//         }
//         if($Display == 'All')
//         {
//             $CategoryID = [];
//             $DESCRIPTION = '';
//             $CategoryHeader = 'All';
//             $CategoryArray = getAllCategories();
//             if($action === 'applyFilter')
//             {
//                 if(isset($_POST['QtyLessThan']))
//                 {
//                     if(is_numeric($_POST['QtyLessThan']))
//                     {
//                         $QtyLessThanStatus = isset($_POST['QtyLessThan']);
//                         $QtyLessThan = $_POST['QtyLessThan'];
//                     }
//                     else
//                     {
//                         $QtyLessThanStatus = false;
//                         $QtyLessThan = '';
//                     }
//                 }
//                 else
//                 {
//                     $QtyLessThanStatus = false;
//                     $QtyLessThan = '';
//                 }
//                 $InactiveItems = isset($_POST['inactiveItems']);
//                 $StockedItems = isset($_POST['stockedItems']);
//                 $ShoppingList = isset($_POST['shoppingList']);
//                 $info = getProducts($CategoryID,$QtyLessThan,$InactiveItems,$StockedItems,$ShoppingList,$SearchTerm='');
//                 $ProductArray = $info[0];
//             }
//             else
//             {
//                 //$ProductArray = getAllProductsAndCategories();
//                 $info = getProducts($CategoryID,$QTYLessThan = '',$IncludeInactiveItems = false,$HideUnstockedItems = false,$ShoppingList= false,$SearchTerm='');
//                 $ProductArray = $info[0];
//             }
//             if ($CategoryArray == false) {
//                 $errorMessage = "No Categories found.";
//                 include '../view/errorPage.php';
//             } else {
//                 include '../view/adminInventory.php';
//             }
//         }
//         else if ($Display == 'category') {
//             $CategoryID = [];
//             array_push($CategoryID, $_GET['CATEGORYID']);
//             $DESCRIPTION = $_GET['DESCRIPTION'];
//             if (!isset($CategoryID))
//             {
//                 $errorMessage = 'You must provide a category ID to display';
//                 include '../view/errorPage.php';
//             }
//             else
//             {
//                 $CategoryHeader = $DESCRIPTION;
//                 $CategoryArray = getAllCategories();
//                 if($action === 'applyFilter')
//                 {
//                     if(isset($_POST['QtyLessThan']))
//                     {
//                         if(is_numeric($_POST['QtyLessThan']))
//                         {
//                             $QtyLessThanStatus = isset($_POST['QtyLessThan']);
//                             $QtyLessThan = $_POST['QtyLessThan'];
//                         }
//                         else
//                         {
//                             $QtyLessThanStatus = false;
//                             $QtyLessThan = 0;
//                         }
//                     }
//                     $InactiveItems = isset($_POST['inactiveItems']);
//                     $StockedItems = isset($_POST['stockedItems']);
//                     $ShoppingList = isset($_POST['shoppingList']);
//                     $info = getProducts($CategoryID,$QTYLessThan = '',$InactiveItems,$StockedItems,$ShoppingList,$SearchTerm='');
//                     $ProductArray = $info[0];
//                 }
//                 else
//                 {
//                     $info = getProducts($CategoryID,$QTYLessThan = '',$IncludeInactiveItems = false,$HideUnstockedItems = false,$ShoppingList= false,$SearchTerm='');
//                     $ProductArray = $info[0];
//                 }
//                 if ($ProductArray == false)
//                 {
//                     $errorMessage = 'That category was not found';
//                     include '../view/errorPage.php';
//                 }
//                 else
//                 {
//                     include '../view/adminInventory.php';
//                 }
//             }
//         }
    }

    function showAdminOrders(){
        $AllOrders = getAdminOrders();
        $SubmittedOrders = $AllOrders[0];
        $ReadyOrders = $AllOrders[1];
        $CompletedOrders = $AllOrders[2];
        include '../view/adminOrders.php';
    }


    function editProduct()
    {
        updateProduct($product);
    }


    function getProductInfo()
    {
        console_log('here');
        $PRODUCTID = $_GET['ProductID'];
        console_log($PRODUCTID);
    }

    function processSingleStockAdjust()
    {
        console_log($_GET['Type']);
        if($_GET['Type'] == 'bulk')
        {
            $IncomingAmtArray = $_POST;
            foreach($IncomingAmtArray as $IncomingAmt)
            {
                $IncomingAmtKey = explode('_', key($IncomingAmtArray));
                $ProductID = $IncomingAmtKey[1];
                $rowsAffected = updateQTY($ProductID, $IncomingAmt);
                next($IncomingAmtArray);
            }
        }
        else
        {
            $IncomingAmt = $_GET['IncomingAmt'];
            $ProductID = $_GET['ProductID'];
            $rowsAffected = updateQTY($ProductID, $IncomingAmt);
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

    function addEditProduct()
    {
        $ProductMode = $_GET['productMode'];
        $ProductName = $_POST['ProductName'];
        $ProductCategories = array();
        if($ProductMode == 'Add')
        {
            $ProductCategories = $_POST['Categories'];
        }
        else if($ProductMode == 'edit')
        {
            $ProductID = $_POST['ProductID'];
            $ProductCategories = $_POST['CategoriesEdit'];
        }
        else
        {

        }
        $QtyOnHand = $_POST['QtyOnHand'];
        $MaxOrderQty = $_POST['MaxOrderQty'];
        $GoalStock = $_POST['GoalStock'];
        $ProductDescription = $_POST['ProductDescription'];
        $ProductMode = $_GET['productMode'];
        $errorMessage = "";
        if(empty($ProductName))
        {
            $errorMessage .= "\\n* Product name is required.";
        }
        if(empty($QtyOnHand) || !is_numeric($QtyOnHand))
        {
            $errorMessage .= "\\n* Qty on hand is required and must be numeric.";
        }
//         if(empty($MaxOrderQty) || !is_numeric($MaxOrderQty))
//         {
//             $errorMessage .= "\\n* Max order quantity is required and must be numeric.";
//         }
//         if(empty($GoalStock) || !is_numeric($GoalStock))
//         {
//             $errorMessage .= "\\n* Goal stock is required and must be numeric.";
//         }
        if($errorMessage == "")
        {
            if($ProductMode == 'Add')
            {
                $ProductID = addProduct($ProductName, $QtyOnHand, $MaxOrderQty, $GoalStock, $ProductDescription, $ProductCategories);
            }
            else
            {
               $ProductID = $_POST['ProductID'];
               $rowsAffected = updateProduct($ProductID, $ProductName, $QtyOnHand, $MaxOrderQty, $GoalStock, $ProductDescription, $ProductCategories);
            }
        }
        else
        {
            include '../view/errorPage.php';
        }

        $target_dir = "../productImages/";
        // Check if image file is a actual image or fake image
        if($_FILES["ProductImage"]["name"]) {
            $target_file = $target_dir . basename($_FILES["ProductImage"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["ProductImage"]["tmp_name"]);
              if($check !== false) {
                    //echo "File is an image - " . $check["mime"] . "." . "<br />";
                    $uploadOk = 1;
              } else {
                    echo "File is not an image." . "<br />";
                    $uploadOk = 0;
              }
            }

            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" )
            {
              echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed." . "<br />";
              $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
              echo "Sorry, your file was not uploaded." . "<br />";
            // if everything is ok, try to upload file
            }
            else {

                move_uploaded_file($_FILES["ProductImage"]["tmp_name"], $target_file);
                rename($target_file, $target_dir . $ProductID . '.jpg');
                $target_file = $target_dir . $ProductID . '.jpg';
                //Set size of images
                $width = 256;
                $height = 256;

                if($imageFileType == "jpeg"){$imgName = imagecreatefromjpeg($target_file);}
                if($imageFileType == "png"){$imgName = imagecreatefrompng($target_file);}
                if($imageFileType == "gif"){$imgName = imagecreatefromgif($target_file);}
                if($imageFileType == "jpg"){$imgName = imagecreatefromjpeg($target_file);}

                $image_p = imagescale(
                    $imgName,
                    $width,
                    $height,
                    $mode = IMG_BICUBIC //IMG_NEAREST_NEIGHBOUR, IMG_BILINEAR_FIXED, IMG_BICUBIC, IMG_BICUBIC_FIXED
                );

                $new_Name =  $target_file;
                $current_Name = $_FILES["ProductImage"]["name"];

                // Display of output image and save in set directory
                imagejpeg($image_p, $target_file);
                header("Location: {$_SERVER['HTTP_REFERER']}");
            }
        }
        else if(file_exists($target_dir . $ProductID . 'jpg'))
        {
            header("Location: {$_SERVER['HTTP_REFERER']}");
        }
        else {
            $imgName = imagecreatefromjpeg('../productImages/ImageNotAvailable.jpg');
            $new_Name =  $ProductID;
            // Display of output image and save in set directory
            imagejpeg($imgName, '../productImages/'.$new_Name.'.jpg');
            header("Location: {$_SERVER['HTTP_REFERER']}");
        }
    }

    function getProductCategories(){
        $ProductID = $_GET['ProductID'];
        $ProductCategories = getCategories($ProductID);
    }
?>