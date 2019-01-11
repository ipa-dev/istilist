<div class="col span_6_of_12">
    <label>Profile Picture ON/OFF</label>
    <input type="radio" name="profile_pic_on_off" value="1" <?php if (get_user_meta($user_ID, 'profile_pic_on_off', true) == 1) {
                echo 'checked="checked"';
            } ?> /> ON
    <input type="radio" name="profile_pic_on_off" value="0" <?php if (get_user_meta($user_ID, 'profile_pic_on_off', true) == 0) {
                echo 'checked="checked"';
            } ?> /> OFF
</div>
<div class="col span_6_of_12 matchheight">
<?php if (get_user_meta($user_ID, 'profile_pic_on_off', true) == 1) { ?>
    <label>Profile Picture</label>
    <input type="file" name="profile_pic" />
<?php /*echo get_store_img($user_ID);*/ } ?>
</div>