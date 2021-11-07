<?php
include_once 'cartItem.php';
class cart
{
    //Properties
    public $UserID;
    public $ProductsInCart;


    //Constructor
    function __construct($UserID, $ProductArray)
    {
        $this->UserID = $UserID;
        $this->ProductsInCart = $ProductArray;
    }

    //Destructor
    function __destruct()
    {

    }

    function getUserID()
    {
        return $this->UserID;
    }

    function getProductsInCart()
    {
        return $this->ProductsInCart;
    }

    function inCart($productID){
        foreach($this->ProductsInCart as $product){
            if($product->getProductObject()->getProductID() == $productID){
                return TRUE;
            }
        }
        return FALSE;
    }

    function getNumberOfItemsInCart(){
        return sizeof($this->ProductsInCart);
    }
    function toString()
    {
        print_r($this);
    }
}