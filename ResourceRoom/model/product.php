<?php
class product
{
    //Properties
    public $ProductID;
    public $Name;
    public $Description;
    public $QtyOnHand;
    public $MaxOrderQty;
    public $GoalStock;
    public $OnOrder;
    public $Available;

    //Constructor
    function __construct($ProductID, $Name, $Description, $QtyOnHand, $MaxOrderQty, $GoalStock, $OnOrder, $Available) {
        $this->ProductID = $ProductID;
        $this->Name = $Name;
        $this->Description = $Description;
        $this->QtyOnHand = $QtyOnHand;
        $this->MaxOrderQty = $MaxOrderQty;
        $this->GoalStock = $GoalStock;
        $this->OnOrder = $OnOrder;
        $this->Available = $Available;
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
    function getProductGoalStock(){
        return $this->GoalStock;
    }
    function getProductOnOrder(){
        return $this->OnOrder;
    }
    function getProductAvailable(){
        return $this->Available;
    }

    function toString(){
        print_r($this);
    }
}
