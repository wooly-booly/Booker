<?php

class Route
{
    public static function run()
    {
        $controllerName = 'Index';
        $actionName = 'index';

        $routes = explode('/', $_SERVER['REQUEST_URI'], 4);

        if (!empty($routes[1])) {
            $controllerName = $routes[1];
        }

        if (!empty($routes[2])) {
            $actionName = $routes[2];
        }

        if (!empty($routes[3])) {
            self::getParams($routes[3]);
        }

        $modelName = 'Model_'.$controllerName;
        $controllerName = 'Controller_'.$controllerName;
        $actionName = 'action_'.$actionName;

        $modelFile = strtolower($modelName).'.php';
        $modelPath = APP_PATH.'classes'.DS.'models'.DS.$modelFile;

        if (file_exists($modelPath)) {
            require_once $modelPath;
        }

        $controllerFile = strtolower($controllerName).'.php';
        $controllerPath = APP_PATH.'classes'.DS.'controllers'.DS
                         .$controllerFile;

        if (file_exists($controllerPath)) {
            require_once $controllerPath;
        } else {
            self::errorPage();
        }

        $controller = new $controllerName();
        $action = $actionName;

        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            self::errorPage();
        }
    }

    public function getParams($params)
    {
        $params = explode('&', $params);

        if (empty($params)) {
            return;
        }

        foreach ($params as $param) {
            $var = explode('=', $param);

            if (empty($var[0]) || empty($var[1])) {
                continue;
            }

            $_GET[$var[0]] = $var[1];
        }
    }

    public function errorPage()
    {
        throw new Exception('404 Not Found, sorry! ))');
    }
}
