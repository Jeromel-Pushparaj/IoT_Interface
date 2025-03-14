<?php

${basename(__FILE__, '.php')} = function(){
    $header = getallheaders();

    if($this->get_request_method() == "GET" and isset($header['devicekey'])){
        $devicekey = $header['devicekey'];
        $device = Devicedata::getDevicedata()->readDeviceState($devicekey);
        if (Devicedata::getDevicedata()->isExists($devicekey)) {
            $updatedAt = strtotime($device['update_at']); // Convert to timestamp
            $currentTime = time(); // Current timestamp
            $timeDifference = $currentTime - $updatedAt; // Time difference in seconds

            // If last update was more than 30 seconds ago, consider it offline
            $deviceStatus = ($timeDifference > 30) ? 0 : $device['indicator'];

            // Send response
            $this->response($this->json([
                "indicator" => $deviceStatus,
                "updated_at" => $device['update_at'],
                "message" => ($deviceStatus === 0) ? "Device is offline" : "Device is online"
            ]), 200);
        } else {
            $this->response($this->json(["error" => "Device not found"]), 404);
        }
    } else {
        $this->response($this->json(["error" => "Bad request"]), 400);
    }
};
