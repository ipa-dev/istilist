<?php
global $options;
$options['general-copyright'] = 'Copyright © All Rights Reserved ' . date( 'Y' ) . ' iSTiLiST - International Prom Association';
$options['general-logo'] = 'istilist';
$options['general-background'] = get_bloginfo( 'url' ) . '/wp-content/uploads/2017/03/istilist-main-image-background-compressor.jpg';

remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );

require_once 'api/api.php';

add_action( 'rest_api_init', function () {
    $args = array('store_id' => array( 'validate_callback' => 'validate_shoppers' ));
    register_rest_route('istilist/v2', '/shoppers/(?P<store_id>[\d]+)', array(
        array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => 'get_shoppers',
            'args' => $args,
        ),
        array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => 'create_shoppers',
            'args' => $args
        ),
        array(
            'methods' => WP_REST_Server::DELETABLE,
            'callback' => 'delete_shoppers',
            'args' => $args
        ),
        array(
            'methods' => 'PUT',
            'callback' => 'update_shoppers',
            'args' => $args
        )
    ));
});


function istilist_scripts() {
    //Scripts that load on all pages
    wp_enqueue_style('swal2', '/node_modules/sweetalert2/dist/sweetalert2.min.css');
    wp_enqueue_script('swal2', '/node_modules/sweetalert2/dist/sweetalert2.min.js', array('jquery'), rand(1, 100), true);
    wp_enqueue_script('jquery-matchheight', get_bloginfo('template_directory') . '/js/jquery.matchHeight-min.js', array('jquery'), rand(1, 100), true);
    wp_enqueue_script('custom-matchheight', get_bloginfo('template_directory') . '/js/custom-matchheight.js', array('jquery', 'jquery-matchheight'), rand(1, 100), true);
    wp_enqueue_script( 'wp-api' );

    //Conditionally load scripts
    if (is_page( array( 'store-preferences', 'dashboard') ) ) {
        wp_enqueue_script('jstz.min', '/node_modules/jstimezonedetect/dist/jstz.min.js', array(), false, true);
    }
    if ( is_page( array( 'store-preferences', 'stylist-employee', 'edit-shoppers-form' ) ) ) {
        wp_enqueue_style('footable-core', '/node_modules/footable/css/footable.core.min.css');
        wp_enqueue_style('footable-standalone', '/node_modules/footable/css/footable.standalone.min.css');
        wp_enqueue_script('footable-all', '/node_modules/footable/dist/footable.all.min.js', array(), rand(1, 100), true);
        wp_enqueue_script('custom-footable', get_bloginfo('template_directory') . '/js/custom-footable.js', array('footable-all'), rand(1, 100), true);
    }
    if (is_page(array('analytics-reporting', 'analytics-htmltopdf'))) {
        wp_enqueue_script('google-charts', '//www.gstatic.com/charts/loader.js', array(), rand(1, 100), false);
        wp_enqueue_script('custom-google-charts', get_bloginfo('template_directory') . '/js/custom-google-graph.js', array('google-charts'), rand(1, 100), false);
        wp_enqueue_script('purchaseflow', get_bloginfo('template_directory') . '/js/purchaseflow.js', array('google-charts', 'custom-google-charts'), rand(1, 100), true);
        wp_enqueue_script('trafficflow', get_bloginfo('template_directory') . '/js/trafficflow.js', array('google-charts', 'custom-google-charts'), rand(1, 100), true);
        wp_enqueue_script('employeeconversion', get_bloginfo('template_directory') . '/js/employeeconversion.js', array('google-charts', 'custom-google-charts'), rand(1, 100), true);
        wp_enqueue_script('sendreport-ajax', get_bloginfo('template_directory') . '/js/custom-sendreport-ajax.js', array('jquery'), rand(1, 100), true);
    }
    if (is_page(array('purchase-texts', 'process-card'))) {
        wp_enqueue_style('sqpayment', get_bloginfo('template_directory') . '/css/sqpaymentform.css');
        wp_enqueue_script('square-base', '//js.squareup.com/v2/paymentform', array(), rand(1, 100), true);
        wp_enqueue_script('sqpayment', get_bloginfo('template_directory') . '/js/sqpaymentform.js', array('jquery', 'square-base'), rand(1, 100), true);
    }
    if (is_page(array('dress-registration', 'analytics-reporting', 'analytics-htmltopdf', 'history'))) {
        wp_enqueue_style('jquery-ui-theme-smoothness', '//ajax.googleapis.com/ajax/libs/jqueryui/' . wp_scripts()->registered['jquery-ui-core']->ver . '/themes/smoothness/jquery-ui.css');        
        wp_enqueue_script('jquery-ui-datepicker', '', array('jquery'), rand(1, 100), true);
        wp_enqueue_script( 'custom-datepicker', get_bloginfo('template_directory') . '/js/custom-datepicker.js', array('jquery', 'jquery-ui-datepicker'), rand(1, 100), true);
    }
    if ( is_page( array( 'edit-shoppers-form', 'stylist-employee') ) ) {
        wp_enqueue_style('switch-button', get_bloginfo('template_directory') . '/css/jquery.switchButton.css', array('jquery'));
        wp_enqueue_script('jquery-switchbutton', get_bloginfo('template_directory') . '/js/jquery.switchButton.js', array('jquery', 'jquery-ui-core', 'jquery-ui-widget'), rand(1, 100), true);
        wp_enqueue_script( 'custom-switchbutton', get_bloginfo( 'template_directory' ) . '/js/custom-switchbutton.js', array('jquery', 'jquery-switchbutton'), rand(1, 100), true);
    }

    if ( is_page( array( 'dress-registration' ) ) ) {
        wp_enqueue_style('jquery-autocomplete', get_bloginfo('template_directory') . '/css/jquery.autocomplete.css', array('jquery'));
        wp_enqueue_script('jquery-autocomplete', get_bloginfo('template_directory') . '/js/jquery.autocomplete.js', array('jquery'), false, true);
        wp_enqueue_script('custom-autocomplete', get_bloginfo( 'template_directory' ) . '/js/custom-autocomplete.js', array( 'jquery', 'jquery-autocomplete' ), false, true);
    }
    if ( is_page( array( 'dashboard', 'history', 'self-registration' ) ) ) {
        wp_enqueue_style('fancybox', '/node_modules/@fancyapps/fancybox/dist/jquery.fancybox.min.css');
        wp_enqueue_script('jquery-fancybox', '/node_modules/@fancyapps/fancybox/dist/jquery.fancybox.min.js', array('jquery'), rand(1, 100), true);
        wp_enqueue_script('custom-fancybox', get_bloginfo( 'template_directory' ) . '/js/custom-fancybox.js', array('jquery', 'jquery-fancybox'), rand(1, 100), true);;
        
        if (is_page(array('history', 'dashboard'))) {
            wp_enqueue_script('custom-shopper-actions', get_bloginfo( 'template_directory' ) . '/js/custom-shopper-actions.js', array('jquery', 'swal2', 'custom-fancybox'), rand(1, 100), true);
        }
        if (is_page(array('self-registration'))) {
            wp_enqueue_script('jquery-validate', '/node_modules/jquery-validation/dist/jquery.validate.min.js', array('jquery'), rand(1, 100), true);
            wp_enqueue_script('additional-methods', '/node_modules/jquery-validation/dist/additional-methods.min.js', array(), rand(1, 100), true);
            wp_enqueue_script('custom-validate', get_bloginfo('template_directory') . '/js/custom-validate.js', array('jquery', 'jquery-validate', 'additional-methods'), rand(1, 100), true);
        }
    }
}

