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

    $show_only_if_pic_exists = $cawpConfig->get('only_display_items_with_pics');
    $collections = cawpCollectionService::get_collections($cawpConfig->get('only_display_public_items'));
    ?>

    <h2>Collections</h2>
    <div id="cawp_collection_wrapper" class="jcarousel-wrapper">
        <div id="collection_carousel" class="jcarousel">
            <ul id="collection_list">
                <?php foreach ($collections as $collection) {
                    if (($collection->getPrimaryImage("small") != null) || !$show_only_if_pic_exists) { ?>
                        <li>
                            <a href="#collection_<?php echo $collection->getId() ?>"
                               class="fancybox">
                                <div style="display: none">
                                    <div id="collection_<?php echo $collection->getId() ?>" ><?php generateCollectionLightbox($collection) ?></div>
                                </div>
                                <img src="<?php echo $collection->getPrimaryImageURL("small"); ?>"
                                     alt="<?php echo $collection->getTitle(); ?>"/>
                            </a>
                            <div class="item_title">
                                <?php echo $collection->getTitle(); ?>
                            </div>
                        </li>
                <?php }
                } ?>
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

    $show_only_if_pic_exists = $cawpConfig->get('only_display_items_with_pics');
    $objects = cawpObjectService::get_objects($cawpConfig->get('only_display_public_items'));
    ?>

    <h2>Objects</h2>
    <div id="cawp_object_wrapper" class="jcarousel-wrapper">
        <div id="object_carousel" class="jcarousel">
            <ul id="object_list">
                <?php foreach ($objects as $object) {
                    if (($object->getPrimaryImage("small") != null) || !$show_only_if_pic_exists) { ?>
                    <li>
                        <a href="#object_<?php echo $object->getId(); ?>"
                           class="fancybox">
                            <div style="display: none">
                                <div id="object_<?php echo $object->getId() ?>" ><?php generateObjectLightbox($object) ?></div>
                            </div>
                            <img src="<?php echo $object->getPrimaryImageURL("small"); ?>"
                                 alt="<?php echo $object->getTitle(); ?>"/>
                        </a>
                        <div class="item_title">
                            <?php echo $object->getTitle(); ?>
                        </div>
                    </li>
                <?php }
                } ?>
            </ul>
        </div>

        <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
        <a href="#" class="jcarousel-control-next">&rsaquo;</a>
    </div>
    <br>
    <?php
}



function generateCollectionLightbox($collection) {

    ?>
    <h2>Collection Info:</h2>
    <p>Id : <?php echo $collection->getId(); ?></p>
<?php
}


function generateObjectLightbox($object) {

    $metadata = $object->getMetadata();

    $date_type = $object->getMetadataLabel($metadata['dates_type']);
    ?>

    <h2><?php echo $object->getTitle(); ?></h2>
    <div class="images">
        <div class="alt_images">

        </div>
        <div class="primary_image">
            <img src="<?php echo $object->getPrimaryImageURL("medium"); ?>"
                 alt="<?php echo $object->getTitle(); ?>"/>
        </div>
    </div>

    <div class="description">
        <p><?php echo $object->getDescription(); ?></p>
    </div>

    <div class="metadata">
        <dl>
            <dt>Medium</dt>
            <dd><?php echo $metadata['work_medium']; ?></dd>

            <?php if ($metadata['dimensions_width'] != null) { ?>
                <dt>Dimensions</dt>
                <dd>
                    <?php echo $metadata['dimensions_width'] . ' W x ' .
                        $metadata['dimensions_height'] . ' H x ' .
                        $metadata['dimenions-depth'] . ' D'; ?>
                </dd>
            <?php } ?>

            <dt><?php echo $date_type; ?></dt>
            <dd><?php echo $metadata['dates_value']; ?></dd>

        </dl>
    </div>
<?php
}