<?php

namespace Progresso\Routing;

class Router {
    public static $routes = [];
    public static $match = false;

    public static function hooks() {
        // actions
        add_action('init', self::class . '::matchRoutes');
        add_action('wp_loaded', self::class . '::route');
    }

    /**
     * Add a route to the array
     * @param $route
     */
    public static function add($route) {
        while (strpos($route['path'], '//') !== false) {
            $route['path'] = str_replace('//', '/', $route['path']);
        }
        static::$routes[] = $route;
    }

    /**
     * Find the matched route
     */
    public static function matchRoutes() {
        if (!static::$routes || !is_array(static::$routes) || count(static::$routes) == 0) {
            return;
        }

        // Fetch method and URI from somewhere
        $httpMethod = strtolower($_SERVER['REQUEST_METHOD']);
        $uri = static::getCurrentUrl();

        // for every route
        foreach (static::$routes as $route) {
            // extract pattern and variables from the route
            $patternAndVars = static::extractPatternAndVariables($route['path']);

            // if the route matches, then grab variables and call function
            if (static::verifyMethod($httpMethod, $route['method']) && preg_match($patternAndVars['pattern'], $uri, $matches)) {
                // get the variables
                $vars = [];
                for ($i = 1; $i <= count($matches); $i++) {
                    if (isset($patternAndVars['vars'][$i])) {
                        $vars[$patternAndVars['vars'][$i]] = $matches[$i];
                    }
                }

                // store the match found
                $route['vars'] = $vars;
                static::$match = $route;
                break;
            }
        }
    }

    /**
     * Execute the matched route
     */
    public static function route() {
        if (!static::$match) {
            return;
        }

        call_user_func(static::$match['handler'], static::$match['vars']);
        exit();
    }

    /////////////////////////////////////
    /// PRIVATE METHODS
    /////////////////////////////////////

    /**
     * Extracts a regexp pattern and a list of variables from the "path"
     *
     * prefix/{id:\d+}/{file:.+}
     *
     * becomes
     *
     * pattern = /prefix\/(\d+)\/(.+)/
     * vars = [1 => id, 2 => file]
     *
     * @param string $path
     * @return array
     */
    protected static function extractPatternAndVariables($path) {
        $path = str_replace(['(', ')', '/'], ['\(', '\)', '\/'], $path);
        $variables = [];
        if (preg_match_all('/\{[^\}]+\}/', $path, $matchedPatterns)) {
            foreach ($matchedPatterns[0] as $index => $matchedPattern) {
                if (preg_match('/\{([^\}|^:]+):?([^\}]+)?\}/', $matchedPattern, $matches)) {
                    $variables[$index + 1] = $matches[1];
                    if (isset($matches[2])) {
                        $path = str_replace($matches[0], '(' . $matches[2] . ')', $path);
                    } else {
                        $path = str_replace($matches[0], '(.+)', $path);
                    }
                }
            }
        }
        return [
            'pattern' => '/' . $path . '/',
            'vars' => $variables,
        ];
    }

    protected static function getCurrentUrl() {
        // Get current URL path, stripping out slashes on boundaries
        $current_url = trim(esc_url_raw(add_query_arg([])), '/');
        // Get the path of the home URL, stripping out slashes on boundaries
        $home_path = trim(parse_url(home_url(), PHP_URL_PATH), '/');
        // If a URL part exists, and the current URL part starts with it...
        if ($home_path && strpos($current_url, $home_path) === 0) {
            // ... just remove the home URL path form the current URL path
            $current_url = trim(substr($current_url, strlen($home_path)), '/');
        }
        return '/'.$current_url;
    }

    protected static function verifyMethod($method, $availableMethods) {
        if ($availableMethods === '*') {
            return true;
        }

        if (is_string($availableMethods)) {
            $availableMethods = [$availableMethods];
        }
        foreach ($availableMethods as $availableMethod) {
            if (strtolower($availableMethod) == strtolower($method)) {
                return true;
            }
        }

        return false;
    }
}