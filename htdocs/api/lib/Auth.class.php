<?php
require_once($_SERVER['DOCUMENT_ROOT']."/libs/includes/ApiKey.class.php");
class Auth{
 
    private $conn;
    private $validKey = false;
    private $deviceToken = null;
    private $deviceAuth;
    private $receivedKey;
    public function __construct($apiKey){
       $this->conn = Database::getConnection();
       $this->receivedKey = $apiKey;
    }

    public function Authenticate(){
        if($this->getApikey() != null){
        if(hash_equals($this->getApikey(), user_string: $this->receivedKey)){
            $this->validKey = true;
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
    }

    private function getApikey(){
        $sql = "SELECT `key` FROM `api_keys` WHERE `key` = '$this->receivedKey';";
        $result = $this->conn->query($sql);
        if($result and $result->num_rows == 1){
            return $result->fetch_assoc()["key"];
        }
         
    }
}