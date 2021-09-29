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

    function getAllCategories() {
        try {
            $db = getDBConnection();
            $query = "select * from category order by DESCRIPTION";
            $statement = $db->prepare($query);
            $statement->execute();
            $results = $statement->fetchAll();
            $statement->closeCursor();
            return $results;           // Assoc Array of Rows
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();
            include '../view/errorPage.php';
            die;
        }
    }

    function getAllProducts() {
        try {
            $db = getDBConnection();
            $query = "select * from product order by NAME";
            $statement = $db->prepare($query);
            $statement->execute();
            $results = $statement->fetchAll();
            $statement->closeCursor();
            return $results;           // Assoc Array of Rows
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();
            include '../view/errorPage.php';
            die;
        }
    }


    function getByGeneralSearch($criteria) {
        try {
            $db = getDBConnection();
            $query = 'SELECT *
                            FROM product
                            WHERE NAME LIKE :criteria OR 
                            DESCRIPTION LIKE :criteria
                            ORDER BY NAME';
            $statement = $db->prepare($query);
            $statement->bindValue(':criteria', "%$criteria%");
            $statement->execute();
            $results = $statement->fetchAll();
            $statement->closeCursor();
            return $results;           // Assoc Array of Rows
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();
            include '../view/errorPage.php';
            die;
        }
    }


?>