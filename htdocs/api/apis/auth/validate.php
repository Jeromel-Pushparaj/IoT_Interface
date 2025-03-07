<?php

${basename(__FILE__, '.php')} = function(){
    $data = [
        "Validation" => "success"
    ];
    $api_token = "0191dc8c95ea00661a7642709fd400f2bcac9ac61815716f518cfefe76b9a8f9b08d9f22b8a74f061e7009d325424918d411fe46e7bf24f96422daed2f910ebc";
    $key = "0191dc8c95ea00661a7642709fd400f2bcac9ac61815716f518cfefe76b9a8f9b08d9f22b8a74f061e7009d325424918d411fe46e7bf24f96422daed2f10ebc";
    if(hash_equals($api_token, $key)){
    $this->response($this->json($data), 200);
    }else {
        $data = [
            "Validation" => "Invadi Key"
        ];
        $this->response($this->json($data), 409);
    }
};
