<?php
/**
 * 
 * 
 * EPS_Menu_Items
 * 
 * The menu item Post Type init.
 *
 * @author Shawn Wernig, Eggplant Studios, www.eggplantstudios.ca
 * @version 1
 * @copyright 2013 Eggplant Studios
 */

 
 

class EPS_Menu_Items  {

   
    static public  $post_type = array (
        'slug' => 'menu_item',
        'single' => 'Menu Item',
        'plural' => 'Menu Items',
        'capability_type' => 'page',
        'supports' => array( 'title', 'thumbnail' ),
        'icon' => true
    ); 
    
    public $taxonomies = array();
    
    static public $meta = array(
        'details'          => array( 
                                array('post_subhead',      'Subhead',              'input'),
                                array('post_content',      'Description',              'textarea'),
                                array('menu_item_type',      'Menu Type',              'select', array( 
                                                                                                    'Single Option' => 'single', 
                                                                                                    'Two Options' => 'double', 
                                                                                                    "Three Options" => 'triple' ) ),
                                array('single_price',      'Price',              'input'),
                                array('single_serving',    'Serving',              'input'),
                                array('double_price',      'Price (Option 2)',              'input'),
                                array('double_serving',    'Serving (Option 2)',              'input'),
                                array('triple_price',      'Price (Option 3)',              'input'),
                                array('triple_serving',    'Serving (Option 3)',              'input')
                                
                                ),
        'item_flags'          => array( 
                                array('vegan',      'Vegan',    'range', 1),
                                array('vegetarian', 'Vegetarian',    'range', 1),
                                array('gluten',     'Gluten Free',    'range', 1),
                                array('spicy',      'Spicy',    'range', 5)
                                )                               
                               
                               
    );
    
    
    function __construct() {
        add_action( 'init',                     array( $this, 'register' ) );
        add_action( 'add_meta_boxes',           array( $this, 'metaboxes') );
        add_action( 'save_post',                array( $this, 'save_metadata') );
        add_action( 'admin_enqueue_scripts',    array( $this, 'photo_scripts'));
        add_action( 'admin_head',               array( $this, 'init_icons') );
        add_shortcode( 'do_eps_menu',              array( $this, 'sc_do_menu' ));
    }

    function init_icons() {
    ?>
        <style type="text/css" media="screen">
            #icon-edit.icon32-posts-menu_item {background: url(<?php echo EPS_MENU_URL; ?>/images/menu_item-32.png) no-repeat;}
        </style>
    <?php 
    }


    public function sc_do_menu($atts, $content) {
        global $EPS_Menu_Items;
        extract(shortcode_atts(array(
            'section' => '',
            'show_title' => '',
        ), $atts));
        
        
        if (empty($section)) return false;
        if ( !term_exists( $section, 'eps_menu_section' ) ) {
            return sprintf('Menu section <strong>%s</strong> does not exist.', $section );
        }
        
        ob_start();
        

        $menu_items = $EPS_Menu_Items->get_entries_by( 'eps_menu_section', $section );
        ?>
            <div class="eps-menu">
                <?php
                if( !empty($show_title) ) {
                    echo "<h3 class='eps-menu-title'>$term->name</h3>";
                }
                ?>
                <ul class="eps-menu-list">
                    <?php
                    if( $menu_items->have_posts() ) {
                        while( $menu_items->have_posts() ) {
                            $menu_items->the_post();
                            include( EPS_MENU_PATH . 'templates/entry.'. EPS_Menu_Items::$post_type['slug'] .'.php');
                        }
                    } else {
                        return sprintf('No menu items in section <strong>%s</strong>.', $section );
                    }
                    ?>
                </ul>
            </div>
        <?php
        wp_reset_postdata();
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }


    
    public static function get_entries_by( $key, $value, $post_count = -1 ) {
        $args = array( 
                'post_type'         => self::$post_type['slug'],
                'posts_per_page'    => $post_count,
                $key                => $value,
                'orderby'          => 'menu_order',
                'order'             => "ASC",
                'post_status'       => array('publish')
            );
        $entries = new WP_Query($args);
        return $entries;
    }
    
/**
     * 
     * REGISTER
     * 
     * Registers the post type. Some modifiers.
     * 
     * @author epstudios
     *      
     */
    public function register() {

        register_post_type(self::$post_type['slug'], array(
        'labels' => array(
            'name'                  => self::$post_type['plural'],
            'singular_name'         => self::$post_type['slug'],
            'add_new_item'          => 'Add ' . self::$post_type['single'],
            'edit_item'             => 'Edit ' . self::$post_type['single'],
            'new_item'              => 'New ' . self::$post_type['single'],
            'all_items'             => 'All ' . self::$post_type['plural'],
            'view_item'             => 'View ' . self::$post_type['single'],
            'search_items'          => 'Search ' . self::$post_type['plural'],
            'not_found'             => 'No ' . self::$post_type['plural'] .' found',
            'not_found_in_trash'    => 'No ' . self::$post_type['plural'] .' found in Trash', 
            'parent_item_colon'     => '',
            'menu_name' => self::$post_type['plural']
            ),
        'public'                => isset( self::$post_type['public'] ) ? true : false,
        'publicly_queryable'    => true,
        'show_ui'               => true, 
        'show_in_menu'          => true, 
        'query_var'             => true,
        'rewrite'               => array( 'slug' => self::$post_type['slug']),
        'capability_type'       => isset( self::$post_type['capability_type'] ) ? self::$post_type['capability_type'] : 'post',
        'has_archive'           => true, 
        'hierarchical'          => true,
        'menu_position'         => null,
        'supports'              => isset( self::$post_type['supports'] ) ? self::$post_type['supports'] : array( 'title', 'editor', 'thumbnail'),
        'menu_icon'             => isset( self::$post_type['icon'] ) ? EPS_MENU_URL . '/images/'.self::$post_type['slug'].'-icon.png' : null
        ));
      
        
    }
        
