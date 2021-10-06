<?php
class cartItem
{
    //Properties
    public $Product;
    public $QTYRequested;
    public $MostRecentDate;



    //Constructor
    function __construct($Product,$QTYRequested,$MostRecentDate)
    {
        $this->Product = $Product;
        $this->MostRecentDate = $MostRecentDate;
        $this->QTYRequested = $QTYRequested;
    }

    //Destructor
    function __destruct()
    {

    }


    function getProductObject()
    {
        return $this->Product;
    }
    function getMostRecentDate()
    {
        return $this->MostRecentDate;
    }
    function getQTYRequested(){
        return $this->QTYRequested;
    }


    function toString()
    {
        print_r($this);
    }
}