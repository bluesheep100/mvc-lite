<?php

namespace App\Routing;

use App\Exceptions\Handler;

/**
 * @method static get($path, $handler)
 * @method static post($path, $handler)
 * @method static patch($path, $handler)
 * @method static delete($path, $handler)
 */
class Router
{
    protected static $routes = [];

    protected static $allowedMethods = [
        'get', 'post', 'patch', 'delete',
    ];

    protected static $routePrefix = null;

    public static function __callStatic($name, $arguments)
    {
        if (in_array($name, self::$allowedMethods)) {
            $path = formatRoute(self::$routePrefix . $arguments[0]);
            self::$routes[$name][$path] = $arguments[1];
            return;
        }
    }

    /**
     * Reads the request's URI, executes the appropriate handler,
     * and prints its response to stdout.
     */
    public static function start()
    {
        $route = $_SERVER['REQUEST_URI'];
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        // Strip trailing slash and query string in path
        $route = formatRoute($route, true);

        if (!array_key_exists($route, self::$routes[$method])) {
            http_response_code(404);
            exit();
        }

        // Split handler string
        list($class, $callback) = explode('@', self::$routes[$method][$route]);
        $class = 'App\Controllers\\' . $class;

        // Instantiate Controller and call handler
        try {
            echo call_user_func([
                new $class(),
                $callback,
            ], new Request());
        } catch (\Exception $exception) {
            Handler::handle($exception);
        }

        exit(0);
    }

    /**
     * Sets a prefix and executes a callable containing route definitions.
     *
     * @param string $prefix
     * @param callable $routeDefs
     */
    public static function group($prefix, $routeDefs)
    {
        if (!empty($prefix)) {
            self::$routePrefix = $prefix;
        }

        $routeDefs();

        self::$routePrefix = null;
    }
}
