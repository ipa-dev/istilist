jQuery( document ) .ready( function() {
    jQuery( '#testPromoText' ).click( function() {
        jQuery.ajax({
            url: window.location.origin + '/wp-json/istilist/v2/texts/' + document.getElementById( 'store_id' ).value,
            method: 'POST',
            data: {
                type: 'test-timed-promo',
                shopper_id: '-1'
            }
        });
    });
});
