<?php
require_once "Database.class.php";
include_once __DIR__ . "/../traits/SQLGetterSetter.trait.php";
include_once "DeviceKey.class.php";

class Device
{
    private $conn;
    public $table;
    public  $id;
    public $max_device_no;

  use SQLGetterSetter;
  public function __construct(){
    $this->conn = Database::getConnection();
    $this->table = 'devices';
    $this->id = Session::getUser()->getid(); 
    
  }

  public static function nextDeviceNo($auth_id){
    $conn = Database::getConnection();

    // Prepare the SQL query
    $sql = "SELECT COALESCE(MAX(deviceno), 0) AS max_device_no FROM devices WHERE id = $auth_id";

    // Execute the query
    $result = $conn->query($sql);

    // Fetch the result
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $max_device_no = $row['max_device_no'];
    return $max_device_no;
} else {
    return false;
}

// hello bro 
// Close the connection
    // $conn->close();
}

    public static function addDevice($dname, $desc, $button=0, $slider=0, $display=0, $indicator=0, $timer=0)
    {
        // print("addDevice Called");
        $conn = Database::getConnection();

        $auth_id = Session::getUser()->getid();
        $nextDeviceNo = Device::nextDeviceNo($auth_id);
        if(!$nextDeviceNo){
            $dno = 1;
        }else{
            $dno = $nextDeviceNo + 1;
        }

        $sql = $conn->prepare("INSERT INTO `devices` (`deviceno`,`devicename`, `button`, `slider`, `display`, `indicator`, `timer`,`desc`,`id`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);");

        if (!$sql) {
            return "Error in preparing SQL statement: " . $conn->error;
        }

        // Bind the parameters
        $sql->bind_param("sssssssss", $dno, $dname, $button, $slider, $display, $indicator, $timer, $desc, $auth_id);

        $error = false;
       
        try {
             
            // Execute the INSERT query
            if ($sql->execute() === true) {
                $result = Devicekey::getDevice_key()->generateDevicekey();
                if($result == false){
                    $error = false;
                }
                printf("Device created succesfully");
            } else {
                // Handle the duplicate entry error
                if ($conn->errno === 1062) {
                    $error = "Device can't to created";
                } else {
                    $error = $sql->error;
                }
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        $sql->close();
        $conn->close();
        return $error;
    }

    public static function  getDevice() {
        return new Device();
    }

    public static function deleteDevice($deviceno){
        $conn = Database::getConnection();
        $sql = $conn->prepare("DELETE FROM devices WHERE deviceno = $deviceno");

        if (!$sql) {
            return "Error in preparing SQL statement: " . $conn->error;
        }


        $error = false;
        try {
            // Execute the INSERT query
            if ($sql->execute() === true) {
                $error = false;
            } else {
                // Handle the duplicate entry error
                if ($conn->errno === 1062) {
                    $error = "Device can't to created";
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

    public static function numOfDevice(){
        $conn = Database::getConnection();
        $id = Session::getUser()->getid();
        $sql = "SELECT `devicename` FROM `devices` WHERE `id` = $id";
        $result = $conn->query($sql)->num_rows;
        return $result;
    }
    public static function deviceProperties($device){
        $conn = Database::getConnection();
        // $id = Session::getUser()->getid();

        $sql = "SELECT 'button' AS property
                FROM devices
                WHERE devicename = '$device' AND button = 1

                UNION ALL

                SELECT 'slider' AS property
                FROM devices
                WHERE devicename = '$device' AND slider = 1

                UNION ALL

                SELECT 'display' AS property
                FROM devices
                WHERE devicename = '$device' AND display = 1

                UNION ALL

                SELECT 'indicator' AS property
                FROM devices
                WHERE devicename = '$device' AND indicator = 1

                UNION ALL

                SELECT 'timer' AS property
                FROM devices
                WHERE devicename = '$device' AND timer = 1;";
        $properties = [];
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $properties[] = $row['property'];
        }
        return $properties;
    }

 
}
?>
