<?php
namespace Progresso\Framework;

use Progresso\Framework\Routing\Router;

class Plugin {
    protected static $config = [];

    public static function init($config) {
        static::$config = $config;
        Router::hooks();
    }

    public static function config() {
        return static::$config;
    }
}