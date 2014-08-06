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
    define('CAWP_PLUGIN_FILE', __FILE__);
}

// Path to the plugin directory
if (!defined('CAWP_DIRECTORY')) {
    define('CAWP_DIRECTORY', plugin_dir_path(__FILE__));
}

if (!defined('CAWP_OPTIONS')) {
    define('CAWP_OPTIONS', 'cawp_options');
}

if (is_admin()) {
    require_once CAWP_DIRECTORY . 'core/admin.php';
}

//Load the actual plugin
require CAWP_DIRECTORY . '/core/init.php';
