<?php
/**
 *  SAML Handler
 */

session_start();
require_once dirname(__DIR__).'/_toolkit_loader.php';

require_once 'settings.php';
require_once 'advanced_settings_example.php';
require_once  '../../../model/model.php';


$auth = new OneLogin_Saml2_Auth($settingsInfo);

if (isset($_GET['sso'])) {
    $auth->login();

    # If AuthNRequest ID need to be saved in order to later validate it, do instead
    # $ssoBuiltUrl = $auth->login(null, array(), false, false, true);
    # $_SESSION['AuthNRequestID'] = $auth->getLastRequestID();
    # header('Pragma: no-cache');
    # header('Cache-Control: no-cache, must-revalidate');
    # header('Location: ' . $ssoBuiltUrl);
    # exit();

} else if (isset($_GET['sso2'])) {
    $returnTo = $spBaseUrl.'demo1/attrs.php';
    $auth->login($returnTo);
} else if (isset($_GET['slo'])) {
    $returnTo = null;
    $parameters = array();
    $nameId = null;
    $sessionIndex = null;
    $nameIdFormat = null;
    $samlNameIdNameQualifier = null;
    $samlNameIdSPNameQualifier = null;

    if (isset($_SESSION['samlNameId'])) {
        $nameId = $_SESSION['samlNameId'];
    }
    if (isset($_SESSION['samlNameIdFormat'])) {
        $nameIdFormat = $_SESSION['samlNameIdFormat'];
    }
    if (isset($_SESSION['samlNameIdNameQualifier'])) {
        $nameIdNameQualifier = $_SESSION['samlNameIdNameQualifier'];
    }
    if (isset($_SESSION['samlNameIdSPNameQualifier'])) {
        $nameIdSPNameQualifier = $_SESSION['samlNameIdSPNameQualifier'];
    }
    if (isset($_SESSION['samlSessionIndex'])) {
        $sessionIndex = $_SESSION['samlSessionIndex'];
    }
    session_destroy();
    #Uncomment later if Virgil fixes
    #$auth->logout($returnTo, $parameters, $nameId, $sessionIndex, false, $nameIdFormat, $nameIdNameQualifier, $nameIdSPNameQualifier);

    # If LogoutRequest ID need to be saved in order to later validate it, do instead
    # $sloBuiltUrl = $auth->logout(null, $paramters, $nameId, $sessionIndex, true);
    # $_SESSION['LogoutRequestID'] = $auth->getLastRequestID();
    # header('Pragma: no-cache');
    # header('Cache-Control: no-cache, must-revalidate');
    # header('Location: ' . $sloBuiltUrl);
    # exit();

} else if (isset($_GET['acs'])) {
    if (isset($_SESSION) && isset($_SESSION['AuthNRequestID'])) {
        $requestID = $_SESSION['AuthNRequestID'];
    } else {
        $requestID = null;
    }

    $auth->processResponse($requestID);

    $errors = $auth->getErrors();

    if (!empty($errors)) {
        echo '<p>',implode(', ', $errors),'</p>';
        if ($auth->getSettings()->isDebugActive()) {
            echo '<p>'.$auth->getLastErrorReason().'</p>';
        }
    }

    if (!$auth->isAuthenticated()) {
        #echo '<p>'.$auth->getAttributes().'</p>';
        echo "<p>Not authenticated pickles</p>";
        exit();
    }

    $_SESSION['samlUserdata'] = $auth->getAttributes();
    $_SESSION['samlNameId'] = $auth->getNameId();
    $_SESSION['samlNameIdFormat'] = $auth->getNameIdFormat();
    $_SESSION['samlNameIdNameQualifier'] = $auth->getNameIdNameQualifier();
    $_SESSION['samlNameIdSPNameQualifier'] = $auth->getNameIdSPNameQualifier();
    $_SESSION['samlSessionIndex'] = $auth->getSessionIndex();
    unset($_SESSION['AuthNRequestID']);
    if (isset($_POST['RelayState']) && OneLogin_Saml2_Utils::getSelfURL() != $_POST['RelayState']) {
        $auth->redirectTo($_POST['RelayState']);
    }
} else if (isset($_GET['sls'])) {
    if (isset($_SESSION) && isset($_SESSION['LogoutRequestID'])) {
        $requestID = $_SESSION['LogoutRequestID'];
    } else {
        $requestID = null;
    }

    $auth->processSLO(false, $requestID);
    $errors = $auth->getErrors();
    if (empty($errors)) {
        echo '<p>Sucessfully logged out</p>';
    } else {
        echo '<p>', implode(', ', $errors), '</p>';
        if ($auth->getSettings()->isDebugActive()) {
            echo '<p>'.$auth->getLastErrorReason().'</p>';
        }
    }
}

if (isset($_SESSION['samlUserdata'])) {
    if (!empty($_SESSION['samlUserdata'])) {
        $attributes = $_SESSION['samlUserdata'];
        $userData = [];
        foreach($attributes as $attr){
            array_push($userData,$attr[0]);
        }
        $user = [$attributes['urn:oid:1.2.840.113556.1.4.221'][0],$attributes['urn:oid:2.5.4.42'][0],$attributes['urn:oid:2.5.4.4'][0],$attributes['urn:oid:0.9.2342.19200300.100.1.3'][0]];
        $_SESSION['user'] = $user;
        #$url = 'https://vcisprod.clarion.edu/~s_smwice/ClarionResourceRoom/ResourceRoom';
        #header("Location: $url");
        #echo '<pre>';
        #print_r($_SESSION);
        #echo  '</pre>';
        echo    "<form action='https://vcisprod.clarion.edu/~s_smwice/ClarionResourceRoom/ResourceRoom/security/index.php' method='post' enctype='multipart/form-data'>";
        echo    "<input type='hidden' name='action' value='ProcessLogin'/>";
        echo    "<input type='hidden' name='sUnderScore' value='{$user[0]}'/>";
        echo    "<input type='hidden' name='firstName' value='{$user[1]}'/>";
        echo    "<input type='hidden' name='lastName' value='{$user[2]}'/>";
        echo    "<input type='hidden' name='email' value='{$user[3]}'/>";
        echo    "<input type='submit' value='Go to remote'/>";
        echo    "</form>";
        echo    "<form action='https://localhost/ClarionResourceRoom/ResourceRoom/security/index.php' method='post' enctype='multipart/form-data'>";
        echo    "<input type='hidden' name='action' value='ProcessLogin'/>";
        echo    "<input type='hidden' name='sUnderScore' value='{$user[0]}'/>";
        echo    "<input type='hidden' name='firstName' value='{$user[1]}'/>";
        echo    "<input type='hidden' name='lastName' value='{$user[2]}'/>";
        echo    "<input type='hidden' name='email' value='{$user[3]}'/>";
        echo    "<input type='submit' value='Go to local'/>";
        echo    "</form>";
    } else {
        echo "<p>You don't have any attribute</p>";
    }
    echo '<p><a href="?slo" >Logout</a></p>';
} else {
    echo '<p><a href="?sso" >Login</a></p>';
    echo '<p><a href="?sso2" >Login and access to attrs.php page</a></p>';
}
