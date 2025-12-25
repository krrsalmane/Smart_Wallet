<?php
require __DIR__ . "/../config/sql.php";

class operation
{
    protected $db;
    protected float $amount;
    protected string $description;
    protected String  $my_date;

      public function __construct( $amount,  $description,  $my_date){
        $this->amount = $amount;
        $this->description = $description;
        $this->my_date = $my_date;
        $std = new Database();
        $this->db = $std->getConnection();
    }


    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    
    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getMyDate(): string
    {
        return $this->my_date;
    }

    public function setMyDate(string $my_date): void
    {
        $this->my_date = $my_date;
    }

}

?>