add_action('wp_enqueue_scripts', 'istilist_scripts');

function add_istilist_promo_list_controller($controllers)
{
    $controllers[] = 'istilist_promo_list';
    return $controllers;
}

add_filter('json_api_controllers', 'add_istilist_promo_list_controller');

function istilist_promo_list_controller_path($default_path)
{
    return ABSPATH . 'wp-content/themes/istilist/istilist_promo_list_controller.php';
}

add_filter('json_api_istilist_promo_list_controller_path', 'istilist_promo_list_controller_path');

function add_authorize_controller($controllers)
{
    // Corresponds to the class JSON_API_MyController_Controller
    $controllers[] = 'authorize';
    return $controllers;
}

add_filter('json_api_controllers', 'add_authorize_controller');

function authorize_controller_path($default_path)
{
    return ABSPATH . 'wp-content/themes/istilist/authorize_controller.php';
}

add_filter('json_api_authorize_controller_path', 'authorize_controller_path');

function revslider_scripts_cleanup()
{
    //DeRegister jquery.themepunch.tools.min
    wp_deregister_script('jquery.themepunch.tools.min');
    //DeRegister jquery.themepunch.revolution.min
    wp_deregister_script('jquery.themepunch.revolution.min');

    //Enqueue js files in footer
    wp_enqueue_script('jquery.themepunch.tools.min', plugin_dir_url(__FILE__) . 'revslider/rs-plugin/js/jquery.themepunch.tools.min.js', array(), '', true);
    wp_enqueue_script('jquery.themepunch.revolution.min', plugin_dir_url(__FILE__) . 'revslider/rs-plugin/js/jquery.themepunch.revolution.min.js', array(), '', true);
}

