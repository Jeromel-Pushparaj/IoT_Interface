<?php

class Auth{
 
    private $db;
    private $validKey = false;
    private $deviceToken = null;
    private $deviceAuth;
    private $apiKey
    public function __construct($apiKey){
       $this->db = Database::getConnection();
       if($apiKey != NULL){
          if($apiKey == $this->$apiKey) {
            $this->validKey = true;
          }
       }else{
           throw new Exception("Missing Api Key");
       }
    }

    private function getApikey(){
        
    }
}