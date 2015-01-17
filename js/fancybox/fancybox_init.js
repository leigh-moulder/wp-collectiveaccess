jQuery(document).ready(function($) {
    $("a.lightbox").click(function(e) {

        var type = $(this).attr("data-type");
        var id = $(this).attr("data-id");

        var width = $(this).attr("data-primaryImgWidth");

        $.fancybox.showActivity();
        e.preventDefault();
        $.post( CAWPLightbox.ajaxurl,
                    {action: 'cawp_display_lightbox', TYPE: type, ID: id}, function (response) {

            $.fancybox({
                content : response,
                // these mirror those found in <theme>/javascripts/advocate.js
                transitionIn:			"elastic",
                transitionOut:		    "elastic",
                easingIn:				"easeOutBack",
                easingOut:				"easeInBack",
                padding:				5,
                speedIn:      		    500,
                speedOut: 				500,
                hideOnContentClick:	    false,
                overlayShow:            true,
                width :                 width,
                //height :                705,
                //autoDimensions :        false,
                autoDimensions :        true,
                scrolling :             "auto",
                //autoScale :             false,
                autoScale :             true,
                //fitToView :             false,
                titleShow :             false,
                onComplete : function() {
                    //$('#fancybox-content').width(width);
                    // note resize only resizes height, not width!
                    $.fancybox.resize();
                }
            });
        });
    });
});