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

    <script type="text/javascript" charset="utf-8">
        jQuery(document).ready(function($) {
            $('.owl-carousel').owlCarousel({
                    items: 4,
                    loop: true,
                    nav: true,
                    margin: 5
                }
            );
        });
    </script>

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
            if (($collection->getPrimaryImage(cawpConstants::IMAGE_CAROUSEL) != null) || !$show_only_if_pic_exists) { ?>
                <div clas="item">
                    <img src="<?php echo $collection->getPrimaryImageURL(cawpConstants::IMAGE_CAROUSEL); ?>"
                         title="<?php echo $collection->getTitle(); ?>"/>
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
            ?>
            <div class="item">
                <img src="<?php echo $object->getPrimaryImageURL(cawpConstants::IMAGE_CAROUSEL); ?>"
                    alt="<?php echo $object->getTitle(); ?>"/>
            </div>
        <?php
        }
    }?>
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
    $alt_images = $object->getAlternativeImages();
    ?>

    <h2><?php echo $object->getTitle(); ?></h2>
    <div class="images">
        <div class="alt_images">
            <ul>
                <?php foreach ($alt_images as $alt_image) { ?>
                    <li>
                        <img src="<?php  echo $alt_image->getURL(cawpConstants::IMAGE_ALT); ?>"
                             title="<?php echo $alt_image->getOriginalName();?>"/>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="primary_image">
            <img src="<?php echo $object->getPrimaryImageURL(cawpConstants::IMAGE_MAIN); ?>"
                 alt="<?php echo $object->getTitle(); ?>"/>
        </div>
    </div>

    <div class="description">
        <p><?php echo $object->getDescription(); ?></p>
    </div>
<?php
}