    /**
     * 
     * ADD TAXONOMY
     * 
     * Adds a taxonomy!
     * 
     * @author epstudios
     *      
    */
    public function add_taxonomy( $args ) {
            extract( $args );
            if( taxonomy_exists( $taxonomy ) ) {
                register_taxonomy_for_object_type( $taxonomy, self::$post_type['slug'] );
            } else {
                register_taxonomy ( $taxonomy, array( self::$post_type['slug'] ), array(
                'hierarchical' => true,
                'labels' => array (
                    'name' => _x( $single, 'taxonomy general name' ),
                    'singular_name' => _x( $single, 'taxonomy singular name' ),
                    'search_items' =>  __( "Search {$plural}" ),
                    'all_items' => __( "All {$plural}" ),
                    'edit_item' => __( "Edit {$single}" ), 
                    'update_item' => __( "Update{$single}" ),
                    'add_new_item' => __( "Add New {$single}" ),
                    'new_item_name' => __( "New {$single}" ),
                    'menu_name' => __( $plural ),
                ),
                'show_ui' => true,
                'query_var' => $taxonomy,
                'rewrite' => array('slug' => $taxonomy),
                ));
                
                $this->taxonomies[$taxonomy] = get_taxonomy($taxonomy);
                if( isset( $thumbnails ) &&  $thumbnails == true ) {
                    add_action( $taxonomy . '_edit_form_fields',     array( &$this, 'term_photo_form'));
                    add_action( $taxonomy . '_add_form_fields',      array( &$this, 'term_photo_form'));  
                    add_action( 'created_' . $taxonomy,              array( &$this, 'save_term_fields'));
                    add_action( 'edited_' . $taxonomy,               array( &$this, 'save_term_fields'));
                }
            }
            
    }    
    
    
    /**
     * 
     * ADD TAG
     * 
     * Adds a tag!
     * 
     * @author epstudios
     *      
     */
    public function add_tag( $args ) {
            extract( $args );
            if( taxonomy_exists( $taxonomy ) ) {
                register_taxonomy_for_object_type( $taxonomy, self::$post_type['slug'] );
            } else {
                register_taxonomy ( $taxonomy, array( self::$post_type['slug'] ), array(
                'hierarchical' => true,
                'labels' => array (
                    'name' => _x( $single, 'taxonomy general name' ),
                    'singular_name' => _x( $single, 'taxonomy singular name' ),
                    'search_items' =>  __( "Search {$plural}" ),
                    'all_items' => __( "All {$plural}" ),
                    'edit_item' => __( "Edit {$single}" ), 
                    'update_item' => __( "Update{$single}" ),
                    'add_new_item' => __( "Add New {$single}" ),
                    'new_item_name' => __( "New {$single}" ),
                    'menu_name' => __( $plural ),
                ),
                'show_ui' => true,
                'query_var' => $taxonomy,
                'rewrite' => array('slug' => $taxonomy),
                'hierarchical'            => false
                ));
                
                $this->taxonomies[] = get_taxonomy($taxonomy);
            }
    }      

  
    /**
     * 
     * INIT ATTACHMENTS
     * 
     * If we're using attachments, this will init them
     * 
     * @author epstudios
     *      
     */
    public function init_attachments( $attachments ) {
      $args = array(
        'label'         => self::$post_type['single'] . ' Photos',
        'post_type'     => array( self::$post_type['slug'] ),
        'filetype'      => null,  // no filetype limit
        'note'          => 'Attach files here!',
        'button_text'   => __( 'Attach Files', 'attachments' ),
        'modal_text'    => __( 'Attach', 'attachments' ),
        'fields'        => array(
                              array(
                                'name'  => 'title',                          // unique field name
                                'type'  => 'text',                           // registered field type
                                'label' => __( 'Title', 'attachments' ),     // label to display
                              ),
                              array(
                                'name'  => 'caption',                        // unique field name
                                'type'  => 'text',                           // registered field type
                                'label' => __( 'Caption', 'attachments' ),   // label to display
                              )
                           ),
      );
    
      $attachments->register( self::$post_type['slug'] . '_photos', $args ); // unique instance name
    }

