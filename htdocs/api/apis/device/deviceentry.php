<?php

${basename(__FILE__, '.php')} = function(){
    $header = getallheaders();
    
    if($this->get_request_method() == "POST" and isset($header['devicekey'])){
        if($this->isAuthenticated()){
            $devicekey = $header['devicekey'];
            if (!Devicedata::getDevicedata()->isExists($devicekey)){
                try{
        
                    $data = Devicedata::getDevicedata()->addDeviceEntry($devicekey);

                    $data["DataEntry"] = "Success";

                    $this->response($this->json($data), 200);
                }catch(Exception $e){
                    $data = [
                        "he"=>"rohi",
                        "error" => $e->getMessage()
                    ];
                    $this->response($this->json($data), 406);
                }
            }else{
                $data = [
                    "error" => "Conflict",
                    "message" => "Device Entry Exists Already"
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