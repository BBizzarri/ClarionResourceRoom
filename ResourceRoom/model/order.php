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
    public $UsersName;

    //Constructor
    public function __construct($OrderID, $UserID, $Status, $DateOrdered, $DateFilled, $DateCompleted, $Comment, $OrderDetails, $UsersName)
    {
        $this->OrderID = $OrderID;
        $this->UserID = $UserID;
        $this->Status = $Status;
        $this->DateOrdered = $DateOrdered;
        $this->DateFilled = $DateFilled;
        $this->DateCompleted = $DateCompleted;
        $this->Comment = $Comment;
        $this->OrderDetails = $OrderDetails;
        $this->UsersName = $UsersName;
    }

    //Destructor
    function __destruct()
    {

    }

    function addOrderDetails($orderDetail){
        array_push($this->OrderDetails,$orderDetail);
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
    function getUsersName(){
        return $this->UsersName;
    }

    function toString()
    {
        print_r($this);
    }
}