add_action( 'wp_enqueue_scripts', 'revslider_scripts_cleanup' );



add_filter('wp_mail_from_name', 'new_mail_from_name');
function new_mail_from_name($old)
{
    $site_title = get_bloginfo('name');
    return $site_title;
}

add_role('storeowner', 'Store Owner');
add_role('storeemployee', 'Store Employee');
add_role('storesupervisor', 'Store Supervisor');

function get_user_role($userid)
{
    $user_info = get_userdata($userid);
    $role = implode(', ', $user_info->roles);
    return $role;
}

register_nav_menus(array(
    'mainmenu' => __('Main Menu'),
    'footermenu' => __('Footer Menu'),
    'storeemployeemenu' => __('Employee Menu'),
    'storesupervisormenu' => __('Supervisor Menu'),
));

register_sidebar(array(
    'name'=>'Sidebar',
    'id' => 'sidebar',
    'before_widget' => '<div>',
    'after_widget' => '</div>',
    'before_title' => '<h2>',
    'after_title' => '</h2>',
));

register_sidebar(array(
    'name'=>'Dashboard Banner',
    'id' => 'dashboard-banner',
    'before_widget' => '<div>',
    'after_widget' => '</div>',
    'before_title' => '<h2 style="display:none;">',
    'after_title' => '</h2>',
));

add_theme_support('post-thumbnails');
add_image_size('img_49_49', 49, 49, array('center', 'center'), true);
add_image_size('img_77_77', 77, 77, array('center', 'center'), true);
add_image_size('img_982_379', 982, 379, array('center', 'center'), true);

function encripted($data)
{
    $key1 = '644CBEF595BC9';
    $final_data = $key1.'|'.$data;
    $val = base64_encode(base64_encode(base64_encode($final_data)));
    return $val;
}
function decripted($data)
{
    $val = base64_decode(base64_decode(base64_decode($data)));
    $final_data = explode('|', $val);
    return $final_data[1];
}

if (!current_user_can('administrator')):
    show_admin_bar(false);
endif;

function add_theme_caps()
{
    $role=get_role('storeowner');
    $role->add_cap('upload_files');
    $role->add_cap('edit_others_php');
    $role->add_cap('edit_published_pages');
    $role->add_cap('read');
    $role->add_cap('edit_others_pages');
}
add_action('admin_init', 'add_theme_caps');

