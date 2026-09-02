<?php

session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/controllers/Controller.php';

$url = $_GET['url'] ?? 'auth/login';
$urlParts = explode('/', filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL));

$controllerName = ucfirst($urlParts[0] ?? 'Auth') . 'Controller';
$actionName = $urlParts[1] ?? 'index';
$params = array_slice($urlParts, 2);

$controllerFile = __DIR__ . '/app/controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        if (method_exists($controller, $actionName)) {
            call_user_func_array([$controller, $actionName], $params);
            exit();
        }
    }
}

http_response_code(404);
echo "<h3>404 Page Not Found</h3><a href='index.php'>Return to Home</a>";