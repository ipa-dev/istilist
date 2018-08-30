<?php /* Template Name: Dashboard */ ?>
<?php get_header(); ?>
<?php if ( is_user_logged_in() ) { ?>
	<?php
	global $user_ID;
	global $wpdb;
	?>
	<?php $store_id = get_user_meta( $user_ID, 'store_id', true ); ?>
	<?php
	//When user presses plus button
	if ( isset( $_POST['plusbtn'] ) ) {
		require_once 'php_modules/template-dashboard/module-onplusbtn.php';
		header( 'Location: ' . get_bloginfo( 'url' ) . '/dashboard' );
	}
	?>
	<?php require_once 'php_modules/section-assignstylist.php'; ?>
<div id="dashboard">
	<div class="maincontent noPadding">
		<div class="section group">
			<?php get_sidebar( 'menu' ); ?>
			<div class="col span_9_of_12 matchheight">
				<div class="dash_content">
					<div class="banner1">
						<?php echo do_shortcode( '[rev_slider home]' ); ?>
					</div>
					<form method="post" action="http://istilist.com/dashboard" style="width:25%;margin-bottom:2%;float:right;">
						<div class="searchForm">
							<input type="text" id="search_query" name="search_query" value="<?php echo $_POST['search_query']; ?>" />
							<input type="submit" id="search_btn" name="search_btn" value="&#xf002" />
						 </div>
					</form>
					<?php require_once 'php_modules/template-dashboard/section-bulkactions.php'; ?>
					<?php
					if ( ! isset( $_POST['search_query'] ) ) {
						$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

						$post_args = array(
							'post_type'      => 'shopper',
							'post_status'    => 'publish',
							'meta_key'       => 'store_id',
							'meta_value'     => $store_id,
							'paged'          => $paged,
							'posts_per_page' => 30,
							'orderby'        => 'modified',
						);

						$store_reverse_order = get_user_meta( $store_id, 'reverse_order', true );
						if ( empty( $store_reverse_order ) || $store_reverse_order == null ) {
							$post_args['order'] = 'DESC';
						} elseif ( $store_reverse_order == 'on' ) {
							$post_args['order'] = 'ASC';
						}


						$the_query = new WP_Query( $post_args );

						if ( $the_query->have_posts() ) {
							while ( $the_query->have_posts() ) :
								$the_query->the_post();
								$shopper_id = get_the_ID();
								require_once 'php_modules/template-dashboard/section-shopperlist.php';
								?>

							<div class="box_actions">
								<ul>
									<li><a href="<?php bloginfo( 'url' ); ?>/edit-shoppers?id=<?php echo encripted( $shopper_id ); ?>"><i class="fa fa-pencil"></i></a></li>
									<li><a href="javascript:void(0)" id="<?php echo $shopper_id; ?>-bell" 
																					<?php
																					if ( get_post_meta( $shopper_id, 'notified', true ) == 'true' ) {
																						echo "style='color:#14b9d6'";
																					}
																					?>
			 onClick="sendTextNotification(
								<?php
								$customer_phone = get_post_meta( $shopper_id, 'customer_phone', true );
								if ( ! empty( $customer_phone ) ) {
									echo $shopper_id . ', \'TRUE\'';
								} else {
									echo $shopper_id . ', \'FALSE\'';
								}
								?>
			)"><i class="fa fa-bell"></i></a></li>
									<li><a href="#stylistpopup" class="assignStylist" 
									<?php
									$assign_stylist = get_post_meta( $shopper_id, 'assign_stylist', true );
									if ( ! empty( $assign_stylist ) ) {
										echo "style='color:#14b9d6'";
									}
									?>
			 rel="<?php echo $shopper_id; ?>"><i class="icon-clothes4"></i></a></li>
									<!-- <li><a href="javascript:void(0);"><i class="fa fa-plus"></i></a></li> -->
									<li>
										<form method="post" action="">
											<input hidden="" value="<?php echo get_the_modified_date( 'Y-m-d H:i:s' ); ?>" />
											<input type="hidden" name="shopper_id" value="<?php echo $shopper_id; ?>" />
											<input class="submitbtnimg" 
											<?php
											$timestamps = get_post_meta( $shopper_id, 'timestamps', true );
											if ( ! empty( $timestamps ) ) {
												echo "style='color:#14b9d6'";
											}
											?>
			 type="submit" name="plusbtn" value="&#xf067;" />
										</form>
									</li>
									<li><a href="javascript:void(0);" 
									<?php
									if ( get_post_meta( $shopper_id, 'dollar_button_clicked', true ) == 1 ) {
										echo "style='color:#14b9d6'";
									}
									?>
			 class="dollar" rel="<?php echo $shopper_id; ?>"><i class="fa fa-usd"></i></a></li>
									<li><a href="javascript:void(0);" onclick="check(<?php echo $shopper_id; ?>);" id="checkBox<?php echo $shopper_id; ?>"></a></li>
									<input type="hidden" value="no" form="bulkActionForm" id="checkInput<?php echo $shopper_id; ?>" name="<?php echo $shopper_id; ?>"/>
								</ul>
							</div>
							<div style="clear: both;"></div>
						</div>
								<?php
							endwhile;
						} else {
							?>
						<div class="box">
							<p style="text-align: center; padding-bottom: 0;">No Shopper Registered</p>
						</div>
										<?php
						}
						?>
						<?php wp_reset_postdata(); ?>
						<div class="paginationWrapper">
						<?php
						if ( function_exists( 'wp_pagenavi' ) ) {
							wp_pagenavi( array( 'query' => $the_query ) );
						}
						?>
		</div>
						<?php
					}
					?>
					<?php
					if ( isset( $_POST['search_query'] ) ) {
						include 'pagination.class.php';
						$current_user_store_id = get_user_meta( $user_ID, 'store_id', true );
						/*Query 2 */
						$arg2   = array(
							'meta_key'       => 'customer_email',
							'meta_value'     => $_POST['search_query'],
							'post_type'      => 'shopper',
							'post_status'    => 'publish',
							'posts_per_page' => -1,
						);
						$query2 = new WP_Query( $arg2 );
						$ids2   = array();
						while ( $query2->have_posts() ) :
							$query2->the_post();
							array_push( $ids2, get_the_ID() );
		endwhile;

						/* Query 3 */
						$arg3   = array(
							'meta_key'       => 'customer_address',
							'meta_value'     => $_POST['search_query'],
							'post_type'      => 'shopper',
							'post_status'    => 'publish',
							'posts_per_page' => -1,
						);
						$query3 = new WP_Query( $arg3 );
						$ids3   = array();
						while ( $query3->have_posts() ) :
							$query3->the_post();
							array_push( $ids3, get_the_ID() );
		endwhile;

						/* Query 4 */
						$arg4   = array(
							'meta_key'       => 'customer_city',
							'meta_value'     => $_POST['search_query'],
							'post_type'      => 'shopper',
							'post_status'    => 'publish',
							'posts_per_page' => -1,
						);
						$query4 = new WP_Query( $arg4 );
						$ids4   = array();
						while ( $query4->have_posts() ) :
							$query4->the_post();
							array_push( $ids4, get_the_ID() );
		endwhile;

						/* Query 5 */
						$arg5   = array(
							'meta_key'       => 'customer_state',
							'meta_value'     => $_POST['search_query'],
							'post_type'      => 'shopper',
							'post_status'    => 'publish',
							'posts_per_page' => -1,
						);
						$query5 = new WP_Query( $arg5 );
						$ids5   = array();
						while ( $query5->have_posts() ) :
							$query5->the_post();
							array_push( $ids5, get_the_ID() );
		endwhile;

						/* Query 6 */
						$arg6   = array(
							'meta_key'       => 'customer_zip',
							'meta_value'     => $_POST['search_query'],
							'post_type'      => 'shopper',
							'post_status'    => 'publish',
							'posts_per_page' => -1,
						);
						$query6 = new WP_Query( $arg6 );
						$ids6   = array();
						while ( $query6->have_posts() ) :
							$query6->the_post();
							array_push( $ids6, get_the_ID() );
		endwhile;

						/* Query 7 */
						/* Code Allows for Partial Searches */
						function name_filter( $where, &$query7 ) {
							global $wpdb;
							if ( $search_term = $query7->get( 'search_shopper_name' ) ) {
								$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'' . esc_sql( like_escape( $search_term ) ) . '%\'';
							}
							return $where;
						}
						/***********************************/
						$arg7 = array(
							'meta_key'                => 'customer_fname',
							//'meta_value' => $_POST['search_query'], //original code
								'search_shopper_name' => $_POST['search_query'], // added code for partial searches
							'post_type'               => 'shopper',
							'post_status'             => 'publish',
							'posts_per_page'          => -1,
						);
						add_filter( 'posts_where', 'name_filter', 10, 2 ); //added code for partial searches
						$query7 = new WP_Query( $arg7 );
						remove_filter( 'posts_where', 'name_filter', 10, 2 ); //added code for partial searches
						$ids7 = array();
						while ( $query7->have_posts() ) :
							$query7->the_post();
							array_push( $ids7, get_the_ID() );
		endwhile;

						/* Query 8 */
						$arg8   = array(
							'meta_key'       => 'customer_lname',
							'meta_value'     => $_POST['search_query'],
							'post_type'      => 'shopper',
							'post_status'    => 'publish',
							'posts_per_page' => -1,
						);
						$query8 = new WP_Query( $arg8 );
						$ids8   = array();
						while ( $query8->have_posts() ) :
							$query8->the_post();
							array_push( $ids8, get_the_ID() );
		endwhile;

						/* Marge IDs and get Unique IDs*/
						$mergedposts = array_merge( $ids2, $ids3, $ids4, $ids5, $ids6, $ids7, $ids8 );
						$postids     = array();
						foreach ( $mergedposts as $item ) {
							array_push( $postids, $item );
						}
						$uniqueposts1 = array_unique( $postids );
						$uniqueposts  = array();
						//print_r($uniqueposts);
						for ( $i = 0; $i < count( $uniqueposts1 ); $i++ ) {
							$shopper_store_id = get_post_meta( $uniqueposts1[ $i ], 'store_id', true );
							if ( $shopper_store_id == $current_user_store_id ) {
								array_push( $uniqueposts, $uniqueposts1[ $i ] );
							}
						}
						?>
								<?php
								if ( count( $uniqueposts ) ) {
									?>
									<?php $pagination = new pagination( $uniqueposts, ( isset( $_GET['pageno'] ) ? $_GET['pageno'] : 1 ), 5 ); ?>
									<?php
									$pagination->setShowFirstAndLast( false );
									$pagination->setMainSeperator( '  ' );
									$productPages = $pagination->getResults();
									if ( count( $productPages ) != 0 ) {
										$pageNumbers = '<div class="numbers">' . $pagination->getLinks( $_GET ) . '</div>';
										?>
														<?php
														foreach ( $productPages as $shopper_id ) {
															?>
															<?php //foreach($uniqueposts as $shopper_id){ ?>
															<?php
															if ( get_post_meta( $shopper_id, 'dollar_button_clicked', true ) == 1 ) {
																?>
										<div class="box active">
																<?php
															} else {
																?>
										<div class="box">
																<?php
															}
															?>
									<div class="box_pic noprofileimg"><img src="<?php bloginfo( 'template_directory' ); ?>/images/noprofileimg.png" /></div>
									<div class="box_description" data-shopperid="<?php echo $shopper_id; ?>">
								<h2><?php echo get_post_meta( $shopper_id, 'customer_fname', true ); ?> <?php echo get_post_meta( $shopper_id, 'customer_lname', true ); ?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><strong>Event:</strong> <?php echo get_post_meta( $shopper_id, 'school_event', true ); ?></span> <br /><span>on <?php echo date( 'm.d.Y', strtotime( get_post_meta( $shopper_id, 'entry_date', true ) ) ); ?> at <?php echo date( 'h:i a', strtotime( get_post_meta( $shopper_id, 'entry_date', true ) ) ); ?></span></h2>
															<?php $assign_stylist = get_post_meta( $shopper_id, 'assign_stylist', true ); ?>
															<?php
															if ( ! empty( $assign_stylist ) ) {
																?>
								<div class="section group">
									<div class="col span_6_of_12">
										<p class="assignStylistClass" style="padding-bottom: 6px;"><strong>Stylist Name </strong>: <span ><?php echo get_the_author_meta( 'display_name', get_post_meta( $shopper_id, 'stylist_id', true ) ); ?></span></p>
										<p class="assignStylistClass" style="padding-bottom: 6px;"><strong>Stylist Assigned </strong>: <span id="assignStylist_<?php echo $shopper_id; ?>"><?php echo date( 'h:i a', strtotime( $assign_stylist ) ); ?></span></p>
									</div>
									<div class="col span_6_of_12">
									<p class="assignStylistClass" style="padding-bottom: 6px;"><strong>Fitting Room ID </strong>: <span ><?php echo get_post_meta( $shopper_id, 'fitting_room_id', true ); ?></span></p>
									<p class="assignStylistClass"><strong>Waiting Time :</strong> <span><?php elapsedtime( date( 'h:i:s', strtotime( get_post_meta( $shopper_id, 'entry_date', true ) ) ), date( 'h:i:s', strtotime( $assign_stylist ) ) ); ?></span></p>
									</div>
								</div>
																<?php
															}
															?>
								<p><?php echo excerpt( 40 ); ?></p>
															<?php
															if ( get_post_meta( $shopper_id, 'dollar_button_clicked', true ) == 1 ) {
																?>
																<?php $purchased = get_post_meta( $shopper_id, 'reason_not_purchased', true ); ?>
																<?php
																if ( $purchased ) {
																	?>
									<p class="reasone" style="padding-bottom: 5px;"><strong>Purchased?: </strong>NO</p>
									<p class="reasone"><strong>Reasons not to buy: </strong><?php echo get_post_meta( $shopper_id, 'reason_not_purchased', true ); ?></p>
																	<?php
																} else {
																	?>
									<p class="reasone"><strong>Purchased?: </strong>YES</p>
																														<?php
																}
																?>
																<?php
															}
															?>
							</div>
									<div class="box_actions">
										<ul>
											<li><a href="<?php bloginfo( 'url' ); ?>/edit-shoppers?id=<?php echo encripted( $shopper_id ); ?>"><i class="fa fa-pencil"></i></a></li>
											<li><a href="#stylistpopup" class="assignStylist" rel="<?php echo $shopper_id; ?>"><i class="icon-clothes4"></i></a></li>
											<li>
											<form method="post" action="">
												<input hidden="" value="<?php echo get_the_modified_date( 'Y-m-d H:i:s' ); ?>" />
												<input type="hidden" name="shopper_id" value="<?php echo $shopper_id; ?>" />
												<input class="submitbtnimg" type="submit" name="plusbtn" value="&#xf067;" />
											</form>
											</li>
											<li><a href="javascript:void(0);" class="dollar" rel="<?php echo $shopper_id; ?>"><i class="fa fa-usd"></i></a></li>
										</ul>
									</div>
									<div style="clear: both;"></div>
									</div>
															<?php
														} // end of if loop
														?>
										<?php echo '<div style="clear: both;"></div><div class="customPagination" style="margin-bottom:2%">' . $pageNumbers . '</div>'; ?>
														<?php
									} // end of foreach loop
									?>
									<?php
								} // end of if loop
								?>
								<?php
					} // end of main if loop
					?>

					<!-- BX Slider -->
					<div class="slider">
						<div class="bxslider">
							<?php
								$tips_args = array(
									'post_type'      => 'tips',
									'post_status'    => 'publish',
									'posts_per_page' => 3,
								);

							$tips_query = new WP_Query( $tips_args );
							if ( $tips_query->have_posts() ) {
								while ( $tips_query->have_posts() ) :
									$tips_query->the_post();
									?>
								<div>
									<h2><?php the_title(); ?></h2>
															<?php the_content(); ?>
								</div>
														<?php endwhile ?>
													<?php
							} else {
								?>
								<div>No Tips</div>
													<?php
							}
							?>
							<?php wp_reset_postdata(); ?>
						</div>
						<div class="slider_controls">
							<div class="slider_prev"></div>
							<div class="slider_next"></div>
						</div>
					</div>
				</div>
				<?php get_footer(); ?>
			</div>
		</div>
	</div>
