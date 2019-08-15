<?php
namespace Progresso\Framework;

use Progresso\Framework\Routing\Router;

class FrontendRouter {
    protected static $groups = [];

    public static function custom($route) {
        if (is_admin()) {
            return false;
        }
        Router::add([
            'type' => 'custom',
            'method' => $route['method'] ?? '*',
            'path' => implode('/', static::$groups) . '/' . $route['path'],
            'handler' => $route['handler'],
        ]);
        return true;
    }

    public static function post($route) {
        if (is_admin()) {
            return false;
        }
        Router::add([
            'type' => 'post',
            'method' => $route['method'] ?? '*',
            'path' => implode('/', static::$groups) . '/' . $route['path'],
            'handler' => $route['handler'] ?? null,
            'disable_post_query' => $route['disable_post_query'] ?? false,
            'query_vars' => $route['query_vars'] ?? null,
            'parse_request' => $route['parse_request'] ?? true,
            'template' => $route['template'] ?? null,
        ]);
        return true;
    }

    public static function group($path, $callable) {
        static::$groups[] = $path;
        call_user_func($callable);
        array_pop(static::$groups);
    }
}