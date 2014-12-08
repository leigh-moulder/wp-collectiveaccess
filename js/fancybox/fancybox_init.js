jQuery(document).ready(function($) {
    $("a.lightbox").click(function(e) {
        $.fancybox.showActivity();
        e.preventDefault();
        $.fancybox(this,{
            type : 'ajax',
            ajax : {
                type : "POST",
                data : {
                    type : $(this).attr("data-type"),
                    object : $(this).attr("data-json")
                }
            },
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
            width :                 705,    // add the padding to max img size
            height :                705,
            autoDimensions :        false,
            autoSize :              true,
            scrolling :             "auto",
            autoScale :             false,
            fitToView :             false,
            titleShow :             false
        });

        $.fancybox.resize();
    })
});