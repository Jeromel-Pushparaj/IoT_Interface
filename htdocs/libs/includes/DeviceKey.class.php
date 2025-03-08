<?php
require_once "Database.class.php";
include_once "Device.class.php";
include_once __DIR__ . "/../traits/SQLGetterSetter.trait.php";

class Devicekey
{
    private $conn;
    public $table;
    public  $id;
    public $deviceid;

  use SQLGetterSetter;
  public function __construct(){
    $this->conn = Database::getConnection();
    $this->table = 'device_keys';
    $this->id = Session::getUser()->getid(); 
    $this->deviceid = Device::getDevice()->getdeviceid();
  }

  public static function getDevice_key(){
    return new Devicekey();
  }

    public function createDeviceKey(){
        return bin2hex(random_bytes(32));
    }

    public function generateDevicekey(){
        $sql = $this->conn->prepare("INSERT INTO `device_keys` (`id`,`deviceid`, `devicekey`)
        VALUES (?, ?, ?);");
        if (!$sql) {
            return "Error in preparing SQL statement: " . $this->conn->error;
        }
        $deviceid = Device::getDevice()->getdeviceid();
        $devicekey = Devicekey::getDevice_key()->createDeviceKey();
        if(is_array($deviceid)){
            $deviceid = $deviceid[count($deviceid) - 1];
        }
        

        // Bind the parameters
        $sql->bind_param("sss", $this->id, $deviceid, $devicekey);


        $error = false;
        try {
            // Execute the INSERT query
            if ($sql->execute() === true) {
                $error = false;
                printf("Device key succesfully");
            } else {
                // Handle the duplicate entry error
                if ($this->conn->errno === 1062) {
                    $error = "Device key can't to created";
                } else {
                    $error = $sql->error;
                }
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            throw new Exception($e->getMessage());
        }

        $sql->close();
        return $error;

    }

    public function getkey($deviceid){
        $sql = "SELECT `devicekey` FROM `$this->table` WHERE `deviceid` = $deviceid;";
        $result = $this->conn->query($sql);
        if($result and $result->num_rows == 1){
            return $result->fetch_assoc()["devicekey"];
        }
         

    }


}