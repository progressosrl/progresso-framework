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

define('PROGRESSO_FRMEWORK', $config['version']);
define('PROGRESSO_FRAMEWORK_PATH', plugin_dir_path(__FILE__));

if ($config['environment'] === 'production') {
    require_once(__DIR__ . "/build/vendor/scoper-autoload.php");
} else {
    require_once(__DIR__ . "/vendor/autoload.php");
}

// Progresso\Framework\Plugin::instance()->init($config);
