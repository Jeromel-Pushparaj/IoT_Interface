<?php

${basename(__FILE__, '.php')} = function(){
    $header = getallheaders();
    
    if($this->get_request_method() == "POST" and isset($header['devicekey']) and isset($this->_request['state'])){
        if($this->isAuthenticated()){
            $devicekey = $header['devicekey'];
            if (Devicedata::getDevicedata()->isExists($devicekey)){
                try{
                    $data = Devicedata::getDevicedata()->readDeviceState($devicekey);
                    if($data != false){

                    $data = [
                        $this->_request['state'] => $data[$this->_request['state']],
                    ];

                    $this->response($this->json($data), 200);
                    }else{
                        $data = [
                            "error" => "can't able to fetch device state"
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