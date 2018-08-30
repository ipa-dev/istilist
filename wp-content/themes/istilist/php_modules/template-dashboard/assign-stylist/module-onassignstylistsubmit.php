<?php

$shopper_id = $_POST['shopper_id'];
    $current_date = date('Y-m-d H:i:s');
    global $wpdb;
    update_post_meta($shopper_id, 'assign_stylist', $current_date);
    update_post_meta($shopper_id, 'stylist_id', $_POST['stylist_id']);
    update_post_meta($shopper_id, 'fitting_room_id', $_POST['fitting_room_id']);


    $prev_stylist_array = get_post_meta($shopper_id, 'prev_stylists', true);

    //Store all information on each stylist girl has been assigned
    if (empty($prev_stylist_array)) {
        $prev_stylist_array = array(
            array(
               'assignment_date' => $current_date,
               'stylist_id' => $_POST['stylist_id'],
               'fitting_room_id' => $_POST['fitting_room_id']
               )
        );
        add_post_meta($shopper_id, 'prev_stylists', $prev_stylist_array);
    } elseif (!empty($prev_stylist_array)) {
        $array_addition = array('assignment_date'  => $current_date, 'stylist_id' => $_POST['stylist_id'], 'fitting_room_id' => $_POST['fitting_room_id']);
        array_push($prev_stylist_array, $array_addition);
        update_post_meta($shopper_id, 'prev_stylists', $prev_stylist_array);
    }

    $hit_plus = get_post_meta($_POST['shopper_id'], 'hit_plus', true);
    if (!empty($hit_plus)) {
        if ($hit_plus == 'true') {
            $my_post = array(
            'ID' => $_POST['shopper_id'],
            'post_modified' => date('Y-m-d H:i:s')
        );
            wp_update_post($my_post);
            $timestamp_array = get_post_meta($_POST['shopper_id'], 'timestamps', true);
            if (!empty($timestamp_array)) {
                array_push($timestamp_array, date('Y-m-d H:i:s'));
                update_post_meta($_POST['shopper_id'], 'timestamps', $timestamp_array);
            } else {
                $timestamp_array = array();
                array_push($timestamp_array, date('Y-m-d H:i:s'));
                add_post_meta($_POST['shopper_id'], 'timestamps', $timestamp_array);
            }
            update_post_meta($_POST['shopper_id'], 'hit_plus', 'false');
        } elseif ($hit_plus == 'false') {
            update_post_meta($_POST['shopper_id'], 'hit_plus', 'true');
        }
    } else {
        //user has not hit plus button after first round
        $test = 0;
    }
