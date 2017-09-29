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
  		'name'              => _x( 'Brand', 'taxonomy general name', 'textdomain' ),
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

    register_taxonomy( 'brand', array(), $args );

    register_post_type( 'products',
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
    register_meta( 'meta_text', '__term_meta_text', '___sanitize_term_meta_text' );
    register_meta( 'meta_brand_image', '__term_brand_image', '___sanitize_term_meta_text' );
    register_meta( 'meta_brand_logo', '__term_brand_logo', '___sanitize_term_meta_text' );
    register_meta( 'meta_brand_tagline', '__term_brand_tagline', '___sanitize_term_meta_text' );
}


// Enqueue Script
add_action( 'admin_enqueue_scripts', 'brand_uploadscript' );
function brand_uploadscript() {
    /*
     * I recommend to add additional conditions just to not to load the scipts on each page
     * like:
     * if ( !in_array('post-new.php','post.php') ) return;
     */
    if ( ! did_action( 'wp_enqueue_media' ) ) {
        wp_enqueue_media();
    }

    wp_enqueue_script( 'productuploadscript', plugin_dir_url( __FILE__ ) . 'brands.js', array('jquery'), null, false );
}

// SANITIZE DATA
function ___sanitize_term_meta_text ( $value ) {
    return sanitize_text_field ($value);
}

// GETTER (will be sanitized)
function ___get_term_meta_text( $term_id ) {
  $value = get_term_meta( $term_id, '__term_meta_text', true );
  return $value;
}

function ___get_term_brand_image( $term_id ) {
  $value = get_term_meta( $term_id, '__term_brand_image', true );
  return $value;
}

function ___get_term_brand_logo( $term_id ) {
  $value = get_term_meta( $term_id, '__term_brand_logo', true );
  return $value;
}

function ___get_term_brand_tagline( $term_id ) {
  $value = get_term_meta( $term_id, '__term_brand_tagline', true );
  return $value;
}

function wpa_show_permalinks( $post_link, $post ){
    if ( is_object( $post ) && $post->post_type == 'products' ){
        $terms = wp_get_object_terms( $post->ID, 'brand' );
        if( $terms ){
            return str_replace( '%brand%' , $terms[0]->slug , $post_link );
        }
    }
    return $post_link;
}
add_filter( 'post_type_link', 'wpa_show_permalinks', 1, 2 );

// Hide Add New from Admin Leftmenu
add_action( 'admin_menu', 'myprefix_adjust_the_wp_menu', 999 );
function myprefix_adjust_the_wp_menu() {
  // $page = remove_submenu_page( 'edit.php', 'post-new.php' );
  //or for custom post type 'myposttype'.
  $page = remove_submenu_page( 'edit.php?post_type=products', 'post-new.php?post_type=products' );
}

// ADD FIELD TO CATEGORY EDIT PAGE
add_action( 'brand_add_form_fields', '___edit_form_field_term_meta_text' );
function ___edit_form_field_term_meta_text( $term ) {
    $brand_title  = ___get_term_meta_text( $term->term_id );
    $brand_image = ___get_term_brand_image( $term->term_id );
    $brand_logo = ___get_term_brand_logo( $term->term_id );
    $brand_tagline = ___get_term_brand_tagline( $term->term_id );
    ?>

    <?php
    // wp_editor(html_entity_decode($term->description), 'description', array('media_buttons' => true));
    ?>

    <script>
    // jQuery(window).ready(function(){
    //   jQuery('label[for=tag-description]').parent().remove();
    // });
    </script>

    <div class="form-field term-meta-text-wrap">
        <label for="term-meta-text"><?php _e( 'Brand Title', 'text_domain' ); ?></label>
        <p>
            <?php wp_nonce_field( basename( __FILE__ ), 'term_meta_text_nonce' ); ?>
            <input type="text" name="term_meta_text" id="term-meta-text" value="<?php echo esc_attr( $brand_title ); ?>" class="term-meta-text-field"  />
        </p>
    </div>
    <div class="form-field term-meta-text-wrap">
      <label for="term-meta-text"><?php _e( 'Brand Header', 'text_domain' ); ?></label>
      <p>
        <input type="text" name="term_brand_header" id="term-meta-text" value="<?php echo esc_attr( $brand_image ); ?>" class="term-meta-text-field"  />
      </p>
    </div>
    <div class="form-field term-meta-text-wrap">
      <label for="term-meta-text"><?php _e( 'Brand Logo', 'text_domain' ); ?></label>
      <p>
        <input type="text" name="term_brand_logo" id="term-meta-text" value="<?php echo esc_attr( $brand_logo ); ?>" class="term-meta-text-field"  />
      </p>
    </div>
    <div class="form-field term-meta-text-wrap">
      <label for="term-meta-text"><?php _e( 'Brand Tagline', 'text_domain' ); ?></label>
      <p>
        <textarea type="text" name="term_brand_tagline" id="term-meta-text" value="<?php echo esc_attr( $brand_tagline ); ?>" class="term-meta-text-field" row="20"><?php echo esc_attr( $brand_tagline ); ?></textarea>
      </p>
    </div>
<?php }

