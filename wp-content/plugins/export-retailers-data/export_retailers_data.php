<?php ob_start(); ?>
<?php /* 
    Plugin Name: Export Retailers Data 
*/  ?>
<?php
class homeoptionsClass{
    public function __construct(){
        add_action('admin_menu', array($this, 'homeoptions_function'));
    }
    
    public function homeoptions_function(){
        add_menu_page('Export Retailers', 'Export Retailers', 'manage_options', 'retailers-menu', array($this, 'retailers_list_function' ));
        add_submenu_page('', 'Retailers Data', 'Retailers Data', 'manage_options', 'retailers-data', array($this, 'retailers_details_function'));
        //add_submenu_page('', 'Delete Order', 'Delete Order', 'manage_options', 'delete-order', array($this, 'delete_order_function'));
    }
    
    public function retailers_list_function(){
        include('retailers_list_function.php');
    }
    
    public function retailers_details_function(){
        include('retailers_details_function.php');
    }
}
$homeoptions = new homeoptionsClass();
?>