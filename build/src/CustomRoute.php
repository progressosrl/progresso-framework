<?php

namespace Progresso;

use Progresso\Routing\Router;
class CustomRoute
{
    protected static $groups = [];
    public static function add($method, $path, $handler)
    {
        if (is_admin()) {
            return \false;
        }
        \Progresso\Routing\Router::add(['method' => $method, 'path' => \implode('/', static::$groups) . '/' . $path, 'handler' => $handler]);
        return \true;
    }
    public static function group($path, $callable)
    {
        static::$groups[] = $path;
        \call_user_func($callable);
        \array_pop(static::$groups);
    }
}
