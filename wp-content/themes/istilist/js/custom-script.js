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
});

jQuery(function($){
    $('.matchheight').matchHeight();
});