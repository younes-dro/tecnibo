<?php

/**
 * Admin .
 * 
 * @author    Younes DRO
 * @copyright Copyright (c) 2020, Younes DRO
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


/**
 * Admin Custom Post Type and Taxonomies.
 * 
 * @class Tecnibo_Portfolio
 * @author Younes DRO <younesdro@gmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class Tecnibo_Portfolio {
    
    public function __construct(){
        
        add_meta_box( 'product_metabox', __( 'Select Tecnibo projects that have used this product', 'tecnibo' ), array( $this , 'product_metabox' ), 'tecnibo_product', 'normal', 'default' );
        
    }

    public static function create_portfolio(){

        /**
         * Create the Product Custom Post Type
         */
        $dro_post_type = new DRO_PostType( Tecnibo_Labels::get_posttype() );
        $dro_post_type->register_post_type();

        /**
         * Create the Product Category taxonomies
         */
        $dro_taxonomies = new DRO_Taxonomies( Tecnibo_Labels::get_taxonomies() );
        $dro_taxonomies->create_taxanomies();
        
        /**
         * Create the Projects Custom Post Type
         */
        $dro_project_post_type = new DRO_PostType( Tecnibo_Labels::get_project_posttype() );
        $dro_project_post_type->register_post_type();     
    }
    
    public function product_metabox( $post_object ){
        
	$appended_projects = get_post_meta( $post_object->ID, '_related_projects',false );
        
	/*
	 * Select Projects with AJAX search
	 */
        $html = '';
	$html .= '<p><label for="tecnibo_projects">'.__( 'Projects:','tecnibo').'</label><br />';
        $html .= '<select id="tecnibo_projects" name="tecnibo_projects[]" multiple="multiple" style="width:99%;max-width:25em;">';
 
	if( $appended_projects ) {
		foreach( $appended_projects[0] as $project_id ) {
			$title = get_the_title( $project_id );
			// if the project title is too long, truncate it and add "..." at the end
			$title = ( mb_strlen( $title ) > 50 ) ? mb_substr( $title, 0, 49 ) . '...' : $title;
			$html .=  '<option value="' . $project_id . '" selected="selected">' . $title . '</option>';
		}
	}
	$html .= '</select></p>';
 
	echo $html;        
    }

    public static function save_product_metabox ( $post_id ){

        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
        
        if ( isset( $_POST['tecnibo_projects'] ) ) {

            $sanitized_data = array();

            $data = (array) $_POST['tecnibo_projects'];

            foreach ($data as $key => $value) {

                $sanitized_data[ $key ] = (int)strip_tags( stripslashes( $value ) );

            }

            update_post_meta( $post_id, '_related_projects', $sanitized_data );

        }  else {
            delete_post_meta ( $post_id, '_related_projects' );
        }
             
    }
 
}