function get_profile_img($postid)
{
    $attachment_id = get_post_meta($postid, 'profile_pic', true);
    $image_attributes = wp_get_attachment_image_src($attachment_id, 'img_77_77');
    if (!empty($image_attributes[0])) {
        $return_img = '<div class="noprofileimg"><img src="'.$image_attributes[0].'" /></div>';
    } else {
        $return_img = '<div class="noprofileimg"><img src="'.get_bloginfo('template_directory').'/images/noprofileimg.png" /></div>';
    }
    return $return_img;
}

function get_store_img($userid)
{
    if (get_user_meta($userid, 'profile_pic_on_off', true) == 1) {
        $attachment_id = get_user_meta($userid, 'profile_pic', true);
        $image_attributes = wp_get_attachment_image_src($attachment_id, 'img_49_49');
        if (!empty($image_attributes[0])) {
            $return_img = '<img src="'.$image_attributes[0].'" />';
        } else {
            $return_img = '<img style="border:1px solid #FFFFFF;" src="'.get_bloginfo('template_directory').'/images/store.png" />';
        }
        return $return_img;
    } else {
        $return_img = '<img style="border:1px solid #FFFFFF;" src="'.get_bloginfo('template_directory').'/images/store.png" />';
        return $return_img;
    }
}

add_action('init', 'promotions_register');
function promotions_register()
{
    $labels = array(
        'name' => _x('Promotions/Tips', 'post type general name'),
        'singular_name' => _x('Promotions/Tips Item', 'post type singular name'),
        'add_new' => _x('Add New', 'portfolio item'),
        'add_new_item' => __('Add New Promotions/Tips'),
        'edit_item' => __('Edit Promotions/Tips Item'),
        'new_item' => __('New Promotions/Tips Item'),
        'view_item' => __('View Promotions/Tips Item'),
        'search_items' => __('Search Promotions/Tips'),
        'not_found' =>  __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'menu_icon' => 'dashicons-format-status',
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title','editor')
    );
    register_post_type('tips', $args);
}

function elapsedtime($time1, $time2)
{
    $first  = new DateTime($time1);
    $second = new DateTime($time2);

    $diff = $first->diff($second);

    echo $diff->format('%H:%I:%S'); // -> 00:25:25
}

function get_shopper_name($shopper_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix.'shoppers';
    $sql = "SELECT * FROM $table_name WHERE id = $shopper_id";
    $result = $wpdb->get_row($sql);
    $shopper_name = $result->customer_fname.' '.$result->customer_lname;
    return $shopper_name;
}

function get_shopper_email($shopper_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix.'shoppers';
    $sql = "SELECT * FROM $table_name WHERE id = $shopper_id";
    $result = $wpdb->get_row($sql);
    return $result->customer_email;
}

function check_is_active( $form_slug )
{
    global $wpdb;
    global $user_ID;
    $store_owner_id = get_user_meta($user_ID, 'store_id', true);
    $table_name2 = $wpdb->prefix.'dynamic_form';
    $sql2 = "SELECT * FROM $table_name2 WHERE store_owner_id = $store_owner_id ORDER BY id";
    $results2 = $wpdb->get_results($sql2);
    foreach ($results2 as $r2) {
        if ($r2->form_slug == $form_slug) {
            return $r2->is_active;
        }
    }
}

function excerpt($limit)
{
    $excerpt = explode(' ', get_the_excerpt(), $limit);
    if (count($excerpt)>=$limit) {
        array_pop($excerpt);
        $excerpt = implode(" ", $excerpt).'...';
    } else {
        $excerpt = implode(" ", $excerpt);
    }
    $excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);
    return $excerpt;
}

function is_user_active($user_id)
{
    $user_status = get_the_author_meta('user_status', $user_id);
    $color = 'red';
    $title = 'Inactive';
    if ($user_status == 1) {
        $color = 'green';
        $title = 'Active';
    }
    return '<div style="color:' . $color . 'text-align:center;" title="' . $title . '"><i class="fa fa-circle"></i></div>';
}

