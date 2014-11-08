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
}


function generateCollectionSlider() {
    $cawpConfig = cawp_get_configuration();

    if (!$cawpConfig->get('include_collections')) {
        return null;
    }

    $collections = cawpCollectionService::get_collections($cawpConfig->get('only_display_public_items'));
    ?>

    <h2>Collections</h2>
    <div id="cawp_collection_wrapper" class="jcarousel-wrapper">
        <div id="collection_carousel" class="jcarousel">
            <ul id="collection_list">
                <?php foreach ($collections as $collection) { ?>
                    <li>
                        <img src="<?php echo $collection->getPrimaryImageURL("small"); ?>"
                             alt="<?php echo $collection->getTitle(); ?>"/>
                        <div class="item_title">
                            <?php echo $collection->getTitle(); ?>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
        <a href="#" class="jcarousel-control-next">&rsaquo;</a>
    </div>
    <br>

    <?php
}


function generateObjectSlider() {
    $cawpConfig = cawp_get_configuration();

    if (!$cawpConfig->get('include_objects')) {
        return null;
    }

    $objects = cawpObjectService::get_objects($cawpConfig->get('only_display_public_items'));
    ?>

    <h2>Objects</h2>
    <div id="cawp_object_wrapper" class="jcarousel-wrapper">
        <div id="object_carousel" class="jcarousel">
            <ul id="object_list">
                <?php foreach ($objects as $object) { ?>
                    <li>
                        <img src="<?php echo $object->getPrimaryImageURL("small"); ?>"
                             alt="<?php echo $object->getTitle(); ?>"/>
                        <div class="item_title">
                            <?php echo $object->getTitle(); ?>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
        <a href="#" class="jcarousel-control-next">&rsaquo;</a>
    </div>
    <br>
    <?php
}

 