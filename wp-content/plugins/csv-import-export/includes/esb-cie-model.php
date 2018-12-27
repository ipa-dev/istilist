<?php

/**
 * Model File
 * Handles to database functionality & other functions
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
* Escape Attr
*/
function esb_cie_escape_attr($data){

    if( !empty( $data ) ) {
        $data = esc_attr(stripslashes_deep($data));
    }
    return $data;
}

/**
* Strip Slashes From Array
*/
function esb_cie_escape_slashes_deep($data = array(),$flag=true){

    if($flag != true) {
            $data = esb_cie_nohtml_kses($data);
    }
    $data = stripslashes_deep($data);
    return $data;
}

/**
* Strip Html Tags
*
* It will sanitize text input (strip html tags, and escape characters)
*/
function esb_cie_nohtml_kses($data = array()) {

    if ( is_array($data) ) {

            $data = array_map(array($this,'esb_cie_nohtml_kses'), $data);

    } elseif ( is_string( $data ) ) {

            $data = wp_filter_nohtml_kses($data);
    }

    return $data;
}

/**
 * Convert Object To Array
 */
function esb_cie_object_to_array($result) {

    $array = array();
    foreach ($result as $key=>$value)
    {
        if (is_object($value)) {
            $array[$key]=esb_cie_object_to_array($value);
        } else {
            $array[$key]=$value;
        }
    }
    return $array;
}

/**
 * Get Date Format
 *
 * Handles to return formatted date which format is set in backend
 */
function esb_cie_get_date_format( $date, $time = false ) {

    $format = $time ? get_option( 'date_format' ).' '.get_option('time_format') : get_option('date_format');
    $date = date_i18n( $format, strtotime($date));
    return $date;
}

/**
 * Get Absulate path
 */
function esb_cie_get_absulate_path( $url ) {

    $abs = '';
    if( !empty( $url ) ) {

        $upload_path = wp_upload_dir();
        $abs = str_replace($upload_path['baseurl'], $upload_path['basedir'], $url);
    }
    return $abs;
}

/**
 * String Conversation with UTF-8
 * Version 1.1.0
 */
function esb_cie_string_conversion($string){
    if( !preg_match( '%(?:
       [\xC2-\xDF][\x80-\xBF]        # non-overlong 2-byte
       |\xE0[\xA0-\xBF][\x80-\xBF]               # excluding overlongs
       |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}      # straight 3-byte
       |\xED[\x80-\x9F][\x80-\xBF]               # excluding surrogates
       |\xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
       |[\xF1-\xF3][\x80-\xBF]{3}                  # planes 4-15
       |\xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
       )+%xs', $string ) ) {

        return utf8_encode($string);
    } else {
        return $string;
    }
}

/**
 * Get all post status
 */
function esb_cie_get_all_status() {
    $all_status = array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash');
    return $all_status;
}

/**
 * Check post status and return valid post status
 */
function esb_cie_check_post_status( $status = 'draft' ) {
    $all_status = esb_cie_get_all_status();
    if( !empty( $status ) && in_array( $status, $all_status ) ) {
        return $status;
    } else {
        return 'draft';
    }
}

/**
 * Check post author and return valid post author
 */
function esb_cie_check_post_author( $author ) {

    global $user_ID;

    if( !empty( $author ) && !intval( $author ) ) {
        $user = get_user_by( 'login', $author );
        return $user->ID;
    } else {
        return $user_ID;
    }
}

/**
 * Check post date and return valid post date
 */
function esb_cie_check_post_date( $post_date = '' ) {

    if( !empty( $post_date ) ) {
        $timestamp = strtotime( $post_date );
        $post_date = date( 'Y-m-d H:i:s', $timestamp );
    }
    return $post_date;
}


/**
 * Check post by post slug and return valid post id
 */
function esb_cie_check_post_by_slug( $post_slug, $post_type = 'any' ) {

    if( !empty( $post_slug ) ) {
        $args = array(
                            'post_type'     => $post_type,
                            'name'          => sanitize_title($post_slug),
                            'posts_per_page'=> 1,
                            'fields'        => 'ids'
                        );
        $post_ids = get_posts( $args );
        return !empty( $post_ids ) && !empty( $post_ids['0'] ) ? $post_ids['0'] : 0;
    } else {
        return 0;
    }
}