add_filter('manage_users_columns', 'pippin_add_user_id_column');
function pippin_add_user_id_column($columns)
{
    $columns['user_status'] = 'User Status';
    return $columns;
}

add_action('manage_users_custom_column', 'pippin_show_user_id_column_content', 10, 3);
function pippin_show_user_id_column_content($value, $column_name, $user_id)
{
    if ($user_id >1) {
        $user = get_userdata($user_id);
        $current_url = 'http://';
        $current_url .= $_SERVER['HTTP_HOST']; // Get host
        $path = explode('?', $_SERVER['REQUEST_URI']); // Blow up URI
        $current_url .= $path[0]; // Only use the rest of URL - before any parameters
        if ('user_status' == $column_name) {
            if ($user->user_status == 2) {
                $status = "<strong style='color:#00FF00;'>Active</strong>&nbsp;&nbsp;<form method='GET' action='$current_url'><input type='hidden' name='uid' value='$user_id' /><input type='submit' class='button button-secondary button-large' name='up_user_status' value='Deactivate' /></form>";
            }
            if (($user->user_status == 1) || ($user->user_status == 0)) {
                $status = "<strong style='color:#333333;'>Not Active</strong>&nbsp;&nbsp;<form method='GET' action='$current_url'><input type='hidden' name='uid' value='$user_id' /><input type='submit' class='button button-primary button-large' name='up_user_status' value='Activate' /></form>";
            }
        }
        return $status;
        return $value;
    }
}
function add_scripts()
{
    $current_screen = get_current_screen();

    if ($current_screen = 'users.php') {
        global $wpdb;
        if (isset($_GET['up_user_status']) && $_GET['up_user_status'] == 'Deactivate') {
            $uid = $_GET['uid'];
            $user_status = 1;
            $wpdb->update($wpdb->users, array('user_status' => $user_status), array('ID' => $uid));
            $current_url = 'http://';
            $current_url .= $_SERVER['HTTP_HOST']; // Get host
            $path = explode('?', $_SERVER['REQUEST_URI']); // Blow up URI
            $current_url .= $path[0]; // Only use the rest of URL - before any parameters
            ?>
				<script>window.location="<?php echo $current_url; ?>";</script>
			<?php
        }
        if (isset($_GET['up_user_status']) && $_GET['up_user_status'] == 'Activate') {
            $uid = $_GET['uid'];
            $user_status = 2;
            $wpdb->update($wpdb->users, array('user_status' => $user_status), array('ID' => $uid));
            $current_url = 'http://';
            $current_url .= $_SERVER['HTTP_HOST']; // Get host
            $path = explode('?', $_SERVER['REQUEST_URI']); // Blow up URI
            $current_url .= $path[0]; // Only use the rest of URL - before any parameters
            ?>
				<script>window.location="<?php echo $current_url; ?>";</script>
			<?php
        }
    }
}
add_action('admin_head', 'add_scripts');

