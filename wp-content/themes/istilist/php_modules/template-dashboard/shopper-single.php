<?php 
    if (get_post_meta($shopper_id, 'dollar_button_clicked', true) == 1) {
        echo '<div class="box active">'; 
    } else {
        echo '<div class="box">';
    } 
?>
                <!--<div class="box_pic">
                    <?php echo get_profile_img($shopper_id); ?>
                </div>-->
                <div class="box_description" data-shopperid="<?php echo $shopper_id; ?>">
                    <h2>
                        <?php echo get_post_meta($shopper_id, 'customer_fname', true) . ' ' . get_post_meta($shopper_id, 'customer_lname', true); ?>
                        <span><strong>Event:</strong> <?php echo get_post_meta($shopper_id, 'school_event', true); ?></span>
                        <br/>
						<?php
                            $timestamps = get_post_meta($shopper_id, 'timestamps', true);
                            $purchases  = get_post_meta($shopper_id, 'purchase_array', true);
                            if (! empty($timestamps)) {
                                $index = count($timestamps);
                                while ($index) {
                                    echo "<span>on " . date('m.d.Y', strtotime($timestamps[ -- $index ])) . " at " . date('h:i a', strtotime($timestamps[ $index ]));
                                    if ($index != (count($timestamps) - 1)) {
                                        if ($purchases[ $index - 1 ] == 'true') {
                                            echo "\tPurchase";
                                        } else {
                                            echo "\tNo Purchase";
                                        }
                                    }
                                    echo "</span><br />";
                                }
                            } ?>
                                    <span><?php echo date('m.d.Y', strtotime(get_post_meta($shopper_id, 'entry_date', true))); ?>
                                        at <?php echo date('h:i a', strtotime(get_post_meta($shopper_id, 'entry_date', true))); ?>
                                        - <?php $stylist_id = get_post_meta($shopper_id, 'stylist_id', true);
            if (! empty($stylist_id)) {
                echo get_the_author_meta('display_name', get_post_meta($shopper_id, 'stylist_id', true));
            } ?>
										<?php
                                        if (! empty($purchases)) {
                                            if ($purchases[0] == 'true') {
                                                echo "\t - YES";
                                            } else {
                                                echo "\t - NO";
                                            }
                                        } ?>
                                  </span>
                                </h2>
								<?php $assign_stylist = get_post_meta($shopper_id, 'assign_stylist', true); ?>
								<?php if (! empty($assign_stylist)) {
                                            ?>
                                    <div class="section group">
                                        <div class="col span_6_of_12">
                                            <p class="assignStylistClass" style="padding-bottom: 6px;"><strong>Stylist
                                                    Name </strong>:
                                                <span><?php echo get_the_author_meta('display_name', get_post_meta($shopper_id, 'stylist_id', true)); ?></span>
                                            </p>
                                            <p class="assignStylistClass" style="padding-bottom: 6px;"><strong>Stylist
                                                    Assigned </strong>: <span
                                                        id="assignStylist_<?php echo $shopper_id; ?>"><?php echo date('h:i a', strtotime($assign_stylist)); ?></span>
                                            </p>
                                        </div>
                                        <div class="col span_6_of_12">
                                            <p class="assignStylistClass" style="padding-bottom: 6px;"><strong>Fitting
                                                    Room ID </strong>:
                                                <span><?php echo get_post_meta($shopper_id, 'fitting_room_id', true); ?></span>
                                            </p>
											<?php if (empty($timestamps)) {
                                                ?>
                                                <p class="assignStylistClass"><strong>Waiting Time :</strong>
                                                    <span><?php elapsedtime(date('h:i:s', strtotime(get_post_meta($shopper_id, 'entry_date', true))), date('h:i:s', strtotime($assign_stylist))); ?></span>
                                                </p>
											<?php
                                            } elseif (count($timestamps) == 1 && get_post_meta($shopper_id, 'hit_plus', true) == 'false') {
                                                ?>
                                                <p class="assignStylistClass"><strong>Waiting Time :</strong>
                                                    <span><?php elapsedtime(date('h:i:s', strtotime(get_post_meta($shopper_id, 'entry_date', true))), date('h:i:s', strtotime($assign_stylist))); ?></span>
                                                </p>
											<?php
                                            } elseif (! empty($timestamps) && get_post_meta($shopper_id, 'hit_plus', true) == 'false') {
                                                ?>
                                                <p class="assignStylistClass"><strong>Waiting Time
                                                        :</strong><span><?php elapsedtime(date('h:i:s', strtotime($timestamps[ count($timestamps) - 2 ])), date('h:i:s', strtotime($assign_stylist))); ?></span>
                                                </p>
											<?php
                                            } elseif (! empty($timestamps) && get_post_meta($shopper_id, 'hit_plus', true) == 'true') {
                                                ?>
                                                <p class="assignStylistClass"><strong>Waiting Time
                                                        :</strong><span><?php elapsedtime(date('h:i:s', strtotime($timestamps[ count($timestamps) - 1 ])), date('h:i:s', strtotime($assign_stylist))); ?></span>
                                                </p>
											<?php
                                            } ?>
                                        </div>
                                    </div>
								<?php
                                        } ?>
                                <div class="section group">
                                    <div class="col span_12_of_12">
										<?php //Adds number to show how many times a girl has gone in a fitting room on the current date
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
            } ?>
                                    </div>
                                </div>
                                <p><?php echo excerpt(40); ?></p>
								<?php if (get_post_meta($shopper_id, 'dollar_button_clicked', true) == 1 && get_post_meta($shopper_id, 'hit_plus', true) == 'false') {
                ?>
									<?php $purchased = get_post_meta($shopper_id, 'reason_not_purchased', true); ?>
									<?php if ($purchased) {
                    ?>
                                        <p class="reasone" style="padding-bottom: 5px;"><strong>Purchased?: </strong>NO
                                        </p>
                                        <p class="reasone"><strong>Reasons not to
                                                buy: </strong><?php echo get_post_meta($shopper_id, 'reason_not_purchased', true); ?>
                                        </p>
									<?php
                } else {
                    ?>
                                        <p class="reasone"><strong>Purchased?: </strong>YES</p>
									<?php
                } ?>
								<?php
            } ?>
                            </div>
                            <div class="box_actions">
                                <ul>
                                    <li>
                                        <a href="<?php bloginfo('url'); ?>/edit-shoppers?id=<?php echo encripted($shopper_id); ?>"><i
                                                    class="fa fa-pencil"></i></a></li>
                                    <li><a href="javascript:void(0)"
                                           id="<?php echo $shopper_id; ?>-bell" <?php if (get_post_meta($shopper_id, 'notified', true) == 'true') {
                echo "style='color:#14b9d6'";
            } ?>
                                           onClick="sendTextNotification(<?php $customer_phone = get_post_meta($shopper_id, 'customer_phone', true);
            if (! empty($customer_phone)) {
                echo $shopper_id . ', \'TRUE\'';
            } else {
                echo $shopper_id . ', \'FALSE\'';
            } ?>)"><i class="fa fa-bell"></i></a></li>
                                    <li><a href="#stylistpopup"
                                           class="assignStylist" <?php $assign_stylist = get_post_meta($shopper_id, 'assign_stylist', true);
            if (! empty($assign_stylist)) {
                echo "style='color:#14b9d6'";
            } ?> rel="<?php echo $shopper_id; ?>"><i class="icon-clothes4"></i></a></li>
                                    <!-- <li><a href="javascript:void(0);"><i class="fa fa-plus"></i></a></li> -->
                                    <li>
                                        <form method="post" action="">
                                            <input hidden=""
                                                   value="<?php echo get_the_modified_date('Y-m-d H:i:s'); ?>"/>
                                            <input type="hidden" name="shopper_id" value="<?php echo $shopper_id; ?>"/>
                                            <input class="submitbtnimg" <?php $timestamps = get_post_meta($shopper_id, 'timestamps', true);
            if (! empty($timestamps)) {
                echo "style='color:#14b9d6'";
            } ?> type="submit" name="plusbtn" value="&#xf067;"/>
                                        </form>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" <?php if (get_post_meta($shopper_id, 'dollar_button_clicked', true) == 1) {
                echo "style='color:#14b9d6'";
            } ?> class="dollar" rel="<?php echo $shopper_id; ?>"><i
                                                    class="fa fa-usd"></i></a></li>
                                    <li><a href="javascript:void(0);" onclick="check(<?php echo $shopper_id; ?>);"
                                           id="checkBox<?php echo $shopper_id; ?>"></a></li>
                                    <input type="hidden" value="no" form="bulkActionForm"
                                           id="checkInput<?php echo $shopper_id; ?>" name="<?php echo $shopper_id; ?>"/>
                                </ul>
                            </div>
                            <div style="clear: both;"></div>
                        </div>