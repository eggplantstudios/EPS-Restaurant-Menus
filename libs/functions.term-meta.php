<?php
if( !class_exists('EPS_Custom_Term_Meta')) {
class EPS_Custom_Term_Meta {
    
    function __construct() {
        add_action("after_switch_theme",    array( $this, "theme_activation") );
        add_action( 'init',                 array( $this, 'define_table') );
    }
    
    function theme_activation() {
        // setup custom table
        
        global $wpdb;
            
        $table_name = $wpdb->prefix . 'termmeta';
        
        if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) :
        
            $sql = "CREATE TABLE " . $table_name . " (
              meta_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              term_id bigint(20) unsigned NOT NULL DEFAULT '0',
              meta_key varchar(255) DEFAULT NULL,
              meta_value longtext,
              PRIMARY KEY (meta_id),
              KEY term_id (term_id),
              KEY meta_key (meta_key)     
            );";
            
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            
        endif;
        
    }
    function define_table() {
        global $wpdb;
        $wpdb->termmeta = $wpdb->prefix . 'termmeta';
    }
    
}
$EPS_Custom_Term_Meta = new EPS_Custom_Term_Meta;

if( !function_exists( 'update_termmeta_cache' ) ) {
function update_termmeta_cache($term_ids) {
	return update_meta_cache('term', $term_ids);
}
}

if( !function_exists( 'add_term_meta' ) ) {
function add_term_meta( $term_id, $meta_key, $meta_value, $unique = false ) {
	return add_metadata('term', $term_id, $meta_key, $meta_value, $unique);
}
}

if( !function_exists( 'delete_term_meta' ) ) {
function delete_term_meta( $term_id, $meta_key, $meta_value = '' ) {
	return delete_metadata('term', $term_id, $meta_key, $meta_value);
}
}

if( !function_exists( 'get_term_meta' ) ) {
function get_term_meta( $term_id, $key, $single = false ) {
	return get_metadata('term', $term_id, $key, $single);
}
}

if( !function_exists( 'update_term_meta' ) ) {
function update_term_meta( $term_id, $meta_key, $meta_value, $prev_value = '' ) {
	return update_metadata('term', $term_id, $meta_key, $meta_value, $prev_value);
}
}

if( !function_exists( 'get_term_custom' ) ) {
function get_term_custom( $term_id ) {
	$term_id = (int) $term_id;

	if ( ! wp_cache_get($term_id, 'term_meta') )
		update_termmeta_cache($term_id);

	return wp_cache_get($term_id, 'term_meta');
}
}

if( !function_exists( 'get_term_custom_keys' ) ) {
function get_term_custom_keys( $term_id ) {
	$custom = get_term_custom( $term_id );

	if ( !is_array($custom) )
		return;

	if ( $keys = array_keys($custom) )
		return $keys;
}
}

if( !function_exists( 'get_term_custom_values' ) ) {
function get_term_custom_values( $key = '', $term_id ) {
	if ( !$key )
		return null;

	$custom = get_term_custom($term_id);

	return isset($custom[$key]) ? $custom[$key] : null;
}
}

}
?>