</div>
<script>
function sendTextNotification(shopper_id, is_phone) {
	/*
		Send AJAX notification if customer_phone meta is set

	*/
	if (is_phone == 'TRUE') {
			//Send AJAX request to PHP script that sends text message to shopper
			jQuery.ajax({
			   url: "http://istilist.com/notify-shopper/",
			   method: "POST",
			   data: {shopperID: shopper_id},
			   success: function (e) {
				   if (e == 'na') {
					   swal({
						  title: "Error",
						  text: "This shopper did not authorize text messages.",
						  type: "info"
					   });
				   }
			   },
			   error: function (e) {
				   if (e == 'na') {
					   swal({
						  title: "Error",
						  text: "This shopper did not authorize text messages.",
						  type: "info"
					   });
				   }
			   }
			});
	}
	else {
			//Do swal and let user know that phone number is not set
			swal({
				title: "Error",
				text: "This shopper does not have a phone number listed.",
				type: 'info'
			});
	}
	jQuery('#'+shopper_id+'-bell').css('color', '#14b9d6');
}

function check(shopper_id) {
	var input = document.getElementById('checkInput'+shopper_id);

	if (input.value == "" || input.value == "no") {
		input.value = "yes";
		jQuery('#checkBox'+shopper_id).append('<i class="fa fa-check"></i>');
		jQuery('#checkBox'+shopper_id).css('color', '#14b9d6');
	}
	else if (input.value === "yes") {
		input.value = "no";
		jQuery('#checkBox'+shopper_id).empty();
		jQuery('#checkBox'+shopper_id).css('color', '');
	}
}


