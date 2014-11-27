<?php

include_once CAWP_DIRECTORY . '/includes/cawpConstants.php';
include_once CAWP_DIRECTORY . '/includes/cawpCollection.php';
include_once CAWP_DIRECTORY . '/includes/cawpObject.php';
include_once CAWP_DIRECTORY . '/includes/cawpCollectionService.php';
include_once CAWP_DIRECTORY . '/includes/cawpObjectService.php';

add_shortcode('cawpSlider', 'cawp_slider_short_code');
function cawp_slider_short_code($attr) {

    extract(shortcode_atts((array('type' => 'collections')), $attr));

    ?>

    <?php

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
    <div id="collection_wrapper" class="owl-carousel">
        <?php foreach ($collections as $collection) {
            if (($collection->getPrimaryImage(cawpConstants::IMAGE_CAROUSEL) != null) || !$show_only_if_pic_exists) {
                $jsonCollection = json_encode($collection->jsonSerialize(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE);
                ?>
                <div clas="item">
                    <a href="<?php echo CAWP_PLUGIN_URL . '/core/lightbox.php';?>"
                       class="lightbox"
                       data-type="collection"
                       data-json='<?php echo $jsonCollection; ?>'>
                        <img class="owl-lazy"
                             data-src="<?php echo $collection->getPrimaryImageURL(cawpConstants::IMAGE_CAROUSEL); ?>"
                             title="<?php echo $collection->getTitle(); ?>"/>
                        <div class="item_title">
                            <?php echo $collection->getTitle(); ?>
                        </div>
                    </a>
                </div>
            <?php }
        } ?>
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
    <div id="object_carousel" class="owl-carousel">
    <?php foreach ($objects as $object) {
        if (($object->getPrimaryImage(cawpConstants::IMAGE_CAROUSEL) != null) || !$show_only_if_pic_exists) {
            $jsonObject = json_encode($object->jsonSerialize(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE);

            ?>
            <div class="item">
                <a href="<?php echo CAWP_PLUGIN_URL . '/core/lightbox.php';?>"
                   class="lightbox"
                   data-type="object"
                   data-json='<?php echo $jsonObject; ?>'>
                    <img class="owl-lazy"
                         data-src="<?php echo $object->getPrimaryImageURL(cawpConstants::IMAGE_CAROUSEL); ?>"
                         title="<?php echo $object->getTitle(); ?>"/>
                    <div class="item_title">
                        <?php echo $object->getTitle(); ?>
                    </div>
                </a>
            </div>
        <?php
        }
    }?>
    </div>
    <br>
<?php
}