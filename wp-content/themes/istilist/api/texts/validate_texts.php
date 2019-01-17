<?php
function validate_texts ( $param, $request, $key ) {
    return is_numeric( $param ); // && !get_userdata( $request['store_id'] );
}
?>