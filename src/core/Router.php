<?php

namespace AppDAF\CORE;

use AppDAF\ENUM\KeyRoute;

class Router
{

    private static array $routes = [];

    public static function resolve(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach (self::$routes as $route => $info) {
            preg_match_all('/\{(\w+)\}/', $route, $paramNames);
            $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $params = array_combine($paramNames[1], $matches);

                $controllerName = $info[KeyRoute::CONTROLLER->value];
                $action = $info[KeyRoute::ACTION->value];


                if (isset($info[KeyRoute::MIDDLEWARES->value]) && is_array($info[KeyRoute::MIDDLEWARES->value])) {
                    foreach ($info[KeyRoute::MIDDLEWARES->value] as $middlewareClass) {
                        if (class_exists($middlewareClass) && method_exists($middlewareClass, '__invoke')) {
                            
                            $middleware = new $middlewareClass();
                            $middleware();
                        }
                    }
                }

                $controller = new $controllerName();

                call_user_func_array([$controller, $action], $params);
                return;
            }
        }

        header("Location: /404");
    }

    public static function setRoute(array $route): void
    {
        if (!is_array($route) || $route === []) {
            throw new \Exception("Veillez donnés le tableau des routes ca dois etre un table non vide !", 1);
        }
        self::$routes = $route;
    }
}
