<?php

require_once 'src/controllers/DefaultController.php';
require_once 'src/controllers/SecurityController.php';

class Routing {
    private static $routes = []; // Declare the static property

    public static function get($url, $controller) {
        self::$routes[$url] = $controller;
    }

    public static function post($url, $controller) {
        self::$routes[$url] = $controller;
    }

    public static function run($url) {
        $action = explode('/', $url)[0];

        if (!array_key_exists($action, self::$routes)) {
            die('Wrong URL');
        }
        
        $controllerName = self::$routes[$action];
        $controller = new $controllerName;
        $action = $action ?: 'index';

        $controller->$action();
    }
}
?>