    /**
     * 
     * METABOXES
     * 
     * Creates metaboxes
     * 
     * @author epstudios
     *      
     */
    public function metaboxes() {
        add_meta_box(
            'Details', // id, used as the html id att
            __( 'Fill in the Details:' ), // meta box title, like "Page Attributes"
            array( get_class($this), 'metabox_html'), // callback function, spits out the content
            self::$post_type['slug'], // post type or page. We'll add this to pages only
            'normal', // context (where on the screen
            'default' // priority, where should this go in the context?
        );
        

    }
   

    /**
     * 
     * METABOX HTML
     * 
     * Metabox HTML
     * 
     * @author epstudios
     *      
     */
    function metabox_html ( $post ) {
        global $wpdb;
        $dashboard_form = new EPS_Menu_Form();
        $values = self::get_meta($post->ID);
        ?>
        
        <div class="misc-pub-section misc-pub-section-last">
            <div id="resort-meta-tabs" class="eps-tab-nav group">
            <?php
                foreach ( self::$meta as $section => $sub_metas ) {
                    ?>
                    <a href="#<?php echo $section; ?>"><?php echo eps_prettify($section); ?></a>
                    <?php
                }
                ?>
            </div>
            
            <div class="eps-panes">
                
            <?php
            foreach ( self::$meta as $section => $sub_metas ) {
            ?>
                <div id="<?php echo $section; ?>-pane" class="eps-pane">
                    <?php
                    for( $i = 0; $i < count($sub_metas); $i ++){
                        switch($sub_metas[$i][2]) {
                            case 'input':
                                echo $dashboard_form->get_input(   $sub_metas[$i][1], $section.'['.$sub_metas[$i][0].']', isset( $values[$section][ $sub_metas[$i][0] ] ) ? $values[$section][$sub_metas[$i][0]] : null);
                            break;
                            case 'bool':
                                echo $dashboard_form->get_bool(    $sub_metas[$i][1], $section.'['.$sub_metas[$i][0].']', isset( $values[$section][ $sub_metas[$i][0] ] ) ? $values[$section][$sub_metas[$i][0]] : false );
                            break;
                            case 'date':
                                echo $dashboard_form->get_date(    $sub_metas[$i][1], $section.'['.$sub_metas[$i][0].']', isset( $values[$section][ $sub_metas[$i][0] ] ) ? $values[$section][$sub_metas[$i][0]] : null);
                            break;
                            case 'range':
                                echo $dashboard_form->get_range(   $sub_metas[$i][1], $section.'['.$sub_metas[$i][0].']', isset( $values[$section][ $sub_metas[$i][0] ] ) ? $values[$section][$sub_metas[$i][0]] : null, $sub_metas[$i][3]);
                            break;
                            case 'textarea':
                                echo $dashboard_form->get_textarea($sub_metas[$i][1], $section.'['.$sub_metas[$i][0].']', isset( $values[$section][ $sub_metas[$i][0] ] ) ? $values[$section][$sub_metas[$i][0]] : null);
                            break;
                            case 'select':
                                echo $dashboard_form->get_select($sub_metas[$i][1], $section.'['.$sub_metas[$i][0].']', $sub_metas[$i][3], isset( $values[$section][ $sub_metas[$i][0] ] ) ? $values[$section][$sub_metas[$i][0]] : null );
                            break;
                            case 'ignore':
                            break;
                        }
                            
                    }
                    ?>
                </div>
            <?php
            }
            ?>
            </div>
        </div>
        <?php

    }

