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
    $this->table = 'api_keys';
    $this->id = Session::getUser()->getid(); 
  }
  public static function getApikey(){
    return new ApiKey();
  }
  public function createKey(){
    return bin2hex(random_bytes(64));
  }

  public function generateKey(){
    //TODO: ALso Implement One user = one key; it avoids multiple key generation;
   $sql = $this->conn->prepare("INSERT INTO `api_keys` (`id`,`key`, `status`,  `last_used`)
        VALUES (?, ?, ?, ?);");

    
  if (!$sql) {
    return "Error in preparing SQL statement: " . $this->conn->error;
  }
  //TODO: Implement the updating last used of the api time & Status by if is in active only we can view the api key in interface
  $key = $this->createKey();
  $status = 'active';
  $last_used = NULL;
          // Bind the parameters
        $sql->bind_param("ssss", $this->id, $key, $status, $last_used);

        // The bind_param statement
        $sql->bind_param("ssss", $this->id, $key, $status, $last_used);


        $error = false;
        try {
            // Execute the INSERT query
            if ($sql->execute() === true) {
                $error = false;
                // printf("apikey created succesfully");
            } else {
                // Handle the duplicate entry error
                if ($this->conn->errno === 1062) {
                    $error = "api_key can't to created";
                } else {
                    $error = $sql->error;
                }
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        $sql->close();
        return $error;
  }


}