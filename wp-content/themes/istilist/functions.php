<?php
global $options;


add_option('cipher_key', 'l5qbZFEoKFn0Hau5q4fYatlq91T9c391');
add_option('cipher_method', 'AES-256-CBC');

add_option('twilio_number', '+18652400405');

function add_istilist_promo_list_controller($controllers)
{
    $controllers[] = 'istilist_promo_list';
    return $controllers;
}

add_filter('json_api_controllers', 'add_istilist_promo_list_controller');

function istilist_promo_list_controller_path($default_path)
{
    return '/home/istilist/public_html/wp-content/themes/istilist/istilist_promo_list_controller.php';
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
    return '/home/istilist/public_html/wp-content/themes/istilist/authorize_controller.php';
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

//add_action( 'wp_enqueue_scripts', 'revslider_scripts_cleanup' );

add_filter('wp_mail_from_name', 'new_mail_from_name');
function new_mail_from_name($old)
{
    $site_title = get_bloginfo('name');
    return $site_title;
}

/*add_role('storeowner', 'Store Owner', array(
    'read' => true,
    'edit_posts' => true,
    'delete_posts' => true,
    'upload_files' => true
));*/
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
    //'mainmenu' => __( 'Main Menu'),
));

register_sidebar(array('name'=>'Sidebar',
'before_widget' => '<div>',
'after_widget' => '</div>',
'before_title' => '<h2>',
'after_title' => '</h2>',
));
register_sidebar(array('name'=>'Dashboard Banner',
'before_widget' => '<div>',
'after_widget' => '</div>',
'before_title' => '<h2 style="display:none;">',
'after_title' => '</h2>',
));

add_theme_support('post-thumbnails');
add_image_size('img_49_49', 49, 49, array('center', 'center'), true);
add_image_size('img_77_77', 77, 77, array('center', 'center'), true);
add_image_size('img_982_379', 982, 379, array('center', 'center'), true);

function content($limit, $postid)
{
    $post = get_page($postid);
    $fullContent = $post->post_content;
    $content = explode(' ', $fullContent, $limit);
    if (count($content)>=$limit) {
        array_pop($content);
        $content = implode(" ", $content).'...';
    } else {
        $content = implode(" ", $content);
    }
    $content = preg_replace('/\[.+\]/', '', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    return $content;
}
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

if (!class_exists('ReduxFramework')) {
    require_once(dirname(__FILE__) . '/frameworks/redux/ReduxCore/framework.php');
}

if (!isset($redux_demo)) {
    require_once(dirname(__FILE__) . '/frameworks/redux/admin-config.php');
}

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

function check_is_active($form_slug)
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

/*add_filter('manage_users_columns', 'pippin_add_user_id_column');
function pippin_add_user_id_column($columns) {
    $columns['user_status'] = 'User Status';
    return $columns;
}

add_action('manage_users_custom_column',  'pippin_show_user_id_column_content', 10, 3);
function pippin_show_user_id_column_content($value, $column_name, $user_id) {
    $user = get_userdata( $user_id );
    if ( 'user_status' == $column_name ){
       if($user->user_status == 1){
           $status = "<strong style='color:#00FF00;'>Active</strong>";
       }
    }
        return $status;
    return $value;
}*/

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
    global $wpdb;
    $user_status = get_the_author_meta('user_status', $user_id);
    if ($user_status == 1) {
        $status = '<div style="color:green; text-align:center;" title="Active"><i class="fa fa-circle"></i></div>';
    } else {
        $status = '<div style="color:red; text-align:center;" title="Inactive"><i class="fa fa-circle"></i></div>';
    }
    return $status;
}

function tz_list()
{
    $zones_array = array();
    $timestamp = time();
    foreach (timezone_identifiers_list(DateTimeZone::AMERICA) as $key => $zone) {
        //if ( preg_match( '/^(America)\//', $zone['timezone_id'] )){
        date_default_timezone_set($zone);
        $zones_array[$key]['zone'] = $zone;
        $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
        //}
    }
    return $zones_array;
}

function smtpmailer($to, $from, $from_name, $subject, $body)
{
    $mail = new PHPMailer();  // create a new object
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true;  // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
    //$mail->Host = 'smtp.gmail.com';
        $mail->Host = 'mail.istilist.com';
    $mail->Port = 465;
    //$mail->Username = 'bhulbhal1981@gmail.com';
    //$mail->Password = 'bhulbhal098';
    $mail->Username = 'info@istilist.com';
    $mail->Password = 'Formal!1468';
    $mail->SetFrom($from, $from_name);
    $mail->Subject = $subject;
    $mail->IsHTML(true);
    $mail->Body = $body;
    $mail->AddAddress($to);
    if (!$mail->Send()) {
        $error = 'Mail error: '.$mail->ErrorInfo;
    } else {
        $error = 'Message sent!';
    }
}

function smtpmailer1($to, $from, $from_name, $subject, $body, $smtp_user, $smtp_pass)
{
    $mail = new PHPMailer();  // create a new object
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true;  // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
    //$mail->Host = 'smtp.gmail.com';
        $mail->Host = 'mail.istilist.com';
    $mail->Port = 465;
    $mail->Username = $smtp_user;
    $mail->Password = $smtp_pass;
    $mail->SetFrom($from, $from_name);
    $mail->Subject = $subject;
    $mail->IsHTML(true);
    $mail->Body = $body;
    $mail->AddAddress($to);
    if (!$mail->Send()) {
        $error = 'Mail error: '.$mail->ErrorInfo;
    } else {
        $error = 'Message sent!';
    }
}

/*************************/
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
        if ($_GET['up_user_status'] == 'Deactivate') {
            $uid = $_GET['uid'];
            $user_status = 1;
            $wpdb->update($wpdb->users, array('user_status' => $user_status), array('ID' => $uid));
            $current_url = 'http://';
            $current_url .= $_SERVER['HTTP_HOST']; // Get host
            $path = explode('?', $_SERVER['REQUEST_URI']); // Blow up URI
            $current_url .= $path[0]; // Only use the rest of URL - before any parameters
            //$current_url = urlencode( $current_url ); // Encode it for use
            ?>
                <script>window.location="<?php echo $current_url; ?>";</script>
            <?php
        }
        if ($_GET['up_user_status'] == 'Activate') {
            $uid = $_GET['uid'];
            $user_status = 2;
            $wpdb->update($wpdb->users, array('user_status' => $user_status), array('ID' => $uid));
            $current_url = 'http://';
            $current_url .= $_SERVER['HTTP_HOST']; // Get host
            $path = explode('?', $_SERVER['REQUEST_URI']); // Blow up URI
            $current_url .= $path[0]; // Only use the rest of URL - before any parameters
            //$current_url = urlencode( $current_url ); // Encode it for use
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
?>
