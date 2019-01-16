
jQuery( document ).ready( function() {
    jQuery( '#add_employee' ).click( function( event ) {
        var url =  window.location.origin + '/wp-json/istilist/v2/stylists/' + document.getElementById( 'store_id' ).value;

        event.preventDefault();
        Axios.post( url, {
            emailAddress: document.getElementById( 'employee_email_address' ).value,
            employeeName: document.getElementById( 'employee_name' ).value,
            employeerole: document.getElementById( 'employeerole' ).value,
            phoneNumber: document.getElementById( 'employee_phone_number' ).value,
            password: document.getElementById( 'employee_password' ).value
        })
        .then( function( response ) {
            swal( 'Success!', 'Employee was successfully added', 'success' );
        })
        .catch( function( error ) {
            swal( 'Error!', 'Whoops! There was an error on our end. Please try again later!', 'error' );

            //TODO MASON: NEED AN ERROR LOG HERE
        });
    });
});
