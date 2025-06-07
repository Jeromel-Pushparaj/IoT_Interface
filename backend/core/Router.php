<?php
class Router {
    public static function handle($routes) {

        // Simple router handling with middleware support
        $requestedMethod = $_SERVER['REQUEST_METHOD'];
        $requestedPath = strtok($_SERVER['REQUEST_URI'], '?');

        // Pre-process routes into a lookup table during initialization
        $routeMap = [];
        foreach ($routes as $route) {
            // Convert path to regex pattern once
            $pattern = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\}#', '([a-zA-Z0-9]+)', $route['path']);
            $route['compiled_pattern'] = "#^{$pattern}$#";
    
            // Group by HTTP method for faster lookup
            $routeMap[$route['method']][] = $route;
        }

        // Get current route data
        $methodRoutes = $routeMap[$requestedMethod] ?? [];

        // Use array_filter for matching (faster than foreach)
        $matchedRoute = current(array_filter($methodRoutes, function($route) use ($requestedPath) {
            return preg_match($route['compiled_pattern'], $requestedPath);
        }));

        if ($matchedRoute) {
            // Extract params
            preg_match($matchedRoute['compiled_pattern'], $requestedPath, $matches);
            $params = array_slice($matches, 1);

            // Handle middleware
            if (isset($matchedRoute['middleware'])) {
                [$middlewareClass, $middlewareMethod] = $matchedRoute['middleware'];
                $data = json_decode(file_get_contents("php://input"), true) ?? [];
                $authHeaders = apache_request_headers();
                $decodedData = (new $middlewareClass())->$middlewareMethod($data, $authHeaders); 
                if (!$decodedData) {
                    http_response_code(401);
                    exit(json_encode(['error' => 'Unauthorized']));
                }
            }

            // Handle controller
            [$controllerClass, $controllerMethod] = $matchedRoute['handler'];
            header('Content-Type: application/json');
            $data = json_decode(file_get_contents("php://input"), true) ?? [];

            (new $controllerClass($decodedData ?? null))->$controllerMethod($data, ...$params);
            exit;
        }

        //TODO: Store this in persistent storage (APCu, Redis, etc.)
        // if (!apcu_exists('route_map')) {
        //     apcu_store('route_map', $routeMap);
        // }
        // If no route matched
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
}
}

