<?php /* Template Name: Edit Shoppers */ ?>
<?php get_header();
if (is_user_logged_in()) {
 global $user_ID;
 $store_id = get_user_meta($user_ID, 'store_id', true);
 $shopper_id = decripted($_GET['id']); ?>
<div id="dashboard" data-shopper_id="<?php echo $shopper_id; ?>">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <?php get_sidebar('menu'); ?>
	        <div class="col span_9_of_12 matchheight">
                <div class="dash_content">
                    <h1><?php the_title(); ?></h1>
                    <div class="box addnewshoppers">
                        <?php
                        if (isset($_POST['update_shopper_order'])) {
                            global $wpdb;
                            $post_arg = array(
                                'ID'            => $shopper_id,
                                'post_date'     => date('Y-m-d H:i:s'),
                                'post_date_gmt' => date('Y-m-d H:i:s')
                            );
                            wp_update_post($post_arg);
                            header('Location: '.get_bloginfo('url').'/dashboard/');
                        }
                        require_once 'php_modules/template-new-shopper/new-shopper-form.php';
						generate_new_shopper_form($wpdb, $store_id, get_bloginfo('url') . '/dashboard');
                        ?>
                            <div class="section group">
                                <div class="col span_12_of_12">
                                    <div style="text-align: right; float: right;">
                                        <input class="fa-input" type="submit" name="update_shopper_order" value="Update &#xf0fe" />
                                    </div>
                                    <div style="text-align: right; float: right; margin-right: 1%;">
                                        <input type="submit" name="update_shopper" value="Update" />
                                    </div>
                                    <div id="delete_shopper" class="" style="float: right; margin-right: 1%;">
                                       Delete Shopper?
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php get_footer(); ?>
	        </div>
	    </div>
	</div>
</div>
<script type="text/javascript">

	jQuery(document).ready( function () {
		jQuery('#delete_shopper').click(function () {
	          swal({
	            title: "Are you sure?",
	            text: "You will not be able to recover this shopper's information",
	            type: "warning",
	            showCancelButton: true,
	            confirmButtonColor: "#DD6B55",
	            confirmButtonText: "Yes",
	            cancelButtonText: "No",
	            closeOnConfirm: false,
	            closeOnCancel: false
	          }, function(isConfirm){
	            if (isConfirm){
	                jQuery.ajax({
	                	url: "<?php echo get_bloginfo('url'); ?>/delete-shopper",
	                	type: "post",
	                        data: {"shopper_id": <?php echo $shopper_id; ?>},
	                	success: function(responce){
	                	   //alert(responce);
	                        swal({
	                            title: "Deleted",
	                            text: "This shopper has been deleted.",
	                            type: "success",
	                        }, function(){
	                            window.location="<?php bloginfo('url') ?>/dashboard/";
	                        });
	                	},
	                	error:function(responce){
	                	    console.log(responce);
	                	    alert("failure : "+responce);
	                	}
	                });
	            } else {
	                swal.close();
	            }
	        });
     	     });
	});

</script>
<?php
} else {
        header('Location: '.get_bloginfo('url').'/login');
    } ?>
