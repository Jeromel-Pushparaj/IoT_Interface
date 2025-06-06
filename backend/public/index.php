<?php
require 'load.php';

use Controllers\UserController;
use Controllers\DeviceController;

$routes = [
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
];

// Simple router handling with middleware support
$requestedMethod = $_SERVER['REQUEST_METHOD'];
$requestedPath = strtok($_SERVER['REQUEST_URI'], '?');

foreach ($routes as $route) {
$pattern = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\}#', '([a-zA-Z0-9]+)', $route['path']);
$pattern = "#^" . $pattern . "$#";

if ($route['method'] === $requestedMethod && preg_match($pattern, $requestedPath, $matches)) {
    array_shift($matches); // Remove full match
    $params = $matches;

    // Middleware check
    if (isset($route['middleware'])) {
        [$middlewareClass, $middlewareMethod] = $route['middleware'];
        $data = json_decode(file_get_contents("php://input"), true) ?? [];
        $authHeaders = apache_request_headers();
        $middleware = new $middlewareClass();
        $result = $middleware->$middlewareMethod($data, $authHeaders);
        if (!$result) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
    }

    // Call controller
    [$controllerClass, $controllerMethod] = $route['handler'];
    $controller = new $controllerClass($result ?? null);
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"), true) ?? [];

    // Call controller with route parameters
    $controller->$controllerMethod($data, ...$params);
    exit;
}

}

// If no route matched
http_response_code(404);
echo json_encode(['error' => 'Route not found']);