    /**
     * 
     * SAVE META DATA
     * 
     * Saves!
     * 
     * @author epstudios
     *      
     */
    function save_metadata($postid) {   
        global $post;  
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return false;
        if ( !current_user_can( 'edit_page', $postid ) ) return false;
        if ( empty($postid) || get_post_type( $postid ) != self::$post_type['slug'] ) return false;

        foreach ( self::$meta as $needle => $meta_array ) {
            if ( array_key_exists($needle, $_REQUEST) ) {
                update_post_meta($postid, $needle, $_REQUEST[$needle]);
            } else {
                delete_post_meta($postid, $needle);
            }
        }
    }
    
   
    /**
     * 
     * ENTRIES
     * 
     * Helpers for retrieving entries!
     * 
     * @author epstudios
     *      
     */
    public static function get_entries( $post_count = -1 ) {
        $args = array( 
                'post_type'         => self::$post_type['slug'],
                'posts_per_page'    => $post_count,
                'post_status'       => array('publish')
            );
        $entries = new WP_Query($args);
        return $entries;
    }
    
  
    public static function get_entry_by( $key, $value ) {
        $args = array( 
                'post_type'         => self::$post_type['slug'],
                'posts_per_page'    => 1,
                $key                => $value,
                'post_status'       => array('publish')
            );
        $entries = new WP_Query($args);
        return $entries;
    }
    
    public function _notice($string){
        echo '<div class="updated"><p>'.$string.'</p></div>';
    }
    /**
     * 
     * TERM PHOTO STUFF
     * 
     * Functions to init term thumbnails for this CPT.
     * 
     * @author epstudios
     *      
     */
    
    // Check for necessary plugin.
    function check_term_meta_compat() {
        if( !class_exists('EPS_Custom_Term_Meta') ) {
            add_action('admin_notices', self::_notice('Please include <strong>functions.term-meta.php</strong> to begin using Term Thumbnails.') );
            return false;
        } else { return true; }
    }
    
    // Enqueue necessary scripts
    function photo_scripts() {
        if( !self::check_term_meta_compat() ) return;
        wp_enqueue_media();
        wp_enqueue_script('eps_photo_uploader', EPS_MENU_URL . 'js/eps_photo_uploader.js', array('jquery'), '1.0');
    }
    
    // Output the HTML.
    function term_photo_form( $args ) {
        if( !self::check_term_meta_compat() ) return;
        
        if( isset( $args->term_id ) ) {
            $thumbnail_id = get_term_meta( $args->term_id, 'thumbnail_id', true  );
            $image = ( $thumbnail_id ) ? wp_get_attachment_url( $thumbnail_id ) : EPS_MENU_URL . '/images/ph-thumbnail.jpg';
        } else {
            $image =  EPS_MENU_URL . '/images/ph-thumbnail.jpg';
        }
        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label>Thumbnail:</label></th>
            <td>
                <div id="term_thumbnail_img" style="margin-bottom:12px;border:5px solid white; box-shadow: 1px 1px 5px #aaaaaa; width: 98%px; max-width: 300px; height: auto; overflow:hidden;"><img src="<?php echo $image; ?>" style="display:block;width:100%;height:auto;" /></div>
                <div style="margin-bottom:24px;">
                    <input type="hidden" id="term_thumbnail_id" name="term_thumbnail_id" value="<?php echo $thumbnail_id; ?>" />
                    <button type="submit" name="upload_image_button" class="upload_image_button button">Upload/Add image</button>
                    <button type="submit" name="remove_image_button" class="remove_image_button button">Remove image</button>
                </div>
                
                <div class="clear"></div>
            </td>
        </tr>
    <?php
    }
    // Save the thumbnail.
    function save_term_fields( $term_id ) {
        if( !self::check_term_meta_compat() ) return;
        
        if ( isset( $_POST['term_thumbnail_id'] ) ) 
            update_term_meta( $term_id, 'thumbnail_id', absint( $_POST['term_thumbnail_id'] ) );
        
        if ( isset( $_POST['remove_image_button'] ) )
            update_term_meta( $term_id, 'thumbnail_id', null );
    }

    public function get_meta( $post_id ) {
        $metas = get_post_meta($post_id);
       
        $unserialized_metas = array();
        foreach( $metas as $key => $meta ) {
            if( array_key_exists($key, self::$meta))
            $unserialized_metas[$key] = unserialize($meta[0]);
        }
        return $unserialized_metas;
    }
    



}



//********************************//
// Init the Resort post type.
//********************************//
$EPS_Menu_Items = new EPS_Menu_Items;

add_action( 'init', 'eps_menu_init' );
function eps_menu_init() {
    global $EPS_Menu_Items;
    

    $EPS_Menu_Items->add_taxonomy( array(
            'taxonomy'  => 'eps_menu_section',
            'single'    => 'Menu Section',
            'plural'    => 'Menu Sections',
            'thumbnails'=> true
        ));
}
?>