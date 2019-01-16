<?php 
    wp_head();
    function echo_shoppers( $query )
    {
        while ( $query->have_posts() ) {
            $query->the_post();
            $shopper_id = get_the_ID();
            if (get_post_meta($shopper_id, 'dollar_button_clicked', true) == 1) {
                echo '<div class="box active">';
            } else {
                echo '<div class="box">';
            } ?>
                <div class="box_description" data-shopperid="<?php echo $shopper_id; ?>">
                    <h2>
                        <?php echo get_post_meta($shopper_id, 'customer_fname', true) . ' ' . get_post_meta($shopper_id, 'customer_lname', true); ?>
                        <span style="margin-left:30px"><strong>Event:</strong> <?php echo get_post_meta($shopper_id, 'school_event', true); ?></span>
                        <br/>
                        <?php print_timestamps($shopper_id); ?>
                    </h2>
                    <?php
                        $assign_stylist = get_post_meta($shopper_id, 'assign_stylist', true);
            if (! empty($assign_stylist)) {
                ?>
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <p class="assignStylistClass">
                                        <strong>Stylist Name :</strong>
                                        <span><?php echo get_the_author_meta('display_name', get_post_meta($shopper_id, 'stylist_id', true)); ?></span>
                                    </p>
                                    <p class="assignStylistClass">
                                        <strong>Stylist Assigned :</strong>
                                        <span id="assignStylist_<?php echo $shopper_id; ?>"><?php echo date('h:i a', strtotime($assign_stylist)); ?></span>
                                    </p>
                                </div>
                                <div class="col span_6_of_12">
                                    <p class="assignStylistClass">
                                        <strong>Fitting Room ID :</strong>
                                        <span><?php echo get_post_meta($shopper_id, 'fitting_room_id', true); ?></span>
                                    </p>
                                    <p class="assignStylistClass">
                                        <strong>Waiting Time :</strong>
                                        <span>
                                            <?php
                                                // TODO: WORK ON THIS LOGIC
                                                $entry_date = date('h:i:s', strtotime(get_post_meta($shopper_id, 'entry_date', true)));
                if (empty($timestamps)) {
                    elapsedtime($entry_date, date('h:i:s', strtotime($assign_stylist)));
                } elseif (count($timestamps) == 1 && get_post_meta($shopper_id, 'hit_plus', true) == 'false') {
                    elapsedtime($entry_date, date('h:i:s', strtotime($assign_stylist)));
                } elseif (get_post_meta($shopper_id, 'hit_plus', true) == 'false') {
                    elapsedtime(date('h:i:s', strtotime($timestamps[ count($timestamps) - 2 ])), date('h:i:s', strtotime($assign_stylist)));
                } elseif (get_post_meta($shopper_id, 'hit_plus', true) == 'true') {
                    elapsedtime(date('h:i:s', strtotime($timestamps[ count($timestamps) - 1 ])), date('h:i:s', strtotime($assign_stylist)));
                } ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
					<?php
            } ?>
                    <div class="section group">
                        <div class="col span_12_of_12">
                            <?php print_fitting_room_rounds($shopper_id); ?>
                        </div>
                    </div>
                        <?php
                            echo '<p>' . excerpt(40) . '</p>';
            if (get_post_meta($shopper_id, 'dollar_button_clicked', true) == 1 && get_post_meta($shopper_id, 'hit_plus', true) == 'false') {
                ?>
                            <p class="reasone"><strong>Purchased?: </strong>
                        <?php
                                $purchased = get_post_meta($shopper_id, 'reason_not_purchased', true);
                if ($purchased) {
                    echo 'NO'; ?>
                                    <p class="reasone">
                                        <strong>Reason not purchased: </strong><?= implode(', ', get_post_meta($shopper_id, 'reason_not_purchased', true)); ?>
                                    </p>
						<?php
                } else {
                    echo 'YES';
                }
            } ?>
                </div>
                <div class="box_actions">
                    <ul>
                        <li>
                            <a href="<?php bloginfo('url'); ?>/edit-shoppers?id=<?php echo encripted($shopper_id); ?>">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </li>
                        <li>
                            <span class="notifyShopper <?= is_active($shopper_id, "notified") ?>" data-id="<?= $shopper_id ?>">
                                <i class="fa fa-bell"></i>
                            </span>
                        </li>
                        <li>
                            <span   class="assignStylist <?= is_active($shopper_id, "assign_stylist") ?>"
                                    data-id="<?= $shopper_id ?>">
                                <i class="icon-clothes4"></i>
                            </span>
                        </li>
                        <li>
                            <form method="post" action="<?= get_bloginfo('url'); ?>/process-plus-button">
                                <input hidden=""
                                        value="<?= get_the_modified_date('Y-m-d H:i:s'); ?>"/>
                                <input type="hidden" name="shopper_id" value="<?= $shopper_id ?>"/>
                                <input class="submitbtnimg <?= is_active($shopper_id, "timestamps"); ?>" 
                                       type="submit" name="plusbtn" value="&#xf067;"/>
                            </form>
                        </li>
                        <li>
                            <span class="dollar <?= is_active($shopper_id, "dollar_button_clicked"); ?>" data-id="<?= $shopper_id ?>">
                                <i class="fa fa-usd"></i>
                            </span>
                        </li>
                        <li>
                            <span class="checkbox active">
                                <input type="hidden" value="false" form="bulkActionForm"
                                    data-id="<?= $shopper_id ?>" name="<?= $shopper_id ?>"/>
                            </span>
                        </li>
                       
                    </ul>
                </div>
                <div style="clear: both;"></div>
            </div>
                    <?php
        }
    } ?>