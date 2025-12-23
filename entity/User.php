<?php 

 class User {

  private int $id;
  private string $full_Name;
  private string $email;
  private string $password;

public function getId():int{
    return $this -> id;}

public function setId (int $id):void{
    $this -> id = $id;
 } 
 public function getFull_Name():string{
    return $this -> full_Name;
 }
public function setFull_Name(string $full_Name):void{
    $this -> full_Name  = $full_Name;
}
public function getEmail():string{
    return $this -> email;
}
public function setEmail(string $email):void{
    $this -> email = $email;
}
public function getPassword():string{
    return $this -> password;
}
}
?>