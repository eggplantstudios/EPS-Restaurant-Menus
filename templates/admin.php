<?php
/**
 * 
 * EPS OPENGRAPH
 * 
 * 
 * 
 * This plugin creates opengrah data for pages, posts and custom post types.
 * 
 * PHP version 5
 *
 *
 * @package    EPS 301 Redirects
 * @author     Shawn Wernig ( shawn@eggplantstudios.ca )
 * @version    1.3.4
 */
$settings = get_option( 'eps_menu_settings' );
global $wp_rewrite; 

?>

<div class="wrap">
        <header id="eps-header">
        <div id="icon-eggplant">&nbsp;</div>
        <h2 class="eps-title"><?php echo self::$page_title; ?></h2>         
        </header>
        
        <div class="clear">
        
            <div class="eps-tab-nav">
                <a href="#eps-menu-order" class="eps-tab-nav-item active">Menu Orders</a>
                <a href="#eps-menu-settings" class="eps-tab-nav-item">Display Settings</a>
            </div>
            
            <div class="eps-panes">
                    <?php include ( EPS_MENU_PATH . 'templates/admin.menu-order.php'  ); ?>
                    <?php include ( EPS_MENU_PATH . 'templates/admin.settings.php'  ); ?>
            </div>
        
        </div>

        
</div>
    
    
    
    
