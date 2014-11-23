jQuery(document).ready(function($) {
    $('.lightbox').click(function() {
        var data = $(this).attr('data');
        $.fancybox({
            'ajax'  : {
            type : "POST",
                data : {
                data : data
            }
        }
        });
    });
});