<?php
require 'load.php';

use Controllers\UserController;
use Controllers\DeviceController;


Router::handle([
    // User routes
    ["method" => "POST", "path" => "/api/register", "handler" => [UserController::class, 'register']],
    ["method" => "POST", "path" => "/api/login", "handler" => [UserController::class, 'login']],

    // Protected route example
    ["method" => "GET", "path" => "/api/profile", "handler" => [UserController::class, 'profile'], "middleware" => [AuthMiddleware::class, 'handle']],
    //Device Routes
    ["method" => "POST", "path" => "/api/device/register", "handler" => [DeviceController::class, 'register'], "middleware" => [AuthMiddleware::class, 'handle']],
    ["method" => "GET", "path" => "/api/device/list", "handler" => [DeviceController::class, 'list'], "middleware" => [AuthMiddleware::class, 'handle']],
    ["method" => "GET", "path" => "/api/device/status", "handler" => [DeviceController::class, 'status'], "middleware" => [AuthMiddleware::class, 'handle']],
    ["method" => "POST", "path" => "/api/device/update", "handler" => [DeviceController::class, 'updateStatus'], "middleware" => [AuthMiddleware::class, 'handle']],
    ["method" => "GET", "path" => "/api/device/show/{id}", "handler" => [DeviceController::class, 'viewDevice'], "middleware" => [AuthMiddleware::class, 'handle']],
    ["method" => "DELETE", "path" => "/api/device/delete/{id}", "handler" => [DeviceController::class, 'deleteDevice'], "middleware" => [AuthMiddleware::class, 'handle']],
    // Add more routes as needed
]);



