<?php

/**
 * 
 * 
 */
class Tecnibo_Helper {

    function __construct() {
        
        add_filter('gettext', array( $this, 'change_excerpt_box_label' ) , 10 , 2 );
        add_filter('enter_title_here', array ( $this , 'change_title_place_holder' ) , 20 , 2 );   
    }
    public function  change_title_place_holder( $title , $post ){

        if( $post->post_type == 'tecnibo_member' ){
            $my_title = "Name Member";
            return $my_title;
        }

        return $title;

    }
    public  function change_excerpt_box_label( $translation, $original ) {

        if ('Excerpt' == $original) {
            return __('Role', 'tecnibo');
        } else {
            $pos = strpos($original, 'Excerpts are optional hand-crafted summaries of your');

            if ($pos !== false) {
                return __('The role of the team member', 'tecnibo'); //Change the default text you see below the box with link to learn more...
            }
        }
        return $translation;
    }

}
