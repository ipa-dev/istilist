jQuery( function() {
    jQuery( '.nav' ).slicknav({
        prependTo: '#rspnavigation',
        label: ''
    });
});

function initializeTimepicker( objectString ) {
    jQuery( objectString ).datetimepicker({
        timepicker: false,
        format: 'm-d-Y',
        onSelectDate: function( ct, $i ) {
            jQuery( '.xdsoft_datetimepicker' ).hide();
        }
    });
}

jQuery( document ).ready( function() {
    var baseUrl = window.location.origin;
    jQuery( '.assignStylist' ).fancybox({
        maxWidth: 300,
        maxHeight: 220,
        fitToView: false,
        width: '90%',
        height: '90%',
        autoSize: false,
        closeClick: false,
        openEffect: 'none',
        closeEffect: 'none'
    });
    jQuery( '.popupform' ).fancybox({
        maxWidth: 300,
        maxHeight: 172,
        fitToView: false,
        width: '90%',
        height: '90%',
        autoSize: false,
        closeClick: false,
        openEffect: 'none',
        closeEffect: 'none'
    });
    initializeTimepicker( '#purchase_date' );
    initializeTimepicker( '#customer_wear_date' );
    initializeTimepicker( '#fromdate' );
    initializeTimepicker( '#todate' );
    initializeTimepicker( '#shoppersfromdate' );

    jQuery( '.editFormTable input[type=checkbox]' ).switchButton({
        width: 50,
        height: 20,
        button_width: 25
    });
    jQuery( '.stylist_employee input[type=checkbox]' ).switchButton({
        width: 50,
        height: 20,
        button_width: 25
    });
    jQuery( '#school_event' ).autocomplete( baseUrl + '/autocomplete-school', {
        selectFirst: true
    });

    jQuery( '#designer' ).autocomplete( baseUrl + '/autocomplete-designer', {
        selectFirst: true
    });
    jQuery( '#parentHorizontalTab' ).easyResponsiveTabs({
        type: 'default',
        width: 'auto',
        fit: true,
        tabidentify: 'hor_1',
        activetab_bg: '#025597',
        inactive_bg: '#FFFFFF'
    });
    jQuery( '.footable' ).footable();
    jQuery( '.editFormTable' ).footable();
    jQuery( '#forms_recheck_pass' ).validate({
        rules: {
            recheck_pass: {
                required: true,
                minlength: 6
            }
        }
    });
    jQuery( '#user_check' ).on( 'click', function() {
        var status = jQuery( '#forms_recheck_pass' ).valid();
        var recheck_pass = jQuery( '#recheck_pass' ).val();

        if ( status ) {
            jQuery.ajax({
                url: baseUrl + '/ajax-recheck-pass/',
                type: 'post',
                cache: false,
                data: { 'recheck_pass': recheck_pass },
                success: function( response ) {
                    if ( 1 == response ) {
                        window.location.href = baseUrl;
                    }
                },
                error: function( response ) {
                    console.log( response );
                }
            });
        }
    });
    jQuery( '#forms_recheck_pass' ).validate({
        rules: {
            recheck_pass: {
                required: true,
                minlength: 6
            }
        }
    });
    jQuery( '#emailthisreport' ).click( function() {
        var chart1 = jQuery( '#chart_to_png_trafficflow' ).html();
        var chart2 = jQuery( '#chart_to_png_trafficflowweekly' ).html();
        var chart3 = jQuery( '#chart_to_png_trafficflowmonthly' ).html();
        var chart4 = jQuery( '#chart_to_png_trafficflowweekly1' ).html();
        var chart5 = jQuery( '#chart_to_png_trafficflowmonthly1' ).html();
        jQuery.ajax({
            url: baseUrl + '/send-report',
            type: 'post',
            data: {
                'chart1': chart1,
                'chart2': chart2,
                'chart3': chart3,
                'chart4': chart4,
                'chart5': chart5
            },
            success: function( response ) {
                alert( 'Email successfully' );
            },
            error: function( response ) {
                alert( 'there is error while submit' );
            }
        });
    });
});

jQuery( function( $ ) {
    $( '.matchheight' ).matchHeight();
});
