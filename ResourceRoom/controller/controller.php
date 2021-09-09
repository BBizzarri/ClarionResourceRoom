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
        include('../view/index.php');  // default action
        exit();
    }
	
    if (!userIsAuthorized($action)) {
        if(!loggedIn()) {
            header("Location:../security/index.php?action=SecurityLogin&RequestedPage=" . urlencode($_SERVER['REQUEST_URI']));
        } else {
            include('../security/not_authorized.html');
        }
    } else {
        switch ($action) {
            case 'Home':
                include '../view/index.php';
                break;
            include('../controller/shopperCasesInclude.php');
            include('../controller/inventoryCasesInclude.php');
            default:
                include('../view/index.php');   // default
        }
    }
    
    include('../controller/shopperFunctionsInclude.php');
    include('../controller/inventoryFunctionsInclude.php');
	
?>

