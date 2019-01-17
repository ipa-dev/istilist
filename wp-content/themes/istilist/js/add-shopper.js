jQuery( document ).ready( function() {
    jQuery( '#add_new_shopper' ).click( function() {
        var apiUrl = window.location.origin + '/wp-json/istilist/v2/shoppers/' + document.getElementById( 'store_id' ).value;
        axios.post( apiUrl, {
            customer_fname: document.getElementById( 'customer_fname' ).value,
            customer_lname: document.getElementById( 'customer_lname' ).value,
            school_event: document.getElementById( 'school_event' ).value,
            graduation_year: document.getElementById( 'graduation_year' ).value,
            customer_email: document.getElementById( 'customer_email' ).value,
            customer_phone: document.getElementById( 'customer_phone' ).value,
            design_preferences: document.getElementById( 'design_preferences' ).value,
            style_preferences: document.getElementById( 'style_preferences' ).value,
            color_preferences: document.getElementById( 'color_preferences' ).value,
            customer_size: document.getElementById( 'customer_size' ).value,
            customer_address: document.getElementById( 'customer_address' ).value,
            customer_city: document.getElementById( 'customer_city' ).value,
            customer_state: document.getElementById( 'customer_state' ),
            customer_zip: document.getElementById( 'customer_zip' ),
            sms_agreement: document.getElementById( 'sms_agreement' ),
            callback_url: document.getElementById( 'callback_url' )
        })
        .catch( function( error ) {
            console.log( error );
        });

        window.location = window.location.origin + '/dashboard/';
    });
	jQuery( '#forms' ).validate({
		rules: {
			customer_fname: {
				required: true
			},
			customer_lname: {
				required: true
			},
			school_event: {
				required: true
			},
			graduation_year: {
				required: true
			}
		}
	});
});
