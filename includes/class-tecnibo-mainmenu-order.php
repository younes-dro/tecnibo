<?php

/**
 * Main Menu Order.
 * 
 * @author    Younes DRO
 * @copyright Copyright (c) 2020, Younes DRO
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Tecnibo_MainMenu_Order {
    
    public static function init() {
        
        add_submenu_page(
                'edit.php?post_type=tecnibo_product', 
                __('Main Menu Order','tecnibo'),
                __('Sort MainMenu','tecnibo'), 
                'manage_options',
                'mainmenu-order',
                array ( 'Tecnibo_MainMenu_Order','mainmenu_render' )
                );
    }


    function mainmenu_render() {
     ?>
    	<div class="wrap tecnibo-maimenu-order">
            <div id="icon-tecnibo"></div>
            <?php 
                if( $_POST['order-submit']){
                    self::update_mainmenu_order();
                }
            ?>
            <h1><?php _e('Main Menu Order','tecnico')?></h1>
            <form name="tecnibo-mainmenu-order" method="post" action="">
                <?php
                /* Nonce */
                    $nonce = wp_create_nonce( 'custom-taxonomy-order-ne-nonce' );
                    echo '<input type="hidden" id="custom-taxonomy-order-ne-nonce" name="custom-taxonomy-order-ne-nonce" value="' . $nonce . '" />';
                ?>
                <div id="poststuff" class="metabox-holder">
                    <div class="widget order-widget">
                        <h2 class="widget-top"><?php esc_html_e('Tecnibo Main Menu','tecnibo')?> | <small><?php esc_html_e('Order the terms by dragging and dropping them into the desired order.', 'tecnibo') ?></small></h2>
                        <div class="misc-pub-section">
                            
                                
                                <?php echo self::build_mainmenu(); ?>
                            
			</div>
                        <div class="misc-pub-section">
                            <div id="publishing-action">
                                <img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" id="custom-loading" style="display:none" alt="" />
                                <input type="submit" name="order-submit" id="order-submit" class="button-primary" value="<?php esc_html_e('Update Order', 'tecnibo') ?>" />
                                <input type="text" id="hidden-custom-order"    name="hidden-custom-order" />
                            </div>
                        </div>
                    </div>
                </div>
                
            </form>
        </div>
    <?php
        
    }
    
    public static function build_mainmenu(){
        $html = '';
        /* Check if the options exists , if not build the Query */
        $mainmenu_order = get_option('mainmenu_order');
        
        if( $mainmenu_order_){
            
        }else{
            $html .= self::build_mainmenu_from_db() ;
            //add_option('mainmenu_order',[1=>[2,3], 2=>[4,5]]);
        }
        
        return $html;
    }
    public static function build_mainmenu_from_option(){
        
    }
    public static function build_mainmenu_from_db(){
        $html = '';
        $parent_cats = Mega_Menu_Categories::get_parent_cat();
        
        foreach ($parent_cats as $parent_cat ) {
            $html .= '<div>'.$parent_cat->name.'</div>';
            $html .= '<ul id="parent_cat_'.$parent_cat->term_id.'" class="ui-sortable  custom-order-mainmenu">';
            $html .= self::get_final_products($parent_cat->term_id);
            $html .= self::get_sub_cat($parent_cat->term_id);
            $html .= '</ul>';
        }
        
        return $html;
        
    }
    public static function get_final_products( $cat_id ){
        $html  ='';
        $query = self::get_custom_query_product( $cat_id );
        if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
            $html .= '<li id="id_'.get_the_ID().'" data-parent="' . $cat_id . '" class="lineitem ui-sortable-handle depth-1">'.get_the_title().'</li>';
        endwhile;
        endif;
        wp_reset_query();
        
        return $html;
    }
    public static function get_sub_cat( $parent_id ){
        $sub_cats = self::get_custom_query_subcat($parent_id);    
        foreach ($sub_cats as $sub_cat) {        
            $html .= '<li id="id_'.$sub_cat->term_id.'" data-parent="' . $parent_id . '" class="lineitem ui-sortable-handle depth-1">'.$sub_cat->name.'</li>';
        }
        
        return $html;
    }
    public static function get_custom_query_product( $cat_id ){
        $args = array(
            'post_type' => 'tecnibo_product',
            'orderby' => 'title',
            'posts_per_page' => -1,
            'order' => 'ASC',
            'tax_query' => array(
                array('taxonomy' => 'product_category',
                    'field' => 'term_id',
                    'terms' => $cat_id 
                )
                ),
            'meta_key' => '_display_mainmenu',
            'meta_value' => 'yes'
            );  
        $query = new WP_Query($args);
        
        return $query;
    }
    public static function get_custom_query_subcat( $parent_id ){
        $terms = get_terms( array(
                            'taxonomy' => 'product_category',
                            'hide_empty' => false,
                            'parent' => $parent_id,
            ) );
        return $terms;         
    }
    
    public static function update_mainmenu_order(){
        /* Check Nonce */
	$verified = false;
	if ( isset($_POST['custom-taxonomy-order-ne-nonce']) ) {
		$verified = wp_verify_nonce( $_POST['custom-taxonomy-order-ne-nonce'], 'custom-taxonomy-order-ne-nonce' );
	}
	if ( $verified == false ) {
		// Nonce is invalid.
		echo '<div id="message" class="error fade notice is-dismissible"><p>' . esc_html__('The Nonce did not validate. Please try again.', 'tecnibo') . '</p></div>';
		return;
	}
        
        echo '<div id="message" class="updated fade notice is-dismissible"><p>'. esc_html__('Order updated successfully.', 'tecnibo').'</p></div>';
    }

    public static function load_css(){
	if ( isset($_GET['page']) ) {
		$pos_page = $_GET['page'];
		$pos_args = 'mainmenu-order';
		$pos = strpos($pos_page,$pos_args);
		if ( $pos === false ) {} else {
			wp_enqueue_style( 'mainmenuorder-css',  Tecnibo::get_plugin_url() . '/assets/tecnibo-mainmenu-order.css' , false, '1.0.0', 'screen' );
		}
	}        
    }
    public static function load_js(){
	if ( isset($_GET['page']) ) {
		$pos_page = $_GET['page'];
		$pos_args = 'mainmenu-order';
		$pos = strpos($pos_page,$pos_args);
		if ( $pos === false ) {} else {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'mainmenuorder-js', Tecnibo::get_plugin_url() . '/assets/tecnibo-mainmenu-order.js', 'jquery-ui-sortable', '1.0.0', true );
		}
	}        
    }
    
}