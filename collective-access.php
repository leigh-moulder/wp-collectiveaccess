<?php
/*
Plugin Name: CollectiveAccess
Plugin URI:
Description: Connects to CollectiveAccess installation to display collections and objects
Version: 0.1
Author: Leigh Moulder
Author URI:
Text Domain: collective-access
License: GPLv2
*/

// Path to this file
if (!defined('CAWP_PLUGIN_FILE')) {
//    define('CAWP_PLUGIN_FILE', __FILE__);
    define('CAWP_PLUGIN_FILE', '/wp-collectiveaccess/collective-access.php');
}

// URL to this file
if (!defined('CAWP_PLUGIN_URL')) {
    define('CAWP_PLUGIN_URL',
            plugins_url($path, '/wp-collectiveaccess/collective-access.php'));
}

// Path to the plugin directory
if (!defined('CAWP_DIRECTORY')) {
    define('CAWP_DIRECTORY', dirname(__FILE__));
}

if (!defined('CAWP_OPTIONS')) {
    define('CAWP_OPTIONS', 'cawp_options');
}

//Load the actual plugin
require 'core/init.php';
