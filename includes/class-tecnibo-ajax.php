<?php

/**
 * 
 * 
 * @author Younés DRO <dro66_8@hotmail.fr>
 * @version 1.0.0
 */
class Tecnibo_Ajax {
    
    public static function Get_Projects(){
       
	$return = array();
	$search_results = new WP_Query( array( 
		's' => $_GET['q'],
		'post_status' => 'publish',
                'post_type' => 'tecnibo_project' ,
		'ignore_sticky_posts' => 1,
		'posts_per_page' => 50, 
                'orderby' => 'title', 
                'order' => 'ASC'
	) );
	if( $search_results->have_posts() ) :
		while( $search_results->have_posts() ) : $search_results->the_post();	
			// shorten the title a little
			$title = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
			$return[] = array( $search_results->post->ID, $title ); // array( Post ID, Post Title )
		endwhile;
	endif;
	echo json_encode( $return );
	
        die();        
    }

}
