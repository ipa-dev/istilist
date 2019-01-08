var baseUrl = window.location.origin;
var storeId = document.getElementById( 'store_id' ).value;
function sendTextNotification( shopperId ) {

    //Send AJAX request to PHP script that sends text message to shopper
    jQuery.ajax({
        url: baseUrl + '/notify-shopper/',
        method: 'POST',
        data: {shopperID: shopperId},
        error: function( e ) {
            if ( 'na' == e ) {
                swal({
                    title: 'Error',
                    text: 'This shopper did not authorize text messages.',
                    type: 'error'
                });
            }
        }
    });

    jQuery( '#' + shopperId + '-bell' ).css( 'color', '#14b9d6' );
}

function check( shopperId ) {
    var input = document.getElementById( 'checkInput' + shopperId );

    if ( '' == input.value || 'no' == input.value ) {
        input.value = 'yes';
        jQuery( '#checkBox' + shopperId ).append( '<i class="fa fa-check"></i>' );
        jQuery( '#checkBox' + shopperId ).css( 'color', '#14b9d6' );
    } else if ( 'yes' == input.value ) {
        input.value = 'no';
        jQuery( '#checkBox' + shopperId ).empty();
        jQuery( '#checkBox' + shopperId ).css( 'color', '' );
    }
}

function confirmation( event ) {
    event.preventDefault();
    swal({
        title: 'Are you sure?',
        text: 'This action cannot be undone.',
        type: 'warning',
        showCancelButton: true,
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        closeOnConfirm: false,
        closeOnCancel: true
    }, function( isConfirm ) {
        if ( isConfirm ) {
            document.getElementById( 'bulkActionForm' ).submit();
        }
    });
}

jQuery( document ).ready( function() {

    document.getElementById( 'bulkActionSubmit' ).onclick = confirmation;

    // this is for second button
    jQuery( '.assignStylist' ).click( function() {
        var shopperId = jQuery( this ).attr( 'rel' );
        jQuery( '#shopper_id' ).val( shopperId );
    });

    // this is for 3rd button
    jQuery( '.dollar' ).click( function() {
        var shopperId = jQuery( this ).attr( 'rel' );
        swal({
            title: 'Made a Purchase?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            closeOnConfirm: false,
            closeOnCancel: false
        }, function( isConfirm ) {
            if ( isConfirm ) {
                jQuery.ajax({
                    url: baseUrl + '/complete-purchase',
                    type: 'post',
                    data: {
                        'store_id': storeId,
                        'shopper_id': shopperId
                    },
                    success: function( response ) {
                        swal({
                            title: 'Thank You',
                            type: 'success'
                        }, function() {
                            location.reload();
                        });
                    },
                    error: function( response ) {
                        console.log( response );
                    }
                });
            } else {
                swal({
                    title: 'Reason',
                    type: 'input',
                    showCancelButton: true,
                    closeOnConfirm: false,
                    animation: 'slide-from-top',
                    inputPlaceholder: ''
                }, function( inputValue ) {
                    if ( false == inputValue ) {
                        return false;
                    }
                    if ( '' == inputValue ) {
                        inputValue = '.';
                    }
                    jQuery.ajax({
                        url: baseUrl + '/no-purchase',
                        type: 'post',
                        data: {
                            'store_id': storeId,
                            'shopper_id': shopperId,
                            'reason': inputValue
                        },
                        success: function( response ) {
                            swal.close();
                            location.reload();
                        },
                        error: function( response ) {
                            console.log( response );
                        }
                    });
                });
            }
        });
    });
});
