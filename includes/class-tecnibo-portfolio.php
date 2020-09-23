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
    

    public static function create_cpt_product(){

        /**
         * Create the Post Types 
         */
        $dro_post_type = new DRO_PostType( Tecnibo_Labels::get_posttype() );
        $dro_post_type->register_post_type();

        /**
         * Create the taxonomies
         */
        $dro_taxonomies = new DRO_Taxonomies( Tecnibo_Labels::get_taxonomies() );
        $dro_taxonomies->create_taxanomies();
             
    }
 
}
