<div id="stylistpopup">
    <form method="post" action="<?= get_bloginfo('url'); ?>/process-assign-stylist">
        <div class="section group">
            <div class="col span_12_of_12">
                <h3>Assign Stylist</h3>
                <label>Select Stylist</label>
                <select name="stylist_id">
					<?php
                    $user_query = new WP_User_Query(array(
                        'role'       => 'storeemployee',
                        'meta_key'   => 'store_id',
                        'meta_value' => $store_id,
                        'orderby'    => 'display_name',
                        'order'      => 'ASC'
                    ));
                    foreach ($user_query->results as $user) {
                        $user_status = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users WHERE ID = %s", $user->ID));
                        if ($user_status[0]->user_status != 0) {
                            echo '<option value="' . $user->ID . '">' . $user->display_name . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="section group">
            <div class="col span_12_of_12">
                <label>Fitting Room ID</label>
                <input type="number" pattern="[0-9]*" inputmode="numeric" name="fitting_room_id"/>
            </div>
        </div>
        <div class="section group">
            <div class="col span_12_of_12">
                <input id="shopper_id" type="hidden" name="shopper_id" value=""/>
                <input type="submit" name="assign_stylist" value="Assign"/>
            </div>
        </div>
    </form>
</div>