add_action("brand_edit_form_fields", 'edit_form_fields_example', 10, 2);
function edit_form_fields_example($term, $taxonomy){
    ?>
    <tr valign="top">
        <th scope="row">Description</th>
        <td>
            <?php wp_editor(html_entity_decode($term->description), 'description', array('media_buttons' => true)); ?>
            <script>
                jQuery(window).ready(function(){
                    jQuery('label[for=description]').parent().parent().remove();
                    jQuery('label[for=parent]').parent().parent().remove();
                });
            </script>
        </td>
    </tr>
    <?php
    $brand_title  = ___get_term_meta_text( $term->term_id );
    $brand_header = ___get_term_brand_image( $term->term_id );
    $brand_logo = ___get_term_brand_logo( $term->term_id );
    $brand_tagline = ___get_term_brand_tagline( $term->term_id );
    ?>

    <tr class="form-field term-meta-text-wrap">
        <th><label for="term-meta-text"><?php _e( 'Brand Title', 'text_domain' ); ?></label></th>
        <td>
            <?php
              wp_nonce_field( basename( __FILE__ ), 'term_meta_text_nonce' );
            ?>
            <input type="text" name="term_meta_text" id="term-meta-text" value="<?php echo esc_attr( $brand_title ); ?>" class="term-meta-text-field"  />
        </td>
    </tr>
    <tr class="form-field term-meta-text-wrap">
      <th><label for="term-meta-text"><?php _e( 'Brand Header', 'text_domain' ); ?></label></th>
      <td>
        <input type="text" name="term_brand_header" id="term-meta-text" value="<?php echo esc_attr( $brand_header ); ?>" class="term-meta-text-field"  />
      </td>
    </tr>
    <tr class="form-field term-meta-text-wrap">
      <th><label for="term-meta-text"><?php _e( 'Brand Logo', 'text_domain' ); ?></label></th>
      <td>
        <input type="text" name="term_brand_logo" id="term-meta-text" value="<?php echo esc_attr( $brand_logo ); ?>" class="term-meta-text-field"  />
      </td>
    </tr>
    <tr class="form-field term-meta-text-wrap">
      <th><label for="term-meta-text"><?php _e( 'Brand Tagline', 'text_domain' ); ?></label></th>
      <td>
        <textarea type="text" name="term_brand_tagline" id="term-meta-text" value="<?php echo esc_attr( $brand_tagline ); ?>" class="term-meta-text-field" row="20"><?php echo esc_attr( $brand_tagline ); ?></textarea>
      </td>
    </tr>
    <?php
}


// SAVE TERM META (on term edit & create)
add_action( 'edit_brand',   '___save_term_meta_text' );
add_action( 'create_brand', '___save_term_meta_text' );
function ___save_term_meta_text( $term_id ) {
    // verify the nonce --- remove if you don't care
    // if ( ! isset( $_POST['term_meta_text_nonce'] ) || ! wp_verify_nonce( $_POST['term_meta_text_nonce'], basename( __FILE__ ) ) )
    //     return;
    $old_value_brand_title  = ___get_term_meta_text( $term_id );
    $new_value_brand_title = isset( $_POST['term_meta_text'] ) ? ___sanitize_term_meta_text ( $_POST['term_meta_text'] ) : '';
    if ( $old_value_brand_title && '' === $new_value_brand_title )
        delete_term_meta( $term_id, '__term_meta_text' );
    else if ( $old_value_brand_title !== $new_value_brand_title )
        update_term_meta( $term_id, '__term_meta_text', $new_value_brand_title );

    $old_brand_image = ___get_term_brand_image( $term_id );
    $new_brand_image = isset( $_POST['term_brand_header'] ) ? ___sanitize_term_meta_text ( $_POST['term_brand_header'] ) : '';
    if ( $old_brand_image && '' === $new_brand_image )
        delete_term_meta( $term_id, '__term_brand_image' );
    else if ( $old_brand_image !== $new_brand_image )
        update_term_meta( $term_id, '__term_brand_image', $new_brand_image );

    $old_brand_logo = ___get_term_brand_logo( $term_id );
    $new_brand_logo = isset( $_POST['term_brand_logo'] ) ? ___sanitize_term_meta_text ( $_POST['term_brand_logo'] ) : '';
    if ( $old_brand_logo && '' === $new_brand_logo )
        delete_term_meta( $term_id, '__term_brand_logo' );
    else if ( $old_brand_logo !== $new_brand_logo )
        update_term_meta( $term_id, '__term_brand_logo', $new_brand_logo );

    $old_brand_tagline = ___get_term_brand_tagline( $term_id );
    $new_brand_tagline = isset( $_POST['term_brand_tagline'] ) ? ___sanitize_term_meta_text ( $_POST['term_brand_tagline'] ) : '';
    if ( $old_brand_tagline && '' === $new_brand_tagline )
        delete_term_meta( $term_id, '__term_brand_tagline' );
    else if ( $old_brand_tagline !== $new_brand_tagline )
        update_term_meta( $term_id, '__term_brand_tagline', $new_brand_tagline );
}

