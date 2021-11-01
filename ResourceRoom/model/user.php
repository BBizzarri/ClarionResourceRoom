<?php
class user
{
    //Properties
    public $sUnderScore;
    public $FirstName;
    public $LastName;
    public $Email;


    //Constructor
    function __construct($sUnderScore, $FirstName,$LastName,$Email)
    {
        $this->sUnderScore = $sUnderScore;
        $this->FirstName = $FirstName;
        $this->LastName = $LastName;
        $this->Email = $Email;
    }

    function getsUnderScore(){
        return $this->sUnderScore;
    }
    function getFirstName(){
        return $this->FirstName;
    }
    function getLastName(){
        return$this->LastName;
    }
    function getEmail(){
        return $this->Email;
    }

    //Destructor
    function __destruct()
    {

    }


    function toString()
    {
        echo '<pre>';
        print_r($this->getsUnderScore());
        print_r($this->getFirstName());
        print_r($this->getLastName());
        print_r($this->getEmail());
        echo  '</pre>';
    }
}