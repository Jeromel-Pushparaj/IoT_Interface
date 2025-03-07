<?php
require_once "Database.class.php";
include_once __DIR__ . "/../traits/SQLGetterSetter.trait.php";

class ApiKey{
    private $conn;
    public $table;
    public  $id;

  use SQLGetterSetter;
  public function __construct(){
    $this->conn = Database::getConnection();
    $this->table = 'auth';
    $this->id = Session::getUser()->getid(); 
  }
}