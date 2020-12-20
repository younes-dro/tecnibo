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
        
        add_meta_box( 'product_metabox', __( 'Product Meta Data', 'tecnibo' ), array( $this , 'product_metabox' ), 'tecnibo_product', 'normal', 'default' );
        add_meta_box( 'variable_product_metabox', __( 'Variable Product', 'tecnibo' ), array( $this , 'variable_product_metabox' ), 'tecnibo_product', 'normal', 'default' );        
        add_meta_box( 'project_metabox', __( 'Select products that are used by this project', 'tecnibo' ), array( $this , 'project_metabox' ), 'tecnibo_project', 'normal', 'default' );
        add_meta_box( 'project_details_metabox', __( 'Project Details', 'tecnibo' ), array( $this , 'project_details_metabox' ), 'tecnibo_project', 'normal', 'default' ); 
        add_meta_box( 'team_social_metabox', __( 'Social', 'tecnibo' ), array( $this , 'team_social_metabox' ), 'tecnibo_member', 'normal', 'default' );         
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
        
        /**
         * Create the Members Custom Post Type
         */
        $dro_member_post_type = new DRO_PostType( Tecnibo_Labels::get_team_posttype() );
        $dro_member_post_type->register_post_type();  
    }
    
    public function product_metabox( $post_object ){
        
	$appended_projects = get_post_meta( $post_object->ID, '_related_projects',false );
        $has_projects = false;
        if(count($appended_projects) ){
            $has_projects = true;
        }
        
        $html = '';
	$html .= '<p><label for="tecnibo_projects">'.__( 'Projets:','tecnibo').'</label><br />';
        $html .= '<select id="tecnibo_projects" name="tecnibo_projects[]" multiple="multiple" style="width:99%;max-width:25em;">';        
	$search_results = new WP_Query( array( 
		'post_status' => 'publish',
                'post_type' => 'tecnibo_project' ,
		'ignore_sticky_posts' => 1,
		'posts_per_page' => 50, 
                'orderby' => 'title', 
                'order' => 'ASC'
	) );        
	if( $search_results->have_posts() ) :
		while( $search_results->have_posts() ) : $search_results->the_post();	
			
			$title      = $search_results->post->post_title;
                        $project_id = $search_results->post->ID;
                        $selected = ( $has_projects && in_array( $project_id, $appended_projects[0] ) ) ? ' selected="selected"' : '';
                        $html .=  '<option value="' . $project_id . '" '. $selected .' >' . $title . '</option>';
			
		endwhile;
	endif;        

	$html .= '</select></p>';
        //PDF
        $pdf_file = get_post_meta( $post_object->ID , '_pdf_file', true );
        $pdf_url = ( !empty ($pdf_file) ) ? '<a target="_blank" href="' . $pdf_file['url'] .'"><span class="dashicons dashicons-pdf"></span> '.self::get_pdf_name($pdf_file['url']).'</a>' : '';
        $html .= '<p class="description">';
        $html .= __( 'Technical brochure 1: (<b>.pdf</b>)','tecnibo' ) ;
        $html .= '</p>';
        $html .= '<input type="file" id="pdf_file" name="pdf_file" value="" size="25">';
        $html .= '<br><br>'.$pdf_url;
        
        $pdf_file_1 = get_post_meta( $post_object->ID , '_pdf_file_1', true );
        $pdf_url_1 = ( !empty ($pdf_file_1) ) ? '<a target="_blank" href="' . $pdf_file_1['url'] .'"><span class="dashicons dashicons-pdf"></span> '.self::get_pdf_name($pdf_file_1['url']).'</a>' : '';
        $html .= '<p class="description">';
        $html .= __( 'Technical brochure 2: (<b>.pdf</b>)','tecnibo' ) ;
        $html .= '</p>';
        $html .= '<input type="file" id="pdf_file_1" name="pdf_file_1" value="" size="25">';
        $html .= '<br><br>'.$pdf_url_1;  
        
        $pdf_file_2 = get_post_meta( $post_object->ID , '_pdf_file_2', true );
        $pdf_url_2 = ( !empty ($pdf_file_2) ) ? '<a target="_blank" href="' . $pdf_file_2['url'] .'"><span class="dashicons dashicons-pdf"></span> '.self::get_pdf_name($pdf_file_2['url']).'</a>' : '';
        $html .= '<p class="description">';
        $html .= __( 'Technical brochure 3: (<b>.pdf</b>)','tecnibo' ) ;
        $html .= '</p>';
        $html .= '<input type="file" id="pdf_file_2" name="pdf_file_2" value="" size="25">';
        $html .= '<br><br>'.$pdf_url_2;         
        
        // Display Product in Main Menu
        
        
        $display_mainmenu = get_post_meta ( $post_object->ID, '_display_mainmenu' , true );
        ($display_mainmenu == "yes") ? $field_checked = 'checked="checked"' : '';
        $html .= '<p class="description">';
        $html .= '<input  name="_display_mainmenu" value="yes" '.$field_checked.' type="checkbox" >';
        $html .= '<label>'.__('Show in MainMenu?','tecnibo').'</label>';
        $html .= '</p>';
	echo $html;        
    }
        public function variable_product_metabox( $post_object ){

            $appended_variations = get_post_meta( $post_object->ID, '_variations',false );
            $has_variations = false;
            if(count($appended_variations) ){
                $has_variations = true;
            }
        
        $html = '';
	$html .= '<p><label for="tecnibo_variations">'.__( 'Variations:','tecnibo').'</label><br />';
        $html .= '<select id="tecnibo_variations" name="tecnibo_variations[]" multiple="multiple" style="width:99%;max-width:25em;">';
 
	$search_results = new WP_Query( array( 
		'post_status' => 'publish',
                'post_type' => 'tecnibo_product' ,
		'ignore_sticky_posts' => 1,
		'posts_per_page' => 50, 
                'orderby' => 'title', 
                'order' => 'ASC'
	) );        
	if( $search_results->have_posts() ) :
		while( $search_results->have_posts() ) : $search_results->the_post();	
			
			$title      = $search_results->post->post_title;
                        $product_id = $search_results->post->ID;
                        $selected = ( $has_variations && in_array( $product_id, $appended_variations[0] ) ) ? ' selected="selected"' : '';
                        $html .=  '<option value="' . $product_id . '" '. $selected .' >' . $title . '</option>';
			
		endwhile;
	endif;
	$html .= '</select></p>';
        
        echo $html;
        }
    public static function update_edit_form (){
        echo ' enctype="multipart/form-data"';
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
        if( ! empty( $_FILES['pdf_file']['name'] ) ) {
            $supported_types = array( 'application/pdf' );
            $arr_file_type = wp_check_filetype( basename( $_FILES['pdf_file']['name'] ) );
            $uploaded_type = $arr_file_type['type'];

            if( in_array( $uploaded_type, $supported_types ) ) {
                $upload = wp_upload_bits( $_FILES['pdf_file']['name'], null, file_get_contents( $_FILES['pdf_file']['tmp_name'] ) );
                if( isset( $upload['error'] ) && $upload['error'] != 0 ) {
                    wp_die( __( 'There was an error uploading your file. The error is: ','tecnibo' ) . $upload['error'] );
                } else {
                    update_post_meta( $post_id, '_pdf_file', $upload );
                }
            }
            else {
                wp_die( __( 'The file type that you\'ve uploaded is not a PDF.' , 'tecnibo' ) );
            }
        }  
        if( ! empty( $_FILES['pdf_file_1']['name'] ) ) {
            $supported_types = array( 'application/pdf' );
            $arr_file_type = wp_check_filetype( basename( $_FILES['pdf_file_1']['name'] ) );
            $uploaded_type = $arr_file_type['type'];

            if( in_array( $uploaded_type, $supported_types ) ) {
                $upload = wp_upload_bits( $_FILES['pdf_file_1']['name'], null, file_get_contents( $_FILES['pdf_file_1']['tmp_name'] ) );
                if( isset( $upload['error'] ) && $upload['error'] != 0 ) {
                    wp_die( __( 'There was an error uploading your file. The error is: ','tecnibo' ) . $upload['error'] );
                } else {
                    update_post_meta( $post_id, '_pdf_file_1', $upload );
                }
            }
            else {
                wp_die( __( 'The file type that you\'ve uploaded is not a PDF.' , 'tecnibo' ) );
            }
        }
        if( ! empty( $_FILES['pdf_file_2']['name'] ) ) {
            $supported_types = array( 'application/pdf' );
            $arr_file_type = wp_check_filetype( basename( $_FILES['pdf_file_2']['name'] ) );
            $uploaded_type = $arr_file_type['type'];

            if( in_array( $uploaded_type, $supported_types ) ) {
                $upload = wp_upload_bits( $_FILES['pdf_file_2']['name'], null, file_get_contents( $_FILES['pdf_file_2']['tmp_name'] ) );
                if( isset( $upload['error'] ) && $upload['error'] != 0 ) {
                    wp_die( __( 'There was an error uploading your file. The error is: ','tecnibo' ) . $upload['error'] );
                } else {
                    update_post_meta( $post_id, '_pdf_file_2', $upload );
                }
            }
            else {
                wp_die( __( 'The file type that you\'ve uploaded is not a PDF.' , 'tecnibo' ) );
            }
        }
        update_post_meta($post_id, "_display_mainmenu", $_POST["_display_mainmenu"]);

    }
    public function save_variations_metabox( $post_id){
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
        
        if ( isset( $_POST['tecnibo_variations'] ) ) {

            $sanitized_data = array();

            $data = (array) $_POST['tecnibo_variations'];

            foreach ($data as $key => $value) {

                $sanitized_data[ $key ] = (int)strip_tags( stripslashes( $value ) );

            }

            update_post_meta( $post_id, '_variations', $sanitized_data );

        }  else {
            delete_post_meta ( $post_id, '_variations' );
        }        
    }
    public function project_metabox( $post_object ){
        
	$appended_products = get_post_meta( $post_object->ID, '_related_products',false );
        
	/*
	 * Select Produits with AJAX search
	 */
        $html = '';
	$html .= '<p><label for="tecnibo_products">'.__( 'Produits:','tecnibo').'</label><br />';
        $html .= '<select id="tecnibo_products" name="tecnibo_products[]" multiple="multiple" style="width:99%;max-width:25em;">';
 
	if( $appended_products ) {
		foreach( $appended_products[0] as $product_id ) {
			$title = get_the_title( $product_id );
			// if the project title is too long, truncate it and add "..." at the end
			$title = ( mb_strlen( $title ) > 50 ) ? mb_substr( $title, 0, 49 ) . '...' : $title;
			$html .=  '<option value="' . $product_id . '" selected="selected">' . $title . '</option>';
		}
	}
	$html .= '</select></p>';
 
	echo $html;        
    } 
    public static function save_project_metabox ( $post_id ){

        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
        
        if ( isset( $_POST['tecnibo_products'] ) ) {

            $sanitized_data = array();

            $data = (array) $_POST['tecnibo_products'];

            foreach ($data as $key => $value) {

                $sanitized_data[ $key ] = (int)strip_tags( stripslashes( $value ) );

            }

            update_post_meta( $post_id, '_related_products', $sanitized_data );

        }  else {
            delete_post_meta ( $post_id, '_related_products' );
        }
        
        if ( isset( $_POST['project_scope'] ) ){
            
            update_post_meta( $post_id, '_project_scope', strip_tags( stripslashes( $_POST['project_scope'] ) ) );
        } else {
            delete_post_meta ( $post_id, '_project_scope' );
        }
        
        if ( isset( $_POST['project_client'] ) ){
            
            update_post_meta( $post_id, '_project_client', strip_tags( stripslashes( $_POST['project_client'] ) ) );
        } else {
            delete_post_meta ( $post_id, '_project_client' );
        } 
        
        if ( isset( $_POST['project_product'] ) ){
            
            update_post_meta( $post_id, '_project_product', strip_tags( stripslashes( $_POST['project_product'] ) ) );
        } else {
            delete_post_meta ( $post_id, '_project_product' );
        }   
        
        if ( isset( $_POST['project_photography'] ) ){
            
            update_post_meta( $post_id, '_project_photography', strip_tags( stripslashes( $_POST['project_photography'] ) ) );
        } else {
            delete_post_meta ( $post_id, '_project_photography' );
        }
        
        if ( isset( $_POST['project_video'] ) ){
            
            update_post_meta( $post_id, '_project_video', strip_tags( stripslashes( $_POST['project_video'] ) ) );
        } else {
            delete_post_meta ( $post_id, '_project_video' );
        }        
        
             
    }
    public static function save_member_metabox ( $post_id ){

        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
        
        
        if ( isset( $_POST['member_linkedin'] ) ){
            
            update_post_meta( $post_id, '_member_linkedin', strip_tags( stripslashes( $_POST['member_linkedin'] ) ) );
        } else {
            delete_post_meta ( $post_id, '_member_linkedin' );
        } 
        
        if ( isset( $_POST['member_twitter'] ) ){
            
            update_post_meta( $post_id, '_member_twitter', strip_tags( stripslashes( $_POST['member_twitter'] ) ) );
        } else {
            delete_post_meta ( $post_id, '_member_twitter' );
        }        
        
             
    }    
    public function project_details_metabox($post_object) {
        
	$scope          = get_post_meta( $post_object->ID, '_project_scope', true );
        $client         = get_post_meta( $post_object->ID, '_project_client', true );
        $product        = get_post_meta( $post_object->ID, '_project_product', true );
        $photography    = get_post_meta( $post_object->ID, '_project_photography', true );
        $video          = get_post_meta( $post_object->ID, '_project_video', true );
        
        
        $html = '';
	$html .= '<p><label for="project_scope">'.__( 'Scope:','tecnibo').'</label><br />';
        $html .= '<input class="widefat" type="text" id="project_scope" name="project_scope" value="'.$scope.'" /> ';
	$html .= '</p>';
        
	$html .= '<p><label for="project_client">'.__( 'Client:','tecnibo').'</label><br />';
        $html .= '<input class="widefat" type="text" id="project_client" name="project_client" value="'.$client.'" />';
	$html .= '</p>'; 
        
	$html .= '<p><label for="project_product">'.__( 'Product:','tecnibo').'</label><br />';
        $html .= '<input class="widefat" type="text" id="project_product" name="project_product" value="'.$product.'" />';
	$html .= '</p>';         

	$html .= '<p><label for="project_photography">'.__( 'Photography:','tecnibo').'</label><br />';
        $html .= '<input class="widefat" type="text" id="project_photography" name="project_photography" value="'.$photography.'"  />';
	$html .= '</p>';     
        
	$html .= '<p><label for="project_video">'.__( 'Video:(The format video link  https://www.youtube.com/<b>embed</b>/)','tecnibo').'</label><br />';
        $html .= '<input placeholder="Youtube" class="widefat" type="text" id="project_video" name="project_video" value="'.$video.'" />';
	$html .= '</p>';  
        
	echo $html;        
    }
    public function team_social_metabox($post_object) {
        
	$linkedin       = get_post_meta( $post_object->ID, '_member_linkedin', true );
        $twitter       = get_post_meta( $post_object->ID, '_member_twitter', true );
        
        
        $html = '';
	$html .= '<p><label for="member_linkedin">'.__( 'LinkedIn ID:','tecnibo').'</label><br />';
        $html .= '<input class="widefat" type="text" id="member_linkedin" name="member_linkedin" value="'.$linkedin.'" /> ';
	$html .= '</p>';
       
	$html .= '<p><label for="member_twitter">'.__( 'Twitter ID:','tecnibo').'</label><br />';
        $html .= '<input class="widefat" type="text" id="member_twitter" name="member_twitter" value="'.$twitter.'" /> ';
	$html .= '</p>';         
        
	echo $html;        
    }
    public static function has_meta( $meta, $post_id , $single = true){
        
        if ( get_post_meta ( $post_id , $meta, $single) )
                return true;
        
        return false;
    }
    public static function has_variations ( $post_id ){
        
        return self::has_meta( '_variations', $post_id , false);
    }
    public static function has_related_objects( $post_id ){
        
        $posttype = get_post_type( $post_id );
        $posttype_to_search = ( $posttype == 'tecnibo_product') ? 'tecbnibo_project' : 'tecnibo_product'; 
        $related_inside = ( $posttype === 'tecnibo_product') ? '_related_projects' : '_related_products';
        $related_outside = ( $posttype === 'tecnibo_product') ? '_related_products' : '_related_projects';
        
        //Inside Objects
        $inside_objects = self::has_meta( $related_inside , $post_id, false);
        
        //Outside Objects
        $all_ids = get_posts( array (
            'post_type' => $posttype_to_search,
            'numberposts' => -1,
            'status' => 'publish',
            'fields' => 'ids'
        ) );

        $outside_objects = false;
        foreach ($all_ids as $id) {
            $q = get_post_meta( $id, $related_outside );
            if ( !empty ( $q ) && in_array( $post_id, $q[0] ) ){
                $outside_objects = true;
            }
        }
        
        if( $inside_objects || $outside_objects ){
            
            return true;
        }else{
            
            return false;
        }
    }
    public static function category_has_product_project ( $args ){
        
        $query = new WP_Query($args);
        if ( $query->have_posts() ) {
            wp_reset_query();
            return true ;
            
        }
        wp_reset_query();
        return false;
        
        
    }
    public static function get_archive_subcat( $parent_id ){
        $html = '';
        $subcats = get_terms( array(
                        'taxonomy' => 'product_category',
                        'hide_empty' => false,
                        'parent' => $parent_id,
        ) );
        $html .= '<section class="tecnibo-row">';
        foreach ($subcats as $sub_cat ) {
            $taxonomy_id = $sub_cat->term_id;
            $taxonomy_image_id = get_term_meta($taxonomy_id, 'showcase-taxonomy-image-id', true);
            $taxonomy_image_url = wp_get_attachment_image_url($taxonomy_image_id,'full');            
            $html .= '<header class="custom_tax_header" style="background-image: url('. $taxonomy_image_url .')">
                <div class="overlay-achive-page"></div>
                <h1 class="custom-tax-title">'.$sub_cat->name.'</h1></header> ';
            // Produits
            $html .= self::get_archive_products_projetcs( 'tecnibo_product' , $taxonomy_id , $sub_cat->name);
            // Projets
            $html .= self::get_archive_products_projetcs( 'tecnibo_project' , $taxonomy_id , $sub_cat->name );  
            
        }
        $html .= '</section>';
        
        return $html;
        
    }
    public static function get_archive_products_projetcs( $type , $term_id , $term_name ){
        $args = array(
            'post_type' => $type,
            'orderby' => 'title',
            'posts_per_page' => -1,
            'order' => 'ASC',
            'tax_query' => array(
                array('taxonomy' => 'product_category',
                    'field' => 'term_id',
                    'terms' => $term_id
                    )
                ),
            );
        
        
        $query = new WP_Query($args);
        if ( $query->have_posts() ) : 
            $tout = ( $parent_cat) ? 'Tout les ':'';
            $html   = '<div class="product_grid">';
            $related = ( $type == 'tecnibo_product') ? __( $tout . 'Produits de ','tecnibo' ) : __( $tout. 'Projets de ' , 'tecnibo' );
            
            $html .= '<h2 class="related_products_projects"><span>' .$related . $term_name . '</span></h2>'; 
            $html .='<div class="items">';
            while ( $query->have_posts() ) : $query->the_post();
            $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'see-details');
            $html .='<a 
                    title = "'.get_the_title().'" 
                    href  ="'.get_the_permalink().'" 
                    class ="" 
                    rel="group" data-id="'.get_the_ID().'" >
                    <img alt="' . get_the_title() .'" src="'. $featured_img_url .'">
                    <h3>'.get_the_title().'</h3>
                    </a>';
        endwhile;
        $html .='</div>';
        $html   .='</div>'; 
        else : 
                    $html .= ''; //<p>'.__( "Sorry, no products or projects  matched your criteria.", "tecnibo") .'</p>';
        endif;
        wp_reset_query();
                                    
        
        
        return $html;
        
    }
    
    public static function get_product_meta ( $meta_title , $meta , $post_id , $video = false ){
        $project_meta = get_post_meta( $post_id , $meta ,true);
        if ( $video ) 
            return Tecnibo_Portfolio::get_video( $project_meta );
        
            return '<p><strong>'.__($meta_title,'tecnibo').':&nbsp;</strong><em>'.$project_meta.'</em></p>';
        
    }
    public static function get_video( $video_url) {
        
        $video = '<iframe width="560" height="315"'
                . ' src="'.$video_url.'"'
                . ' frameborder="0" allow="autoplay; encrypted-media" '
                . 'allowfullscreen></iframe>';
        
        return $video;
    }
    public static function get_featured_images ( $post_id ){
        if( class_exists('Dynamic_Featured_Image') ){
            global $dynamic_featured_image;
            $featured_images = $dynamic_featured_image->get_featured_images( $post_id ); 
        
            return $featured_images;
        }else{
            
            return __( 'Class "Dynamic_Featured_Image" not loaded' , 'tecnibo' );
        }
    }
    public function get_variations( $post_id ){
        $html  = '';
        $html .= '<h2>'.__('Variations Disponibles:','').'</h2>';
        $html .= '<div class="container-fluid product_variations">';
        $html .= '<div class="row">';
//        $html .= '<div class="items col-12 col-md-3">';        
        
        $variations =  get_post_meta( $post_id , '_variations');
        $search_results = new WP_Query( array (
            'post_type' => 'tecnibo_product',
            'post__in' => $variations[0]
        ) );
        while( $search_results->have_posts() ) : $search_results->the_post();
            
            $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'see-details');
            $html .='<a 
                    title = "'.get_the_title().'" 
                    href  ="'.get_the_permalink().'" 
                    class ="col-12 col-sm-6 col-md-4 col-lg-4" 
                    rel="group" data-id="'.get_the_ID().'" >
                    <img alt="' . get_the_title() .'" src="'. $featured_img_url .'">
                    <h3>'.get_the_title().'</h3>
                    </a>';                    
        endwhile; 
        $html .= '</div>';
        $html .= '</div>';
