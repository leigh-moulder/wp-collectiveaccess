<?php

 global $cawp_config_manager;

// Attempt to load any already saved settings
$cawp_config_manager->load_options(CAWP_OPTIONS);

// set the installation flag
$cawp_config_manager->set('installation_complete', true);
$cawp_config_manager->save_options();

