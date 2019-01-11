<?php
function validate_shoppers ($param, $request, $key) {
    return is_numeric( $param );
    //TODO MASON: Work on error handling if not a valid store id
}
?>