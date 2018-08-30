<?php
$my_post = array(
        'ID' => $_POST['shopper_id'],
        'post_modified' => date('Y-m-d H:i:s')
    );
    wp_update_post($my_post);

    $purchases = get_post_meta($_POST['shopper_id'], 'purchase_array', true);
    if ('dollar_button_clicked' == 0) {
        if (empty($purchases)) {
            add_post_meta($_POST['shopper_id'], 'purchase_array', ['false']);
        } else {
            array_push($purchases, 'false');
            update_post_meta($_POST['shopper_id'], 'purchase_array', $purchases);
        }
    }
    update_post_meta($_POST['shopper_id'], 'dollar_button_clicked', 0);
    update_post_meta($_POST['shopper_id'], 'complete_purchase', 0);
    update_post_meta($_POST['shopper_id'], 'reason_not_purchased', '');
    delete_post_meta($_POST['shopper_id'], 'notified');


    $timestamp_array = get_post_meta($_POST['shopper_id'], 'timestamps', true);
    if (!empty($timestamp_array)) {
        array_push($timestamp_array, date('Y-m-d H:i:s'));
        update_post_meta($_POST['shopper_id'], 'timestamps', $timestamp_array);
    } else {
        $timestamp_array = array();
        array_push($timestamp_array, date('Y-m-d H:i:s'));
        add_post_meta($_POST['shopper_id'], 'timestamps', $timestamp_array);
    }

    $hit_plus = get_post_meta($_POST['shopper_id'], 'hit_plus', true);
    if (!empty($hit_plus)) {
        update_post_meta($_POST['shopper_id'], 'hit_plus', 'false');
    } else {
        add_post_meta($_POST['shopper_id'], 'hit_plus', 'false');
    }
