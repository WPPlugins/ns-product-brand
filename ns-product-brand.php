<?php
/*
Plugin Name: NS Product brand for WooCommerce
Plugin URI: https://www.nsthemes.com/
Description: This plugin allow to add a Brand to Products
Version: 1.0.1
Author: NsThemes
Author URI: http://www.nsthemes.com
License: GNU General Public License v2.0
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! defined( 'PRODUCTBRAND_NS_PLUGIN_DIR' ) )
    define( 'PRODUCTBRAND_NS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'PRODUCTBRAND_NS_PLUGIN_DIR_URL' ) )
    define( 'PRODUCTBRAND_NS_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );



/* *** plugin options *** */
require_once( PRODUCTBRAND_NS_PLUGIN_DIR.'/ns-product-brand-option.php');

require_once( plugin_dir_path( __FILE__ ).'ns-admin-options/ns-admin-options-setup.php');


function ns_add_brand_init() {

	$labels = array(

    'name' => _x( 'Brands', 'taxonomy general name' ),

    'singular_name' => _x( 'Brand', 'taxonomy singular name' ),

    'search_items' =>  __( 'Search Brands' ),

    'popular_items' => __( 'Popular Brands' ),

    'all_items' => __( 'All Brands' ),

    'parent_item' => null,

    'parent_item_colon' => null,

    'edit_item' => __( 'Edit Brand' ),

    'update_item' => __( 'Update Brand' ),

    'add_new_item' => __( 'Add New Brand' ),

    'new_item_name' => __( 'New Brand Name' ),

    'separate_items_with_commas' => __( 'Separate brands with commas' ),

    'add_or_remove_items' => __( 'Add or remove brands' ),

    'choose_from_most_used' => __( 'Choose from the most used brands' ),

    'menu_name' => __( 'Brands' ),

  );

	// create a new taxonomy
	register_taxonomy(
		'brand',
		'product',
		array(
			'hierarchical' => false,

    'labels' => $labels,

    'show_ui' => true,

    'show_admin_column' => true,

    'update_count_callback' => '_update_post_term_count',

    'query_var' => true,

    'rewrite' => array( 'slug' => 'brand' ),

			
			)
		);
}
add_action( 'init', 'ns_add_brand_init' );

add_action( 'brand_add_form_fields', 'nspd_add_brand_image', 10, 2 );
function nspd_add_brand_image ( $taxonomy ) { ?>
   <div class="form-field term-group">
     <label for="brand-image-id"><?php _e('Image', 'hero-theme'); ?></label>
     <div style="width: 75px; height: 75px;">
     <img src="" id="ns_image_show" style="width: inherit; height: inherit;"></img>
     </div>
     <input type="hidden" id="brand-image-id" name="brand-image-id" class="custom_media_url" value="">
     <p>
       <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e( 'Add/Change Image', 'hero-theme' ); ?>" />
       <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e( 'Remove Image', 'hero-theme' ); ?>" />
    </p>
   </div>
 <?php
}

add_action( 'created_brand', 'nspd_save_brand_image', 10, 2 );
function nspd_save_brand_image ( $term_id, $tt_id ) {
   if( isset( $_POST['brand-image-id'] ) && '' !== $_POST['brand-image-id'] ){
     $image = sanitize_text_field($_POST['brand-image-id']);
     add_term_meta( $term_id, 'brand-image-id', $image, true );
   }
 }

add_action( 'brand_edit_form_fields', 'nspd_update_brand_image', 10, 2 );
 function nspd_update_brand_image ( $term, $taxonomy ) { ?>
   <tr class="form-field term-group-wrap">
     <th scope="row">
       <label for="brand-image-id"><?php _e( 'Image', 'hero-theme' ); ?></label>
     </th>
     <td>
       <?php $image_id = get_term_meta ( $term -> term_id, 'brand-image-id', true ); ?>
       <input type="hidden" id="brand-image-id" name="brand-image-id" value="<?php echo $image_id; ?>">
       <div id="brand-image-wrapper">
         <?php if ( $image_id ) { ?>
           <?php echo wp_get_attachment_image ( $image_id, 'thumbnail',false, array('id' => 'image-tax'));
         } ?>
       </div>
       <p>
         <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_button" name="ct_tax_media_button" value="<?php _e( 'Add/Change Image', 'hero-theme' ); ?>" />
         <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_remove" name="ct_tax_media_remove" value="<?php _e( 'Remove Image', 'hero-theme' ); ?>" />
       </p>
     </td>
   </tr>
 <?php
 }


add_action( 'edited_brand', 'nspd_updated_brand_image', 10, 2 );  
add_action( 'create_brand', 'nspd_updated_brand_image', 10, 2 );
function nspd_updated_brand_image( $term_id, $tt_id) {
	if( isset( $_POST['brand-image-id'] ) && '' !== $_POST['brand-image-id'] ){
     $image = sanitize_text_field($_POST['brand-image-id']);
     update_term_meta ( $term_id, 'brand-image-id', $image );
   } else {
     update_term_meta ( $term_id, 'brand-image-id', '' );
   }
 }

add_action( 'woocommerce_product_meta_end', 'nspd_prodbrand_list_attributes' );
function nspd_prodbrand_list_attributes() {
global $product;
global $post;
 
$attributes = wp_get_post_terms($post->ID,'brand');

 
if ( ! $attributes ) {
     
    return;
     
}


// Display the label followed by a clickable list of terms.
echo get_the_term_list( $post->ID, 'brand' , '<div class="attributes">Brands : ', ', ', '</div>' );
  
}

add_action( 'woocommerce_archive_description', 'nspd_woocommerce_category_image', 2 );
function nspd_woocommerce_category_image() {
    if ( is_product_taxonomy() ){
      global $wp_query;
      $cat = $wp_query->get_queried_object();
      $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'brand-image-id', true );
      $image = wp_get_attachment_url( $thumbnail_id );
      if ( $image ) {
        echo '<img src="' . $image . '" alt="" />';
    }
  }
}

add_filter("manage_edit-brand_columns", 'nspd_theme_columns'); 
 
function nspd_theme_columns($columns) {
   $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'name' => __('Name'),
        'brand_image' =>  __('Logo'),
        'description' => __('Description'),
        'slug' => __('Slug'),
        'posts' => __('Posts')
        );
    return $new_columns;
}


// Add to admin_init function
add_filter("manage_brand_custom_column", 'nspd_manage_theme_columns',10,3); 
 
function nspd_manage_theme_columns($value, $column_name, $brand_id) {
  $image_id = get_term_meta ( $brand_id, 'brand-image-id', true ); 
    if ( 'brand_image' == $column_name ) {
        $value = wp_get_attachment_image ( $image_id, array(75,75));
    }
    return $value;    
}