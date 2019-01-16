<?php
function get_shoppers( $request ) {

    $needed_metadata_fields = array(
        'dollar_button_clicked', 'customer_fname', 'customer_lname', 'school_event',
        'assign_stylist', 'stylist_id', 'fitting_room_id', 'entry_date',
        'hit_plus', 'reason_not_purchased', 'timestamps', 'purchased_array'
    );

    $post_args = array(
        'post_type'      => 'shopper',
        'post_status'    => 'publish',
        'meta_key'       => 'store_id',
        'meta_value'     => $request['store_id'],
        'paged'          => $request['page'],
        'posts_per_page' => 10,
        'orderby'        => 'modified',
    );

    if (isset($request['search_query'])) {
        header('HTTP/1.1 501 Invalid Parameters');
        return rest_ensure_response('Functionality not yet supported');
    }
    
    $query = new WP_Query($post_args);

    //building return array - TODO MASON: Is there a way to avoid looping through again
    $response_array = array();
    while ($query->have_posts()) {
        $query->the_post();
        $shopper = array();
        foreach( $needed_metadata_fields as $needed_metadata_field ) {
            $shopper[$needed_metadata_field] = get_post_meta( get_the_ID(), $needed_metadata_field, true);
        }
        
        $shopper['stylist_name'] = get_the_author_meta( 'display_name', $shopper['stylist_id'] );
        array_push($response_array, $shopper);
    }
    return rest_ensure_response( json_encode( $response_array ) );
}
?>