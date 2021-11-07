<?php
include_once 'product.php';
class orderDetail
{
    //Properties
    public $OrderID;
    public $Product;
    public $QTYRequested;
    public $QTYFilled;


    //Constructor
    function __construct($OrderID, $Product, $QTYRequested, $QTYFilled)
    {
        $this->OrderID = $OrderID;
        $this->Product = $Product;
        $this->QTYRequested = $QTYRequested;
        $this->QTYFilled = $QTYFilled;
    }

    function getOrderID(){
        return $this->OrderID;
    }
    function getProductID(){
        return $this->Product->getProductID();
    }
    function getProduct(){
        return $this->Product;
    }
    function getQTYRequested(){
        return $this->QTYRequested;
    }
    function getQTYFilled(){
        return $this->QTYFilled;
    }

    //Destructor
    function __destruct()
    {

    }

    function toString()
    {
        print_r($this);
    }
}