var baseUrl = window.location.origin;
var storeId = document.getElementById( 'store_id' ).value;

jQuery( document ).ready( function() {
    var storeId = document.getElementById( 'store_id' ).value;
    jQuery.ajax({
        url: window.location.origin + '/wp-json/istilist/v2/shoppers/' + storeId,
        method: 'GET',
        data: {
            'paged': '0'
        },
        success: function( response ) {
            console.log( response );
        },
        error: function( response ) {
            console.log( response );
        }
    });

    jQuery( '#bulkActionSubmit' ).click( function() {
        Swal({
            title: 'Are you sure?',
            text: 'This action cannot be undone.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            preConfirm: function() {
                document.getElementById( 'bulkActionForm' ).submit();
            }
        });
    });

    //Send AJAX request to send text message to shopper
    jQuery( '.notifyShopper' ).click( function() {
        jQuery.ajax({
            context: this,
            url: baseUrl + '/notify-shopper/',
            method: 'POST',
            data: { shopperID: jQuery( this ).data( 'id' ) },
            success: function( response ) {
                swal({
                    title: 'Success!',
                    text: 'The shopper has been notified.',
                    type: 'success'
                });

                jQuery( this ).addClass( 'active' );
            },
            error: function( response ) {
                if ( 'na' == response.responseText ) {
                    swal({
                        title: 'Error',
                        text: 'This shopper did not authorize text messages.',
                        type: 'error'
                    });
                }

                jQuery( this ).addClass( 'shopper_action_error' );
            }
        });
    });

    jQuery( '.checkbox' ).click( function() {
        var inputElement = jQuery( this ).children( 'input' );
        var newValue = ! ( 'true' == inputElement.val() );
        inputElement.val( newValue.toString() );
        if ( newValue ) {
            jQuery( this ).append( '<i class="fa fa-check"></i>' );
        } else {
            jQuery( this ).children( 'i' ).remove();
        }
    });

    // Place shopper_id into the div
    // TODO MASON: Look into what this is really doing
    jQuery( '.assignStylist' ).click( function() {
        var shopperId = jQuery( this ).data( 'id' );
        jQuery( '#shopper_id' ).val( shopperId );
    });

    // Purchased or Not Button
    jQuery( '.dollar' ).click( function() {
        var shopperId = jQuery( this ).data( 'id' );
        function followUpSentAlert() {
            Swal( 'Follow Up Sent', '', 'success' );
            jQuery( this ).addClass( 'active' );
        }
        function errorProcessAlert() {
            Swal( 'Error', 'There was an error in processing your request, please try again!', 'error' );
            jQuery( this ).addClass( 'shopper_action_error' );
        }
        Swal.queue([ {
            title: 'Did the shopper make a purchase?',
            type: 'question',
            input: 'radio',
            confirmButtonText: 'Next',
            inputOptions: {
                'true': 'Yes',
                'false': 'No'
            },
            inputValidator: function( value ) {
                return ! value && 'You must choose one option.';
            },
            preConfirm: function( inputValue ) {

                // TODO MASON: Work on this dynamic queue
                if ( 'true' == inputValue ) {

                    // Send Purchased Shopper Message
                    jQuery.ajax({
                        url: baseUrl + '/complete-purchase',
                        method: 'POST',
                        data: {
                            'store_id': storeId,
                            'shopper_id': shopperId
                        },
                        success: followUpSentAlert(),
                        error: errorProcessAlert()
                    });
                } else {

                    // Gather additional details and send Not Purchased Shopper Message
                    Swal({
                        title: 'What was the reason?',
                        type: 'question',
                        input: 'text',
                        inputPlaceholder: ''
                    }).then( function( inputValue ) {
                        jQuery.ajax({
                            url: baseUrl + '/no-purchase',
                            method: 'POST',
                            data: {
                                'store_id': storeId,
                                'shopper_id': shopperId,
                                'reason': inputValue
                            },
                            success: followUpSentAlert(),
                            error: errorProcessAlert()
                        });
                    });
                }
            }
        } ]);
    });
});
