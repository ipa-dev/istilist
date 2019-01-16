jQuery( document ).ready( function() {
    var baseUrl = window.location.origin;

    jQuery( '#forms' ).validate({
        rules: {
            employee_name: {
                required: true
            }
        }
    });
});
