<?php

${basename(__FILE__, '.php')} = function(){
    $data = [
        "current" => "success"
    ];

    $this->response($this->json($data), 200);
};