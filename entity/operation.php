<?php

class operation
{
    protected int $id;
    protected float $amount;
    protected string $description;
    protected String  $my_date;

     public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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