<?php
/**
 * The Hole entry template.
 *
 *
 * @package WordPress
 * @subpackage EPSBOILERPLATE
 * @since EPSBOILERPLATE 1.0
 * @author Eggplant Studios (www.eggplantstudios.ca)
 */
$options = get_option( EPS_Menu::$option_slug );
$menu_item_obj = new EPS_Menu_Item( get_the_ID() );
global $post;
?>        

<li id="menu-item-<?php the_ID(); ?>" <?php post_class('group'); ?> >
    <?php
    if ( has_post_thumbnail() && isset($options['thumbnails']) && $options['thumbnails'] == "on") {
        ?>
        <div class="menu-item-thumb-frame menu-item-thumb-<?php echo $options['thumbnail-size'] ?>"  href="<?php the_permalink(); ?>">
        <?php 
        switch( $options['thumbnail-size'] ) {
            case 'small': $size = array(64,64); break;
            case 'medium': $size = array(96,96); break;
            case 'large': $size = array(128,128); break;
        }
        the_post_thumbnail( $size, array(
                            'class' => "eps-menu-thumb-img",
                        ));
        ?>
        </div>
    <?php
    } 
    ?>
    <div class="menu-item-content">
        <header>
            <?php $menu_item_obj->get_price_string(); ?>    
            <h4 class="menu-item-title eps-colour"><?php the_title(); ?>
                <?php if ( isset($options['subheading']) && $options['subheading'] == "on" ) { ?>
                    <span class="menu-item-subtitle"><?php echo $menu_item_obj->get_attr('details', 'post_subhead'); ?></span>
                <?php } ?>
            </h4> 
            
        </header>
        <div class="menu-item-description"><?php echo $menu_item_obj->get_attr('details', 'post_content'); ?></div>
    </div>
</li>


              
