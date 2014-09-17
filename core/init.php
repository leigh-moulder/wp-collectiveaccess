<?php

/***********************************************
Configuration
 ************************************************/
require CAWP_DIRECTORY . '/includes/cawpConfigurationManager.php';
global $cawp_config_manager;
$cawp_config_manager = new cawpConfigurationManager(
    CAWP_OPTIONS,
    array(
        'ca_host' => 'localhost',
        'ca_database' => 'collective_access',
        'ca_username' => 'collective_access',
        'ca_password' => 'collective_access_password',
        'include_objects' => true,
        'include_collections' => true,
        'only_display_public_items' => true,
        'installation_complete' => false,
        'db_connection_valid' => false,
        'ca_url' => 'http://localhost',
        'ca_img_url_path' => 'media/collectiveaccess/images'
    )
);


/***********************************************
Global functions
 ************************************************/
/**
 * Get the configuration object used by Broken Link Checker.
 *
 * @return blcConfigurationManager
 */
function cawp_get_configuration(){
    return $GLOBALS['cawp_config_manager'];
}


/***********************************************
Main functionality
 ************************************************/

/**
 * Activates the plugin and stores a default set of data in the system if this
 * is the first time being activated.
 */
register_activation_hook(CAWP_PLUGIN_FILE, 'cawp_activation_hook');
function cawp_activation_hook() {
    include_once CAWP_DIRECTORY . '/core/activation.php';
}

/**
 * Register stylesheets and scripts
 */
add_action('wp_enqueue_scripts', 'cawp_add_scripts');
add_action('admin_enqueue_scripts', 'cawp_add_scripts');
function cawp_add_scripts() {
    wp_enqueue_style('cawp_styles', CAWP_PLUGIN_URL . '/css/cawpStyle.css');
}


if ($cawp_config_manager->options['installation_complete']) {
    add_action('init', 'cawp_init');
    function cawp_init() {
        if (is_admin()) {
            require_once CAWP_DIRECTORY . '/core/admin.php';
        }
    }
}


/***********************************************
Shortcut functionality
 ************************************************/
require_once CAWP_DIRECTORY . '/core/shortcode.php';
