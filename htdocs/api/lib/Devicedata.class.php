<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/libs/includes/DeviceKey.class.php');
class Devicedata {

    public $mongoconn;

    public $conn;
    public $data;
    
    public $deviceid;
    public $devicekey;
    public $collection;
    

    public function __construct(){
       $this->mongoconn = Database::getMongoConnection();
       $this->conn = Database::getConnection();
       $this->collection = $this->mongoconn->deviceEntry;
    }

    public static function getDevicedata(){
        return new Devicedata();
    }

    public function validateDevice($rdevicekey){
        $device = Devicekey::getDevice_key()->getkeywithkey($rdevicekey);
        
        if(isset($device[1])){
            $this->deviceid = $device[0];
            $this->devicekey = $device[1];
            return true;
        }else{
            return false;
        }
    }
    
    public function isExists($rdevicekey){
        $existDoccument = $this->collection->findOne(["devicekey" => $rdevicekey]);
        return !$existDoccument ? false : true;
    }

    public function addDeviceEntry($rdevicekey){
        if($this->validateDevice($rdevicekey)){
            $deviceEntery = [
                "devicekey" => "$this->devicekey",
            ];
            $properties = Device::deviceProperties($this->deviceid);
            in_array("button", $properties) ? $deviceEntery["buttonstate"] = 0 : null;
            in_array("slider", $properties) ? $deviceEntery["slidervalue"] = 0 : null;
            in_array("indicator", $properties) ? $deviceEntery["indicator"] = 0 : null ;
            in_array("timer", $properties) ? $deviceEntery["timer"] = 0 : null;
            in_array("display", $properties) ? $deviceEntery["display"] = "" : null;
            try{
                $insertedDevice = $this->collection->insertOne($deviceEntery);
                return $deviceEntery;
            }catch(Exception $e){
                throw new Exception("Error inserting DataEntry".$e->getMessage());
            }
        }else{
            return false;
        }
    }

    //TODO: Sanities the value before updatnig - Now it is not sanitied and in vulnerable state
    public function updateDeviceState($rdevicekey, $propertyToUpdatae, $value){
        if($this->validateDevice($rdevicekey)){
            $this->collection->updateOne(['devicekey' => $rdevicekey], 
            [
                '$set' => [
                    $propertyToUpdatae => $value,
                    "updated_at" => date("Y-m-d H:i:s")
                ],
            
            ]);
            return true;
        }else{
            return false;
        }
    }

    public function readDeviceState($rdevicekey){
        if($this->validateDevice($rdevicekey)){
            $state = $this->collection->findOne(["devicekey" => $rdevicekey]);
            if(isset($state)){
                return $state;
            }else{
                return false;
            }
        }else{
            return false;
        }
        
    }
}