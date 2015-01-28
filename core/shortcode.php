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
                ?>
                <div clas="item">
                    <a class="lightbox"
                       data-type="collection"
                       data-id="<?php echo $collection->getId(); ?>">
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
                <a class="lightbox"
                   data-type="object"
                   data-id="<?php echo $object->getId(); ?>">
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

add_action('wp_ajax_cawp_display_lightbox', 'displayLightbox');
add_action('wp_ajax_nopriv_cawp_display_lightbox', 'displayLightbox');
function displayLightbox() {
    $type = $_POST['TYPE'];
    $id = $_POST['ID'];
    ?>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('.owl-carousel-small').owlCarousel({
                items: 3,
                center: true,
                loop: true,
                margin: 5,
                nav: true,
                navText: ['&#10094;', '&#10095;'],
                dots: false,
                responsive: {
                    0:{
                        items:1
                    },
                    600:{
                        items:3
                    },
                    1000:{
                        items:5
                    }
                }
            });
        });
    </script>

    <?php

    if ($type == "collection") {
        generateCollectionLightbox($id);
    }
    elseif ($type == "object") {
        generateObjectLightbox($id);
    }

    die;
}


function generateCollectionLightbox($id) {

    $collection = cawpCollectionService::get_collection_by_id($id, false);
    $collection_objects = cawpObjectService::get_objects_by_collection($id);
    $objectCount = count($collection_objects);

    $primaryImg = $collection->getPrimaryImage();
    ?>

    <div clas="lightbox_title">
        <h2><?php echo $collection->getTitle() ?></h2>
    </div>
    <div class="lightbox_images">
        <div class="lightbox_primary_image">
            <img src="<?php echo $primaryImg->getURL(cawpConstants::IMAGE_MAIN); ?>"
                 alt="<?php echo $collection->getTitle(); ?>"
                 height="<?php echo $primaryImg->getHeight(cawpConstants::IMAGE_MAIN); ?>"
                 width="<?php echo $primaryImg->getWidth(cawpConstants::IMAGE_MAIN); ?>"/>
        </div>
    </div>

    <div class="description">
        <h4>Description</h4>
        <p><?php echo $collection->getDescription(); ?></p>
    </div>

    <div class="collection_objects">
        <?php if ($objectCount > 0) { ?>
            <h4>Collection Objects</h4>
            <div class="owl-carousel owl-carousel-small">
                <?php for ($i = 0; $i < $objectCount; $i++) { ?>
                    <div class="item">
                        <img src="<?php echo $collection_objects[$i]->getPrimaryImageURL(cawpConstants::IMAGE_CAROUSEL);?>"
                             alt="<?php echo $collection_objects[$i]->getTitle(); ?>"/>
                    </div>
                <?php } ?>
            </div>
            <br>
        <?php } ?>
    </div>
<?php
}


function generateObjectLightbox($id) {

    $object = cawpObjectService::get_object_by_id($id, false);
    $primaryImg = $object->getPrimaryImage();
    $altImages = $object->getAlternativeImages();
    $altImageCount = count($altImages);
    ?>

    <div class="lightbox_title">
        <h2><?php echo $object->getTitle() ?></h2>
    </div>
    <div class="lightbox_images">
        <div class="lightbox_primary_image">
            <img src="<?php echo $primaryImg->getURL(cawpConstants::IMAGE_MAIN);?>"
                 alt="<?php $object->getTitle(); ?>"
                 height="<?php echo $primaryImg->getHeight(cawpConstants::IMAGE_MAIN); ?>"
                 width="<?php echo $primaryImg->getWidth(cawpConstants::IMAGE_MAIN); ?>"/>
        </div>
        <?php if ($altImageCount > 0) { ?>
            <div id="alt_images" class="owl-carousel owl-carousel-small">
                <div class="item">
                    <img src="<?php echo $object->getPrimaryImageURL(cawpConstants::IMAGE_CAROUSEL);?>"/>
                </div>

                <?php for ($i = 0; $i < $altImageCount; $i++) { ?>
                    <div class="item">
                        <img src="<?php echo $altImages[$i]->getURL(cawpConstants::IMAGE_CAROUSEL);?>"/>
                    </div>
                <?php } ?>
            </div>
        <?php }?>
    </div>

    <div class="description">
        <h4>Description</h4>
        <p><?php echo $object->getDescription(); ?></p>
    </div>

    <div class="metadata">
        <?php
            $metadata = $object->getMetadata();
            $medium = $metadata['work_medium'];
            $width = $metadata['dimensions_width'];
            $height = $metadata['dimensions_height'];
            $depth = $metadata['dimensions_depth'];
            $show_metadata = ($medium != null) || ($width != null) || ($height != null) ||
                ($depth != null);
        ?>
        <?php if ($show_metadata) {?>
            <h4>Detailed Information</h4>
            <dl>
                <?php
                    displayMetadata("Object Medium", $medium);
                    displayMetadata("Width", $width);
                    displayMetadata("Height", $height);
                    displayMetadata("Depth" , $depth);
                ?>
            </dl>
        <?php } ?>
    </div>

    <?php
}


function displayMetadata($heading, $value, $displayIfNull = false) {
    if (($value != null) || $displayIfNull) { ?>
        <dt><?php echo $heading; ?></dt>
        <dd><?php echo  $value; ?></dd>
    <?php }
}