<?php

${basename(__FILE__, '.php')} = function(){
    $data = [
        "testing" => "success"
    ];

    $this->response($this->json($data), 200);
};