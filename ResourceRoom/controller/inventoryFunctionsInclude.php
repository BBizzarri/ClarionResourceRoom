<?php

    // This file is included in the main controller as a series of functions.  All code should be within function scope.
    // The purpose is to separate the shopper actions from the back-end inventory actions to help version control.  These
    // functions should all be called from code in the inventoryCasesInclude.php file
    function getDatabaseConnection()
    {
        $dsn  = 'mysql:host=localhost;dbname=resourceroom';
        $username = 'cis411';
        $password = 'cis411';

        try
        {
            $db = new PDO($dsn, $username, $password);
        }
        catch (Exception $ex) {
            $errorMessage = $ex->getMessage();
            include '../view/errorPage.php';
            die;
        }
        return $db;
    }

   function getCategory($categoryID)
       {
           try{
               $db = getDatabaseConnection();
               $query = "SELECT * FROM category
                        WHERE CATEGORYID = :categoryID ";
               $statement = $db->prepare($query);
               $statement->bindValue(":categoryID", $categoryID);
               $statement->execute();
               $result = $statement->fetch();
               //$result['CustomerImagePath'] = checkCustomerImagePath($customerID);
               $statement->closeCursor();
               return $result;
           }
           catch (Exception $ex)
           {
               $errorMessage = $e->getMessage();
               include '../view/errorPage.php';
               die;
           }
       }

?>