/**
 * Check post by post slug and return valid post id
 */
function esb_cie_check_post_by_title( $post_title, $post_type = 'any' ) {

    if( !empty( $post_title ) ) {
        /*
        $args = array(
                            'post_type'     => $post_type,
                            's'          => sanitize_title($post_title),
                            'posts_per_page'=> 1,
                            'fields'        => 'ids'
                        );
        $post_ids = get_posts( $args );
        */
        global $wpdb;
        $search_query = "SELECT ID FROM $wpdb->posts
                         WHERE post_type = '$post_type'
                         AND post_title LIKE '$post_title'";

        //$like = '%'.$post_title.'%';
        $results = $wpdb->get_results($wpdb->query($search_query), ARRAY_N);
        foreach($results as $key => $array){
             $post_ids[] = $array['ID'];
        }
        return !empty( $post_ids ) && !empty( $post_ids['0'] ) ? $post_ids['0'] : 0;
    } else {
        return 0;
    }
}

/**
 * Check post image and return valid post image
 */
function esb_cie_check_post_image( $post_image_slug ) {

    if( !empty( $post_image_slug ) ) {
        $args = array(
                            'post_type'     => 'attachment',
                            'name'          => sanitize_title($post_image_slug),
                            'posts_per_page'=> 1,
                            'fields'        => 'ids'
                        );
        $post_ids = get_posts( $args );
        return !empty( $post_ids ) && !empty( $post_ids['0'] ) ? $post_ids['0'] : 0;
    } else {
        return 0;
    }
}
function esb_cie_check_additional_images( $additional_images_slugs ) {

    if( !empty( $additional_images_slugs ) ) {
        $img_array = array();
       $additional_images_slug_array = explode('|',$additional_images_slugs);
       $i = 0;
       foreach($additional_images_slug_array as $slug) {
            array_push($img_array, array());
        $args = array(
                            'post_type'     => 'attachment',
                            'name'          => sanitize_title($slug),
                            'posts_per_page'=> 1,
                            'fields'        => 'ids'
                        );
        $post_ids = get_posts( $args );
        if(!empty( $post_ids ) && !empty( $post_ids['0'] )) {
            $img_array[$i]["ID"] = $post_ids['0'];
            $img_array[$i]["url"] = get_the_guid($post_ids['0']);
            //array_push($img_array/*[$i]["ID"]*/, $post_ids['0']);
            //array_push($img_array[$i]["url"], get_the_guid($pos_ids['0']));
        }

        $i++;
       }
       return $img_array;
    } else {
        return 0;
    }
}

/**
 * Check comment status and return valid comment status
 */
function esb_cie_check_comment_status( $status = 'closed' ) {

    if( !empty( $status ) && in_array( $status, array( 'open', 'closed' ) ) ) {
        return $status;
    } else {
        return 'closed';
    }
}

/**
 * Check ping status and return valid ping status
 */
function esb_cie_check_ping_status( $status = 'closed' ) {

    if( !empty( $status ) && in_array( $status, array( 'open', 'closed' ) ) ) {
        return $status;
    } else {
        return 'closed';
    }
}

/**
 * Get all post types
 */
function esb_cie_get_all_post_types() {

    $args = array(
                        'public'   => true,
                        '_builtin' => false
                     );
    $output = 'objects'; // names or objects
    $post_types = get_post_types( $args, $output );
    unset( $post_types['attachment'] );

    return $post_types;
}

/**
 * Get all taxonomies
 */
function esb_cie_get_all_taxonomies( $post_type ) {

    $taxonomies = array();
    if( !empty( $post_type ) ) {

        $taxonomies = get_object_taxonomies( $post_type, 'objects' );
        if( !empty( $taxonomies ) ) {
            if( isset( $taxonomies['post_format'] ) ) {
                unset( $taxonomies['post_format'] );
            }
            foreach( $taxonomies as $key => $taxonomy ) {
                if( empty( $taxonomy->public ) ) {
                    unset( $taxonomies[$key] );
                }
            }
        }
    }
    return $taxonomies;
}

