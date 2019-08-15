<?php
/**
 * Plugin Name: 		Progresso Framework
 * Version:     		1.0.0
 * Description:			Framework for WordPress by Progresso srl
 * Network:     		true

 * Author:				Progresso srl
 * Author URI:  		https://www.progresso.srl

 * Requires at least:	4.9
 * Tested up to:		5.2.2

 * License:    			GPL-3.0+
 * License URI:			http://www.gnu.org/licenses/gpl-3.0.txt

 * Text Domain: 		progresso-framework
 */

// don't load the plugin file directly
if (!defined('ABSPATH')) exit;

$config = include(__DIR__ . '/config/plugin.php');

define('PROGRESSO_FRAMEWORK', $config['version']);
define('PROGRESSO_FRAMEWORK_PATH', __DIR__);

if ($config['environment'] === 'production') {
    if (file_exists(__DIR__ . "/build/vendor/scoper-autoload.php")) {
        require_once(__DIR__ . "/build/vendor/scoper-autoload.php");
    } else {
        require_once(__DIR__ . "/build/vendor/autoload.php");
    }

    // load the plugin update checker
    require_once(PROGRESSO_FRAMEWORK_PATH . '/libraries/plugin-update-checker/v4p7/plugin-update-checker.php');
    $myUpdateChecker = Puc_v4p7_Factory::buildUpdateChecker(
        'https://github.com/progressosrl/progresso-framework/',
        __FILE__,
        'progresso-framework-update'
    );
} else {
    require_once(__DIR__ . "/vendor/autoload.php");
}

// Progresso\Framework\Plugin::instance()->init($config);
