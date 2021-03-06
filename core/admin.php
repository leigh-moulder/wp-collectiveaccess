<?php

require_once CAWP_DIRECTORY . '/includes/cawpDBConn.php';
require_once CAWP_DIRECTORY . '/includes/cawpObjectService.php';
require_once CAWP_DIRECTORY . '/includes/cawpCollectionService.php';

global $cawp_config_manager;

add_action('admin_menu', 'cawp_admin_menu');
function cawp_admin_menu() {
    add_options_page('Collective Access Interface Settings', 'Collective Access Link',
                     'manage_options', 'cawp_settings', 'cawp_plugin_options');
}

function cawp_plugin_options() {
    global $cawp_config_manager;
    if (!current_user_can('manage_options')) {
        wp_die(__('You do  not have sufficient permissions to access this page'));
    }

    // Process a 'Save Changes' submit
    if (isset($_POST['cawp_submit'])) {
        if (isset($_POST['cawp_server'])) {
            $cawp_config_manager->set('ca_host', $_POST['cawp_server']);
        }
        if (isset($_POST['cawp_database'])) {
            $cawp_config_manager->set('ca_database', $_POST['cawp_database']);
        }
        if (isset($_POST['cawp_user'])) {
            $cawp_config_manager->set('ca_username', $_POST['cawp_user']);
        }
        if (isset($_POST['cawp_password'])) {
            $cawp_config_manager->set('ca_password', $_POST['cawp_password']);
        }
        if (isset($_POST['cawp_display_objects'])) {
            $cawp_config_manager->set('include_objects',
                $_POST['cawp_display_objects'] == "true" ? true : false);
        }
        if (isset($_POST['cawp_display_collections'])) {
            $cawp_config_manager->set('include_collections',
                $_POST['cawp_display_collections'] == "true" ? true : false);
        }
        if (isset($_POST['cawp_display_public_only'])) {
            $cawp_config_manager->set('only_display_public_items',
                $_POST['cawp_display_public_only'] == "true" ? true : false);
        }
        if (isset($_POST['cawp_display_items_with_pics_only'])) {
            $cawp_config_manager->set('only_display_items_with_pics',
                $_POST['cawp_display_items_with_pics_only'] == "true" ? true : false);
        }
        if (isset($_POST['cawp_ca_url'])) {
            $cawp_config_manager->set('ca_url', $_POST['cawp_ca_url']);
        }
        if (isset($_POST['cawp_ca_img_url'])) {
            $cawp_config_manager->set('ca_img_url_path', $_POST['cawp_ca_img_url']);
        }
        $cawp_config_manager->save_options();

        // update the database connection information with the new settings
        cawpDBConn::getInstance()->refreshDBConn();

        // automatically test the connection
        $cawp_config_manager->set('db_connection_valid', cawpDBConn::getInstance()->is_db_connected());
        $cawp_config_manager->save_options();

        $base_url = remove_query_arg( array('_wpnonce', 'noheader', 'updated', 'error', 'action', 'message') );
        wp_redirect( add_query_arg( array( 'settings-updated' => true), $base_url ) );
    }

    // Process a 'Test Connection' submit
    if (isset($_POST['cawp_test_conn'])) {
        $cawp_config_manager->set('db_connection_valid', cawpDBConn::getInstance()->is_db_connected());
        $cawp_config_manager->save_options();

        $base_url = remove_query_arg( array('_wpnonce', 'noheader', 'updated', 'error', 'action', 'message') );
        wp_redirect($base_url);
    }

    // Show a confirmation message when settings are saved.
    if ( !empty($_GET['settings-updated']) ){
        echo '<div id="message" class="updated fade"><p><strong>',__('Settings saved.', 'cawp_settings'), '</strong></p></div>';
    }

    // if database connection is valid, determine how many objects will be shown
    $objects = array();
    $collections = array();
    if ($cawp_config_manager->get('db_connection_valid')) {
        if ($cawp_config_manager->get('include_objects')) {
            $objects = cawpObjectService::get_objects($cawp_config_manager->get('only_display_public_items'));
        }

        if ($cawp_config_manager->get('include_collections')) {
            $collections = cawpCollectionService::get_collections($cawp_config_manager->get('only_display_public_items'));
        }
    }
    ?>

    <div class="wrap">
        <?php screen_icon(); ?>
        <h2>Collective Access Database Configuration</h2>
        <form id="cawp-conf" method="post" action="<?php echo admin_url('options-general.php?page=cawp_settings&noheader=1');?>" >
            <table class="form-table">
                <tbody>
                <tr>
                    <td>Collective Access Server:</td>
                    <td><input id="cawp_server" name="cawp_server" type="text" size="30" maxlength="30" class="regular-text" value="<?php echo $cawp_config_manager->get('ca_host') ?>"></td>
                </tr>
                <tr>
                    <td>Collective Access Database:</td>
                    <td><input id="cawp_database" name="cawp_database" type="text" size="30" maxlength="30" class="regular-text" value="<?php echo $cawp_config_manager->get('ca_database') ?>"></td>
                </tr>
                <tr>
                    <td>Database User:</td>
                    <td><input id="cawp_user" name="cawp_user" type="text" size="30" maxlength="30" class="regular-text" value="<?php echo $cawp_config_manager->get('ca_username') ?>"></td>
                </tr>
                <tr>
                    <td>Database Password:</td>
                    <td><input id="cawp_password" name="cawp_password" type="password" size="30" maxlength="30" class="regular-text" value="<?php echo $cawp_config_manager->get('ca_password') ?>"></td>
                </tr>
                <tr>
                    <td>Display Objects:</td>
                    <td>
                        <input type="radio" name="cawp_display_objects" value="true" <?php if ($cawp_config_manager->get('include_objects') == true) {echo 'checked';} ?> >Yes
                        <input type="radio" name="cawp_display_objects" value="false" <?php if ($cawp_config_manager->get('include_objects') == false) {echo 'checked';} ?> >No
                    </td>
                </tr>
                <tr>
                    <td>Display Collections:</td>
                    <td>
                        <input type="radio" name="cawp_display_collections" value="true" <?php if ($cawp_config_manager->get('include_collections') == true) {echo 'checked';} ?> >Yes
                        <input type="radio" name="cawp_display_collections" value="false" <?php if ($cawp_config_manager->get('include_collections') == false) {echo 'checked';} ?> >No
                    </td>
                </tr>
                <tr>
                    <td>Only Display Public Objects:</td>
                    <td>
                        <input type="radio" name="cawp_display_public_only" value="true" <?php if ($cawp_config_manager->get('only_display_public_items') == true) {echo 'checked';} ?> >Yes
                        <input type="radio" name="cawp_display_public_only" value="false" <?php if ($cawp_config_manager->get('only_display_public_items') == false) {echo 'checked';} ?> >No
                    </td>
                </tr>
                <tr>
                    <td>Only Display Items with Pictures:</td>
                    <td>
                        <input type="radio" name="cawp_display_items_with_pics_only" value="true" <?php if ($cawp_config_manager->get('only_display_items_with_pics') == true) {echo 'checked';} ?> >Yes
                        <input type="radio" name="cawp_display_items_with_pics_only" value="false" <?php if ($cawp_config_manager->get('only_display_items_with_pics') == false) {echo 'checked';} ?> >No
                    </td>
                </tr>
                <tr>
                    <td>Collective Access Site URL:</td>
                    <td><input id="cawp_ca_url" name="cawp_ca_url" type="text" size="50" maxlength="50" class="regular-text" value="<?php echo $cawp_config_manager->get('ca_url') ?>"/></td>
                </tr>
                <tr>
                    <td>Collective Access Image path:</td>
                    <td><input id="cawp_ca_img_url" name="cawp_ca_img_url" type="text" size="50" maxlength="50" class="regular-text" value="<?php echo $cawp_config_manager->get('ca_img_url_path') ?>"/></td>
                </tr>
                </tbody>
            </table>
            <?php wp_nonce_field('cawp_Admin_Page'); ?>
            <p class="submit">
                <input type="submit" name="cawp_submit" id="cawp_submit" class="button button-primary" value="Save Changes">
            </p>
        </form>

        <?php screen_icon(); ?>
        <h2>Collective Access Connectivity</h2>
        <?php
        $connection_state = ($cawp_config_manager->get('db_connection_valid') == true) ? 'Connected' : 'Disconnected';
        $objects_displayed = ($cawp_config_manager->get('db_connection_valid') == true) ? count($objects) : 'Database connection must be established';
        $collections_displayed = ($cawp_config_manager->get('db_connection_valid') == true) ? count($collections) : 'Database connection must be established';
        ?>
        <form id="cawp-test-conn" method="post" action="<?php echo admin_url('options-general.php?page=cawp_settings&noheader=1');?>" >
            <table class="form-table">
                <tbody>
                    <tr>
                        <td>Database Connection Status:</td>
                        <td><input id="cawp_conn_state" name="cawp_conn_state" type="text" disabled size="30" class="regular-text readonly" value="<?php echo $connection_state ?>" /></td>
                    </tr>
                    <tr>
                        <td>Number of Objects To Display:</td>
                        <td><input id="cawp_objects_found" name="cawp_objects_found" type="text" disabled size="30" class="regular-text readonly" value="<?php echo $objects_displayed ?>" /></td>
                    </tr>
                    <tr>
                        <td>Number of Collections To Display:</td>
                        <td><input id="cawp_collections_found" name="cawp_collections_found" type="text" disabled size="30" class="regular-text readonly" value="<?php echo $collections_displayed ?>" /></td>
                    </tr>
                </tbody>
            </table>
            <p class="submit">
                <input type="submit" name="cawp_test_conn" id="cawp_test_conn" class="button button-primary" value="Test Connection">
            </p>
        </form>
    </div>

    <?php
}

