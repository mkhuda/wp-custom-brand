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
    register_post_type( 'brands',
        array(
            'labels' => array(
                'name' => 'Custom Brands',
                'singular_name' => 'Custom Brands',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Brand',
                'edit' => 'Edit',
                'edit_item' => 'Edit Brand',
                'new_item' => 'New Brand',
                'view' => 'View',
                'view_item' => 'View Brand',
                'search_items' => 'Search Brands',
                'not_found' => 'No Brands found',
                'not_found_in_trash' => 'No Brands found in Trash',
                'parent' => 'Parent Brand'
            ),

            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail' ),
            'taxonomies' => array( 'category' ),
            'menu_icon' => plugins_url( 'brand.png', __FILE__ ),
            'has_archive' => true,
            'rewrite' => array('slug' => 'our-brands'),
        )
    );
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
