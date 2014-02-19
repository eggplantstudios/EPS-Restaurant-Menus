<?php
/**
 * 
 * 
 * EPS_Menu_Item
 * 
 * The menu item class.
 *
 * @author Shawn Wernig, Eggplant Studios, www.eggplantstudios.ca
 * @version 1
 * @copyright 2013 Eggplant Studios
 */

 
 

class EPS_Menu_Item extends EPS_Posttype_Entry {
    public $details = array();
    var $terms      = array();
   
   
   public function get_money_format($price_string) {
       $options = get_option( EPS_Menu::$option_slug );
       
       $currency = (isset($options['currency'])) ? $options['currency'] : null;

       return $currency.$price_string;
   }
   /**
    * 
    * 
    * Gets
    */
   public function get_price_string() {
       switch( $this->get_attr('details', 'menu_item_type') ) {
            case 'single':
                printf('<p class="menu-item-price"><span class="menu-item-currency eps-colour">%s</span><span class="menu-item-serving">%s</span></p>',
                    self::get_money_format( $this->get_attr('details', 'single_price') ),
                    ($this->has_attr('details', 'single_serving') ? ' /'. $this->get_attr('details', 'single_serving')  : null)
                    );
                break;
            case 'double':
                 printf('<p class="menu-item-price">
                            <span class="menu-item-currency eps-colour">%s</span><span class="menu-item-serving"> /%s</span>
                            &nbsp;&bull;&nbsp;<span class="menu-item-currency eps-colour">%s</span><span class="menu-item-serving"> /%s</span>
                         </p>',
                    self::get_money_format( $this->get_attr('details', 'single_price') ),
                    $this->get_attr('details', 'single_serving'),
                    self::get_money_format( $this->get_attr('details', 'double_price') ),
                    $this->get_attr('details', 'double_serving')
                    );
                break;
            case 'triple':
                 printf('<p class="menu-item-price">
                            <span class="menu-item-currency eps-colour">%s</span><span class="menu-item-serving"> /%s</span>
                            &nbsp;&bull;&nbsp;<span class="menu-item-currency eps-colour">%s</span><span class="menu-item-serving"> /%s</span>
                            &nbsp;&bull;&nbsp;<span class="menu-item-currency eps-colour">%s</span><span class="menu-item-serving"> /%s</span>
                                                     </p>',
                    self::get_money_format( $this->get_attr('details', 'single_price') ),
                    $this->get_attr('details', 'single_serving'),
                    self::get_money_format( $this->get_attr('details', 'double_price') ),
                    $this->get_attr('details', 'double_serving'),
                    self::get_money_format( $this->get_attr('details', 'triple_price') ),
                    $this->get_attr('details', 'triple_serving')
                    );
                break;
        }
   }
}
?>