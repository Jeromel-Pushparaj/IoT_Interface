<?php

${basename(__FILE__, '.php')} = function(){
    $header = getallheaders();
    
    if($this->get_request_method() == "POST" and isset($header['devicekey']) and (isset($this->_request['buttonstate']) or isset($this->_request['indicator']) or isset($this->_request['display']) or isset($this->_request['slidervalue']) or isset($this->_request['timer']))){
        if($this->isAuthenticated()){
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
                    $result = Devicedata::getDevicedata()->updateDeviceState($devicekey, $rpropertyToUpdate, $value);
                    if($result == true){

                    $data = [
                        $rpropertyToUpdate => $this->_request[$rpropertyToUpdate],
                        "message" => "device state updated successfully"
                    ];

                    $this->response($this->json($data), 200);
                    }else{
                        $data = [
                            "error" => "device state can't able to update"
                        ];
                        $this->response($this->json($data), 406);                      
                    }
                }catch(Exception $e){
                    //TODO: Change this $e->getMessage() to normal msg in string
                    $data = [
                        "error" => $e->getMessage()
                    ];
                    $this->response($this->json($data), 406);
                }
            }else{
                $data = [
                    "error" => "Conflict",
                    "message" => "device not found"
                ];
                $data = $this->json($data);
                $this->response($data, 409); 
            }
        }
    }else{
        $data = [
            "error" => "Bad request from device"
        ];
        $data = $this->json($data);
        $this->response($data, 400);
    }

    
};