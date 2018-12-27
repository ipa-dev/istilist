<?php
/*
  Plugin Name: Banner Upload
  Plugin URI: http://buffercode.com/project/banner-upload/
  Description: Easy way to display the different size of banner advertisements in WordPress using widgets
  Version: 1.6
  Author: vinoth06
  Author URI: http://buffercode.com/
  License: GPLv2
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */


add_action( 'widgets_init', 'buffercode_banner_upload' );

function buffercode_banner_upload() {
	register_widget( 'buffercode_banner_upload_info' );
}

class buffercode_banner_upload_info extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'buffercode_banner_upload_info',
			esc_html__( 'Banner Upload', 'bc_banner' ),
			array( 'description' => esc_html__( 'ASelect the category to display', 'bc_banner' ), )
		);
	}

	public function form( $instance ) {
		if ( isset( $instance['buffercode_BU_img_url'] ) && isset( $instance['buffercode_BU_width'] ) && isset( $instance['buffercode_BU_height'] ) && isset( $instance['buffercode_BU_title'] ) && isset( $instance['buffercode_BU_URL'] ) && isset( $instance['buffercode_BU_new_wind'] ) ) {
			$buffercode_BU_img_url  = $instance['buffercode_BU_img_url'];
			$buffercode_BU_width    = $instance['buffercode_BU_width'];
			$buffercode_BU_height   = $instance['buffercode_BU_height'];
			$buffercode_BU_title    = $instance['buffercode_BU_title'];
			$buffercode_BU_URL      = $instance['buffercode_BU_URL'];
			$buffercode_BU_new_wind = $instance['buffercode_BU_new_wind'];
		} else {
			$buffercode_BU_img_url  = '';
			$buffercode_BU_width    = 300;
			$buffercode_BU_height   = 250;
			$buffercode_BU_title    = 'Advertisement';
			$buffercode_BU_URL      = '';
			$buffercode_BU_new_wind = "1";
		}
		?>

		<p>Custom Title
			<input maxlength="50" class="widefat" name="<?php echo $this->get_field_name( 'buffercode_BU_title' ); ?>" type="text" value="<?php echo esc_attr( $buffercode_BU_title ); ?>" placeholder="Banner Title" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'buffercode_BU_img_url' ); ?>">Image</label>
			<br/>
			<input type="text" class="img" name="<?php echo $this->get_field_name( 'buffercode_BU_img_url' ); ?>" id="<?php echo $this->get_field_id( 'buffercode_BU_img_url' ); ?>" value="<?php echo $buffercode_BU_img_url; ?>"/>
			<button type="button" class="select-img">Select Image</button>
		</p>

		<p>size
		<br>
			<input maxlength="4" style="width:60px" class="widefat" name="<?php echo $this->get_field_name( 'buffercode_BU_width' ); ?>" type="text" value="<?php echo esc_attr( $buffercode_BU_width ); ?>" placeholder="W"/>
			px X
			<input maxlength="4" style="width:60px" class="widefat" name="<?php echo $this->get_field_name( 'buffercode_BU_height' ); ?>" type="text" value="<?php echo esc_attr( $buffercode_BU_height ); ?>" placeholder="H" />
			px
		</p>

		<p>Image Click Link
			<input class="widefat urlfield" name="<?php echo $this->get_field_name( 'buffercode_BU_URL' ); ?>" type="text" value="<?php echo esc_attr( $buffercode_BU_URL ); ?>" placeholder="Enter the URL for the image ads"/>
		</p>


		<p>Open in New Window ?
			<select name="<?php echo $this->get_field_name( 'buffercode_BU_new_wind' ); ?>" id="<?php echo $this->get_field_id( 'buffercode_BU_new_wind' ); ?>" class="widefat">
				<?php
				$bc_BU_new_wind_link_options = array( 'Yes' => '1', 'No' => '2' );
				foreach ( $bc_BU_new_wind_link_options as $bc_BU_new_wind_link_vlaue => $bc_BU_new_wind_code ) {
					echo '<option value="' . $bc_BU_new_wind_code . '" id="' . $bc_BU_new_wind_code . '"', $bc_BU_new_wind_code == $buffercode_BU_new_wind ? ' selected="selected"' : '', '>', $bc_BU_new_wind_link_vlaue, '</option>';
				}
				?>
			</select>
		</p>
		<p>
			<strong>If you would like to track the ads by number of impression and click and would like to add SWF and Script ads, please check this <a href="https://ifecho.com/random-banner-pro">Random Banner PRO version</a></strong>
		</p>

		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['buffercode_BU_title'] = ( ! empty( $new_instance['buffercode_BU_title'] ) ) ? strip_tags( $new_instance['buffercode_BU_title'] ) : '';

		$instance['buffercode_BU_img_url'] = ( ! empty( $new_instance['buffercode_BU_img_url'] ) ) ? strip_tags( $new_instance['buffercode_BU_img_url'] ) : '';

		$instance['buffercode_BU_width'] = ( ! empty( $new_instance['buffercode_BU_width'] ) ) ? strip_tags( $new_instance['buffercode_BU_width'] ) : '';

		$instance['buffercode_BU_height'] = ( ! empty( $new_instance['buffercode_BU_height'] ) ) ? strip_tags( $new_instance['buffercode_BU_height'] ) : '';

		$instance['buffercode_BU_URL'] = ( ! empty( $new_instance['buffercode_BU_URL'] ) ) ? strip_tags( $new_instance['buffercode_BU_URL'] ) : '';

		$instance['buffercode_BU_new_wind'] = ( ! empty( $new_instance['buffercode_BU_new_wind'] ) ) ? strip_tags( $new_instance['buffercode_BU_new_wind'] ) : '';

		return $instance;
	}

	function widget( $args, $instance ) {
		extract( $args );
		echo $before_widget;
		$buffercode_BU_title    = apply_filters( 'widget_title', $instance['buffercode_BU_title'] );
		$buffercode_BU_img_url  = empty( $instance['buffercode_BU_img_url'] ) ? '&nbsp;' :
			$instance['buffercode_BU_img_url'];
		$buffercode_BU_width    = empty( $instance['buffercode_BU_width'] ) ? '&nbsp;' :
			$instance['buffercode_BU_width'];
		$buffercode_BU_height   = empty( $instance['buffercode_BU_height'] ) ? '&nbsp;' :
			$instance['buffercode_BU_height'];
		$buffercode_BU_URL      = empty( $instance['buffercode_BU_URL'] ) ? '&nbsp;' :
			$instance['buffercode_BU_URL'];
		$buffercode_BU_new_wind = empty( $instance['buffercode_BU_new_wind'] ) ? '&nbsp;' :
			$instance['buffercode_BU_new_wind'];

		if ( ! empty( $name ) ) {
			echo $before_title . $buffercode_BU_title . $after_title;
		};

		if ( $buffercode_BU_new_wind == 1 ) {
			echo '<a href="' . $buffercode_BU_URL . '" alt="' . $buffercode_BU_URL . '" target="_blank">';
			echo '<img src="' . $buffercode_BU_img_url . '" width="' . $buffercode_BU_width . 'px" height="' . $buffercode_BU_height . 'px" />';
			echo '</a> ';
		} else {
			echo '<a href="' . $buffercode_BU_URL . '" alt="' . $buffercode_BU_URL . '">';
			echo '<img src="' . $buffercode_BU_img_url . '" width="' . $buffercode_BU_width . 'px" height="' . $buffercode_BU_height . 'px" />';
			echo '</a> ';
		}

		echo $after_widget;
	}

}

function my_enqueue( $hook ) {
	if ( 'widgets.php' != $hook ) {
		return;
	}
	wp_enqueue_style( 'thickbox' );
	wp_enqueue_script( 'media-upload' );
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_script( 'bannerupload-script', plugin_dir_url( __FILE__ ) . '/js/script.js' );
}

add_action( 'admin_enqueue_scripts', 'my_enqueue' );
?>