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



}
?>