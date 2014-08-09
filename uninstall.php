<?php

if (defined('ABSPATH') && defined('WP_UNINSTALL_PLUGIN')) {

    // remove the plugins settings and installation log
    delete_option(CAWP_OPTIONS);
    delete_option('cawp_installation_log');

    // remove the database tables
    // TODO
}
else {
    exit();
}

 