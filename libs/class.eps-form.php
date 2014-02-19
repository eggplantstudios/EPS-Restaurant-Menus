<?php
/**
 * Form helper.
 *
 * @author Shawn Wernig, Eggplant Studios, www.eggplantstudios.ca
 * @version 1
 * @copyright 2012 Eggplant Studios
 * 
 * 
 */

 
if( !class_exists('EPS_Menu_Form')) {
 
class EPS_Menu_Form {


    public $rows = array();
    public $form_id;
    
   function __construct() {}
    
    /**
     * 
     * INITS A NEW FORM.
     * 
     * Starts the form!
     * 
     * @return html string
     * @author epstudios
     *      
     */
    public function new_form( $id, $method='post', $action='', $enctype="multipart/form-data" ) {
        $this->form_id = $id;
        printf('<form id="%s" method="%s" action="%s" enctype="%s">', 
            $id, $method, $action, $enctype );
            
        echo '<div style="display: none;">';
            wp_nonce_field('eps_form_'.$id); // TODO may fail if not a wp site
            echo '<input type="hidden" name="form_id" value="'.$id.'"/>';
        echo '</div>';
        
    }
    /**
     * 
     * CLOSES THE FORM!
     * 
     * This function will check the current version and do any fixes required
     * 
     * @return html string
     * @author epstudios
     *      
     */
    public function close_form() {
        foreach( $this->rows as $row ) 
            echo $row;
            
        echo '</form>';
        $this->rows = array(); // empty.
    }
    
    
    /**
     * 
     * DO MESSAGES
     * 
     * Any messages during form validation will be placed into $_POST. retrieve them here.
     * Defaults to display this form ids messages only.
     *  
     * @return html string
     * @author epstudios
     *      
     */

    public function do_messages($id=null){
        if (!isset($id)) $id = $this->form_id;
        echo ( isset($_POST[$id]) ) ? $_POST[$id] : null; //Will place messages in $_POST[$id] for user feedback display.
    }
    
    /**
     * 
     * DO HTML
     * 
     * Adds HTML to the form rows array.
     *  
     * @return html string
     * @author epstudios
     *      
     */
    public function do_html( $input) {
        $this->rows[] = $input; // inserts some html into a row.
    }   
    
    /**
     * 
     * ADD/GET ROW
     * 
     * Adds an input to a row.
     *  
     * @return html string
     * @author epstudios
     *      
     */
    public function add_row($label, $input) {
        $this->rows[] = sprintf('%s<span class="eps-form-row">%s</span>%s', 
            ( isset($label) && !empty($label) ) ? '<p><label>'.eps_prettify( $label ).':</label><br>' : null, 
            $input,
            ( isset($label) && !empty($label) ) ? '</p>' : null );
    }
    public function get_row($label, $input) {
        return sprintf('%s<span class="eps-form-row">%s</span>%s', 
            ( isset($label) && !empty($label) ) ? '<p><label>'.eps_prettify( $label ).':</label><br>' : null, 
            $input,
            ( isset($label) && !empty($label) ) ? '</p>' : null );
    }
    
    /**
     * 
     * DO SUBMIT
     * 
     * Adds a submit button!
     *  
     * @return html string
     * @author epstudios
     *      
     */
    function do_submit($value = 'Submit', $slug = null ) {
        if ( !$slug ) $slug = $this->form_id . '-submit';
        
        $input = sprintf('<input id="%s" type="submit" name="Submit" value="%s" class="submit wpcf7-submit wide" />', 
                $slug, $value
            );
            
        $this->add_row('', $input);
    }    
    
    
    /**
     * 
     * Echos an input as per the parameters.
     *
     * @return void
     * @param $label == the title, or label $slug == the name property $value == the starting value
     * @author epstudios
     * 
     */
    private function _get_range($slug, $value, $max, $data){
        return sprintf('<input id="%s" type="range" name="%s" class="%s" value="%s" min="0" max="%s" %s/>', 
            $slug, $slug,
            ( (isset($max) && $max == 1 ) ? 'switch' : 'full' ),
            ( (isset($value) ) ? $value : 0 ),
            ( (isset($max) ) ? $max : null ),
            ( (isset($data) ) ? $data : null )
        );
    }
    
    function do_range($label, $slug, $value, $max, $data = false ) {
        $input = $this->_get_range( $slug, $value, $max, $data);
        $label .= (isset($max) && $max == 1 ) ? ' <small>( NO / YES )</small>' : null;
        $this->add_row($label, $input);
    }
    function get_range($label, $slug, $value, $max, $data = false) {
        $input = $this->_get_range( $slug, $value, $max, $data);
        $label .= (isset($max) && $max == 1 ) ? ' <small>( NO / YES )</small>' : null;
        return $this->get_row($label, $input);
    }
    
    /**
     * 
     * Echos an input as per the parameters.
     *
     * @return void
     * @param $label == the title, or label $slug == the name property $value == the starting value
     * @author epstudios
     * 
     */
    private function _get_date($slug, $value, $data = false ){
        return sprintf('<input id="%s" type="date" name="%s" value="%s" %s/>', 
            $slug, $slug,
            ( (isset($value) ) ? $value : null ),
            ( (isset($max) ) ? $max : null ),
            ( (isset($data) ) ? $data : null )
        );
    }

    function do_date($label, $slug, $value, $data = false ) {
        $input = $this->_get_date( $slug, $value, $data );
        $this->add_row($label, $input);
    }
    function get_date($label, $slug, $value, $data = false ) {
        $input = $this->_get_date( $slug, $value, $data );
        return $this->get_row($label, $input);
    }

    /**
     * 
     * Echos a textarea as per the parameters.
     *
     * @return void
     * @param $label == the title, or label $slug == the name property $value == the starting value
     * @author epstudios
     * 
     */
    private function _get_textarea( $slug, $value, $data = null ){
        return'<textarea id="'.$slug.'" name="'.$slug.'" class="wp-editor-container" rows="10" tabindex="8" style="width:100%" '. ( (isset($data) ) ? "data-limit='$data'" : null ) .'>'. ( (isset($value) ) ? $value : null ) . '</textarea>';
    }
    
    function do_textarea($label, $slug, $value, $data = false) {
        $input = $this->_get_textarea( $slug, $value, $data );
        $this->add_row($label, $input);
    }
    function get_textarea($label, $slug, $value, $data = null) {
        $input = $this->_get_textarea( $slug, $value, $data );
        return $this->get_row($label, $input);
    }

     /**
      * 
     * Echos an input as per the parameters.
     *
     * @return void
     * @param $label == the title, or label $slug == the name property $value == the starting value
     * @author epstudios
     * 
     */
    function do_hidden($slug, $value ) {
        $input = sprintf('<input id="%s" type="hidden" name="%s" value="%s"/>', 
                $slug, $slug, ( (isset($value) ) ? $value : null )
            );
        $this->add_row('', $input);
    }
    
     /**
      * 
     * Echos an input as per the parameters.
     *
     * @return void
     * @param $label == the title, or label $slug == the name property $value == the starting value
     * @author epstudios
     * 
     */
    function do_password($label, $slug, $data = false ) {
        
        $input = sprintf('<input id="%s" type="password" name="%s" %s/>', 
                $slug.'1', $slug.'1',
                ( (isset($data) ) ? $data : null )
            );
        $this->add_row($label, $input);
        
        $input = sprintf('<input id="%s" type="password" name="%s" %s/>', 
                $slug.'2', $slug.'2',
                ( (isset($data) ) ? $data : null )
            );
        $this->add_row($label, $input);
    }
    
       
       
    /**
     * 
     * Echos an input as per the parameters.
     *
     * @return void
     * @param $label == the title, or label $slug == the name property $value == the starting value
     * @author epstudios
     * 
     */
    
    private function _get_input( $slug, $value, $ph = false, $data = false ) {
        return sprintf('<input id="%s" type="text" name="%s" value="%s" placeholder="%s" %s/>', 
                $slug, $slug,
                ( (isset($value) ) ? $value : null ),
                ( (isset($ph) ) ? $ph : null ),
                ( (isset($data) ) ? $data : null )
            );
    }
    function do_input($label, $slug, $value, $ph = false, $data = false ) {
        $input = $this->_get_input( $slug, $value, $ph, $data );
        $this->add_row($label, $input);
    }
    function get_input($label, $slug, $value, $ph = false, $data = false ) {
        $input = $this->_get_input( $slug, $value, $ph, $data );
        return $this->get_row($label, $input);
    }
    
    /**
     * 
     * Echos an input as per the parameters.
     *
     * @return void
     * @param $label == the title, or label $slug == the name property $value == the starting value
     * @author epstudios
     * 
     */
    function do_image($label, $slug, $value, $ph = false, $data = false ) {
        $input = self::get_image($label, $slug, $value, $ph, $data);
        $this->add_row($label, $input);
    }
    
    function get_image($label, $slug, $value, $ph = false, $data = false ) {
        
        $input = sprintf('<input id="%s" type="file" name="%s" value="%s" placeholder="%s" %s accept="image/*"/>', 
                $slug, $slug,
                ( (isset($value) ) ? $value : null ),
                ( (isset($ph) ) ? $ph : null ),
                ( (isset($data) ) ? $data : null )
            );
        return $input;
    }
    
    
   /**
    * 
     * Echos an input as per the parameters.
     *
     * @return void
     * @param $label == the title, or label $slug == the name property $value == the starting value
     * @author epstudios
     * 
     */
    private function _get_bool( $slug, $value, $set = null ) {
        return sprintf('<input type="checkbox"  name="%s" value="%s" %s/>', 
            $slug,
            ( (isset($set) ) ? $set : 'on' ),
            ( (isset($value) ) ? 'checked="checked"' : null )
        );
    }
    function do_bool($label, $slug, $value, $set = null) {
        $input = $this->_get_bool( $slug, $value, $set );
        $this->add_row($label, $input);
    }
    
    function get_bool($label, $slug, $value, $set = null) {
        $input = $this->_get_bool( $slug, $value, $set );
        return $this->get_row($label, $input);

    }
    
    /**
     * 
     * Echos a required agreement checkbox.
     *
     * @return void
     * @param $label == the title, or label $slug == the name property $value == the starting value
     * @author epstudios
     * 
     */
    
    function do_terms_agree($label) {
        $input = '<input id="terms-agree" type="checkbox" name="agree" required="required"/>I Accept';
        $this->add_row($label, $input);
    }
    
   
    /**
     * 
     * Echos an input as per the parameters.
     *
     * @return void
     * @param $label == the title, or label $slug == the name property $value == the starting value
     * @author epstudios
     * 
     */
    private function _get_select( $slug, $options=array(), $cur_value, $dummy = null, $attr = null ){
        $input = sprintf('<select id="%s" name="%s" %s>',  $slug, $slug, $attr );
        if ($dummy) $input .= '<option value="">...</option>';
        
        foreach ( $options as $key => $value ) {
            $input .= sprintf('<option value="%s" %s>%s</option>', 
                $value,
                ( $value == $cur_value ) ? 'selected="selected"' : '',
                eps_prettify($key) 
                );
        }
        
        $input .= '</select>';
        return $input;
    }
    function do_select($label, $slug, $options=array(), $cur_value, $dummy = null, $attr = null ) {
        $input = $this->_get_select( $slug, $options, $cur_value, $dummy, $attr );
        $this->add_row($label, $input);
    }
    


    function get_select($label, $slug, $options=array(), $cur_value, $dummy = null, $attr = null ) {
        $input = $this->_get_select( $slug, $options, $cur_value, $dummy, $attr );
        return $this->get_row($label, $input);
    }
    

    
}
}


        
        
?>