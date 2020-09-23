<?php

/**
 *  Load the options for  : Custom Post Type , Taxanomies 
 * 
 * @author Younes DRO<dro66_8@hotmail.fr>
 * @todo To use a YAML file 
 */
class Tecnibo_Labels {

    public static function get_posttype() {


        $labels = array(
            'name' => __('Products', 'tecnibo'),
            'singular_name' => __('Product', 'tecnibo'),
            'add_new' => __('Add New', 'tecnibo'),
            'add_new_item' => __('Add New Product', 'tecnibo'),
            'edit_item' => __('Edit Product', 'tecnibo'),
            'new_item' => __('New Product', 'tecnibo'),
            'all_items' => __('All Products', 'tecnibo'),
            'view_item' => __('View Product', 'tecnibo'),
            'search_items' => __('Search Product', 'tecnibo'),
            'not_found' => __('No Products found', 'tecnibo'),
            'not_found_in_trash' => __('No Products found in the Trash'),
            'parent_item_colon' => '',
            'menu_name' => 'Tecnibo'
        );
        $args = array(
            'labels' => $labels,
            'description' => __('Add Products for Tecnibo Web site', 'tecnibo'),
            'public' => true,
            'menu_position' => 5,
            'menu_icon' => dirname(plugin_dir_url(__FILE__)) . '/assets/images/tecnibo-logo.png',
            'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
            'has_archive' => true,
            'rewrite' => array(
                'slug' => 'product'
            )
        );

        $posttypes['product'] = array(
            'posttype_options' => $args
        );
        
        return $posttypes;
    }

    public static function get_taxonomies() {

        $taxonomies = array();

        $product_category = array(
            'name' => __('Product Category', 'tecnibo'),
            'singular_name' => __('Product Categoryt', 'tecnibo'),
            'search_items' => __('Search Product Category', 'tecnibo'),
            'all_items' => __('All Categories ', 'tecnibo'),
            'parent_item' => __('Parent Product Category', 'tecnibo'),
            'parent_item_colon' => __('Parent Product Category:', 'tecnibo'),
            'edit_item' => __('Edit Product Category', 'tecnibo'),
            'update_item' => __('Update Product Category', 'tecnibo'),
            'add_new_item' => __('Add New Product Category', 'tecnibo'),
            'new_item_name' => __('New Product Category', 'tecnibo'),
            'menu_name' => __('Product Category', 'tecnibo'),
        );

        $taxonomies['product_category'] = array(
            'post_types' => array('product'), // ID post type
            'description' => 'Product Category',
            'options' => array(
                'labels' => $product_category,
                'hierarchical' => true,
                'rewrite' => array('slug' => 'tecnibo-product-category', 'with_front' => false)
            ),
        );
        
        return $taxonomies;
    }

}
