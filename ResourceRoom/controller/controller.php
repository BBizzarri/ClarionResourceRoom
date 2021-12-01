<?php
    session_start();
    require_once("../security/model.php");
    require_once '../model/model.php';
    require_once '../lib/general_fns.php';

    if (isset($_POST['action'])) {  // check get and post
        $action = $_POST['action'];
    } else if (isset($_GET['action'])) {
        $action = $_GET['action'];
    } else {
        $action = 'accountSettings';
    }
	
    if (!userIsAuthorized($action)) {
        if(!loggedIn()) {
            #header("Location:../security/index.php?action=SecurityLogin&RequestedPage=" . urlencode($_SERVER['REQUEST_URI']));
            header("Location: ../security/php-saml-2.19.1/demo1");
        } else {
            include('../security/not_authorized.html');
        }
    } else {
            include('../controller/shopperCasesInclude.php');
            include('../controller/inventoryCasesInclude.php');
    }
    
    include('../controller/shopperFunctionsInclude.php');
    include('../controller/inventoryFunctionsInclude.php');
?>

