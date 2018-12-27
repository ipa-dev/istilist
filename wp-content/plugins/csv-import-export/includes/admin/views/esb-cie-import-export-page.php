<?php

/**
 * Settings Page
 * Handles to settings
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

?>
<div class="wrap">

    <h2><?php _e( 'CSV Import - Export', 'esbcie' ); ?></h2>

    <?php

        $current = 'shopper';
        //$tabs = esb_cie_post_type_tabs();
        $tabs = array(
        'shopper' => 'Shopper',
        //'inventory' => 'Inventory',
        //'global_event' => 'Global Event'
        );

        if( isset( $_GET['cie_import'] ) || isset( $_GET['cie_update'] ) || isset( $_GET['cie_ignore'] ) ) {

            $item_name    = !empty( $_GET['cie_type'] ) ? ( $_GET['cie_type'] == 'term' ? __( 'categories', 'esbcie' ) : __( 'posts', 'esbcie' ) ) : __( 'items', 'esbcie' );
            $total_import = !empty( $_GET['cie_import'] ) ? $_GET['cie_import'] : 0;
            $total_update = !empty( $_GET['cie_update'] ) ? $_GET['cie_update'] : 0;
            $total_ignore = !empty( $_GET['cie_ignore'] ) ? $_GET['cie_ignore'] : 0;

            echo "<div class='updated below-h2'>
                    <p><strong>{$total_import}</strong> {$item_name} was successfully imported. <strong>{$total_update}</strong> {$item_name} updated. <strong>{$total_ignore}</strong> {$item_name} ignored.</p>
                </div>";
        }
        echo '<h2 class="nav-tab-wrapper">';
        foreach( $tabs as $tab => $name ) {
            $class = ( $tab == $current ) ? ' nav-tab-active ' : '';
            echo "<a class='nav-tab$class' href='#$tab'>$name</a>";
        }
        echo '</h2>';

    ?>

    <!-- beginning of the settings meta box -->
    <div id="esb_cie_settings" class="post-box-container">

        <div class="esb-cie-content">

            <?php foreach( $tabs as $tab => $name ) { ?>

                <div class="esb-cie-tab-content" id="<?php echo $tab ?>">

                    <!-- Post Type View Start -->
                    <div class="metabox-holder">
                        <div class="meta-box-sortables ui-sortable">
                            <div id="esb_cie_<?php echo $tab ?>" class="postbox">

                                <!-- settings box title -->
                                <h3 class="hndle">
                                    <span style='vertical-align: top;'><?php echo $name; ?></span>
                                </h3>

                                <div class="inside">

                                    <!-- CSV description Start -->
                                    <table class="form-table esb-cie-form-table">

                                        <tr>
                                            <td colspan="2" valign="top" scope="row">
                                                <strong><?php _e( 'CSV description', 'esbcie' ); ?></strong>
                                            </td>
                                        </tr>

                                    </table>

                                    <?php
                                        $all_options = esb_cie_get_all_post_fields();
                                        if( !empty( $all_options ) ) {
                                    ?>
                                    <form method="post">
                                        <table class="widefat importers esb-cie-importers">
                                            <thead>
                                                <tr>
                                                    <th class="cb"></th>
                                                    <th><strong><?php _e( 'ATTRIBUTE', 'esbcie' ) ?></strong></th>
                                                    <th><strong><?php _e( 'COLUMN NAME', 'esbcie' ) ?></strong></th>
                                                    <th><strong><?php _e( 'NOTICE', 'esbcie' ) ?></strong></th>
                                                </tr>
                                            </thead>
                                    <?php
                                        $taxonomies = esb_cie_get_all_taxonomies( $tab );
                                        if( !empty( $taxonomies ) ) {
                                            foreach( $taxonomies as $key => $taxonomy ) {
                                                $menu_title = !empty( $taxonomy->labels ) && !empty( $taxonomy->labels->menu_name ) ? $taxonomy->labels->menu_name : $taxonomy->label;
                                                $all_options[] = array(
                                                                            'key'   => 'tax-'.$key,
                                                                            'label' => $menu_title,
                                                                            'notice'=> __( 'Comma separated list of categories names (slugs) (e.g. cat1,cat2)', 'esbcie' )
                                                                        );
                                            }
                                        }
                                        foreach ( $all_options as $opt => $option_data ) {

                                            $row_class = ( $opt % 2 == 0 ) ? ' alternate ' : '';
                                            $key    = isset( $option_data['key'] ) ? $option_data['key'] : '';
                                            $label  = isset( $option_data['label'] ) ? $option_data['label'] : '';
                                            $notice = isset( $option_data['notice'] ) ? $option_data['notice'] : '';
                                    ?>
                                            <tr class="<?php echo $row_class ?>">
                                                <td><input type="checkbox" id="esb_cie_<?php echo $key ?>_<?php echo $tab ?>" name="esb_cie_column_name[]" value="<?php echo $key ?>" checked="checked" /></td>
                                                <td class="row-title"><label for="esb_cie_<?php echo $key ?>_<?php echo $tab ?>"><?php echo $label ?></label></td>
                                                <td><code><?php echo $key ?></code></td>
                                                <td><span class="description"><?php echo $notice ?></span></td>
                                            </tr>
                                    <?php } ?>
                                    <?php if($tab == 'shopper') { ?>
                                            <tr class="row-fname">
                                                <td><input type="checkbox" id="esb_cie_fname_shopper" name="esb_cie_column_name[]" value="fname" checked="checked" /></td>
                                                <td class="row-title"><label for="esb_cie_fname_shopper">Fist Name</label></td>
                                                <td><code>fname</code></td>
                                                <td><span class="description"></span></td>
                                            </tr>
                                            <tr class="row-lname">
                                                <td><input type="checkbox" id="esb_cie_lname_shopper" name="esb_cie_column_name[]" value="lname" checked="checked" /></td>
                                                <td class="row-title"><label for="esb_cie_lname_shopper">Last Name</label></td>
                                                <td><code>lname</code></td>
                                                <td><span class="description"></span></td>
                                            </tr>
                                            <tr class="row-school">
                                                <td><input type="checkbox" id="esb_cie_school_shopper" name="esb_cie_column_name[]" value="school" checked="checked" /></td>
                                                <td class="row-title"><label for="esb_cie_school_shopper">School</label></td>
                                                <td><code>school</code></td>
                                                <td><span class="description"></span></td>
                                            </tr>
                                            <tr class="row-gradyear">
                                                <td><input type="checkbox" id="esb_cie_gradyear_shopper" name="esb_cie_column_name[]" value="gradyear" checked="checked" /></td>
                                                <td class="row-title"><label for="esb_cie_gradyear_shopper">Graduation Year</label></td>
                                                <td><code>gradyear</code></td>
                                                <td><span class="description"></span></td>
                                            </tr>
                              					    <tr class="row-customer-email">
                              					    	<td><input type="checkbox" id="esb_cie_customer_email_shopper" name="esb_cie_column_name[]" value="customer-email" checked="checked" /></td>
                              					    	<td class="row-title"><lable for="esb_cie_customer_email_shopper">Customer E-mail</label></td>
                              					    	<td><code>customer-email</code></td>
                              					    	<td><span class="description"></span</td>
                              					    </tr>
                                            <tr class="row-customer-phone">
                                              <td><input type="checkbox" id="esb_cie_customer_phone_shopper" name="esb_cie_column_name[]" value="customer-phone" checked="checked" /></td>
                              					    	<td class="row-title"><lable for="esb_cie_customer_phone_shopper">Customer Phone</label></td>
                              					    	<td><code>customer-phone</code></td>
                              					    	<td><span class="description"></span</td>
                                            </tr>
                                            <tr class="row-designer-preferences">
                                              <td><input type="checkbox" id="esb_cie_designer_preferences_shopper" name="esb_cie_column_name[]" value="designer-preferences" checked="checked" /></td>
                                              <td class="row-title"><lable for="esb_cie_designer_preferences_shopper">Designer Preferences</label></td>
                                              <td><code>designer-preferences</code></td>
                                              <td><span class="description"></span</td>
                                            </tr>
                                            <tr class="row-style-preferences">
                                              <td><input type="checkbox" id="esb_cie_style_preferences_shopper" name="esb_cie_column_name[]" value="style-preferences" checked="checked" /></td>
                                              <td class="row-title"><lable for="esb_cie_style_preferences_shopper">Style Preferences</label></td>
                                              <td><code>style-preferences</code></td>
                                              <td><span class="description"></span</td>
                                            </tr>
                                            <tr class="row-color-preferences">
                                              <td><input type="checkbox" id="esb_cie_color_preferences_shopper" name="esb_cie_column_name[]" value="color-preferences" checked="checked" /></td>
                                              <td class="row-title"><lable for="esb_cie_color_preferences_shopper">Color Preferences</label></td>
                                              <td><code>color-preferences</code></td>
                                              <td><span class="description"></span</td>
                                            </tr>
                                            <tr class="row-customer-size">
                                              <td><input type="checkbox" id="esb_cie_customer_size_shopper" name="esb_cie_column_name[]" value="customer-size" checked="checked" /></td>
                                              <td class="row-title"><lable for="esb_cie_customer_size_shopper">Customer Size</label></td>
                                              <td><code>customer-size</code></td>
                                              <td><span class="description"></span</td>
                                            </tr>
                                            <tr class="row-customer-address">
                                              <td><input type="checkbox" id="esb_cie_customer_address_shopper" name="esb_cie_column_name[]" value="customer-address" checked="checked" /></td>
                                              <td class="row-title"><lable for="esb_cie_customer_address_shopper">Customer Address</label></td>
                                              <td><code>customer-address</code></td>
                                              <td><span class="description"></span</td>
                                            </tr>
                                            <tr class="row-customer-city">
                                              <td><input type="checkbox" id="esb_cie_customer_city_shopper" name="esb_cie_column_name[]" value="customer-city" checked="checked" /></td>
                                              <td class="row-title"><lable for="esb_cie_customer_city_shopper">Customer City</label></td>
                                              <td><code>customer-city</code></td>
                                              <td><span class="description"></span</td>
                                            </tr>
                                            <tr class="row-customer-state">
                                              <td><input type="checkbox" id="esb_cie_customer_state_shopper" name="esb_cie_column_name[]" value="customer-state" checked="checked" /></td>
                                              <td class="row-title"><lable for="esb_cie_customer_state_shopper">Customer State</label></td>
                                              <td><code>customer-state</code></td>
                                              <td><span class="description"></span</td>
                                            </tr>
                                            <tr class="row-customer-zip">
                                              <td><input type="checkbox" id="esb_cie_customer_zip_shopper" name="esb_cie_column_name[]" value="customer-zip" checked="checked" /></td>
                                              <td class="row-title"><lable for="esb_cie_customer_zip_shopper">Customer Zip</label></td>
                                              <td><code>customer-zip</code></td>
                                              <td><span class="description"></span</td>
                                            </tr>
                                            <tr class="row-entry-date">
                                              <td><input type="checkbox" id="esb_cie_entry_date_shopper" name="esb_cie_column_name[]" value="entry-date" checked="checked" /></td>
                                              <td class="row-title"><lable for="esb_cie_entry_date_shopper">Entry Date</label></td>
                                              <td><code>entry-date</code></td>
                                              <td><span class="description"></span</td>
                                            </tr>
                                            <tr class="row-store-id">
                                              <td><input type="checkbox" id="esb_cie_store_id_shopper" name="esb_cie_column_name[]" value="store-id" checked="checked" /></td>
                                              <td class="row-title"><lable for="esb_cie_store_id_shopper">Store ID</label></td>
                                              <td><code>store-id</code></td>
                                              <td><span class="description"></span</td>
                                            </tr>
                                    <?php } ?>


                                        </table>
                                        <p>
                                            <input type="hidden" name="esb_cie_csv_file_name" value="<?php echo $name ?>" />
                                            <input type="hidden" name="esb_cie_export_post_type" value="<?php echo $tab ?>" />
                                            <input type="submit" name="esb_cie_export_posts_csv" class="button-secondary" value="<?php _e( 'Export CSV', 'esbcie' ) ?>" />
                                            <input type="submit" name="esb_cie_download_sample_csv" class="button-secondary" value="<?php _e( 'Download Sample CSV', 'esbcie' ) ?>" />
                                        </p>
                                    </form>
                                    <?php } ?>
                                    <!-- CSV description End -->

                                    <!-- Import from file Start -->
                                    <table class="form-table esb-cie-form-table">

                                        <tr>
                                            <td colspan="2" valign="top" scope="row">
                                                <strong><?php _e( 'Import from file', 'esbcie' ); ?></strong>
                                            </td>
                                        </tr>

                                    </table>

                                    <form method="post" enctype="multipart/form-data">
                                        <table class="widefat importers esb-cie-importers">
                                            <tr class="alternate">
                                                <td class="cb"><input type="radio" id="esb_cie_post_new" name="esb_cie_import_choice" value="new" checked="checked" /></td>
                                                <td><label for="esb_cie_post_new"><?php _e( 'Rename item\'s name (slug) if item with name (slug) already exists', 'esbcie' ) ?></label></td>
                                            </tr>
                                            <tr>
                                                <td class="cb"><input type="radio" id="esb_cie_post_update" name="esb_cie_import_choice" value="update" /></td>
                                                <td><label for="esb_cie_post_update"><?php _e( 'Update old item\'s data if item with name (slug) already exists', 'esbcie' ) ?></label></td>
                                            </tr>
                                            <tr class="alternate">
                                                <td class="cb"><input type="radio" id="esb_cie_post_ignore" name="esb_cie_import_choice" value="ignore" /></td>
                                                <td><label for="esb_cie_post_ignore"><?php _e( 'Ignore item if item with name (slug) already exists', 'esbcie' ) ?></label></td>
                                            </tr>
                                        </table>
                                        <p>
                                            <input type="hidden" name="esb_cie_csv_post_type_name" value="<?php echo $tab ?>" />
                                            <input type="file" name="esb_cie_import_file" />
                                            <input type="submit" name="esb_cie_import_csv" class="button-secondary" value="<?php _e( 'Import From CSV', 'esbcie' ) ?>" />
                                        </p>
                                    </form>
                                    <!-- Import from file End -->

                                </div><!-- .inside -->

                            </div><!-- #settings -->
                        </div><!-- .meta-box-sortables ui-sortable -->
                    </div><!-- .metabox-holder -->
                    <!-- Post Type View End -->

                <?php
                    $taxonomies = esb_cie_get_all_taxonomies( $tab );
                    if( !empty( $taxonomies ) ) {
                        foreach( $taxonomies as $taxonomy_key => $taxonomy ) {
                            $menu_title = !empty( $taxonomy->labels ) && !empty( $taxonomy->labels->menu_name ) ? $taxonomy->labels->menu_name : $taxonomy->label;
                ?>
                    <!-- Taxonomy View Start -->
                    <div class="metabox-holder">
                        <div class="meta-box-sortables ui-sortable">
                            <div id="esb_cie_<?php echo $tab ?>_<?php echo $taxonomy_key ?>" class="postbox">

                                <!-- settings box title -->
                                <h3 class="hndle">
                                    <span style='vertical-align: top;'><?php echo $menu_title; ?></span>
                                </h3>

                                <div class="inside">

                                    <!-- CSV description Start -->
                                    <table class="form-table esb-cie-form-table">

                                        <tr>
                                            <td colspan="2" valign="top" scope="row">
                                                <strong><?php _e( 'CSV description', 'esbcie' ); ?></strong>
                                            </td>
                                        </tr>

                                    </table>

                                    <?php
                                        $all_options = esb_cie_get_all_term_fields();
                                        if( !empty( $all_options ) ) {
                                    ?>
                                    <form method="post">
                                        <table class="widefat importers esb-cie-importers">
                                            <thead>
                                                <tr>
                                                    <th class="cb"></th>
                                                    <th><strong><?php _e( 'ATTRIBUTE', 'esbcie' ) ?></strong></th>
                                                    <th><strong><?php _e( 'COLUMN NAME', 'esbcie' ) ?></strong></th>
                                                    <th><strong><?php _e( 'NOTICE', 'esbcie' ) ?></strong></th>
                                                </tr>
                                            </thead>
                                    <?php
                                        if( isset( $taxonomy->hierarchical ) && $taxonomy->hierarchical == '1' ) {

                                            $all_options[] = array(
                                                                    'key'       => 'parent',
                                                                    'label'     => __( 'Parent Category', 'esbcie' ),
                                                                    'notice'    => __( 'Parent category name (slug)', 'esbcie' )
                                                            );
                                        }
                                        foreach ( $all_options as $opt => $option_data ) {

                                            $row_class = ( $opt % 2 == 0 ) ? ' alternate ' : '';
                                            $key    = isset( $option_data['key'] ) ? $option_data['key'] : '';
                                            $label  = isset( $option_data['label'] ) ? $option_data['label'] : '';
                                            $notice = isset( $option_data['notice'] ) ? $option_data['notice'] : '';
                                    ?>
                                            <tr class="<?php echo $row_class ?>">
                                                <td><input type="checkbox" id="esb_cie_<?php echo $key ?>_<?php echo $tab ?>" name="esb_cie_column_name[]" value="<?php echo $key ?>" checked="checked" /></td>
                                                <td class="row-title"><label for="esb_cie_<?php echo $key ?>_<?php echo $tab ?>"><?php echo $label ?></label></td>
                                                <td><code><?php echo $key ?></code></td>
                                                <td><span class="description"><?php echo $notice ?></span></td>
                                            </tr>
                                    <?php } ?>
                                        </table>
                                        <p>
                                            <input type="hidden" name="esb_cie_csv_file_name" value="<?php echo $menu_title ?>" />
                                            <input type="hidden" name="esb_cie_export_taxonomy" value="<?php echo $taxonomy_key ?>" />
                                            <input type="submit" name="esb_cie_export_terms_csv" class="button-secondary" value="<?php _e( 'Export CSV', 'esbcie' ) ?>" />
                                            <input type="submit" name="esb_cie_download_sample_csv" class="button-secondary" value="<?php _e( 'Download Sample CSV', 'esbcie' ) ?>" />
                                        </p>
                                    </form>
                                    <?php } ?>
                                    <!-- CSV description End -->

                                    <!-- Import from file Start -->
                                    <table class="form-table esb-cie-form-table">

                                        <tr>
                                            <td colspan="2" valign="top" scope="row">
                                                <strong><?php _e( 'Import from file', 'esbcie' ); ?></strong>
                                            </td>
                                        </tr>

                                    </table>

                                    <form method="post" enctype="multipart/form-data">
                                        <table class="widefat importers esb-cie-importers">
                                            <tr class="alternate">
                                                <td class="cb"><input type="radio" id="esb_cie_term_new" name="esb_cie_import_choice" value="new" checked="checked" /></td>
                                                <td><label for="esb_cie_term_new"><?php _e( 'Rename item\'s name (slug) if item with name (slug) already exists', 'esbcie' ) ?></label></td>
                                            </tr>
                                            <tr>
                                                <td class="cb"><input type="radio" id="esb_cie_term_update" name="esb_cie_import_choice" value="update" /></td>
                                                <td><label for="esb_cie_term_update"><?php _e( 'Update old item\'s data if item with name (slug) already exists', 'esbcie' ) ?></label></td>
                                            </tr>
                                            <tr class="alternate">
                                                <td class="cb"><input type="radio" id="esb_cie_term_ignore" name="esb_cie_import_choice" value="ignore" /></td>
                                                <td><label for="esb_cie_term_ignore"><?php _e( 'Ignore item if item with name (slug) already exists', 'esbcie' ) ?></label></td>
                                            </tr>
                                        </table>
                                        <p>
                                            <input type="hidden" name="esb_cie_csv_taxonomy_name" value="<?php echo $taxonomy_key ?>" />
                                            <input type="file" name="esb_cie_import_file" />
                                            <input type="submit" name="esb_cie_import_term_csv" class="button-secondary" value="<?php _e( 'Import From CSV', 'esbcie' ) ?>" />
                                        </p>
                                    </form>
                                    <!-- Import from file End -->

                                </div><!-- .inside -->

                            </div><!-- #settings -->
                        </div><!-- .meta-box-sortables ui-sortable -->
                    </div><!-- .metabox-holder -->
                    <!-- Taxonomy View End -->

                <?php
                        }
                    }
                ?>

                </div><!-- .esb-cie-tab-content -->

            <?php } ?>

        </div><!-- .esb-cie-content -->

    </div><!-- #esb_cie_settings -->
</div>