jQuery(document).ready(function(){

	// this is for second button
	jQuery('.assignStylist').click(function(){
		var shopper_id = jQuery(this).attr('rel');
		jQuery('#shopper_id').val(shopper_id);
	});

	// this is for 3rd button
	jQuery('.dollar').click(function(){
		var shopper_id = jQuery(this).attr('rel');
		swal({
			title: "Made a Purchase?",
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
					url: "<?php echo get_bloginfo( 'url' ); ?>/complete-purchase",
					type: "post",
					data: {"store_id": <?php echo get_user_meta( $user_ID, 'store_id', true ); ?>, "shopper_id": shopper_id},
					success: function(responce){
						swal({
							title: "Thank You",
							type: "success",
						}, function(){
							location.reload();
						});
					},
					error:function(responce){
						console.log(responce);
						alert("failure : "+responce);
					}
				});
			} else {
				swal({
					title: "Reason",
					//text: "Reason:",
					type: "input",
					showCancelButton: true,
					closeOnConfirm: false,
					animation: "slide-from-top",
					inputPlaceholder: ""
				},function(inputValue){
					if (inputValue === false) return false;
					if (inputValue === ""){
						inputValue = ".";
					}
					jQuery.ajax({
						url: "<?php echo get_bloginfo( 'url' ); ?>/no-purchase",
						type: "post",
						data: {"store_id": <?php echo get_user_meta( $user_ID, 'store_id', true ); ?>, "shopper_id": shopper_id, "reason": inputValue},
						success: function(responce){
						   //alert("success : "+responce);
						   swal.close();
						   location.reload();
						},
						error:function(responce){
						   console.log(responce);
							alert("failure : "+responce);
						}
					});
				});
			}
		});
	});
});
</script>
	<?php
} else {
		header( 'Location: ' . get_bloginfo( 'url' ) . '/login' );
} ?>