/**
 * Get Post Type Tabs
 */
function esb_cie_post_type_tabs() {

    $post_types = esb_cie_get_all_post_types();

    $all_post_types = array();
    if( !empty( $post_types ) ) {
        foreach ( $post_types as $post_type => $post_type_data ) {

            $menu_title = !empty( $post_type_data->labels ) && !empty( $post_type_data->labels->menu_name ) ? $post_type_data->labels->menu_name : $post_type_data->label;
            $all_post_types[$post_type] = $menu_title;
        }
    }

    $tabs = array();

    $tabs = array_merge( $tabs, $all_post_types );

    return $tabs;
}

/**
 * Insert Term
 */
function esb_cie_insert_term( $taxonomy_name, $term_title, $term_slug = '', $term_description = '', $parent_term_id = 0 ) {

    $current_term_id = 0;
    if( !empty( $taxonomy_name ) && !empty( $term_title ) ) {
        $result = wp_insert_term(
                        $term_title, // the term
                        $taxonomy_name, // the taxonomy
                        array(
                            'description'=> $term_description,
                            'slug'       => $term_slug,
                            'parent'     => $parent_term_id
                        )
                    );
        if( !is_wp_error( $result ) ) {
            $current_term_id = isset( $result['term_id'] ) ? $result['term_id'] : 0;
        }
    }
    return $current_term_id;
}

/**
 * Get all post fields
 */
function esb_cie_get_all_post_fields() {

    $default_options = array(
                                array(
                                        'key'       => 'post_name',
                                        'label'     => __( 'Name / Identifier / Slug', 'esbcie' ),
                                        'notice'    => __( 'Name (slug) for identifing item', 'esbcie' ),
                                ),
                                array(
                                        'key'       => 'post_title',
                                        'label'     => __( 'Title', 'esbcie' ),
                                        'notice'    => ''
                                ),
                                array(
                                        'key'       => 'post_status',
                                        'label'     => __( 'Status', 'esbcie' ),
                                        'notice'    => __( 'Available values: <b>draft</b>, <b>publish</b>, <b>pending</b>, <b>future</b>, <b>private</b>. Default value is <b>draft</b>', 'esbcie' )
                                ),
                                array(
                                        'key'       => 'post_content',
                                        'label'     => __( 'Content', 'esbcie' ),
                                        'notice'    => ''
                                ),
                                array(
                                        'key'       => 'post_excerpt',
                                        'label'     => __( 'Excerpt', 'esbcie' ),
                                        'notice'    => ''
                                ),
                                array(
                                        'key'       => 'post_author',
                                        'label'     => __( 'Author username', 'esbcie' ),
                                        'notice'    => __( 'Default author is currently logged user', 'esbcie' )
                                ),
                                array(
                                        'key'       => 'post_parent',
                                        'label'     => __( 'Parent', 'esbcie' ),
                                        'notice'    => __( 'Name (slug) of parent item if post type support it', 'esbcie' )
                                ),
                                array(
                                        'key'       => 'post_date',
                                        'label'     => __( 'Date', 'esbcie' ),
                                        'notice'    => __( 'Date in format: <b>Y-m-d H:i:s</b> (e.g. <b>2014-08-20 17:16:18</b>). Default insert current date and time.', 'esbcie' )
                                ),
                                array(
                                        'key'       => 'post_image',
                                        'label'     => __( 'Featured Image', 'esbcie' ),
                                        'notice'    => __( 'Slug (name) of media file', 'esbcie' )
                                ),
                                array(
                                        'key'       => 'comment_status',
                                        'label' => __( 'Comment status', 'esbcie' ),
                                        'notice' => __( 'Available values: <b>closed</b>, <b>open</b>. Default value is <b>closed</b>', 'esbcie' )
                                ),
                                array(
                                        'key'       => 'ping_status',
                                        'label' => __( 'Ping status', 'esbcie' ),
                                        'notice' => __( 'Available values: <b>closed</b>, <b>open</b>. Default value is <b>closed</b>', 'esbcie' )
                                ),
                        );
    return $default_options;
}

/**
 * Get all term fields
 */
