<?php

    function getDBConnection() {
        $dsn = 'mysql:host=localhost;dbname=resourceroom';
        $username = 'cis411';
        $password = 'cis411';

        try {
                $db = new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
                $errorMessage = $e->getMessage();
                include '../view/errorPage.php';
                die;
        }
        return $db;
    }

    function logSQLError($errorInfo) {
        $errorMessage = $errorInfo[2];
        if (isset($_SESSION["UserName"])) {
            $username = $_SESSION["UserName"];
            $userID = $_SESSION["UserID"];
        } else {
            $userID = 0;
            $username = "unknown";
        }

        $db = getDBConnection();
        $query = 'INSERT INTO errorlog (UserID, UserName, ErrorMessage)
                VALUES (:UserID, :UserName, :ErrorMessage)';
        $statement = $db->prepare($query);

        $statement->bindValue(':UserName', $username);
        $statement->bindValue(':UserID', $userID);
        $statement->bindValue(':ErrorMessage', $errorMessage);

        $success = $statement->execute();
        $statement->closeCursor();            
        include '../view/errorPage.php';
    }
?>