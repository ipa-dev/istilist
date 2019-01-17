<?php
function update_email( $request ) {
    if ( isset($request['type'] ) ) {
        if ( $request['type'] == 'no-purchase' ) {
            
        }
        elseif ( $request['type'] == 'purchase' ) {

        }
        elseif ( $request['type'] == 'employee' ) {

        }
        else {
            return new WP_Error( 'invalid_type', 'E-mail type given is invalid', array( 'status' => 404 ) );
        }
    }
    else {
        return new WP_Error( 'no_type', 'No e-mail type given', array( 'status' => 404 ) );
    }
}
?>