add_action('admin_head', 'remove_default_category_description');
function remove_default_category_description()
{
    global $current_screen;
    if ( $current_screen->id == 'edit-category' )
    {
    ?>
      <script type="text/javascript">
              jQuery(function($) {
                  $('textarea#description').closest('tr.form-field').remove();
              });
      </script>
    <?php
    }
}

// remove the html filtering
remove_filter( 'pre_term_description', 'wp_filter_kses' );
remove_filter( 'term_description', 'wp_kses_data' );


// Init for Metabox
add_action( 'admin_init', 'product_meta_box' );
function product_meta_box() {
    add_meta_box( 'brand_brands_meta_box',
        'Product Details',
        'display_product_meta_box',
        'products', 'normal', 'high'
    );
}
function display_product_meta_box( $product ) {
    $product_name = esc_html( get_post_meta( $product->ID, 'brand_name', true ) );
    $product_title = esc_html( get_post_meta( $product->ID, 'brand_title', true ) );
    $product_tagline = esc_html( get_post_meta( $product->ID, 'brand_tagline', true ) );
    $product_header = get_post_meta( $product->ID, 'brand_header', true );
    $button_brand_header = '<a href="#" class="product_upload_image_button button">Upload Brand Header</a>';
    $display= "none";
    if( $image_attributes = wp_get_attachment_image_src( $product_header, 'full' ) ) {

        $button_brand_header = '<a href="#" class="product_upload_image_button"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" /></a>';
        $display = 'inline-block';

    }
    ?>
        <div class="form-field">
            <label>Product Name</label>
            <p>
            <input type="text" size="100" name="brand_name" value="<?php echo $product_name; ?>" />
            </p>
        </div>
        <!-- <div class="form-field">
            <label>Product Title</label>
            <p>
            <input type="text" size="80" name="brand_title" value="<?php echo $product_title; ?>" />
            </p>
        </div>
        <div class="form-field">
            <label>Product Tagline</label>
            <p>
            <input type="text" size="80" name="brand_tagline" value="<?php echo $product_tagline; ?>" />
            </p>
        </div> -->
        <div class="form-field">
          <label>Product Header Image</label>
            <p>
            <?php echo $button_brand_header; ?>
            <input type="hidden" name="brand_header" id="brand_header" value="<?php echo $product_header; ?>" />
            <a href="#" class="product_remove_image_button" style="display: <?php echo $display; ?>;">Remove Brand Header</a>
            </p>
            <p class="description">
              Width: 690px x Height: 360px (Transparent Background PNG if desired)
            </p>
        </div>
<?php
}

add_action( 'save_post', 'add_brand_fields', 10, 2 );
function add_brand_fields( $product_id, $product ) {
    // Check post type for movie reviews
    if ( $product->post_type == 'products' ) {
        // Store data in post meta table if present in post data
        if ( isset( $_POST['brand_name'] ) && $_POST['brand_name'] != '' ) {
            update_post_meta( $product_id, 'brand_name', $_POST['brand_title'] );
        }
        if ( isset( $_POST['brand_title'] ) && $_POST['brand_title'] != '' ) {
            update_post_meta( $product_id, 'brand_title', $_POST['brand_title'] );
        }
        if ( isset( $_POST['brand_tagline'] ) && $_POST['brand_tagline'] != '' ) {
            update_post_meta( $product_id, 'brand_tagline', $_POST['brand_tagline'] );
        }
        if ( isset( $_POST['brand_header'] ) && $_POST['brand_header'] != '' ) {
            update_post_meta( $product_id, 'brand_header', $_POST['brand_header'] );
        }
    }
}

// Templating
add_filter( 'taxonomy_template', function( $template )
{
  $mytemplate = __DIR__ . '/custom-brand-template.php';

  if( is_tax( 'brand' ) && is_readable( $mytemplate ) )
  $template =  $mytemplate;

  return $template;
} );

?>
