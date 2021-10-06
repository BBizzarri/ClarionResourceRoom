<?php
class category
{
    //Properties
    public $CategoryID;
    public $Description;

    //Constructor
    function __construct($CategoryID, $Description) {
        $this->CategoryID = $CategoryID;
        $this->Description = $Description;
    }
    //Destructor
    function __destruct() {

    }
    function getCategoryID(){
        return $this->CategoryID;
    }
    function  getCategoryDescription(){
        return $this->Description;
    }
    function setCategoryDescription($productCategory) {
        $this->ProductCategory = $productCategory;
    }

    function toString(){
        print_r($this);
    }
}