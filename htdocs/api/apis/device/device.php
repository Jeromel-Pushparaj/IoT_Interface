<?php

${basename(__FILE__, '.php')} = function(){
    $header = getallheaders();
    if($this->get_request_method() == "POST" and isset($header['devicekey'])){
        if($this->isAuthenticated()){
        try{
            $devicekey = $header['devicekey'];
        
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
    }
    }else{
        $data = [
            "Request" => $this->get_request_method(),
            "devicekey" => $this->_request["devicekey"],
            "POST" => $_POST["devicekey"],
            "error" => "Bad request from device"
        ];
        $data = $this->json($data);
        $this->response($data, 400);
    }

    
};