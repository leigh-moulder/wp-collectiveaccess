jQuery(document).ready(function($) {
    $("a.lightbox").click(function(e) {

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
            transitionIn:			"elastic",
            transitionOut:		    "elastic",
            easingIn:				"easeOutBack",
            easingOut:				"easeInBack",
            padding:				0,
            speedIn:      		    500,
            speedOut: 				500,
            hideOnContentClick:	    false,
            overlayShow:            true,
            width :                 700,
            height :                800,
            autoDimensions :        false,
            scrolling :             "auto",
            autoScale :             false,
            titleShow :             false
        })
    })
});