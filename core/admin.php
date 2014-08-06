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
    ?>

    <div class="wrap">
        <h3>Collective Access Interface Configuration</h3>
        <form action="" method="post" id="cawp-conf">
            <table class="form-table">
                <tbody>
                <tr>
                    <td>Server:</td>
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
                    <td>Reconfirm Password:</td>
                    <td><input id="cawp_password2" name="cawp_password2" type="password" size="30" maxlength="30" class="regular-text" value="<?php echo $cawp_config_manager->get('ca_password') ?>"></td>
                </tr>
                <tr>
                    <td>Display Objects:</td>
                    <td>
                        <input type="radio" name="cawp_display_objects" value="yes" <?php if ($cawp_config_manager->get('include_objects') == true) {echo 'checked';} ?> >Yes
                        <input type="radio" name="cawp_display_objects" value="no" <?php if ($cawp_config_manager->get('include_objects') == false) {echo 'checked';} ?> >No
                    </td>
                </tr>
                <tr>
                    <td>Display Collections:</td>
                    <td>
                        <input type="radio" name="cawp_display_collections" value="yes" <?php if ($cawp_config_manager->get('include_collections') == true) {echo 'checked';} ?> >Yes
                        <input type="radio" name="cawp_display_collections" value="no" <?php if ($cawp_config_manager->get('include_collections') == false) {echo 'checked';} ?> >No
                    </td>
                </tr>
                <tr>
                    <td>Display Only Public Objects:</td>
                    <td>
                        <input type="radio" name="cawp_display_public_only" value="yes" <?php if ($cawp_config_manager->get('only_display_public_items') == true) {echo 'checked';} ?> >Yes
                        <input type="radio" name="cawp_display_public_only" value="no" <?php if ($cawp_config_manager->get('only_display_public_items') == false) {echo 'checked';} ?> >No
                    </td>
                </tr>
                </tbody>
            </table>
            <p class="submit">
                <input type="submit" name="cawp_submit" id="cawp_submit" class="button button-primary" value="Save Changes">
            </p>
        </form>

        <h3>Collective Access Connectivity</h3>
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

