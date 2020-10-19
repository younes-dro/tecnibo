<?php

/**
 * Shortcode .
 * 
 * @author    Younes DRO
 * @copyright Copyright (c) 2020, Younes DRO
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


class Tecnibo_ShortCode {

    public static function add_shortcode(){
        
        add_shortcode ( 'tecnibo_job_pdf' , array ( 'Tecnibo_ShortCode' , 'job_pdf_link' ) );
    }
    public static function job_pdf_link( $atts ){
        
       extract( $atts );
       $pdf = get_field( 'pdf' ); 
       $html = '';
       
       if ( $pdf  ){
           
           
           $html .= '<a style="font-size:20px" href="' . $pdf['url'] . '"><i class="far fa-file-pdf"></i> '.__('Job Document', 'tecnibo').'</a>';
           
       }
       
       return $html;
    }
}
