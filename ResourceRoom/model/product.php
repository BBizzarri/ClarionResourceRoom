<?php
class product
{
    //Properties
    public $ProductID;
    public $Name;
    public $Description;
    public $QtyOnHand;
    public $MaxOrderQty;
    public $OrderLimit;
    public $GoalStock;
    public $OnOrder;
    public $QTYAvailable;

    //Constructor
    function __construct($ProductID, $Name, $Description, $QtyOnHand, $MaxOrderQty,$OrderLimit, $GoalStock, $OnOrder, $QTYAvailable) {
        $this->ProductID = $ProductID;
        $this->Name = $Name;
        $this->Description = $Description;
        $this->QtyOnHand = $QtyOnHand;
        $this->MaxOrderQty = $MaxOrderQty;
        $this->OrderLimit = $OrderLimit;
        $this->GoalStock = $GoalStock;
        $this->OnOrder = $OnOrder;
        $this->QTYAvailable = $QTYAvailable;
    }
    //Destructor
    function __destruct() {

    }
    function getProductID(){
        return $this->ProductID;
    }
    function getProductName(){
        return $this->Name;
    }
    function  getProductDescription(){
        return $this->Description;
    }
    function  getProductQtyOnHand(){
        return $this->QtyOnHand;
    }
    function  getProductMaxOrderQty(){
        return $this->MaxOrderQty;
    }
    function getProductOrderLimit(){
        return $this->OrderLimit;
    }
    function getProductGoalStock(){
        return $this->GoalStock;
    }
    function getProductOnOrder(){
        return $this->OnOrder;
    }
    function getProductQTYAvailable(){
        return $this->QTYAvailable;
    }
    function setProductName($productName) {
        $this->Name = $productName;
    }
    function setProductDescription($productDescription) {
        $this->Description = $productDescription;
    }
    function setProductQtyOnHand($productQtyOnHand) {
        $this->QtyOnHand = $productQtyOnHand;
    }

    function setProductMaxOrderQty($productMaxOrderQty) {
        $this->MaxOrderQty = $productMaxOrderQty;
    }
    function setProductGoalStock($productGoalStock) {
        $this->GoalStock = $productGoalStock;
    }
    function setProductOnOrder($productOnOrder) {
        $this->OnOrder = $productOnOrder;
    }
    function setProductAvailable($productAvailable) {
        $this->QTYAvailable = $productAvailable;
    }




    function toString(){
        print_r($this);
    }
}
