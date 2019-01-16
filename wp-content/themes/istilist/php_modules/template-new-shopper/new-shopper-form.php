<?php 
function generate_new_shopper_form ($wpdb, $store_id, $redirect_to) { ?>
    <input type="hidden" value="<?= $redirect_to ?>" id="callback_url" />
    <div class="section group form_list">
        <?php if (check_is_active('customer_fname')) { ?>
            <div class="col span_6_of_12 matchheight">
                <label>First Name <span>*</span></label>
                <input type="text" id="customer_fname" />
            </div>
        <?php } if (check_is_active('customer_lname')) { ?>
            <div class="col span_6_of_12 matchheight">
                <label>Last Name <span>*</span></label>
                <input type="text" id="customer_lname" />
            </div>
        <?php } if (check_is_active('school_event')) { ?>
            <div class="col span_6_of_12 matchheight">
                <label>School/Event <span>*</span></label>
                <input type="text" id="school_event" />
            </div>
        <?php } if (check_is_active('graduation_year')) { ?>
            <div class="col span_6_of_12 matchheight">
                <label>Graduation Year <span>*</span></label>
                <select id="graduation_year">
                    <option value="graduate">Graduated</option>
                    <?php 
                    for ($i=intval(date('Y')) - 1; $i<=2030; $i++) {
                        echo '<option value="'. $i . '"';
                        if ($i == 2018) {
                            echo 'selected="selected"';
                        }
                        echo '>' . $i . '</option>';
                    }
                    ?>
                </select>
            </div>
        <?php } if (check_is_active('customer_email')) { ?>
        <div class="col span_6_of_12 matchheight">
            <label>Email</label>
            <input type="text" id="customer_email" />
        </div>
        <?php } if (check_is_active('customer_phone')) { ?>
        <div class="col span_6_of_12 matchheight">
            <label>Phone</label>
            <input type="tel" id="customer_phone">
        </div>
        <?php } if (check_is_active('customer_address')) { ?>
        <div class="col span_6_of_12 matchheight">
            <label>Address</label>
            <input type="text" id="customer_address" />
        </div>
        <?php } if (check_is_active('customer_city')) { ?>
        <div class="col span_6_of_12 matchheight">
            <label>City</label>
            <input type="text" id="customer_city" />
        </div>
        <?php } if (check_is_active('customer_state')) { ?>
        <div class="col span_6_of_12 matchheight">
            <label>State</label>
            <input type="text" id="customer_state" />
        </div>
        <?php } if (check_is_active('customer_zip')) { ?>
        <div class="col span_6_of_12 matchheight">
            <label>ZIP</label>
            <input type="text" id="customer_zip" />
        </div>
        <?php } ?>
    </div>
    <?php if (check_is_active('customer_phone')) { ?>
    <div class="section group">
        <div class="col span_12_of_12">
            <input type="checkbox" id="sms_agreement" value="yes" /> Yes, I want istilist texts!<br /><br />
            <p>Up to 6 autodialed msgs/mo.  Consent not required to purchase. Msg&data rates may apply. Text STOP to stop, HELP for help. Terms:<a href="internationalprom.com/privacy-policy">internationalprom.com</a></p>
        </div>
    </div>
    <?php } ?>
    <div class="section group form_list">
        <?php if (check_is_active('design_preferences')) { ?>
        <div class="col span_6_of_12 matchheight">
            <label>Designer Preference</label>
            <select id="design_preferences">
                <option value="">Select Designer</option>
                <?php
                    $size_args = array(
                        'post_type' => 'designer_pref',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'meta_key' => 'store_id',
                        'meta_value' => $store_id,
                        'orderby' => 'title',
                        'order' => 'ASC'
                    );
                    $sizes = new WP_Query($size_args);
                    if ($sizes->have_posts()) {
                        while ($sizes->have_posts()) : 
                            $sizes->the_post();
                            echo '<option value="' . get_the_title() . '">' . get_the_title() . '</option>';
                        endwhile;
                    } 
                ?>
            </select>
        </div>
        <?php }  if (check_is_active('style_preferences')) { ?>
        <div class="col span_6_of_12 matchheight">
            <label>Style Preference</label>
            <select id="style_preferences">
                <option value="">Select Style</option>
            <?php
            $size_args = array(
                'post_type' => 'style_pref',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'meta_key' => 'store_id',
                'meta_value' => $store_id,
                'orderby' => 'title',
                'order' => 'ASC'
            );
            $sizes = new WP_Query($size_args);
            if ($sizes->have_posts()) {
                while ($sizes->have_posts()) : 
                    $sizes->the_post();
                    echo '<option value="' . get_the_title() .'">' . get_the_title() . '</option>';
                endwhile;
            }
            ?>
            </select>
        </div>
        <?php } if (check_is_active('color_preferences')) { ?>
        <div class="col span_6_of_12 matchheight">
            <label>Color Preferences</label>
            <select id="color_preferences">
                <option value="">Select Color</option>
            <?php
                $color_args = array(
                    'post_type' => 'color_pref',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'meta_key' => 'store_id',
                    'meta_value' => $store_id,
                    'orderby' => 'title',
                    'order' => 'ASC'
                );
                $colors = new WP_Query($color_args);
                if ($colors->have_posts()) {
                    while ($colors->have_posts()) : 
                        $colors->the_post();
                        echo '<option value="' . get_the_title() . '">' . get_the_title() . '</option>';
                    endwhile;
                } ?>
            </select>
        </div>
        <?php }  if (check_is_active('customer_size')) { ?>
        <div class="col span_6_of_12 matchheight">
            <label>Size</label>
            <select id="customer_size">
                <option value="">Select Size</option>
                <?php
                $size_args = array(
                    'post_type' => 'size_pref',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'meta_key' => 'store_id',
                    'meta_value' => $store_id
                );
                $sizes = new WP_Query($size_args); 
                if ($sizes->have_posts()) {
                    while ($sizes->have_posts()) : 
                        $sizes->the_post();
                        echo '<option value="' . get_the_title() . '">' . get_the_title() . '</option>';
                    endwhile; 
                }
                ?>
            </select>
        </div>
        <?php } ?>
    	<div class="section group">
        <?php
        $table_name2 = $wpdb->prefix.'dynamic_form';
        $sql2 = "SELECT * FROM $table_name2 WHERE store_owner_id = $store_id AND is_custom = 1 ORDER BY id";
        $results2 = $wpdb->get_results($sql2);
        foreach ($results2 as $r2) {
            if (check_is_active($r2->form_slug)) {
        ?>
				<div class="col span_6_of_12">
                    <label>
                    <?php 
                    echo $r2->form_display_name;
                    if ($r2->is_required == 1) {
                        echo "<span>*</span>";
                    }
                    ?>
                    </label>
                    <?php 
                    if ($r2->form_type == 'text') {
                        echo '<input type="text" name="' . $r2->form_slug . '"/>';
                    }
					if ($r2->form_type == 'textarea') {
                        echo '<textarea name="' . $r2->form_slug . '"></textarea>';
                    }
					echo '</div>';
            } 
        }
        ?>
	</div>
    <div class="section group">
        <div class="col span_12_of_12">
            <label>Shopper Feedback</label>
            <textarea id="shoppers_feedback"></textarea>
        </div>
    </div>
    <div class="section group">
        <div class="col span_12_of_12">
            <div style="text-align: right;">
                <span class="custom_button" id="add_new_shopper">Add New Shopper</span>
            </div>
        </div>
    </div>
<? } ?>