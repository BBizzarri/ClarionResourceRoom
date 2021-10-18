<?php
include_once 'orderDetail.php';
class order
{
    //Properties
    public $OrderID;
    public $UserID;
    public $Status;
    public $DateOrdered;
    public $DateFilled;
    public $DateCompleted;
    public $Comment;
    public $OrderDetails;

    //Constructor
    public function __construct($OrderID, $UserID, $Status, $DateOrdered, $DateFilled, $DateCompleted, $Comment, $OrderDetails)
    {
        $this->OrderID = $OrderID;
        $this->UserID = $UserID;
        $this->Status = $Status;
        $this->DateOrdered = $DateOrdered;
        $this->DateFilled = $DateFilled;
        $this->DateCompleted = $DateCompleted;
        $this->Comment = $Comment;
        $this->OrderDetails = $OrderDetails;
    }

    //Destructor
    function __destruct()
    {

    }


    function getOrderID()
    {
        return $this->OrderID;
    }
    function getUserID()
    {
        return $this->UserID;
    }
    function getOrderStatus()
    {
        return $this->Status;
    }
    function getOrderDateOrdered(){
        return $this->DateOrdered;
    }
    function getOrderDateFilled(){
        return $this->DateFilled;
    }
    function getOrderDateCompleted(){
        return $this->DateCompleted;
    }
    function getOrderComment(){
        return $this->Comment;
    }
    function getOrderDetails(){
        return $this->OrderDetails;
    }
    function getOrderSize(){
        return sizeof($this->OrderDetails);
    }








    function toString()
    {
        print_r($this);
    }
}