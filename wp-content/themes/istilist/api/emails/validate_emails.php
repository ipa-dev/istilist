<?php
function validate_emails ( $param, $request, $key ) {
    return is_numeric( $param ); // && !get_userdata( $request['store_id'] );
}
?>