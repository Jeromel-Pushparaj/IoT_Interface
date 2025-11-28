<?php
require 'load.php';

use Controllers\UserController;
use Controllers\DeviceController;


Router::handle([
    // User routes
    ["method" => "POST", "path" => "/api/users", "handler" => [UserController::class, 'createUser']],

    //authentication
    ["method" => "POST", "path" => "/api/auth/login", "handler" => [UserController::class, 'login']],

    // Protected route example
    ["method" => "GET", "path" => "/api/profile", "handler" => [UserController::class, 'profile'], "middleware" => [AuthMiddleware::class, 'handle']],

    //Device Routes
    ["method" => "POST", "path" => "/api/devices", "handler" => [DeviceController::class, 'createDevice'], "middleware" => [AuthMiddleware::class, 'handle']],
    ["method" => "GET", "path" => "/api/devices", "handler" => [DeviceController::class, 'getAllDevices'], "middleware" => [AuthMiddleware::class, 'handle']],
    ["method" => "GET", "path" => "/api/devices/{id}", "handler" => [DeviceController::class, 'getDevice'], "middleware" => [AuthMiddleware::class, 'handle']],
    ["method" => "DELETE", "path" => "/api/devices/{id}", "handler" => [DeviceController::class, 'deleteDevice'], "middleware" => [AuthMiddleware::class, 'handle']],
    
    //Device Status
    // ["method" => "GET", "path" => "/api/devices/status", "handler" => [DeviceController::class, 'getDeviceStatus'], "middleware" => [AuthMiddleware::class, 'handle']],
    ["method" => "PUT", "path" => "/api/devices/status", "handler" => [DeviceController::class, 'updateDeviceStatus'], "middleware" => [AuthMiddleware::class, 'handle']],
    // Add more routes as needed
]);



