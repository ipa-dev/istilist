<?php
function validate_shopper_forms ( $param, $request, $key ) {
    return is_numeric( $param ); // && !get_userdata( $request['store_id'] );
}
?>