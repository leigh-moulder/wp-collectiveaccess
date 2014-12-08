<?php
$instance_type = $_POST['type'];
$json_object = $_POST['object'];

$object = json_decode($json_object, true);

if ($instance_type == "collection") {
    generateCollectionLightbox($object);
}
elseif ($instance_type == "object") {
    generateObjectLightbox($object);
}
else {

}
?>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.owl-carousel-small').owlCarousel({
            items: 6,
            center: true,
            loop: true,
            margin: 5,
            nav: true,
            navText: ['&#10094;', '&#10095;'],
            dots: false
        });
    });
</script>


<?php
function generateCollectionLightbox($collection) {
    $altImgCount = array_key_exists("alt_img", $collection) ? count($collection['alt_img']) : 0;
    ?>

    <h2><?php echo $collection["title"] ?></h2>
    <div class="lightbox_images">
        <div class="lightbox_primary_image">
            <img src="<?php echo $collection["img_primary_main"]?>"
                 alt="<?php echo $collection["title"]; ?>"/>
        </div>
        <div class="lightbox_alt_images">
            <ul>
                <?php for ($i = 0; $i < $altImgCount; $i++) { ?>
                    <li class="lightbox_alt_img">
                        <img src="<?php echo $collection['alt_img']['alt_img_' . $i . '_thumb'];?>"/>
                    </li>
                <?php }?>
            </ul>
        </div>
    </div>
<?php
}


function generateObjectLightbox($object) {

    $altImgCount = array_key_exists("alt_img", $object) ? count($object['alt_img']) : 0;
    ?>

    <h2><?php echo $object["title"] ?></h2>
    <div class="lightbox_images">
        <div class="lightbox_primary_image">
            <img src="<?php echo $object["img_primary_main"]?>"
                 alt="<?php echo $object["title"]; ?>"/>
        </div>
        <?php if ($altImgCount > 0) { ?>
            <div id="alt_images" class="owl-carousel owl-carousel-small">
                <div class="item">
                    <img src="<?php echo $object['img_primary_thumb'];?>"/>
                </div>

                <?php for ($i = 0; $i < $altImgCount; $i++) { ?>
                    <div class="item">
                        <img src="<?php echo $object['alt_img']['alt_img_' . $i . '_thumb'];?>"/>
                    </div>
                <?php } ?>
            </div>
        <?php }?>
    </div>

    <div class="description">
        <p><?php echo $object["description"]; ?></p>
    </div>
<?php
}