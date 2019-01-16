jQuery( document ).ready( function() {
    jQuery( '.popupform' ).click( function() {
        Swal({
            title: 'Re-enter your password',
            input: 'password',
            inputValidator: function( value ) {
                return ! value && 'You must re-enter your password to return!';
            },
            preConfirm: function( pass ) {
                jQuery.ajax({
                    url: window.location.origin + '/ajax-recheck-pass/',
                    method: 'POST',
                    cache: false,
                    data: { 'recheck_pass': pass },
                    success: function( response ) {
                        if ( 1 == response ) {
                            window.location.href = window.location.origin;
                        }
                    },
                    error: function( response ) {
                        swal( 'Incorrect Password', '', 'error' );
                    }
                });
            }

        });
    });
});
