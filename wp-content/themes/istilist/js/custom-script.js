jQuery( function () {
  jQuery('.nav').slicknav({
    prependTo: '#rspnavigation',
    label: ''
  });
})

jQuery(document).ready(function() {
    jQuery(".fancybox").fancybox({

    });

    jQuery(".assignStylist").fancybox({
        maxWidth	: 300,
           maxHeight	: 220,
        fitToView	: false,
        width		: '90%',
        height		: '90%',
        autoSize	: false,
        closeClick	: false,
        openEffect	: 'none',
        closeEffect	: 'none'
    });
    jQuery(".popupform").fancybox({
        maxWidth	: 300,
        maxHeight	: 172,
        fitToView	: false,
        width		: '90%',
        height		: '90%',
        autoSize	: false,
        closeClick	: false,
        openEffect	: 'none',
        closeEffect	: 'none'
    });
    jQuery('#purchase_date').datetimepicker({
        timepicker:false,
        format:'m-d-Y',
        onSelectDate:function(ct,$i){
            jQuery('.xdsoft_datetimepicker').hide();
        }
    });
    jQuery('#customer_wear_date').datetimepicker({
        timepicker:false,
        format:'m-d-Y',
        onSelectDate:function(ct,$i){
            jQuery('.xdsoft_datetimepicker').hide();
        }
    });
    jQuery('#fromdate').datetimepicker({
        timepicker:false,
        format:'m-d-Y',
        onSelectDate:function(ct,$i){
            jQuery('.xdsoft_datetimepicker').hide();
        }
    });
    jQuery('#todate').datetimepicker({
        timepicker:false,
        format:'m-d-Y',
        onSelectDate:function(ct,$i){
            jQuery('.xdsoft_datetimepicker').hide();
        }
    });
    jQuery('#shoppersfromdate').datetimepicker({
    	timepicker:false,
    	format:'Y-m-d',
    	onSelectDate:function(ct,$i){
            jQuery('.xdsoft_datetimepicker').hide();
        }
    });
    jQuery(".editFormTable input[type=checkbox]").switchButton({
        width: 50,
        height: 20,
        button_width: 25
    });
    jQuery(".stylist_employee input[type=checkbox]").switchButton({
        width: 50,
        height: 20,
        button_width: 25
    });
    jQuery("#school_event").autocomplete("<?php get_bloginfo('url'); ?>/autocomplete-school", {
    	selectFirst: true
    });

    jQuery("#designer").autocomplete("<?php get_bloginfo('url'); ?>/autocomplete-designer", {
    	selectFirst: true
    });
    jQuery('#parentHorizontalTab').easyResponsiveTabs({
        type: 'default',
        width: 'auto',
        fit: true,
        tabidentify: 'hor_1',
        activetab_bg: '#025597',
        inactive_bg: '#FFFFFF',
    });
    jQuery('.footable').footable();
    jQuery('.editFormTable').footable();
    jQuery("#forms_recheck_pass").validate({
        rules: {
            recheck_pass:{
                required: true,
                minlength: 6
            }
        }
    });

    jQuery('#user_check').on('click', function() {
        var status = jQuery("#forms_recheck_pass").valid();
        if(status){
            var recheck_pass = jQuery('#recheck_pass').val();
            jQuery.ajax({
                url: "<?php echo get_bloginfo('url'); ?>/ajax-recheck-pass/",
                type: "post",
                cache: false,
                data: {"recheck_pass": recheck_pass},
                success: function(response){
                    if(response == 1){
                        window.location.href = "<?php echo get_bloginfo('url'); ?>";
                    }
                },
                error:function(response){
                    console.log(response);
                }
            });
        }
    });
});

jQuery(function($){
    $('.matchheight').matchHeight();
});