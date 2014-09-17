<?php

include_once CAWP_DIRECTORY . '/includes/cawpCollection.php';
include_once CAWP_DIRECTORY . '/includes/cawpObject.php';
include_once CAWP_DIRECTORY . '/includes/cawpCollectionService.php';
include_once CAWP_DIRECTORY . '/includes/cawpObjectService.php';


add_shortcode('cawpSlider', 'cawp_slider_short_code');
function cawp_slider_short_code($attr) {

    extract(shortcode_atts((array('type' => 'collections')), $attr));

    if ($type == 'collection') {
        return generateCollectionSlider();
    }
    elseif ($type == 'object') {
        return generateObjectSlider();
    }
    else {
        return null;
    }

    //wp_reset_query();
}


function generateCollectionSlider() {
    $cawpConfig = cawp_get_configuration();

    if (!$cawpConfig->get('include_collections')) {
        return null;
    }

    $collections = cawpCollectionService::get_collections($cawpConfig->get('only_display_public_items'));

    ?>

    <div id="cawp_collection_slider" class="cawp_slider">
        <h2>Collections</h2>

        <?php foreach ($collections as $collection) { ?>
            <div class="">

            </div>
        <?php } ?>
    </div>

    <?php
}


function generateObjectSlider() {
    $cawpConfig = cawp_get_configuration();

    if (!$cawpConfig->get('include_objects')) {
        return null;
    }

    $objects = cawpObjectService::get_objects($cawpConfig->get('only_display_public_items'));

    ?>

    <div id="cawp_object_slider" class="cawp_slider">
        <h2>Objects</h2>

        <?php foreach ($objects as $object) { ?>
            <div class="cawp_item">

            </div>
        <?php } ?>
    </div>
    <?php
}

 