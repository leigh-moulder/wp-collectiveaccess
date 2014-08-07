<?php

global $cawp_config_manager;

add_action('admin_menu', 'cawp_admin_menu');
function cawp_admin_menu() {
    add_options_page('Collective Access Interface Settings', 'Collective Access Link',
                     'manage_options', 'cawp_identifier', 'cawp_plugin_options');
}

function cawp_plugin_options() {
    global $cawp_config_manager;
    if (!current_user_can('manage_options')) {
        wp_die(__('You do  not have sufficient permissions to access this page'));
    }

    if (isset($_POST['cawp_submit'])) {
        if (isset($_POST['cawp_server'])) {
            $cawp_config_manager->set('ca_host', $_POST['cawp_server']);
        }
        if (isset($_POST['cawp_user'])) {
            $cawp_config_manager->set('ca_username', $_POST['cawp_user']);
        }
        if (isset($_POST['cawp_password']) && isset($_POST['cawp_password2'])) {
            if ($_POST['cawp_password'] == $_POST['cawp_password2']) {
                $cawp_config_manager->set('ca_password', $_POST['cawp_password']);
            }
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
        $cawp_config_manager->save_options();
    }

    ?>

    <div class="wrap">
        <?php screen_icon(); ?>
        <h2>Collective Access Interface Configuration</h2>
        <form action="" method="post" id="cawp-conf">
            <table class="form-table">
                <tbody>
                <tr>
                    <td>Collective Access Server:</td>
                    <td><input id="cawp_server" name="cawp_server" type="text" size="30" maxlength="30" class="regular-text" value="<?php echo $cawp_config_manager->get('ca_host') ?>"></td>
                </tr>
                <tr>
                    <td>Database User:</td>
                    <td><input id="cawp_user" name="cawp_user" type="text" size="30" maxlength="30" class="regular-text" value="<?php echo $cawp_config_manager->get('ca_username') ?>"></td>
                </tr>
                <tr>
                    <td>Database User Password:</td>
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
                    <td>Display Only Public Objects:</td>
                    <td>
                        <input type="radio" name="cawp_display_public_only" value="true" <?php if ($cawp_config_manager->get('only_display_public_items') == true) {echo 'checked';} ?> >Yes
                        <input type="radio" name="cawp_display_public_only" value="false" <?php if ($cawp_config_manager->get('only_display_public_items') == false) {echo 'checked';} ?> >No
                    </td>
                </tr>
                </tbody>
            </table>
            <p class="submit">
                <input type="submit" name="cawp_submit" id="cawp_submit" class="button button-primary" value="Save Changes">
            </p>
        </form>

        <h2>Collective Access Connectivity</h2>
    </div>

    <?php
}


function cawp_plugin_action_links( $links, $file) {
    if ($file == plugin_basename(dirname(__FILE__) . '/collective-access.php')) {
        $links[] = '<a href="' . admin_url( 'admin.php?page=cawp-config' ) . '">'.__( 'Settings' ).'</a>';
    }

    return $links;
}
add_filter('plugin_action_links', 'cawp_plugin_action_links', 10, 2);

