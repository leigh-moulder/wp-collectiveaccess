<?php

/***********************************************
Configuration
 ************************************************/
global $cawp_config_manager;
$default_options = array(
    'ca_host' => 'localhost',
    'ca_username' => 'collective_access',
    'ca_password' => 'collective_access_password',
    'include_objects' => true,
    'include_collections' => true,
    'only_display_public_items' => true,
    'installation_complete' => false
);

require CAWP_DIRECTORY . '/includes/config-manager.php';
$cawp_config_manager = new cawpConfigurationManager(
    CAWP_OPTIONS,
    $default_options
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

register_activation_hook(CAWP_PLUGIN_FILE, 'cawp_activation');
/**
 * Activates the plugin and stores a default set of data in the system if this
 * is the first time being activated.
 */
function cawp_activation() {
    require CAWP_DIRECTORY . '/core/activation.php';
}


if ($cawp_config_manager->options['installation_complete']) {
    function cawp_init() {
        if (is_admin()) {
            require_once CAWP_DIRECTORY . '/core/admin.php';
        }
    }
    add_action('init', 'cawp_init');
}


