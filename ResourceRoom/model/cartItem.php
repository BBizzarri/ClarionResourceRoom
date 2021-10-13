<?php
class cartItem
{
    //Properties
    public $Product;
    public $QTYRequested;



    //Constructor
    function __construct($Product,$QTYRequested)
    {
        $this->Product = $Product;
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
    function getQTYRequested(){
        return $this->QTYRequested;
    }


    function toString()
    {
        print_r($this);
    }
}