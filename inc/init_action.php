<?php
add_action( 'init', 'create_brands' );
function create_brands() {
    $labels = array(
  		'name'              => _x( 'Brand', 'taxonomy general name', 'wp_custom_brands' ),
  		'singular_name'     => _x( 'Brand', 'taxonomy singular name', 'wp_custom_brands' ),
  		'search_items'      => __( 'Search Brands', 'wp_custom_brands' ),
  		'all_items'         => __( 'All Brands', 'wp_custom_brands' ),
  		'parent_item'       => __( 'Parent Brand', 'wp_custom_brands' ),
  		'parent_item_colon' => __( 'Parent Brand:', 'wp_custom_brands' ),
  		'edit_item'         => __( 'Edit Brand', 'wp_custom_brands' ),
  		'update_item'       => __( 'Update Brand', 'wp_custom_brands' ),
  		'add_new_item'      => __( 'Add New Brand', 'wp_custom_brands' ),
  		'new_item_name'     => __( 'New Brand Name', 'wp_custom_brands' ),
  		'menu_name'         => __( 'Brands', 'wp_custom_brands' ),
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
            'menu_icon' => plugins_url( '../assets/images/brand.png', __FILE__ ),
            'has_archive' => true,
            'rewrite' => array('slug' => '%brand%'),
        )
    );
    register_meta( 'meta_text', '__term_meta_text', '___sanitize_term_meta_text' );
    register_meta( 'meta_brand_image', '__term_brand_image', '___sanitize_term_meta_text' );
    register_meta( 'meta_brand_logo', '__term_brand_logo', '___sanitize_term_meta_text' );
    register_meta( 'meta_brand_tagline', '__term_brand_tagline', '___sanitize_term_meta_text' );
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
?>