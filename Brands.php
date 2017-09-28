<?php
/*
Plugin Name: Custom Brands
Plugin URI: http://mkhuda.com
Description: Plugin for Custom Post of some Brands
Version: 1.0
Author: Mkhuda
Author URI: -
License: GPLv2
*/

// Init Plugin Name
add_action( 'init', 'create_brands' );
function create_brands() {
    $labels = array(
  		'name'              => _x( 'Add New Brand', 'taxonomy general name', 'textdomain' ),
  		'singular_name'     => _x( 'Brand', 'taxonomy singular name', 'textdomain' ),
  		'search_items'      => __( 'Search Brands', 'textdomain' ),
  		'all_items'         => __( 'All Brands', 'textdomain' ),
  		'parent_item'       => __( 'Parent Brand', 'textdomain' ),
  		'parent_item_colon' => __( 'Parent Brand:', 'textdomain' ),
  		'edit_item'         => __( 'Edit Brand', 'textdomain' ),
  		'update_item'       => __( 'Update Brand', 'textdomain' ),
  		'add_new_item'      => __( 'Add New Brand', 'textdomain' ),
  		'new_item_name'     => __( 'New Brand Name', 'textdomain' ),
  		'menu_name'         => __( 'Brands', 'textdomain' ),
  	);

  	$args = array(
  		'hierarchical'      => true,
  		'labels'            => $labels,
  		'show_ui'           => true,
  		'show_admin_column' => true,
  		'query_var'         => true,
  		'rewrite'           => array( 'slug' => 'our-brand', 'with_front' => false ),
  	);

    register_taxonomy( 'brand', array( 'book' ), $args );

    register_post_type( 'brands',
        array(
            'labels' => array(
                'name' => 'Custom Brands',
                'singular_name' => 'Custom Brands',
                'all_items' => 'Products',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Product',
                'edit' => 'Edit',
                'edit_item' => 'Edit Product',
                'new_item' => 'New Product',
                'view' => 'View',
                'view_item' => 'View Product',
                'search_items' => 'Search Products',
                'not_found' => 'No Products found',
                'not_found_in_trash' => 'No Products found in Trash',
                'parent' => 'Parent Brand'
            ),

            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail' ),
            'taxonomies' => array('brand'),
            'menu_icon' => plugins_url( 'brand.png', __FILE__ ),
            'has_archive' => true,
            'rewrite' => array('slug' => '%brand%'),
        )
    );
    register_meta( 'term', '__term_meta_text', '___sanitize_term_meta_text' );
}

// SANITIZE DATA
function ___sanitize_term_meta_text ( $value ) {
    return sanitize_text_field ($value);
}
// GETTER (will be sanitized)
function ___get_term_meta_text( $term_id ) {
  $value = get_term_meta( $term_id, '__term_meta_text', true );
  $value = ___sanitize_term_meta_text( $value );
  return $value;
}

function wpa_show_permalinks( $post_link, $post ){
    if ( is_object( $post ) && $post->post_type == 'brands' ){
        $terms = wp_get_object_terms( $post->ID, 'brand' );
        if( $terms ){
            return str_replace( '%brand%' , $terms[0]->slug , $post_link );
        }
    }
    return $post_link;
}
add_filter( 'post_type_link', 'wpa_show_permalinks', 1, 2 );

add_action( 'admin_menu', 'myprefix_adjust_the_wp_menu', 999 );
function myprefix_adjust_the_wp_menu() {
  // $page = remove_submenu_page( 'edit.php', 'post-new.php' );
  //or for custom post type 'myposttype'.
  $page = remove_submenu_page( 'edit.php?post_type=brands', 'post-new.php?post_type=brands' );
}

// ADD FIELD TO CATEGORY TERM PAGE
add_action( 'brand_add_form_fields', '___add_form_field_term_meta_text' );
function ___add_form_field_term_meta_text() { ?>
    <?php wp_nonce_field( basename( __FILE__ ), 'term_meta_text_nonce' ); ?>
    <div class="form-field term-meta-text-wrap">
        <label for="term-meta-text"><?php _e( 'Brand Title', 'text_domain' ); ?></label>
        <input type="text" name="term_meta_text" id="term-meta-text" value="" class="term-meta-text-field" />
    </div>
<?php }

// ADD FIELD TO CATEGORY EDIT PAGE
add_action( 'brand_edit_form_fields', '___edit_form_field_term_meta_text' );
function ___edit_form_field_term_meta_text( $term ) {
    $value  = ___get_term_meta_text( $term->term_id );
    if ( ! $value )
        $value = ""; ?>

    <tr class="form-field term-meta-text-wrap">
        <th scope="row"><label for="term-meta-text"><?php _e( 'TERM META TEXT', 'text_domain' ); ?></label></th>
        <td>
            <?php wp_nonce_field( basename( __FILE__ ), 'term_meta_text_nonce' ); ?>
            <input type="text" name="term_meta_text" id="term-meta-text" value="<?php echo esc_attr( $value ); ?>" class="term-meta-text-field"  />
        </td>
    </tr>
<?php }

