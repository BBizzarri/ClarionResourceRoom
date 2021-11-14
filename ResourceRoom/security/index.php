<?php

    session_start();
    require_once("../security/model.php");

    if (isset($_POST['action'])) {	    // check get and post
        $action = $_POST['action'];
    } else if (isset($_GET['action'])) {
        $action = $_GET['action'];
    } else {
        $action = "SecurityHome";	// Default action that guest is authorized to use.
    }
    if ($action != 'SecurityLogin' && $action != 'ProcessLogin' && $action != 'SecurityProcessLogin' && !userIsAuthorized($action)) {
        echo 'First If';
        if(!loggedIn()) {
            print_r('not logged in');
            print_r($_SESSION);
            #header("Location:../security/index.php?action=SecurityLogin&RequestedPage=" . urlencode($_SERVER['REQUEST_URI']));
            header("Location: https://vcisprod.clarion.edu/~s_ajrobinso1/php-saml-2.19.1/demo1");
        } else {
            include('../security/not_authorized.html');
        }
    } else {
        switch ($action) {
            case 'ProcessLogin':
                ProcessSSOLogin();
                break;
            case 'SecurityLogin':
                if (!isset($_SERVER['HTTPS'])) {
                    $url = 'https://' . $_SERVER['HTTP_HOST'] .
                        $_SERVER['REQUEST_URI'];
                    header("Location: " . $url);
                    exit();
                }
                include('login_form.php');
                header("Location: https://vcisprod.clarion.edu/~s_ajrobinso1/php-saml-2.19.1/demo1");
                break;
            case 'SecurityChangeUserLevel':
                if (!isset($_SERVER['HTTPS'])) {
                    $url = 'https://' . $_SERVER['HTTP_HOST'] .
                        $_SERVER['REQUEST_URI'];
                    header("Location: " . $url);
                    exit();
                }
                include('login_form.php');
                break;
            case 'SecurityCheckUserNameExists':
                CheckSecurityNameExists();
                break;
            case 'SecurityProcessLogin':
                ProcessLogin();
                break;
            case 'SecurityLogOut':
                ProcessLogOut();
                break;
            case 'SecurityManageUsers':
                ManageUsers();
                break;
            case 'SecurityUserAdd':
                UserAdd();
                break;
            case 'SecurityUserEdit':
                UserEdit();
                break;
            case 'SecurityUserDelete':
                UserDelete();
                break;
            case 'SecurityProcessUserAddEdit':
                ProcessUserAddEdit();
                break;
            case 'SecurityManageFunctions':
                ManageFunctions();
                break;
            case 'SecurityFunctionAdd':
                FunctionAdd();
                break;
            case 'SecurityFunctionEdit':
                FunctionEdit();
                break;
            case 'SecurityFunctionDelete':
                FunctionDelete();
                break;
            case 'SecurityProcessFunctionAddEdit':
                ProcessFunctionAddEdit();
                break;
            case 'SecurityManageRoles':
                ManageRoles();
                break;
            case 'SecurityRoleAdd':
                RoleAdd();
                break;
            case 'SecurityRoleEdit':
                RoleEdit();
                break;
            case 'SecurityRoleDelete':
                RoleDelete();
                break;
            case 'SecurityProcessRoleAddEdit':
                ProcessRoleAddEdit();
                break;
            default:
                include('../security/control_panel_form.php');               // default action
        }
    }

    function ProcessSSOLogin(){
        print_r("POST");
        echo '<pre>';
        print_r($_POST);
        echo  '</pre>';
        $sUnderScore = $_POST['sUnderScore'];
        $firstName = $_POST['firstName'];
        $lastname = $_POST['lastName'];
        $email = $_POST['email'];
        $user = new user($sUnderScore,$firstName,$lastname,$email);
        processSignIn($user);
    }

    function ProcessLogin(){
        $username = $_POST["username"];
        $password = $_POST["password"];

        if(login($username,$password)){
            if (isset($_REQUEST["RequestedPage"])) {
                header("Location: https://" . $_SERVER['HTTP_HOST'] . $_REQUEST["RequestedPage"]);
            } else {
                header("Location:../security/index.php");
            }
        } else {
            header("Location:../security/index.php?action=SecurityLogin&LoginFailure&RequestedPage=" . urlencode($_POST["RequestedPage"]));
        }
    }

    function CheckSecurityNameExists() {
        $username = $_GET['username'];
        $dupFound = FALSE;
        $id = 0;

        $row = getUserByUsername($username);
        if ($row) {
                $dupFound = TRUE;
                $id = $row['UserID'];
        }

        echo json_encode(array('id'=>$id, 'username'=>$username, 'dupeFound'=>$dupFound));
    }

    function ProcessLogOut() {
        logOut();
        if (isset($_REQUEST["RequestedPage"])) {
                header("Location:" . $_REQUEST["RequestedPage"]);
        } else {
                header("Location:../security/index.php");
        }
    }
    function ManageUsers() {
        $results = getAllUsers();
        include('../security/manage_users_form.php');
    }
    function UserAdd() {
        include('../security/add_user_form.php');
    }
    function UserEdit() {
        $id = $_GET["id"];
        if (empty($id)) {
            displayError("An ID is required for this function.");
        } else {
            $row = getUser($id);
            if ($row == false) {
                    displayError("<p>User ID is not on file.</p> ");
            } else {
                    $hasAttrResults = getUserRoles($id);
                    $hasNotAttrResults = getNotUserRoles($id);
                    $userID = $row["UserID"];
                    $firstName = $row["FirstName"];
                    $lastName = $row["LastName"];
                    $userName = $row["UserName"];
                    $email = $row["Email"];
                    include('../security/modify_user_form.php');
            }
        }
    }
    function UserDelete() {
        if(isset($_POST["numListed"]))
        {
            $numListed = $_POST["numListed"];
            for($i = 0; $i < $numListed; ++$i)
            {
                if(isset($_POST["record$i"]))
                {
                    deleteUser($_POST["record$i"]);
                }
            }
        }
        $results = getAllUsers();
        include('../security/manage_users_form.php');
    }
    function ProcessUserAddEdit() {
        $errors = "";

        if(empty($_POST["FirstName"]))
                        $errors .= "<li>Error, field \"First Name\" is blank.</li>";
        if(empty($_POST["LastName"]))
                        $errors .= "<li>Error, field \"Last Name\" is blank.</li>";
        if(empty($_POST["UserName"]))
                        $errors .= "<li>Error, field \"User Name\" is blank.</li>";
        if(empty($_POST["Email"]))
                        $errors .= "<li>Error, field \"Email\" is blank.</li>";

        if($errors == "") {
            $UserID = $_POST["UserID"];
            $firstName = $_POST["FirstName"];
            $lastName = $_POST["LastName"];
            $userName = $_POST["UserName"];
            $password = $_POST["Password"];
            $email = $_POST["Email"];
            if (empty($UserID)) {   // No UserID means we are processing an ADD
                    $UserID = addUser($firstName, $lastName, $userName, $password, $email);
            } else {
                    $hasAttributes = $_POST["hasAttributes"];
                    updateUser($UserID, $firstName, $lastName, $userName, $password, $email, $hasAttributes);
            }
            $results = getAllUsers();
            include('../security/manage_users_form.php');
        } else {
            displayError($errors);
        }
    }

    function ManageFunctions() {
        $results = getAllFunctions();
        include('../security/manage_functions_form.php');
    }
    function FunctionAdd() {
        include('../security/add_function_form.php');
    }
    function FunctionEdit() {
        $id = $_GET["id"];
        if (empty($id)) {
            displayError("An ID is required for this function.");
        } else {
            $row = getFunction($id);
            if ($row == false) {
                displayError("<p>Function ID is not on file.</p> ");
            } else {
                $id = $row["FunctionID"];
                $name = $row["Name"];
                $desc = $row["Description"];
                include('../security/modify_function_form.php');
            }
        }
    }
    function FunctionDelete() {
        if(isset($_POST["numListed"]))
        {
            $numListed = $_POST["numListed"];
            for($i = 0; $i < $numListed; ++$i)
            {
                if(isset($_POST["record$i"]))
                {
                        deleteFunction($_POST["record$i"]);
                }
            }
        }
        $results = getAllFunctions();
        include('../security/manage_functions_form.php');
    }
    function ProcessFunctionAddEdit() {
        $errors = "";

        if(empty($_POST["Name"]))
            $errors .= "<li>Error, field \"Name\" is blank.</li>";

        if($errors == "") {
            $FunctionID = $_POST["FunctionID"];
            $name = $_POST["Name"];
            $desc = $_POST["Description"];
            if (empty($FunctionID)) {   // No FunctionID means we are processing an ADD
                    $FunctionID = addFunction($name, $desc);
            } else {
                    updateFunction($FunctionID, $name, $desc);
            }
            $results = getAllFunctions();
            include('../security/manage_functions_form.php');
        } else {
            displayError($errors);
        }
    }

    function ManageRoles() {
        $results = getAllRoles();
        include('../security/manage_roles_form.php');
    }
    function RoleAdd() {
        include('../security/add_role_form.php');
    }
    function RoleEdit() {
        $id = $_GET["id"];
        if (empty($id)) {
            displayError("An ID is required for this function.");
        } else {
            $row = getRole($id);
            if ($row == false) {
                displayError("<p>Role ID is not on file.</p> ");
            } else {
                $hasAttrResults = getRoleFunctions($id);
                $hasNotAttrResults = getNotRoleFunctions($id);
                $name = $row["Name"];
                $desc = $row["Description"];
                include('../security/modify_role_form.php');
            }
        }
    }
    function RoleDelete() {
        if(isset($_POST["numListed"]))
        {
            $numListed = $_POST["numListed"];
            for($i = 0; $i < $numListed; ++$i)
            {
                if(isset($_POST["record$i"]))
                {
                    deleteRole($_POST["record$i"]);
                }
            }
        }
        $results = getAllRoles();
        include('../security/manage_roles_form.php');
    }
    function ProcessRoleAddEdit() {
        $errors = "";
        if(empty($_POST["Name"]))
            $errors .= "<li>Error, field \"Name\" is blank.</li>";
        if($errors == "") {
            $RoleID = $_POST["RoleID"];
            $name = $_POST["Name"];
            $desc = $_POST["Description"];
            if (empty($RoleID)) {   // No RoleID means we are processing an ADD
                    $RoleID = addRole($name, $desc);
            } else {
                    $hasAttributes = $_POST["hasAttributes"];
                    updateRole($RoleID, $name, $desc, $hasAttributes);
            }
            $results = getAllRoles();
            include('../security/manage_roles_form.php');
        } else {
            displayError($errors);
        }
    }
?>


