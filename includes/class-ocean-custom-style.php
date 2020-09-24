<?php

/**
 * Custom ocean style 
 * 
 * 
 * @author Younés DRO <dro66_8@hotmail.fr>
 * @version 1.0.0
 */
class Ocean_Custom_Style {

    public function __construct(){
        add_filter( 'ocean_display_page_header', array ( $this , 'disable_title' ) );
        add_filter( 'ocean_post_layout_class', array ( $this , 'product_layout_class' ), 20 );
        add_filter( 'ocean_main_metaboxes_post_types', array ( $this , 'oceanwp_metabox' ), 20 );       
    }
    public function disable_title( $return ){
        global $post;
        if ( $post->post_type == 'tecnibo_product' ) {
            $return = false;
        }
        
        return $return;        
    }
    public function product_layout_class (){
        global $post;
        if ( $post->post_type == 'tecnibo_product' ) {	
		$class = 'full-width';
	}

	// Return correct class
	return $class;        
    }
    /**
     * Add the OceanWP Settings metabox in your CPT
     */
    public function oceanwp_metabox( $types ){
	// Your custom post type
	$types[] = 'tecnibo_product';

	// Return
	return $types;   
    }
}
