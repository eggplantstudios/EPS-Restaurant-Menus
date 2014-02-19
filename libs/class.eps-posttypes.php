<?php
/**
 * My Super Awesome CPT helper!
 * 
 * Requires class.form.php!
 *
 * @author Shawn Wernig, Eggplant Studios, www.eggplantstudios.ca
 * @version 1
 * @copyright 2013 Eggplant Studios
 */

 
 
 
 /**
 * 
 * EPS_Posttype_Entry.
 * 
 * Bones for the post type entries.
 * 
 * @author epstudios
 *      
 */

if( !class_exists('EPS_Posttype_Entry')) {
    
class EPS_Posttype_Entry {
    
    public $details = array();
    var $terms      = array();
    
    function __construct(  $id = null ) {
        if ( !isset($id) ) {
            // new from post
            global $post;
            $this->get_meta( get_the_id() );
        } else {
            $this->get_meta( $id );
        }
    }
    
    /**
     * 
     * GET META
     * 
     * Gets all the custom meta for this post type.
     * 
     * @return html string
     * @author epstudios
     *      
     */
    public function get_meta( $post_id ) {
        $metas = get_post_meta($post_id);
        $unserialized_metas = array();
        foreach( $metas as $key => $meta ) {
            if ( $this->_has_attribute($key) ) {
                if ( @unserialize($meta[0]) !== false ) {
                    $this->$key = unserialize($meta[0]);
                } else {
                    $this->$key = $meta[0];
                }
            }
        }
        
    }
    
    /**
     * 
     * ATTRIBUTE STUFF
     * 
     * Attribute helpers
     * 
     * @return html string
     * @author epstudios
     *      
     */
    private function _has_attribute ( $key ) {
        $object_vars = get_object_vars($this);
        return array_key_exists($key, $object_vars);
    }
    
    public function get_attr( $key, $field ) {
        $meta_section = $this->$key;
        return isset( $meta_section[$field] ) ? $meta_section[$field] : false;
    }
    public function has_attr( $key, $field ) {
        if( !isset( $this->$key ) ) return false;
        $meta_section = $this->$key;
        return  ( isset($meta_section[$field]) && !empty($meta_section[$field]) ) ? true : false;
    }
    
    public function attr( $key, $field, $default = '&nbsp;') {
        $meta_section = $this->$key;
        echo (isset($meta_section[$field]) && !empty($meta_section[$field]) ) ? esc_attr( $meta_section[$field] ) : $default;
    }
    
    
    /**
     * 
     * DO GALLERY   
     * 
     * Takes the 'attachments' and outputs a gallery.
     * 
     * @return html string
     * @author epstudios
     *      
     */
    public static function do_gallery( $gallery_id = null ) {
        global $post;
        $gallery_id = (isset($gallery_id)) ? $gallery_id : get_the_ID();
        
        $attachments = new Attachments( 'resort_photos', $gallery_id );
                
        if( $attachments->exist() ) : 
            
            // Get an array of data.
            $gallery = array();
            while( $attachments->get() ) {
                $photo = array();    
                $photo['ID'] =  $attachments->id();
                $photo['src'] =  $attachments->src( 'gallery' );
                $photo['title'] = $attachments->field( 'title' );
                $photo['caption'] = $attachments->field( 'caption' );
                $gallery[] = $photo;
            }    
        
            ?>
            <div class="slider-wrapper theme-default">
                
                
                <?php
                // Loop through the slides:
                ?>
                <div id="resort-slider" class="nivoSlider">
                <?php 
                foreach( $gallery as $photo ) { 
                    $caption = ( isset($photo['caption']) && !empty($photo['caption']) ) ? 
                        'title="#caption-'. $photo['ID'].'"' : null ;
                ?>
                    <img src="<?php  echo $photo['src']; ?>" <?php echo $caption; ?> alt="<?php  echo $photo['title']; ?>">
                <?php 
                }
                ?>
                </div>
                
                <?php 
                // Loop through the captions
                foreach( $gallery as $photo ) { 
                    if( !isset($photo['caption']) || empty($photo['caption']) ) continue;
                ?>
                    <div id="caption-<?php echo $photo['ID']; ?>" class="nivo-html-caption">
                        <?php echo $photo['caption']; ?>
                    </div>
                <?php 
                } 
                ?>
                
            </div>
            <?php
       
        endif;
    }

    /**
     * 
     * GET TERMS CSV.
     * 
     * Gets the terms!
     * 
     * @return html string
     * @author epstudios
     *      
     */
    public function get_terms_csv( $term_slug, $key = 'name' ) {
        global $post;
        
        
        $terms = get_the_terms( get_the_ID(), $term_slug );
        if ( !isset($terms ) || empty($terms) ) return false; // no keywords set.

        $html ='';
        foreach ( $terms as $term ) {
                $html .= prettify($term->$key) . ", ";
            }
        return substr($html, 0, -2);
    } 

}
}



?>