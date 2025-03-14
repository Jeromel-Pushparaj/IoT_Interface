<?php

${basename(__FILE__, '.php')} = function(){
    $header = getallheaders();
    
    if($this->get_request_method() == "POST" and isset($header['devicekey']) and (isset($this->_request['buttonstate']) or isset($this->_request['indicator']) or isset($this->_request['display']) or isset($this->_request['slidervalue']) or isset($this->_request['timer']))){
        
        $devicekey = $header['devicekey'];
        $rpropertyToUpdate = '';
        $value = NULL;
        $propertyToUpdate = ['buttonstate', 'indicator','display', 'slidervalue', 'timer'];

        if (Devicedata::getDevicedata()->isExists($devicekey)){
            try{
                for($i=0;$i<count($propertyToUpdate); $i++){
                    if(array_key_exists($propertyToUpdate[$i], $this->_request)){
                        $rpropertyToUpdate = $propertyToUpdate[$i];
                        $value = $this->_request[$rpropertyToUpdate];
                        break;
                    }
                }

                // Update device state and updated_at timestamp
                $result = Devicedata::getDevicedata()->updateDeviceState($devicekey, $rpropertyToUpdate, $value);
                if($result == true){
                    $updatedAt = date("Y-m-d H:i:s"); // Store the update timestamp
                    Devicedata::getDevicedata()->updateDeviceState($devicekey, "updated_at", $updatedAt);

                    $data = [
                        $rpropertyToUpdate => $this->_request[$rpropertyToUpdate],
                        "updated_at" => $updatedAt,
                        "message" => "device state updated successfully"
                    ];
                    $this->response($this->json($data), 200);
                }else{
                    $data = [
                        "error" => "device state can't be updated"
                    ];
                    $this->response($this->json($data), 406);                      
                }
            }catch(Exception $e){
                $data = ["error" => $e->getMessage()];
                $this->response($this->json($data), 406);
            }
        }else{
            $data = [
                "error" => "Conflict",
                "message" => "device not found"
            ];
            $this->response($this->json($data), 409); 
        }
    }else{
        $data = [
            "error" => "Bad request from device"
        ];
        $this->response($this->json($data), 400);
    }
};
