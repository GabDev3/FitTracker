<?php

require_once 'src/controllers/DefaultController.php';
require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/MealController.php'; // Add MealController

class Routing {
    private static $routes = [];

    public static function get($url, $controller, $method = 'index') {
        self::$routes['GET'][$url] = [$controller, $method];
    }

    public static function post($url, $controller, $method = 'index') {
        self::$routes['POST'][$url] = [$controller, $method];
    }

    public static function run($url) {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $action = explode('/', $url)[0];

        if (!isset(self::$routes[$requestMethod][$action])) {
            die('Wrong URL or method');
        }

        [$controllerName, $method] = self::$routes[$requestMethod][$action];
        $controller = new $controllerName;

        if (!method_exists($controller, $method)) {
            die("Method $method not found in $controllerName");
        }

        $controller->$method();
    }
}
?>
