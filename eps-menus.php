<?php
/**
 * 
 * EPS RESTAURANT MENUS
 * 
 * 
 * Creates a new post type for restaurant menus. Includes some starter themes, shortcodes and widgets.
 * 
 * PHP version 5
 *
 *
 * @package    EPS Restaurant Menus
 * @author     Shawn Wernig ( shawn@eggplantstudios.ca )
 * @version    1.4.0
 */

 
/*
Plugin Name: EPS Restaurant Menus
Plugin URI: http://www.eggplantstudios.ca
Description: Create your own restaurant menus!.
Version: 1.0
Author: Shawn Wernig http://www.eggplantstudios.ca
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

define ( 'EPS_MENU_PATH', plugin_dir_path(__FILE__) );
define ( 'EPS_MENU_URL', plugin_dir_url( __FILE__ ) );
define ( 'EPS_MENU_VERSION', '1.0');

register_activation_hook(__FILE__, array('EPS_Menu', 'eps_menu_activation'));
register_deactivation_hook(__FILE__, array('EPS_Menu', 'eps_menu_deactivation'));

require_once( EPS_MENU_PATH . 'libs/class.eps-posttypes.php' );
require_once( EPS_MENU_PATH . 'libs/class.eps-form.php' );
require_once( EPS_MENU_PATH . 'libs/functions.term-meta.php' );
require_once( EPS_MENU_PATH . 'libs/class.menu-items.php' );
require_once( EPS_MENU_PATH . 'libs/class.menu-item.php' );

class EPS_Menu {
    
    static $option_slug = 'eps_menu';
    static $page_slug   = 'eps_menu';
    static $page_title  = 'Restaurant Menus';


    public function __construct(){
        if(is_admin()){
            add_action( 'admin_menu', array($this, 'add_plugin_page'));
            add_action( 'admin_init', array($this, '_save'));
            add_action( 'init', array($this, 'enqueue_admin_resources'));
            add_action( 'admin_footer_text',  array($this, 'set_ajax_url'));
        }
        add_action( 'init', array($this, 'enqueue_resources'));
        add_action('wp_footer',  array($this, 'set_ajax_url'));
    }



    
    public static function eps_menu_activation() {
            self::check_version();
            //self::check_php();
    }
    
    
    public static function eps_menu_deactivation() {
            update_option( 'eps_menu_version', null );        
    }
    
     /**
     * 
     * CHECK PHP
     * 
     * This function will check the current version of wp and php
     * 
     * @return html string
     * @author epstudios
     *      
     */
    public function check_php() {
       if ( ! version_compare( PHP_VERSION, '5.3.0', '>=' ) ) {
            deactivate_plugins( __FILE__ );
            wp_die( wp_sprintf( '%1s: ' . __( 'Sorry, This plugin has taken a bold step in requiring PHP 5.3.0+. Your server is currently running PHP %2s, Please bug your host to upgrade to a recent version of PHP which is less bug-prone.', 'myplugin' ), __FILE__ , PHP_VERSION ) );
        }
    }
     /**
     * 
     * CHECK VERSION
     * 
     * This function will check the current version and do any fixes required
     * 
     * @return html string
     * @author epstudios
     *      
     */
    public function check_version() {
        
        $version = get_option( self::$option_slug."_version" );
        if ( isset($version) ) {
            switch( $version ) {
                case '1':
                    // do stuff
                default:
                    break;   
            }
        }
        update_option( self::$option_slug."_version", EPS_MENU_VERSION );
        return EPS_MENU_VERSION;
    }
    
    
    /**
     * 
     * ENQUEUE_ADMIN_RESOURCES
     * 
     * This function will queue up the javascript and CSS for the admin area of the plugin.
     * 
     * @return html string
     * @author epstudios
     *      
     */
    public function enqueue_admin_resources(){
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-sortable');
        //wp_enqueue_script("eps_jquery_ui","https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.8/jquery-ui.min.js");
        
        wp_enqueue_script('eps_menu_script', EPS_MENU_URL .'js/scripts.js');
        wp_enqueue_style('eps_menu_styles', EPS_MENU_URL .'css/eps-menu-admin.css');
        wp_enqueue_style('eps_menu_styles', EPS_MENU_URL .'css/jquery.ui.all.css');
    }
    /**
     * 
     * ENQUEUE_RESOURCES
     * 
     * This function will queue up the javascript and CSS for the plugin.
     * 
     * @return html string
     * @author epstudios
     *      
     */
    public function enqueue_resources(){
        $options = get_option( self::$option_slug );
        if( isset( $options['theme'] ) ) {
            wp_enqueue_style('eps_menu_styles', EPS_MENU_URL . "css/eps-menu-".$options['theme'].".css");
        } else {
            // Custom style.
        }
    }
    /**
     * 
     * ADD_PLUGIN_PAGE
     * 
     * This function initialize the plugin settings page.
     * 
     * @return html string
     * @author epstudios
     *      
     */
    public function add_plugin_page(){
        add_submenu_page('edit.php?post_type=menu_item', 'Menu Settings', 'Menu Settings', 'manage_options', self::$page_slug, array($this, 'do_admin_page'));
    }

        
    /**
     * 
     * DO_ADMIN_PAGE
     * 
     * This function will create the admin page.
     * 
     * @author epstudios
     *      
     */
    public function do_admin_page(){
        include ( EPS_MENU_PATH . 'templates/admin.php'  );
    }
    
    /**
     * 
     * _SAVE
     * 
     * This function will save the settings.
     * 
     * @return html string
     * @author epstudios
     *      
     */
    public function _save(){
       if ( isset( $_POST['eps_menu_settings_submit'] ) && isset($_POST['eps_menu_settings']) && wp_verify_nonce( $_POST['eps_menu_setting_nonce_submit'], 'eps_menu_setting_nonce') )
            $this->_save_settings();
       
       if ( isset( $_POST['eps_menu_order_submit'] ) && wp_verify_nonce( $_POST['eps_menu_order_nonce_submit'], 'eps_menu_order_nonce') )
            $this->_save_menu_order();    }
    
    private function _save_settings() {
        update_option( self::$option_slug, $_POST['eps_menu_settings'] );
    }
 
    private function _save_menu_order() {
        
        global $wpdb;
        $menu_terms = get_terms( 'eps_menu_section', array( 'hide_empty' => false ) ); 
        
        foreach( $menu_terms as $term ) {
            if( array_key_exists($term->slug, $_POST)) {
                foreach($_POST[$term->slug] as $new_order => $post_id ) {
                    $wpdb->update( 
                        $wpdb->posts, 
                        array( 'menu_order' => $new_order ), 
                        array( 'ID' => $post_id ), 
                        array( '%d' ), 
                        array( '%d' ) 
                    );
                }
            }
        }
    }
    
    /**
     * 
     * SET_AJAX_URL
     * 
     * This function will output a variable containing the admin ajax url for use in javascript.
     * 
     * @author epstudios
     *      
     */
    public static function set_ajax_url() {
        echo '<script>var eps_ajax_url = "'. admin_url( 'admin-ajax.php' ) . '"</script>';
    }

}



/**
 * Outputs an object or array in a readable form.
 *
 * @return void
 * @param $string = the object to prettify; Typically a string.
 * @author epstudios
 */
if( !function_exists('eps_prettify')) {
    function eps_prettify( $string ) {
        return ucwords( str_replace("_"," ",$string) );
    }
}



// Run the plugin.
$EPS_Restaurant_Menus = new EPS_Menu();
?>