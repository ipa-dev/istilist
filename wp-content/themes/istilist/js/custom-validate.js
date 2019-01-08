jQuery( document ).ready( function() {
    var baseUrl = window.location.origin;
    jQuery( '#user_check' ).on( 'click', function() {
        var status = jQuery( '#forms_recheck_pass' ).valid();
        var recheckPass = jQuery( '#recheck_pass' ).val();

        if ( status ) {
            jQuery.ajax({
                url: baseUrl + '/ajax-recheck-pass/',
                type: 'post',
                cache: false,
                data: { 'recheck_pass': recheckPass },
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
            recheckPass: {
                required: true,
                minlength: 6 //TODO: should alert user of this
            }
        }
    });
});