//        $html   .= '</div>';
        wp_reset_query();
        
        return $html;
    }
    public static function get_related_products_projects ( $meta , $post_id  , $post_type  ){
        
        $outside_objects = self::get_outside_objects( $post_id , $post_type);
        $inside_objects = get_post_meta( $post_id , $meta );
        
        $objects = array_merge( ( array ) $outside_objects , ( array ) $inside_objects[0] );
        
        $search_results = new WP_Query( array (
            'post_type' => $post_type,
            'post__in' => $objects
        ) );
        $related = ( $post_type == 'tecnibo_product') ? __( 'Related Products','tecnibo' ) : __( 'Related Projects' , 'tecnibo' );
        $html ='';
        $html .= '<h2 class="related_products_projects"><span>' . $related . '</span></h2>';
        $html .= '<div class="items">';
       
		while( $search_results->have_posts() ) : $search_results->the_post();	
			$post_thumbnail_url = get_the_post_thumbnail_url( $search_results->post->ID, 'see-details' ); 
                        
			$html .= '<a  href="'.get_the_permalink().'" 
                                class="" 
                                rel="group" data-id="'.get_the_ID().'" 
                                data-slug="">
                                <img alt="" src="'.$post_thumbnail_url .'">
                            <span class="hover middleParent">
                                <span class="bg"></span>
                                <span class="middle">
                                    <span class="title">'.$search_results->post->post_title.'</span>
                                    <span class="see">'. __('See details','tecnibo').'</span>
                                </span>                                
                            </span>
                            </a>';
                           
                            
		endwhile;
                 wp_reset_query();
        $html .= '</div><!-- #items -->';
        
        return $html;
    }

    public static function get_outside_objects( $post_id){
        $posttype = get_post_type( $post_id );
        $posttype_to_search = ( $posttype == 'tecnibo_product') ? 'tecnibo_project' : 'tecnibo_product'; 
        $related_outside = ( $posttype === 'tecnibo_product') ? '_related_products' : '_related_projects'; 
        
        //Outside Objects
        $all_ids = get_posts( array (
            'post_type' => $posttype_to_search,
            'numberposts' => -1,
            'status' => 'publish',
            'fields' => 'ids'
        ) );

        $objects_ids = [];
        foreach ($all_ids as $id) {
            $q = get_post_meta( $id, $related_outside );
            if ( !empty ( $q ) && in_array( $post_id, $q[0] ) ){
                $objects_ids [] = $id;
            }
        } 
         return $objects_ids;
        
    }


    public static function get_grid_products_projects( $args ){
        
        $html   = '<div class="items">';
        $query = new WP_Query($args);
        if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
            $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'see-details');
            $html .='<a 
                    href="'.get_the_permalink().'" 
                    class="" 
                    rel="group" data-id="'.get_the_ID().'" data-slug="<?php //?>">
                    <img alt="" src="'. $featured_img_url .'">
                    <span class="hover middleParent">
                        <span class="bg"></span>
                        <span class="middle">
                            <span class="title">'.get_the_title() .'</span>
                            <span class="see">'.__( "See details","tecnibo" ).'</span>
                        </span>                                
                    </span>
                    </a>';
        endwhile; else : 
                    $html .='<p>'.__( "Sorry, no products or projects  matched your criteria.", "tecnibo") .'</p>';
        endif;
        wp_reset_query();
                                    
        $html   .='</div>';
        
        return $html;
    }
    
    public static function get_carousel_product_images( $product_id ) {
        
        $html= '';
    
        $featured_images = Tecnibo_Portfolio::get_featured_images( $product_id );
        if( count( $featured_images) > 0 ){ 
            $html .='<section class="product-photos">';
            foreach ($featured_images as $key => $value) { 
                $html .='<div><a class="element-carousel-product" href="#" title="" data-full="'.$value['full'].'"><img src="' . $value['thumb'] . '" /></a></div>';
                
            }
            $html .= '</section>';
        }
        
        return $html;
    }
    public static function get_pdf_link ( $product_id ){
        
        $html = '';
        $html = '<h2>'. __('Documents techniques:','tecnibo') .'</h2>';
        $pdf_file = get_post_meta( $product_id, '_pdf_file', true );
        $pdf_file_1 = get_post_meta( $product_id, '_pdf_file_1', true );
        $pdf_file_2 = get_post_meta( $product_id, '_pdf_file_2', true );
        
        if ( ! empty($pdf_file) ){
            $pdf_name =  self::get_pdf_name( $pdf_file['url'] ) ;
            $html .= '<a class="fiche-technique" target="_blank" href="' . $pdf_file['url'] .'"><i class="ionicon ion-android-download"></i> '. self::get_pdf_name($pdf_file['url']) .'</a>';
        }
        
        if ( ! empty($pdf_file_1) ){
            $pdf_name_1 = self::get_pdf_name( $pdf_file_1['url'] );
            $html .= '<a class="fiche-technique" target="_blank" href="' . $pdf_file_1['url'] .'"><i class="ionicon ion-android-download"></i> '. self::get_pdf_name($pdf_file_1['url']) .'</a>';
        }        
       
        if ( ! empty($pdf_file_2) ){
            $pdf_name_2 = self::get_pdf_name( $pdf_file_2['url'] );
            $html .= '<a class="fiche-technique" target="_blank" href="' . $pdf_file_2['url'] .'"><i class="ionicon ion-android-download"></i> '. self::get_pdf_name($pdf_file_2['url']) .'</a>';
        }        

        return $html;
        
        
    }
    public static function get_pdf_name ( $pdf_url ){
        
        return basename ( str_replace( site_url('/'), ABSPATH, esc_url( $pdf_url ) ) );
    }
    public static function get_team_members(){
        
        $html = '';
        
        $html .= '<div class="tecnibo-team">
                    <div class="row-team">';
        
        $the_slug = 'equipe';
        $args=array(
            'name'           => $the_slug,
            'post_type'      => 'tecnibo_pages',
            'post_status'    => 'publish',
            'posts_per_page' => 1
        );
        $equipe = get_posts( $args );
        $the_title = get_field('title_member', $equipe[0]->ID);
        $the_text = get_field('text_member', $equipe[0]->ID);
        $html .= '<div class="heading-title">
                    <h1>'. $the_title .' </h1>
                    <p>'. $the_text .'</p>
                  </div>';
        
        
        $offices_args = array(
            'orderby'       => 'term_id',
            'order'         => 'ASC',
            'hide_empty'    => true
            );
        $offices = get_terms('tecnibo_offices', $offices_args);

        $html .= '<div class="button-group filters-button-group">';
        $html .= ' <button class="button is-checked " data-filter="*"><i class="fas fa-list"></i>'.__('show all','tecnibo').'</button>';
        foreach ($offices as $office) {
            $html .= ' <button class="button" data-filter=".'.$office->slug.'">'.$office->name.'</button>';
        }
        $html .= '</div>';
        
        // CEO/COO
        
        
        $args =  array (
            'post_status' => array ( 'publish' ),
            'post_type' => 'tecnibo_member' ,
            'posts_per_page' => -1,
            'meta_key' => 'ceocoo',
            'meta_value' => array('ceo','coo', 'w'),
            'orderby' => 'meta_value title',
            'order' => 'ASC'
            );
            $query = new WP_Query($args);
            if ( $query->have_posts() ) :
                $html .= '<div class="grid-team">';
                while ( $query->have_posts() ) :
                    $query->the_post();
                    $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
                    $the_offices = get_the_terms( get_the_ID() , 'tecnibo_offices' ); 
                    if ( $the_offices){
                        $office = '';
                        foreach ($the_offices as $the_office ){
                            $office .= $the_office->slug . ' ';
                        }
                        $html .= '<div class="col-member '.$office.' ">';
                    
                    }  else{                  
                    $html .= '<div class="col-member ">';
                    }
                    $html .= '<div class="member-image" style="background-image:url('.$featured_img_url.')"></div>';

                    
                    $html .='<div class="team-title">
                                <h3>'.get_the_title() .'</h3>
                                <h5>'.get_the_excerpt().'</h5>
                            </div>';
                    $html .='<div class="social-container">';
                    if ( self::has_meta ( '_member_linkedin' , get_the_ID()) ){
                        $html .= '<a href="https://linkedin.com/in/'.get_post_meta( get_the_ID(), '_member_linkedin', true ).'"><span class="dashicons dashicons-linkedin"></span></a>';
                    }
                    if ( self::has_meta ( '_member_twitter' , get_the_ID()) ){
                        $html .= '<a href="https://twitter.com/'.get_post_meta( get_the_ID(), '_member_twitter', true ).'"><span class="dashicons dashicons-twitter"></span></a>';
                    }  
                    $html .= '</div>';
                    $html .= '</div>';// .col                    
                    
                endwhile;
                $html .='</div>';
            endif;
            wp_reset_query();
            $html .= '</div>';
            $html .= '</div>';
            
            return $html;
    }
    public static function get_Customers(){
        $html = '';
        
        $html .= '<section class="tecnibo-customer">
                    <div class="row-customer">';
        
        $the_slug = 'clients';
        $args=array(
            'name'           => $the_slug,
            'post_type'      => 'tecnibo_pages',
            'post_status'    => 'publish',
            'posts_per_page' => 1
        );
        $equipe = get_posts( $args );
        $the_title = get_field('title_member', $equipe[0]->ID);
        $the_text = get_field('text_member', $equipe[0]->ID);
        $html .='<div class="customer-populated">';
        $html .= '<h1>'. $the_title .' </h1>';
        $html .=  $the_text ;
        $html .= '</div>';
        
        // Outsourcing
	$search_results = new WP_Query( array( 
		'post_status' => 'publish',
                'post_type' => 'tecnibo_customer' ,
                'meta_key' => 'type_customer',
                'meta_value' => 'outsourcing',
		'ignore_sticky_posts' => 1,
		'posts_per_page' => -1, 
                'orderby' => 'title', 
                'order' => 'ASC'
	) );        
	if( $search_results->have_posts() ) :
            $html .= '<h2 class="related_products_projects"><span>'.__('Clients','tecnibo').'</span></h2>';
            $html .= '<section class="tecnibo-customsers tecnibo-row">';
            
            while( $search_results->have_posts() ) : $search_results->the_post();	
                
                $featured_img_url = get_the_post_thumbnail_url($search_results->post->ID);
                $html .= '<div class="customer-item">';
                $html .= '<img src="'.$featured_img_url.'" />';
                $html .= '<input type="hidden" value="'. $search_results->post->ID .'"/>';
                $html .= '</div>';
            endwhile;
            $html .= '</section>';
	endif;   
        wp_reset_query();     
        
        // End Users
	$search_results = new WP_Query( array( 
		'post_status' => 'publish',
                'post_type' => 'tecnibo_customer' ,
                'meta_key' => 'type_customer',
                'meta_value' => 'client',
		'ignore_sticky_posts' => 1,
		'posts_per_page' => -1, 
                'orderby' => 'title', 
                'order' => 'ASC'
	) );        
	if( $search_results->have_posts() ) :
            $html .= '<h2 class="related_products_projects"><span>'.__('Clients finaux','tecnibo').'</span></h2>';
            $html .= '<section class="tecnibo-customsers tecnibo-row">';
            
            while( $search_results->have_posts() ) : $search_results->the_post();	
                
                $featured_img_url = get_the_post_thumbnail_url($search_results->post->ID);
                $html .= '<div class="customer-item">';
                $html .= '<img src="'.$featured_img_url.'" />';
                $html .= '</div>';
            endwhile;
            $html .= '</section>';
	endif;   
        wp_reset_query();
        
        $html .= '</div>';
        $html .= '</div>';        
        
        return $html;
    }
}