function esb_cie_get_all_term_fields() {

    $default_options = array(
                                array(
                                        'key'       => 'slug',
                                        'label'     => __( 'Name / Identifier / Slug', 'esbcie' ),
                                        'notice'    => __( 'Name (slug) for identifing item', 'esbcie' ),
                                ),
                                array(
                                        'key'       => 'name',
                                        'label'     => __( 'Title', 'esbcie' ),
                                        'notice'    => ''
                                ),
                                array(
                                        'key'       => 'description',
                                        'label'     => __( 'Description', 'esbcie' ),
                                        'notice'    => ''
                                ),
                        );
    return $default_options;
}



function esb_cie_check_fname( $fname ) {

    if( !empty( $fname ) ) {
        $fname = $fname;
    } else {
        $fname = '';
    }
    return $fname;
}

function esb_cie_check_lname( $lname ) {

    if( !empty( $lname ) ) {
        $lname = $lname;
    } else {
        $lname = '';
    }
    return $lname;
}

function esb_cie_check_school( $school ) {

    if( !empty( $school ) ) {
        $school = $school;
    } else {
        $school = '';
    }
    return $school;
}

function esb_cie_check_gradyear( $gradyear ) {

    if( !empty( $gradyear ) ) {
        $gradyear = $gradyear;
    } else {
        $gradyear = '';
    }
    return $gradyear;
}

function esb_cie_check_customer_email( $customer_email ) {

    if( !empty( $customer_email ) ) {
        $customer_email = $customer_email;
    } else {
        $customer_email = '';
    }
    return $customer_email;
}

function esb_cie_check_customer_phone( $customer_phone ) {

    if( !empty( $customer_phone ) ) {
        $customer_phone = $customer_phone;
    } else {
        $customer_phone = '';
    }
    return $customer_phone;
}

function esb_cie_check_designer_preferences( $designer_preferences ) {

    if( !empty( $designer_preferences ) ) {
        $designer_preferences = $designer_preferences;
    } else {
        $designer_preferences = '';
    }
    return $designer_preferences;
}

function esb_cie_check_style_preferences( $style_preferences ) {

    if( !empty( $style_preferences ) ) {
        $style_preferences = $style_preferences;
    } else {
        $style_preferences = '';
    }
    return $style_preferences;
}

function esb_cie_check_color_preferences( $color_preferences ) {

    if( !empty( $color_preferences ) ) {
        $color_preferences = $color_preferences;
    } else {
        $color_preferences = '';
    }
    return $color_preferences;
}

function esb_cie_check_customer_size( $customer_size ) {

    if( !empty( $customer_size ) ) {
        $customer_size = $customer_size;
    } else {
        $customer_size = '';
    }
    return $customer_size;
}

function esb_cie_check_customer_address( $customer_address ) {

    if( !empty( $customer_address ) ) {
        $customer_address = $customer_address;
    } else {
        $customer_address = '';
    }
    return $customer_address;
}

function esb_cie_check_customer_city( $customer_city ) {

    if( !empty( $customer_city ) ) {
        $customer_city = $customer_city;
    } else {
        $customer_city = '';
    }
    return $customer_city;
}

function esb_cie_check_customer_state( $customer_state ) {

    if( !empty( $customer_state ) ) {
        $customer_state = $customer_state;
    } else {
        $customer_state = '';
    }
    return $customer_state;
}

function esb_cie_check_customer_zip( $customer_zip ) {

    if( !empty( $customer_zip ) ) {
        $customer_zip = $customer_zip;
    } else {
        $customer_zip = '';
    }
    return $customer_zip;
}

function esb_cie_check_store_id( $store_id ) {

    if( !empty( $store_id ) ) {
        $store_id = $store_id;
    } else {
        $store_id = '';
    }
    return $store_id;
}
/**
 * Check post Meta and Get Entry date
 */
function esb_cie_check_entry_date( $entry_date ) {

    if( !empty( $entry_date ) ) {
    	$newDate = date("Y-m-d HH:II:SS", strtotime($entry_date ));
        $entry_date = $newDate ;
    } else {
        $entry_date = '';
    }
    return $entry_date ;
}


?>