// SAVE TERM META (on term edit & create)
add_action( 'edit_brand',   '___save_term_meta_text' );
add_action( 'create_brand', '___save_term_meta_text' );
function ___save_term_meta_text( $term_id ) {
    // verify the nonce --- remove if you don't care
    if ( ! isset( $_POST['term_meta_text_nonce'] ) || ! wp_verify_nonce( $_POST['term_meta_text_nonce'], basename( __FILE__ ) ) )
        return;
    $old_value  = ___get_term_meta_text( $term_id );
    $new_value = isset( $_POST['term_meta_text'] ) ? ___sanitize_term_meta_text ( $_POST['term_meta_text'] ) : '';
    if ( $old_value && '' === $new_value )
        delete_term_meta( $term_id, '__term_meta_text' );
    else if ( $old_value !== $new_value )
        update_term_meta( $term_id, '__term_meta_text', $new_value );
}


// Enqueue Script
function brand_uploadscript() {
    /*
     * I recommend to add additional conditions just to not to load the scipts on each page
     * like:
     * if ( !in_array('post-new.php','post.php') ) return;
     */
    if ( ! did_action( 'wp_enqueue_media' ) ) {
        wp_enqueue_media();
    }

    wp_enqueue_script( 'uploadscript', plugin_dir_url( __FILE__ ) . 'brands.js', array('jquery'), null, false );
}

add_action( 'admin_enqueue_scripts', 'brand_uploadscript' );

// Init for Metabox
add_action( 'admin_init', 'brand_meta_box' );
function brand_meta_box() {
    add_meta_box( 'brand_brands_meta_box',
        'Brands Details',
        'display_brand_meta_box',
        'brands', 'normal', 'high'
    );
}
function display_brand_meta_box( $brand ) {
    $brand_name = esc_html( get_post_meta( $brand->ID, 'brand_name', true ) );
    $brand_title = esc_html( get_post_meta( $brand->ID, 'brand_title', true ) );
    $brand_tagline = esc_html( get_post_meta( $brand->ID, 'brand_tagline', true ) );
    $brand_header = get_post_meta( $brand->ID, 'brand_header', true );
    $button_brand_header = '<a href="#" class="brand_upload_image_button button">Upload Brand Header</a>';
    $display= "none";
    if( $image_attributes = wp_get_attachment_image_src( $brand_header, 'full' ) ) {

        $button_brand_header = '<a href="#" class="brand_upload_image_button"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" /></a>';
        $display = 'inline-block';

    }
    ?>
    <table>
        <tr>
            <td style="width: 25%">Brand Name</td>
            <td><input type="text" size="100" name="brand_name" value="<?php echo $brand_name; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 25% ">Brand Title</td>
            <td><input type="text" size="80" name="brand_title" value="<?php echo $brand_title; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 25% ">Brand Tagline</td>
            <td><input type="text" size="80" name="brand_tagline" value="<?php echo $brand_tagline; ?>" /></td>
        </tr>
        <tr>
          <td style="width: 25% ">Brand Header Image</td>
          <td>
            <?php echo $button_brand_header; ?>
            <input type="hidden" name="brand_header" id="brand_header" value="<?php echo $brand_header; ?>" />
            <a href="#" class="brand_remove_image_button" style="display: <?php echo $display; ?>;">Remove Brand Header</a>
          </td>
        </tr>
    </table>
<?php
}

add_action( 'save_post', 'add_brand_fields', 10, 2 );
function add_brand_fields( $brand_id, $brand ) {
    // Check post type for movie reviews
    if ( $brand->post_type == 'brands' ) {
        // Store data in post meta table if present in post data
        if ( isset( $_POST['brand_name'] ) && $_POST['brand_name'] != '' ) {
            update_post_meta( $brand_id, 'brand_name', $_POST['brand_title'] );
        }
        if ( isset( $_POST['brand_title'] ) && $_POST['brand_title'] != '' ) {
            update_post_meta( $brand_id, 'brand_title', $_POST['brand_title'] );
        }
        if ( isset( $_POST['brand_tagline'] ) && $_POST['brand_tagline'] != '' ) {
            update_post_meta( $brand_id, 'brand_tagline', $_POST['brand_tagline'] );
        }
        if ( isset( $_POST['brand_header'] ) && $_POST['brand_header'] != '' ) {
            update_post_meta( $brand_id, 'brand_header', $_POST['brand_header'] );
        }
    }
}
?>
