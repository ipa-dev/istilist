<?php
function get_stylists( $request ) {

    $user_query = new WP_User_Query(array(
        'role'       => 'storeemployee',
        'meta_key'   => 'store_id',
        'meta_value' => $request['store_id'],
        'orderby'    => 'display_name',
        'order'      => 'ASC'
    ));

    $response_array = array();

    foreach ($user_query->results as $user) {
        $stylist = array();
        if ($user->user_status != 0) {
            $stylist['id'] = $user->ID;
            $stylist['display_name'] = $user->display_name;
            array_push( $response_array, $stylist );
        }
    }

    return rest_ensure_response( json_encode( $response_array ) );
}
?>