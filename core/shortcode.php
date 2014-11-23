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
            if (($collection->getPrimaryImage(cawpConstants::IMAGE_CAROUSEL) != null) || !$show_only_if_pic_exists) { ?>
                <div clas="item">
                    <a href="<?php echo CAWP_PLUGIN_URL . '/core/lightbox.php';?>" class="lightbox">
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
            ?>
            <div class="item">
                <?php
                    $serialObject = $object->convert_to_array();
                ?>
                <a href="<?php echo CAWP_PLUGIN_URL . '/core/lightbox.php';?>" class="lightbox" data="<?php echo json_encode($serialObject); ?>">
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