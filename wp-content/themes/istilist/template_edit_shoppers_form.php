<?php /* Template Name: Edit Shoppers Form */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
    ?>
<div id="dashboard">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <?php get_sidebar('menu'); ?>
	        <div class="col span_9_of_12 matchheight">
                <div class="dash_content">
                    <h1><?php the_title(); ?></h1>
                    <div class="box">
                        <?php
                            global $wpdb;
    global $user_ID;
    $store_id = get_user_meta($user_ID, 'store_id', true);
    if (isset($_POST['validate_me'])) {
        global $user_ID;
        $valid_authentication = 0;
        $user = get_user_by('id', $user_ID);
        $pass = $_POST['authentication_pass'];
        if ($user && wp_check_password($pass, $user->data->user_pass, $user->ID)) {
            $valid_authentication = 1;
        } else {
            $valid_authentication = 0;
        }
    }
    if (isset($_POST['save_changes'])) {
        if (!empty($_POST)) {
            $table_name2 = $wpdb->prefix.'dynamic_form';
            $sql2 = "SELECT * FROM $table_name2 WHERE store_owner_id = $store_id ORDER BY id";
            $results2 = $wpdb->get_results($sql2);
            foreach ($results2 as $r2) {
                $var = 'form_element_'.$r2->form_slug;
                $form_element = $_POST[$var];
                if (empty($form_element)) {
                    $form_element = 0;
                }
                $sql3 = "UPDATE $table_name2 SET is_active = $form_element WHERE form_slug = '".$r2->form_slug."' AND store_owner_id = $store_id";
                $wpdb->query($sql3);
            }
        }
        $valid_authentication = 1;
    }
    if (isset($_POST['add_new_field'])) {
        $table_name = $wpdb->prefix.'dynamic_form';
        $form_slug = preg_replace('/\s+/', '_', strtolower($_POST['form_display_name']));
        $form_slug = preg_replace('/[^A-Za-z\_]/', '', $form_slug);
        $form_slug = preg_replace('/-+/', '_', $form_slug);
        echo $form_slug;
        $sql = "INSERT INTO $table_name(form_display_name, form_slug, form_type, is_required, is_active, store_owner_id, is_custom) VALUES('".$_POST['form_display_name']."', '".$form_slug."', '".$_POST['form_type']."', '".$_POST['is_required']."', 1, '".$_POST['store_id']."', 1)";
        $wpdb->query($sql);
        $field_added = 1;
        $valid_authentication = 1;
    } ?>
                        <?php if ($valid_authentication == 1) {
        ?>
                            <div class="section group">
                                <div class="col span_12_of_12">
                                    <form method="post" action="">
                                        <table class="editFormTable">
                                            <thead>
                                                <tr>
                                                    <th>Form Field Name</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $table_name1 = $wpdb->prefix.'dynamic_form';
        $sql1 = "SELECT * FROM $table_name1 WHERE store_owner_id = $user_ID ORDER BY id";
        $results1 = $wpdb->get_results($sql1);
        foreach ($results1 as $r1) {
            ?>
                                                <tr>
                                                    <td><?php echo $r1->form_display_name; ?></td>
                                                    <td><input type="checkbox" name="form_element_<?php echo $r1->form_slug; ?>" value="1" <?php if ($r1->is_active == 1) {
                echo 'checked="checked"';
            } ?> /></td>
                                                </tr>
                                                <?php
        } ?>
                                            </tbody>
                                            <tfoot class="hide-if-no-paging">
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="pagination pagination-centered"></div>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <div class="section group">
                                            <div class="col span_12_of_12">
                                                <div style="text-align: right;">
                                                    <input type="submit" name="save_changes" value="Save Changes" />
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_12_of_12">
                                    <div class="add_new_field">
                                        <form method="post" action="">
                                        <fieldset>
                                            <legend>Add New Field</legend>
                                            <?php if ($field_added == 1) {
            ?>
                                            <div class="successMsg">New field added.</div>
                                            <?php
        } ?>
                                            <div class="section group">
                                                <div class="col span_12_of_12">
                                                    <label>Field Name</label>
                                                    <input type="text" name="form_display_name" />
                                                </div>
                                            </div>
                                            <div class="section group">
                                                <div class="col span_12_of_12">
                                                    <label>Field Type</label>
                                                    <select name="form_type">
                                                        <option value="text">Text</option>
                                                        <option value="textarea">Textarea</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="section group">
                                                <div class="col span_12_of_12">
                                                    <label>Required? <input type="checkbox" name="is_required" value="1" /></label>
                                                </div>
                                            </div>
                                            <div class="section group">
                                                <div class="col span_12_of_12">
                                                    <input type="hidden" name="store_id" value="<?php echo $store_id; ?>" />
                                                    <input type="submit" name="add_new_field" value="Add New Field" />
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        <?php
    } else {
        ?>
                        <div class="section group">
                            <div class="col span_4_of_12"></div>
                            <div class="col span_4_of_12">
                                <div class="passwordFrom">
                                    <form method="post" action="">
                                        <h1>istilist</h1>
                                        <div>
                                            <label>Password</label>
                                            <input type="password" name="authentication_pass" />
                                        </div>
                                        <div style="text-align: center; margin-top: 15px;"><input type="submit" name="validate_me" value="Submit" /></div>
                                    </form>
                                </div>
                            </div>
                            <div class="col span_4_of_12"></div>
                        </div>
                        <?php
    } ?>
                    </div>
                </div>
                <?php get_footer(); ?>                          
	        </div>
	    </div>
	</div>
</div>
<?php
} else {
        header('Location: '.get_bloginfo('url').'/login');
    } ?>