<?php

    // This file is included in the main controller as a series of cases to check the $action querystring parameter.
    // The purpose is to separate the shopper actions from the back-end inventory actions to help version control.
    include_once '../model/order.php';
    include_once '../model/orderDetail.php';


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
            adminReports();
            break;
        case 'adminSecurity':
            adminSecurity();
            break;
        case 'adminShoppingList':
            shopperPage();
            break;
        case 'deleteCategory':
            adminDeleteCategory();
            break;
        case 'deleteOrder':
            adminDeleteOrder();
            break;
        case 'mobileAdd':
            mobileAdd();
            break;
        case 'processStockAdjust':
            processStockAdjust();
            break;
        case 'reNotifyEmail':
            reNotifyEmail();
            break;
        case 'updateEmailAnnouncementSettings':
            updateEmailAnnouncementSettings();
            break;
    }

    function adminDeleteCategory()
    {
        $categoryID = $_POST['CategoryID'];
        $result = deactivateCategory($categoryID);
        header("Location: {$_SERVER['HTTP_REFERER']}");
    }

    function adminDeleteOrder()
    {
        $OrderID = $_POST['ORDERID'];
        $currentOrder = getOrder($OrderID)[0];
        $OrderedByEmail = getEmailToOrder($OrderID);
        deleteOrder($OrderID);
        $SettingsInfo = getAllSettingsInfo();
        $to = $OrderedByEmail['Email'];
        $subject = $SettingsInfo['OrderCancelledSubj'];
        foreach($currentOrder->getOrderDetails() as $orderDetail){
            $ProductName = $orderDetail->getProduct()->getProductName();
            $QtyRequested = $orderDetail->getQTYRequested();
            $QtyFilled = $orderDetail->getQTYFilled();
            $tableBody .= "
                    <tr>
                    <td>$ProductName</td>
                    <td style='text-align: center;'>$QtyRequested</td>
                    <td style='text-align: center;'>$QtyFilled</td>
                    </tr>
                    ";
        }
//         $message = setMessage('',$SettingsInfo['OrderCancelledText'],$tableBody,'cancelled');
        $message = $SettingsInfo['OrderCancelledText'] . PHP_EOL . PHP_EOL . "
                                                <html>
                                                <head>
                                                <title>HTML email</title>
                                                </head>
                                                <body>
                                                <table>
                                                <thead>
                                                    <th>Product Name</th>
                                                    <th style='padding-left: 30px;'>Quantity Requested</th>
                                                    <th style='padding-left: 30px;'>Quantity Filled</th>
                                                </thead>
                                                <tbody>" .
                                                    $tableBody .
                                                "</tbody>
                                                 </table>
                                                 </body>
                                                 </html>";
        $cc = $SettingsInfo['EmailOrderCancelled'];
        sendEmail($to, $cc, $subject, $message);
        header("Location: {$_SERVER['HTTP_REFERER']}");
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
        $fillerComments = $_POST['fillerComments'];
        $orderDetails = array();
        $QTYRequested = '';
        foreach($_POST as $productID=>$QTYFilled){
            array_push($orderDetails,new orderDetail($orderID,new product((int)$productID,'','','','','','','','',''),$QTYRequested,$QTYFilled));
        }
        $order = new order($orderID,'',$status,'','','','',$orderDetails, '');
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

        $currentOrder = getOrder($orderID)[0];
        $SettingsInfo = getAllSettingsInfo();
        $USERID = getUserID();
        $OrderedByEmail = getEmailToOrder($orderID);
        $to = $OrderedByEmail['Email'];
        $subject = $SettingsInfo['OrderFilledSubj'];
        $tableBody = "";
        foreach($currentOrder->getOrderDetails() as $orderDetail){
            $ProductName = $orderDetail->getProduct()->getProductName();
            $QtyRequested = $orderDetail->getQTYRequested();
            $QtyFilled = $orderDetail->getQTYFilled();
            $tableBody .= "
            <tr>
            <td>$ProductName</td>
            <td style='text-align: center;'>$QtyRequested</td>
            <td style='text-align: center;'>$QtyFilled</td>
            </tr>
            ";
        }
//         $message = setMessage($fillerComments, $SettingsInfo['OrderFilledText'],$tableBody,'filled');
        $message = $fillerComments . PHP_EOL . PHP_EOL . $SettingsInfo['OrderFilledText'] . PHP_EOL . PHP_EOL . "
                                                                                        <html>
                                                                                        <head>
                                                                                        <title>HTML email</title>
                                                                                        </head>
                                                                                        <body>
                                                                                        <table>
                                                                                        <thead>
                                                                                            <th>Product Name</th>
                                                                                            <th style='padding-left: 30px;'>Quantity Requested</th>
                                                                                            <th style='padding-left: 30px;'>Quantity Filled</th>
                                                                                        </thead>
                                                                                        <tbody>" .
                                                                                            $tableBody .
                                                                                        "</tbody>
                                                                                         </table>
                                                                                         </body>
                                                                                         </html>";
        $cc = $SettingsInfo['EmailOrderFilled'];
        sendEmail($to, $cc, $subject, $message);
        header("Location: {$_SERVER['HTTP_REFERER']}");
    }

    function adminReports()
    {
        $SettingsInfo = getAllSettingsInfo();
        $SelectedReport = getReport();
        include '../view/adminReports.php';
    }

    function adminSecurity()
    {
        $SettingsInfo = getAllSettingsInfo();
        include '../security/index.php';
    }

    function mobileAdd()
    {
        $CategoryArray = getAllCategories();
        include '../view/addNewItemMobile.php';
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

    function reNotifyEmail()
    {
        $orderID = $_GET['orderID'];
        $SettingsInfo = getAllSettingsInfo();
        $USERID = getUserID();
        $OrderedByEmail = getEmailToOrder($orderID);
        $to = $OrderedByEmail['Email'];
        $subject = $SettingsInfo['OrderReminderSubj'];
        $currentOrder = getOrder($orderID)[0];
        foreach($currentOrder->getOrderDetails() as $orderDetail){
            $ProductName = $orderDetail->getProduct()->getProductName();
            $QtyRequested = $orderDetail->getQTYRequested();
            $QtyFilled = $orderDetail->getQTYFilled();
            $tableBody .= "
                        <tr>
                        <td>$ProductName</td>
                        <td style='text-align: center;'>$QtyRequested</td>
                        <td style='text-align: center;'>$QtyFilled</td>
                        </tr>
                        ";
        }
//         $message = setMessage('',$SettingsInfo['OrderReminderText'],$tableBody,'renotify');
        $message = $SettingsInfo['OrderReminderText'] . PHP_EOL . PHP_EOL . "
                                                <html>
                                                <head>
                                                <title>HTML email</title>
                                                </head>
                                                <body>
                                                <table>
                                                <thead>
                                                    <th>Product Name</th>
                                                    <th style='padding-left: 30px;'>Quantity Requested</th>
                                                    <th style='padding-left: 30px;'>Quantity Filled</th>
                                                </thead>
                                                <tbody>" .
                                                    $tableBody .
                                                "</tbody>
                                                 </table>
                                                 </body>
                                                 </html>";
        $cc = $SettingsInfo['EmailOrderReminder'];
        sendEmail($to, $cc, $subject, $message);
        header("Location: {$_SERVER['HTTP_REFERER']}");
    }

    function shopperPage(){
        $IncludeInactiveItems = false;
        $HideUnstockedItems = true;
        $ShoppingList = false;
        $info = getProducts([1,7,9],10,$IncludeInactiveItems,$HideUnstockedItems,$ShoppingList,"");
        include '../view/adminShoppingList.php';
    }

    function showAccountSettings() {
        $USERID = getUserID();
        $UserInfo = getUserInfo($USERID);
        $CategoryArray = getAllCategories();
        $SettingsInfo = getAllSettingsInfo();
        include '../view/accountSettings.php';
    }

    function showAdminOrders(){
        $SettingsInfo = getAllSettingsInfo();
        $USERID = getUserID();
        $AllOrders = getAdminOrders();
        include '../view/adminOrders.php';
    }

    function showInventory()
    {
        $SettingsInfo = getAllSettingsInfo();
        if(isset($_GET['ClearFilters']))
        {
            $_SESSION['CategoryID'] = [];
            $_SESSION['QtyLessThan'] = null;
            $_SESSION['InactiveItems'] = null;
            $_SESSION['StockedItems'] = null;
            $_SESSION['ShoppingList'] = null;
            $_SESSION['SearchTerm'] = null;
            $CategoryMode = true;
            $CategoryHeader = 'All';
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
            console_log($_POST['inactiveItems']);
            $InactiveItems = $_POST['inactiveItems'];
        }
        else if(isset($_SESSION['InactiveItems']))
        {
            $InactiveItems = $_SESSION['InactiveItems'];
        }
        else
        {
            $InactiveItems = 0;
        }
        if(isset($_POST['stockedItems']))
        {
            $StockedItems = $_POST['stockedItems'];
        }
        else if(isset($_SESSION['StockedItems']))
        {
            $StockedItems = $_SESSION['StockedItems'];
        }
        else
        {
            $StockedItems = 0;
        }
        if(isset($_POST['shoppingList']))
        {
            $ShoppingList = $_POST['shoppingList'];

        }
        else if(isset($_SESSION['ShoppingList']))
        {
            $ShoppingList = $_SESSION['ShoppingList'];
        }
        else
        {
            $ShoppingList = 0;
        }
        if($CategoryMode or (isset($_POST['CategoryList']) and $_POST['CategoryList'][0] == 0))
        {
            $CategoryID = [];
            $CategoryHeader = 'All';
        }
        else if(isset($_POST['CategoryList']))
        {
            $CategoryID = $_POST['CategoryList'];
            $CategoryHeader = getCategoryHeader($CategoryID);
        }
        else if(isset($_SESSION['CategoryID']))
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
        else if(isset($_SESSION['QtyLessThan']))
        {
            $QtyLessThan = $_SESSION['QtyLessThan'];
        }
        else
        {
            $QtyLessThan = '';
        }
        if(isset($_POST['adminSearchCriteria']))
        {
            $SearchTerm = htmlspecialchars($_POST['adminSearchCriteria']);
        }
        else if(isset($_SESSION['SearchTerm']))
        {
            $SearchTerm = htmlspecialchars($_SESSION['SearchTerm']);
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

    function updateEmailAnnouncementSettings()
    {
        $PlacedCC = $_POST['PlacedCC'];
        $FilledCC = $_POST['FilledCC'];
        $ReNotifyCC = $_POST['ReNotifyCC'];
        $CancelledCC = $_POST['CancelledCC'];
        $PlacedSubject = $_POST['PlacedSubject'];
        $FilledSubject = $_POST['FilledSubject'];
        $ReNotifySubject = $_POST['ReNotifySubject'];
        $CancelledSubject  = $_POST['CancelledSubject'];
        $PlacedText = $_POST['PlacedText'];
        $FilledText = $_POST['FilledText'];
        $ReNotifyText = $_POST['ReNotifyText'];
        $CancelledText = $_POST['CancelledText'];
        $FooterAnnouncement = $_POST['Announcement'];

        $errorMessage = '';
        $PlacedCCArray = explode(',',$PlacedCC);
        $FilledCCArray = explode(',',$FilledCC);
        $ReNotifyCCArray = explode(',',$ReNotifyCC);
        $CancelledCCArray = explode(',',$CancelledCC);
        foreach($PlacedCCArray as $singleEmail)
        {
            console_log($singleEmail);
            if (!filter_var($singleEmail, FILTER_VALIDATE_EMAIL)) {
                $errorMessage = "Invalid email format, emails must be seprated by a ',' and have no spaces in between";
            }
        }
        foreach($FilledCCArray as $singleEmail)
        {
            console_log($singleEmail);
            if (!filter_var($singleEmail, FILTER_VALIDATE_EMAIL)) {
                $errorMessage = "Invalid email format, emails must be seprated by a ',' and have no spaces in between";
            }
        }
        foreach($ReNotifyCCArray as $singleEmail)
        {
            console_log($singleEmail);
            if (!filter_var($singleEmail, FILTER_VALIDATE_EMAIL)) {
                $errorMessage = "Invalid email format, emails must be seprated by a ',' and have no spaces in between";
            }
        }
        foreach($CancelledCCArray as $singleEmail)
        {
            console_log($singleEmail);
            if (!filter_var($singleEmail, FILTER_VALIDATE_EMAIL)) {
                $errorMessage = "Invalid email format, emails must be seprated by a ',' and have no spaces in between";
            }
        }


        console_log($errorMessage);
        if($errorMessage == '')
        {
            console_log('good to go');
            $SettingAffected = UpdateSettings($PlacedCC, $FilledCC, $ReNotifyCC, $CancelledCC, $PlacedSubject, $FilledSubject, $ReNotifySubject, $CancelledSubject, $PlacedText, $FilledText, $ReNotifyText, $CancelledText, $FooterAnnouncement);
            header("Location: {$_SERVER['HTTP_REFERER']}");
        }
        else
        {
            include '../view/errorPage.php';
        }
    }
?>