<?php

class json_api_istilist_promo_list_controller
{
    public function get_store_list()
    {
        global $json_api;
      
        // Make sure we have key/value query vars
        if (!$json_api->query->id) {
            $json_api->error("Include an 'id' query var.");
        }
      
        // See also: http://codex.wordpress.org/Template_Tags/query_posts
        $args = array(
        'posts_per_page' => -1,
        'post_type' => 'shopper',
        'meta_key' => 'promo_list',
        'meta_value' => $json_api->query->id
      );
        $ret = array();
        $text_list =  new WP_Query($args);
        if ($text_list->have_posts()) {
            while ($text_list->have_posts()) {
                $text_list->the_post();
                $ret[] = array(
                    'phone' => get_post_meta(get_the_ID(), 'customer_phone', true),
                    'first_name' => get_post_meta(get_the_ID(), 'customer_fname', true)
              );
            }
        }


        //return openssl_encrypt(json_encode($ret), get_option('cipher_method'), get_option('cipher_key'));
        return array(
            "message" => $ret,
        );
        //return $text_list;
    }
}
