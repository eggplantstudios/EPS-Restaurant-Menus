<?php
/**
 * 
 * EPS redirects
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
$dashboard_form = new EPS_Menu_Form;
$options = get_option( EPS_Menu::$option_slug );
?>


<div id="eps-menu-settings-pane" class="eps-pane">
<form method="post" action="">
    <h2>Restaurant Menu Display Settings:</h2>
    
    <hr class="eps-divider">
    
    <div class="eps-white-box">
        <h3>Select a theme:</h3>
        <table>
            <tr>
                <td><label for="olive"><img src="#" width="400" height="100"></td></label>
                <td>
                    <input id="olive" type="radio" name="eps_menu_settings[theme]" value="olive" <?php echo ( ($options['theme'] == "olive") ? "checked='checked'" : null ) ?>>
                    <strong>Olive</strong>
                </td>
            </tr>
            <tr>
                <td><label for="red-wine"><img src="#" width="400" height="100"></td></label>
                <td>
                    <input id="red-wine" type="radio" name="eps_menu_settings[theme]" value="red-wine" <?php echo ( ($options['theme'] == "red-wine") ? "checked='checked'" : null ) ?>>
                    <strong>Red Wine</strong>
                </td>
            </tr>
            <tr>
                <td><label for="red-wine-light"><img src="#" width="400" height="100"></td></label>
                <td>
                    <input id="red-wine-light" type="radio" name="eps_menu_settings[theme]" value="red-wine-light" <?php echo ( ($options['theme'] == "red-wine-light") ? "checked='checked'" : null ) ?>>
                    <strong>Red Wine Light</strong>
                </td>
            </tr>
            <tr>
                <td><label for="custom"><img src="#" width="400" height="100"></td></label>
                <td>
                    <input id="custom"  type="radio" name="eps_menu_settings[theme]" value="" <?php echo ( ($options['theme'] == "") ? "checked='checked'" : null ) ?>>
                    <strong>Custom</strong>
                </td>
            </tr>
        </table>
        
    </div>
    
    <div class="eps-white-box">
        <h3>Customize Menu Items:</h3>
        <?php
            echo $dashboard_form->get_input( 'Currency Symbol', 'eps_menu_settings[currency]', ( isset($options['currency'])  ? $options['currency'] : null ) );
            echo $dashboard_form->get_bool( 'Show Thumbnails', 'eps_menu_settings[thumbnails]', ( ( isset($options['thumbnails']) && $options['thumbnails'] == "on") ? "checked='checked'" : null ) );
            echo $dashboard_form->get_bool( 'Show Subheading', 'eps_menu_settings[subheading]', ( ( isset($options['subheading']) && $options['subheading'] == "on") ? "checked='checked'" : null ) );
            echo $dashboard_form->get_select( 'Thumbnai Size', 'eps_menu_settings[thumbnail-size]', array('Small'=>'small', 'Medium'=>'medium', 'Large'=>'large', ), ( ( isset($options['thumbnail-size']) ) ? $options['thumbnail-size'] : null ) );
        ?>
    </div>
    

    
    <p class="submit">
        <?php wp_nonce_field('eps_menu_setting_nonce', 'eps_menu_setting_nonce_submit');   ?>
        <input type="submit" name="eps_menu_settings_submit" id="submit" class="button button-primary" value="Save Changes"/>
    </p>
</form>
</div>