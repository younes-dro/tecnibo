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
            <h1><?php _e('Main Menu Order','tecnibo')?></h1>
                <div id="poststuff" class="metabox-holder">
                    <div class="widget order-widget">
                        <div class="misc-pub-section">
                            <?php
                                
                            /* Display The list item sortable */
                            if ( isset($_POST['select-category'])){
                                echo self::build_list_sortable($_POST['tecnibo-parent-cat']);
                            }
                            /* Submit */
                            if( isset($_POST['order-submit'])){
                                $new_order = $_POST['hidden-custom-order'];
                                $parent_ID = $_POST['hidden-parent-cat'];
                                $IDs = explode(",", $new_order);
                                $arr = [$parent_ID => $IDs];
                                self::update_mainmenu_order( $parent_ID , $arr );
                                echo self::build_list_sortable($parent_ID);    
                            }
                            ?>
			</div>
                        <div class="misc-pub-section">
                            <?php
                            /* Select menu parent categories form */
                            echo self::parent_cat_menu_select();
                            ?>
                        </div>

                    </div>
                </div>
        </div>
    <?php
    }
    /**
     * Return form select menu categorie 
     * 
     * @return string
     */
    public static function parent_cat_menu_select(){
        $html = '';
        $html .= '<form name="submit-parent-cat" id="submit-parent-cat" action="" method="post">';
        $parent_cats = Mega_Menu_Categories::get_parent_cat();
        $html .='<select id="tecnibo-parent-cat" name="tecnibo-parent-cat">';
        $html .= '<option value="0">'.__('Select a parent category','tecnibo').'</option>';
        foreach ($parent_cats as $parent_cat ) {
            $html .= '<option value="'.$parent_cat->term_id.'">'.$parent_cat->name.'</option>';
        }
        $html .= '</select>';
        $html .= '<input id="select-category" name="select-category" type="submit" class="button" value="'.__('Select Category to order','tecnibo').'">';
        $html .= '</form>';
        
        return $html;        
    }
    /**
     * Return  sortable item
     * 
     * @param init $parent_cat
     * 
     * @return string
     */
    public static function build_list_sortable( $parent_cat){
        
        $html .= '';
        
        /* Check option */
        
        if( ! get_option( 'mainmenu_'.$parent_cat ) ){
            
            $term = get_term( $parent_cat );
            $html .='<form name="custom-order-form" method="post" action="">';
            $html .= '<h1>'.$term->name.'</h1>';
            $html .= '<ul id="parent_cat_'.$term->term_id.'" class="ui-sortable  custom-order-mainmenu">';
            $html .= self::get_final_products($term->term_id);
            $html .= self::get_sub_cat($term->term_id);
            $html .= '</ul>';
            $html .= '<div class="misc-pub-section">';
            $html .= '<div id="publishing-action">';
            $html .= '<img src="'.esc_url( admin_url( 'images/wpspin_light.gif' ) ).'" id="custom-loading" style="display:none" alt="" />';
            $html .= '<input type="submit" disabled name="order-submit" id="order-submit" class="button-primary" value="'.__('Update Order', 'tecnibo').'" />';
            $html .= '<input type="hidden" id="hidden-custom-order"    name="hidden-custom-order" />';
            $html .= '<input type="hidden" id="hidden-parent-cat"    name="hidden-parent-cat" value="'.$term->term_id.'" />';
            $html .= '</div>';
            
            $html .= '</div>';
            $html .= '</form>';

            
        }else{
            $menu_items = get_option( 'mainmenu_'.$parent_cat );
            $term = get_term( $parent_cat );
            $html .='<form name="custom-order-form" method="post" action="">';
            $html .= '<h1>'.$term->name.'</h1>';
            $html .= '<ul id="parent_cat_'.$term->term_id.'" class="ui-sortable  custom-order-mainmenu">';
            $html .= self::get_items_from_option( $menu_items[$parent_cat] , $parent_cat);
            $html .= '</ul>';
            $html .= '<div class="misc-pub-section">';
            $html .= '<div id="publishing-action">';
            $html .= '<img src="'.esc_url( admin_url( 'images/wpspin_light.gif' ) ).'" id="custom-loading" style="display:none" alt="" />';
            $html .= '<input type="submit" disabled name="order-submit" id="order-submit" class="button-primary" value="'.__('Update Order', 'tecnibo').'" />';
            $html .= '<input type="hidden" id="hidden-custom-order"    name="hidden-custom-order" />';
            $html .= '<input type="hidden" id="hidden-parent-cat"    name="hidden-parent-cat" value="'.$term->term_id.'" />';
            $html .= '</div>';
            
            $html .= '</div>';
            $html .= '</form>';            
            
        }
        
        return $html;
    }

    public static function get_final_products( $cat_id ){
        $html  ='';
        $query = self::get_custom_query_product( $cat_id );
        if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
            $html .= '<li id="p_'.get_the_ID().'" data-parent="' . $cat_id . '" class="lineitem ui-sortable-handle depth-1">'.get_the_title().'</li>';
        endwhile;
        endif;
        wp_reset_query();
        
        return $html;
    }
    public static function get_sub_cat( $parent_id ){
        $sub_cats = self::get_custom_query_subcat($parent_id);    
        foreach ($sub_cats as $sub_cat) {        
            $html .= '<li id="c_'.$sub_cat->term_id.'" data-parent="' . $parent_id . '" class="lineitem ui-sortable-handle depth-1">'.$sub_cat->name.'</li>';
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
    /**
     * 
     * @param type array
     */
    public static function get_items_from_option( $items , $parent_id){
        $html = '';        
        $current_products = self::get_current_products( $items,  $parent_id );
//        update_option('mainmenu_');
//        echo '<pre>';
//        var_dump($items);
//        echo '</pre>';
//        echo '<pre>current';
//        var_dump($current_products);
//        echo '</pre>';        
        foreach ($current_products as $value) {
           $item_type = explode('_', $value);
           if($item_type[0] == 'p'){
               $the_product = get_post ( $item_type[1] );
               $html .= '<li id="p_'.$the_product->ID.'" data-parent="' . $parent_id. '" class="lineitem ui-sortable-handle depth-1">'.$the_product->post_title.'</li>';              
           }elseif( $item_type[0] == 'c'){
               $sub_cat = get_term($item_type[1]);
               $html .= '<li id="c_'.$sub_cat->term_id.'" data-parent="' . $parent_id . '" class="lineitem ui-sortable-handle depth-1">'.$sub_cat->name.'</li>';
           }
        }
        $html .= self::new_product_not_in_option($items , $parent_id);
        $html .= self::new_cat_not_in_option($items , $parent_id);
        
        return $html;
    }
    
    public static function new_product_not_in_option( $items , $parent_id ){
        $html = '';
        $t = $items;
        foreach ($t as &$p){
            $p= str_replace('p_', '', $p);
        }
        $new_products = get_posts(array(
            'post_type' => 'tecnibo_product',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'order' => 'ASC',
            'post__not_in' => $t,
            'tax_query' => array(
                array('taxonomy' => 'product_category',
                    'field' => 'term_id',
                    'terms' => $parent_id
                )
                ),
            'meta_key' => '_display_mainmenu',
            'meta_value' => 'yes'
            ) );
        foreach ($new_products as $new_product){
            
             $html .= '<li id="p_'.$new_product.'" data-parent="' . $parent_id. '" class="lineitem ui-sortable-handle depth-1">'.get_the_title($new_product).'</li>';
        }
        
        return $html;
    }
    public static function new_cat_not_in_option($items , $parent_id){
        $html = '';
        $new_subcats = self::get_custom_query_subcat($parent_id);
        foreach ($new_subcats as $new_subcat) {
            if( ! in_array( 'c_'.$new_subcat->term_id, $items ) ){
                $html .= '<li id="c_'.$new_subcat->term_id.'" data-parent="' . $parent_id . '" class="lineitem ui-sortable-handle depth-1">'.$new_subcat->name.'</li>';                
            }
        }
        
        return $html;
    }
    public static function get_current_products( $items, $parent_id ){

        /* Get the ids of the actual produts */
        $current_products = get_posts(array(
            'post_type' => 'tecnibo_product',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'order' => 'ASC',
            'tax_query' => array(
                array('taxonomy' => 'product_category',
                    'field' => 'term_id',
                    'terms' => $parent_id
                )
                ),
            'meta_key' => '_display_mainmenu',
            'meta_value' => 'yes'
            ) );
        $current_subcats = self::get_custom_query_subcat($parent_id);
        $subcats = array();
        foreach ($current_subcats as $current_subcat) {
            $subcats[] = 'c_'.$current_subcat->term_id;
        }
        
//        echo '<pre>';
//        var_dump($subcats);
//        echo '</pre>';
        
        $t = $current_products;
        foreach ($t as &$p){
            $p= 'p_'.$p;
        }
        $c = array_merge($subcats, $t);
        /* Comapre */
        $new_arr = array();
        foreach ($items as $item ) {
            if(in_array($item, $c)){
                $new_arr[] = $item;
            }
        }
        
        return $new_arr;
        
    }

    public static function update_mainmenu_order( $parent_ID, $arr ){
        /* Check Nonce */

        if( update_option( 'mainmenu_'.$parent_ID, $arr ) ) {
            echo '<div id="message" class="updated fade notice is-dismissible"><p>'. esc_html__('Order updated successfully.', 'tecnibo').'</p></div>';
        }else{
            echo '<div id="message" class="error fade notice is-dismissible"><p>' . esc_html__('Order updated failed. Please try again.', 'tecnibo') . '</p></div>';
        }
        
    }
       
    public static function hook_create_term( $term_id, $tt_id ){
        $parent = self::get_parent_term_id( $term_id );
        if ( $parent != 0){
            $mainmenu = 'mainmenu_'.$parent;
            $option_mainmenu = get_option( $mainmenu );
            if( $option_mainmenu ){   
                $option_mainmenu[$parent][] = 'c_'.$term_id;
                update_option( $mainmenu , $option_mainmenu);
            }
        }
        
    }
    public static function hook_delete_term( $tt_id ){
        $parent = self::get_parent_term_id( $tt_id );
        if ( $parent != 0){
            $mainmenu = 'mainmenu_'.$parent;
            $option_mainmenu = get_option( $mainmenu );
            if( in_array( 'c_'.$tt_id, $option_mainmenu[$parent] ) ){
                $option_mainmenu [$parent] = array_diff( $option_mainmenu[$parent], ['c_'.$tt_id] );
                update_option( $mainmenu , $option_mainmenu);
            }
        }        
    }
    public static function hook_create_product( $ID ) {
        $display_mainmenu = get_post_meta( $ID ,'_display_mainmenu');
        if( $display_mainmenu[0] == "yes" ){
            $parent = get_the_terms( $ID, 'product_category' );
            $mainmenu = 'mainmenu_'.$parent[0]->term_id;
            $option_mainmenu = get_option( $mainmenu );
             if( ! in_array( 'p_'.$ID, $option_mainmenu[$parent[0]->term_id] ) ){
                if( $option_mainmenu ){   
                    $option_mainmenu[$parent[0]->term_id][] = 'p_'.$ID;
                    update_option( $mainmenu , $option_mainmenu );
                }           
             }
           
        }
        
    }
    public static function hook_trashed_product ( $ID ){
        $display_mainmenu = get_post_meta( $ID ,'_display_mainmenu');
        if( $display_mainmenu[0] == "yes" ){
            $parent = get_the_terms( $ID, 'product_category' );
            $mainmenu = 'mainmenu_'.$parent[0]->term_id;
            $option_mainmenu = get_option( $mainmenu );
            if( $option_mainmenu ){  
                if( in_array( 'p_'.$ID, $option_mainmenu[$parent[0]->term_id] ) ){
                    $option_mainmenu [$parent[0]->term_id] = array_diff( $option_mainmenu[$parent[0]->term_id], ['p_'.$ID] );
                    update_option( $mainmenu , $option_mainmenu);
                }
            }
        }
    }
    public static function get_parent_term_id ( $term_id ){
        $term = get_term( $term_id );
        
        return $term->parent;
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