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

$menu_item_obj = new EPS_Menu_Item( get_the_ID() );
global $post;
?>        

<li id="menu-item-<?php the_ID(); ?>" <?php post_class('admin-menu-item-entry ui-state-default group'); ?> >
    <input type="hidden" name="<?php echo $term->slug; ?>[]" value="<?php echo get_the_ID(); ?>">
    <a class="eps-text-link" href="<?php echo get_edit_post_link(); ?>">Edit &rsaquo;</a>
    <h6 class="admin-menu-item-title"><?php the_title();?></h6>
</li>


              