function get_unique_post_meta_values($searchkey = '', $searchvalue = '', $status = 'publish', $type = 'post', $findkey = '')
{
    global $wpdb;
    if (empty($searchkey)) {
        return;
    }
    $res = $wpdb->get_col($wpdb->prepare("
		  SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm WHERE pm.post_id IN
		  (SELECT pm.post_id FROM {$wpdb->postmeta} pm
		  LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
		  WHERE pm.meta_key = '%s'
		  AND pm.meta_value = '%s'
		  AND p.post_status = '%s'
		  AND p.post_type = '%s')
		  AND pm.meta_key = '%s'
	", $searchkey, $searchvalue, $status, $type, $findkey));
    return $res;
}

/* Code Allows for Partial Searches */
function name_filter($where, $query)
{
    global $wpdb;
    if ($search_term = $query->get('search_shopper_name')) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql($wpdb->esc_like($search_term)) . '%\'';
    }

    return $where;
}

function print_timestamps($shopper_id) {
    $timestamps = get_post_meta($shopper_id, 'timestamps', true);
    $purchases  = get_post_meta($shopper_id, 'purchase_array', true);
    $entry_date = get_post_meta($shopper_id, 'entry_date', true);
    if (! empty($timestamps)) {
        $index = count($timestamps);
        while (-- $index) {
            echo "<span>on " . date('m.d.Y', strtotime($timestamps[ $index  ])) . " at " . date('h:i a', strtotime($timestamps[ $index ]));
            if ($index != (count($timestamps) - 1)) {
                if ($purchases[ $index - 1 ] == 'true') {
                    echo "\tPurchase";
                } else {
                    echo "\tNo Purchase";
                }
            }
            echo "</span><br />";
        }
    } 
    echo '<span>' . date('m.d.Y \a\t h:i a', strtotime($entry_date)) . ' - '; 
    
    $stylist_id = get_post_meta($shopper_id, 'stylist_id', true);
    if (! empty($stylist_id)) {
    echo get_the_author_meta('display_name', get_post_meta($shopper_id, 'stylist_id', true));
    }
    if (! empty($purchases)) {
        if ($purchases[0] == 'true') {
            echo "\t - YES";
        } else {
            echo "\t - NO";
        }
    } 
    echo '</span>';
}

function print_fitting_room_rounds($shopper_id) {
    $daily_count = 0;
    if (date('m.d.Y', strtotime(get_post_meta($shopper_id, 'entry_date', true))) == date('m.d.Y')) {
        $daily_count ++;
    }
    if (!empty($timestamps)) {
        foreach ($timestamps as $timestamp) {
            if (date('m.d.Y', strtotime($timestamp)) == date('m.d.Y')) {
                $daily_count ++;
            }
        }
    }
    if ($daily_count > 1) {
        echo "<p class='daily_rounds'>Fitting Room Rounds: " . $daily_count . "</p>";
    } 
}

function is_active($shopper_id, $shopper_field) {
    $field_value = get_post_meta($shopper_id, $shopper_field, true);
    if (! empty($field_value) && ($field_value == 1 || $field_value == 'true')) {
        return 'active';
    }
    if (! empty($field_value) && $field_value == 'timestamps') {
        return 'active';
    }
    return '';
}

function active_section( $active_section, $test_string ) {
    if ($active_section == $test_string) {
        return 'activeSection';
    }
}

$optional_shopper_fields = array('customer_fname', 'customer_lname', 'school_event', 'graduation_year', 'customer_email', 
'customer_phone', 'customer_address', 'customer_city', 'customer_state', 'customer_phone',
'customer_address', 'customer_city', 'customer_state', 'customer_zip', 'design_preferences',
'style_preferences', 'color_preferences', 'customer_size');

//TODO MASON: COMBINE THESE FUNCTIONS IN SOME WAY
function push_active_sections_headers( $base_array ) {
    foreach ( $optional_shopper_fields as $optional_shopper_field ) {
        if ( check_is_active( $optional_shopper_field ) == 1) {
            array_push( $base_array, $optional_shopper_field );
        }
    }
    return $base_array;
}

function push_active_sections_meta_values( $base_array, $post_id ) {
    foreach ( $optional_shopper_fields as $optional_shopper_field ) {
        if ( check_is_active( $optional_shopper_field ) == 1) {
            array_push( $base_array, get_post_meta( $post_id, $optional_shopper_field, true ) );
        }
    }

    if (check_is_active('customer_phone') == 1) { //TODO MASON: here there are two fields
        array_push($base_array, get_post_meta($post->ID, 'promo_list_timestamp', true));
    }

    if (get_post_meta($post->ID, 'complete_purchase', true) == 1) {
        array_push($output, 'yes');
    } else {
        array_push($output, 'no');
    }
}
//END NEEDED COMBINATION
?>