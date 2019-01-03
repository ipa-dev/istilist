<form id="forms" method="post" action="<?= bloginfo('url') ?>/process-new-shopper" enctype="multipart/form-data">
    <div class="section group form_list">
        <?php if (check_is_active('customer_fname') == 1) { ?>
            <div class="col span_6_of_12 matchheight">
                <label>First Name <span>*</span></label>
                <input type="text" name="customer_fname" />
            </div>
        <?php } ?>
        <?php if (check_is_active('customer_lname') == 1) {
        ?>
            <div class="col span_6_of_12 matchheight">
                <label>Last Name <span>*</span></label>
                <input type="text" name="customer_lname" />
            </div>
        <?php
    }  if (check_is_active('school_event') == 1) {
        ?>
            <div class="col span_6_of_12 matchheight">
                <label>School/Event <span>*</span></label>
                <input type="text" name="school_event" />
            </div>
        <?php
    }  if (check_is_active('graduation_year') == 1) {
        ?>
            <div class="col span_6_of_12 matchheight">
                <label>Graduation Year <span>*</span></label>
                <select name="graduation_year">
                    <option value="graduate">Graduated</option>
                    <?php for ($i=intval(date('Y')) - 1; $i<=2030; $i++) {
            ?>
                    <option value="<?php echo $i; ?>" <?php if ($i == 2017) {
                echo "selected='selected'";
            } ?>><?php echo $i; ?></option>
                    <?php
        } ?>
                </select>
            </div>
        <?php
    }  if (check_is_active('customer_email') == 1) {
        ?>
        <div class="col span_6_of_12 matchheight">
            <label>Email</label>
            <input type="text" name="customer_email" />
        </div>
        <?php
    }  if (check_is_active('customer_phone') == 1) {
        ?>
        <div class="col span_6_of_12 matchheight">
            <label>Phone</label>
            <input type="tel" name="customer_phone">
        </div>
        <?php
    }  if (check_is_active('customer_address') == 1) {
        ?>
        <div class="col span_6_of_12 matchheight">
            <label>Address</label>
            <input type="text" name="customer_address" />
        </div>
        <?php
    } if (check_is_active('customer_city') == 1) {
        ?>
        <div class="col span_6_of_12 matchheight">
            <label>City</label>
            <input type="text" name="customer_city" />
        </div>
        <?php
    }  if (check_is_active('customer_state') == 1) {
        ?>
        <div class="col span_6_of_12 matchheight">
            <label>State</label>
            <input type="text" name="customer_state" />
        </div>
        <?php
    }  if (check_is_active('customer_zip') == 1) {
        ?>
        <div class="col span_6_of_12 matchheight">
            <label>ZIP</label>
            <input type="text" name="customer_zip" />
        </div>
        <?php
    } ?>
    </div>
    <?php if (check_is_active('customer_phone') == 1) {
        ?>
    <div class="section group">
        <div class="col span_12_of_12">
            <input type="checkbox" name="sms_agreement" value="yes" /> Yes, I want istilist texts!<br /><br />
            <p>Up to 6 autodialed msgs/mo.  Consent not required to purchase. Msg&data rates may apply. Text STOP to stop, HELP for help. Terms:<a href="internationalprom.com/privacy-policy">internationalprom.com</a></p>
        </div>
    </div>
    <?php
    } ?>
    <div class="section group form_list">
        <?php if (check_is_active('design_preferences')) {
        ?>
        <div class="col span_6_of_12 matchheight">
            <label>Designer Preference</label>
            <select name="design_preferences">
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
        <?php
        }  if (check_is_active('style_preferences')) {
        ?>
        <div class="col span_6_of_12 matchheight">
            <label>Style Preference</label>
            <select name="style_preferences">
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
            <select name="color_preferences">
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
        <?php
    }  if (check_is_active('customer_size') == 1) {
        ?>
        <div class="col span_6_of_12 matchheight">
            <label>Size</label>
            <select name="customer_size">
                <option value="">Select Size</option>
                <?php
                    $size_args = array(
                        'post_type' => 'size_pref',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'meta_key' => 'store_id',
                        'meta_value' => $store_id
                    );
        $sizes = new WP_Query($size_args); ?>
                <?php if ($sizes->have_posts()) {
            ?>
                <?php while ($sizes->have_posts()) : $sizes->the_post(); ?>
                <option value="<?php the_title(); ?>"><?php the_title(); ?></option>
                <?php endwhile; ?>
                <?php
        } ?>
            </select>
        </div>
        <?php
    } ?>
    						<div class="section group">
							<?php

                                $table_name2 = $wpdb->prefix.'dynamic_form';
            $sql2 = "SELECT * FROM $table_name2 WHERE store_owner_id = $store_owner_id AND is_custom = 1 ORDER BY id";
            $results2 = $wpdb->get_results($sql2);
            foreach ($results2 as $r2) {
                 if (check_is_active($r2->form_slug) == 1) {
                    ?>
							<div class="col span_6_of_12">
								<label><?php echo $r2->form_display_name; ?> <?php if ($r2->is_required == 1) {
                        ?><span>*</span><?php
                    } ?></label>
								<?php if ($r2->form_type == 'text') {
                        ?>
									<input type="text" name="<?php echo $r2->form_slug; ?>" />
								<?php
                    }  if ($r2->form_type == 'textarea') {
                        ?>
									<textarea name="<?php echo $r2->form_slug; ?>"></textarea>
								<?php
                    } ?>
							</div>
						   <?php
                } 
            } ?>
							</div>
							<div class="section group">
								<div class="col span_12_of_12">
									<label>Shopper Feedback</label>
									<textarea name="shoppers_feedback"></textarea>
								</div>
							</div>
							<div class="section group">
								<div class="col span_12_of_12">
									<div style="text-align: right;">
										<input type="submit" name="add_new_shopper" value="Add New Shopper" />
									</div>
								</div>
							</div>
						</form>