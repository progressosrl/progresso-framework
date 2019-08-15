<?php

namespace Progresso\Framework;

use Progresso\Framework\Routing\Router;
class Plugin
{
    protected static $config = [];
    public static function init($config)
    {
        static::$config = $config;
        \Progresso\Framework\Routing\Router::hooks();
    }
    public static function config()
    {
        return static::$config;
    }
}
