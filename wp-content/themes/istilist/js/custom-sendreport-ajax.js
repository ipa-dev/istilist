jQuery( document ).ready( function() {
    var baseUrl = window.location.origin;
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
                swal({
                    title: 'Success!',
                    text: 'Report was e-mailed successfully',
                    type: 'success'
                });
            },
            error: function( response ) {
                swal({
                    title: 'Whoops!',
                    text: 'There was an error in processing. Give us a minute and we\'ll work on that',
                    type: 'error'
                });
            }
        });
    });
});
