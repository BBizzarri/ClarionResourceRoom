<?php

    // This file is included in the main controller as a series of cases to check the $action querystring parameter.
    // The purpose is to separate the shopper actions from the back-end inventory actions to help version control.

    switch ($action) {
        case 'accountSettings':
            showAccountSettings();
            break;
        case 'adminChangeOrderStatus':
            adminChangeOrderStatus();
            break;
        case 'addEditCategory':
            addEditCategory();
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
        case 'processStockAdjust':
            processStockAdjust();
            break;
    }

    function addEditCategory()
    {
        $CategoryMode = $_GET['categoryMode'];
        $CategoryName = $_POST['CatName'];
        $errorMessage = '';
        if($CategoryName == '')
        {
             $errorMessage .= "\\n* A Category name is required.";
        }
        if($errorMessage == "")
        {
            if($CategoryMode == 'Add')
            {

                $CategoryID = addCategory($CategoryName);
            }
            else if($CategoryMode == 'edit')
            {
               $CategoryID = $_POST['CategoryID'];
               $rowsAffected = updateCategory($CategoryID, $CategoryName);
            }
            header("Location: {$_SERVER['HTTP_REFERER']}");
        }
        else
        {
            include '../view/errorPage.php';
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
        if(!is_numeric($QtyOnHand))
        {
            $errorMessage .= "\\n* Qty on hand is required and must be numeric.";
        }
        if($MaxOrderQty < 0)
        {
            $errorMessage .= "\\n* Max order quantity is required and must be greater than or equal to 0.";
        }
        if($GoalStock < 0)
        {
            $errorMessage .= "\\n* Goal stock is required and must be greater than or equal to 0.";
        }
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

    function adminChangeOrderStatus(){
        $orderID = $_GET['orderID'];
        $status = $_GET['STATUS'];
        $usersName = $_GET['usersName'];
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
        $usersName = $_GET['usersName'];
        $orderDetails = array();
        $QTYRequested = '';
        foreach($_POST as $productID=>$QTYFilled){
            array_push($orderDetails,new orderDetail($orderID,new product((int)$productID,'','','','','','','','',''),$QTYRequested,$QTYFilled));
        }
        $order = new order($orderID,'',$status,'','','','',$orderDetails, $usersName);
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

    function processStockAdjust()
    {
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
//         header("Location: {$_SERVER['HTTP_REFERER']}");
        showInventory();
    }

    function shopperPage(){
        $IncludeInactiveItems = false;
        $HideUnstockedItems = true;
        $ShoppingList = false;
        $info = getProducts([1,7,9],10,$IncludeInactiveItems,$HideUnstockedItems,$ShoppingList,"");
        include '../view/adminShoppingList.php';
    }

    function showAccountSettings() {
        $CategoryArray = getAllCategories();
        include '../view/accountSettings.php';
    }

    function showAdminOrders(){
        $USERID = getUserID();
        $AllOrders = getAdminOrders();
        include '../view/adminOrders.php';
    }

    function showInventory()
    {
        if(isset($_GET['ClearFilters']))
        {
            $_SESSION['CategoryID'] = [];
            $_SESSION['QtyLessThan'] = null;
            $_SESSION['InactiveItems'] = null;
            $_SESSION['StockedItems'] = null;
            $_SESSION['ShoppingList'] = null;
            $_SESSION['SearchTerm'] = null;
            $CategoryMode = true;
        }
        if(isset($_GET['CategoryMode']))
        {
            $CategoryMode = true;
        }
        else
        {
            $CategoryMode = false;
        }
        $CategoryArray = getAllCategories();
        if(isset($_POST['inactiveItems']))
        {
            $InactiveItems = $_POST['inactiveItems'];

        }
        else
        {
            $InactiveItems = $_SESSION['InactiveItems'];
        }
        if(isset($_POST['stockedItems']))
        {
            $StockedItems = $_POST['stockedItems'];

        }
        else
        {
            $StockedItems = $_SESSION['StockedItems'];
        }
        if(isset($_POST['shoppingList']))
        {
            $ShoppingList = $_POST['shoppingList'];

        }
        else
        {
            $ShoppingList = $_SESSION['ShoppingList'];
        }
        if($CategoryMode)
        {
            $CategoryID = [];
            $CategoryHeader = 'All';
        }
        else if(isset($_POST['CategoryList']))
        {
            $CategoryID = $_POST['CategoryList'];
            $CategoryHeader = getCategoryHeader($CategoryID);
        }
        else if(!is_null($_SESSION['CategoryID']))
        {
            $CategoryID = $_SESSION['CategoryID'];
            $CategoryHeader = getCategoryHeader($_SESSION['CategoryID']);
        }
        else{
            $CategoryID = [];
            $CategoryHeader = 'All';
        }
        if(isset($_POST['QtyLessThan']))
        {
            $QtyLessThan = $_POST['QtyLessThan'];
        }
        else if(!is_null($_SESSION['QtyLessThan']))
        {
            $QtyLessThan = $_SESSION['QtyLessThan'];
        }
        else
        {
            $QtyLessThan = '';
        }
        if(isset($_POST['adminSearchCriteria']))
        {
            $SearchTerm = $_POST['adminSearchCriteria'];
        }
        else if(!is_null($_SESSION['SearchTerm']))
        {
            $SearchTerm = $_SESSION['SearchTerm'];
        }
        else
        {
            $SearchTerm = '';
        }
        $info = getProducts($CategoryID,$QtyLessThan,$InactiveItems,$StockedItems,$ShoppingList,$SearchTerm);
        $ProductArray = $info[0];
        $_SESSION['CategoryID'] = $CategoryID;
        $_SESSION['QtyLessThan'] = $QtyLessThan;
        $_SESSION['InactiveItems'] = $InactiveItems;
        $_SESSION['StockedItems'] = $StockedItems;
        $_SESSION['ShoppingList'] = $ShoppingList;
        $_SESSION['SearchTerm'] = $SearchTerm;
        include '../view/adminInventory.php';
    }
?>