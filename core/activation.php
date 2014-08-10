<?php

global $cawp_config_manager;

// Attempt to load any already saved settings
$cawp_config_manager->load_options(CAWP_OPTIONS);

// set the installation flag
$cawp_config_manager->set('installation_complete', true);
$cawp_config_manager->save_options();

// if we're reloading the values from the database, check ca database connectivity
if (is_array($cawp_config_manager->loaded_values)) {
    require CAWP_DIRECTORY . '/includes/database-conn.php';
    $db = new cawpDBConn($cawp_config_manager->get('ca_host'), $cawp_config_manager->get('ca_database'),
                         $cawp_config_manager->get('ca_username'), $cawp_config_manager->get('ca_password'));


    if ($db->is_db_connected()) {
        $cawp_config_manager->set('db_connection_valid', true);
    }
    $cawp_config_manager->save_options();
}

