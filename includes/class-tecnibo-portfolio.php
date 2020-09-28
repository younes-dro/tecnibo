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
        add_meta_box( 'project_metabox', __( 'Select Tecnibo products that are used by this project', 'tecnibo' ), array( $this , 'project_metabox' ), 'tecnibo_project', 'normal', 'default' );
        add_meta_box( 'project_details_metabox', __( 'Project Details', 'tecnibo' ), array( $this , 'project_details_metabox' ), 'tecnibo_project', 'normal', 'default' );            
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
        $has_projects = false;
        if(count($appended_projects) ){
            $has_projects = true;
        }
        
        $html = '';
	$html .= '<p><label for="tecnibo_projects">'.__( 'Projects:','tecnibo').'</label><br />';
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
    public function project_metabox( $post_object ){
        
	$appended_products = get_post_meta( $post_object->ID, '_related_products',false );
        
	/*
	 * Select Products with AJAX search
	 */
        $html = '';
	$html .= '<p><label for="tecnibo_products">'.__( 'Products:','tecnibo').'</label><br />';
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
    public static function has_meta( $meta, $post_id , $single = true){
        
        if ( get_post_meta ( $post_id , $meta, $single) )
                return true;
        
        return false;
    }

    public static function get_product_meta ( $meta_title , $meta , $post_id , $video = false ){
        $project_meta = get_post_meta( $post_id , $meta ,true);
        if ( $video ) 
            return Tecnibo_Portfolio::get_video( $project_meta );
        
            return '<p><strong>'.$meta_title.':&nbsp;</strong><em>'.$project_meta.'</em></p>';
        
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
    public static function get_related_products_projects ( $meta , $post_id  , $post_type  ){
        
        $posts = get_post_meta( $post_id , $meta );
//        var_dump($posts);
        $related = ( $post_type == 'tecnibo_product') ? __( 'Related Products','tecnibo' ) : __( 'Related Projects' , 'tecnibo' );
        $search_results = new WP_Query( array (
            'post_type' => $post_type,
            'post__in' => $posts[0]
        ) );
        $html .= '<h2 class="related_products_projects"><span>' . $related . '</span></h2>';
        $html .= '<div class="items">';
       
		while( $search_results->have_posts() ) : $search_results->the_post();	
			$post_thumbnail_url = get_the_post_thumbnail_url( $search_results->post->ID, 'full' ); 
                        
			$html .= '<a  href="'.get_the_permalink().'" 
                                class="" 
                                rel="group" data-id="'.get_the_ID().'" 
                                data-slug="">
                                <img alt="DOLCE" src="'.$post_thumbnail_url .'">
                            <span class="hover middleParent" style="line-height: 213px;">
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
    
    public static function get_grid_products_projects( $args ){
        
        $html   = '<div class="items">';
        $query = new WP_Query($args);
        if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
            $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
            $html .='<a 
                    href="'.get_the_permalink().'" 
                    class="" 
                    rel="group" data-id="'.get_the_ID().'" data-slug="<?php //?>">
                    <img alt="" src="'. $featured_img_url .'">
                    <span class="hover middleParent" style="line-height: 213px;">
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
}


                            