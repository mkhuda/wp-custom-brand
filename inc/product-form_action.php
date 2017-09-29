<?php
add_action( 'admin_init', 'product_meta_box' );
function product_meta_box() {
    add_meta_box( 'brand_brands_meta_box',
        'Product Details',
        'display_product_meta_box',
        'products', 'normal', 'high'
    );
}
function display_product_meta_box( $product ) {
    $product_name = esc_html( get_post_meta( $product->ID, '_product_name', true ) );
    $product_header = get_post_meta( $product->ID, '_product_header', true );
    $button_product_header = '<a href="#" class="product_upload_image_button button">Upload Brand Header</a>';
    $display= "none";
    if( $image_attributes = wp_get_attachment_image_src( $product_header, 'full' ) ) {

        $button_product_header = '<a href="#" class="product_upload_image_button"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" /></a>';
        $display = 'inline-block';

    }
    ?>
        <div class="form-field">
            <label>Product Name</label>
            <p>
            <input type="text" size="100" name="_product_name" value="<?php echo $product_name; ?>" />
            </p>
        </div>
        <div class="form-field">
          <label>Product Header Image</label>
            <p>
            <?php echo $button_product_header; ?>
            <input type="hidden" name="_product_header" id="_product_header" value="<?php echo $product_header; ?>" />
            <a href="#" class="product_remove_image_button" style="display: <?php echo $display; ?>;">Remove Brand Header</a>
            </p>
            <p class="description">
              Width: 690px x Height: 360px (Transparent Background PNG if desired)
            </p>
        </div>
<?php
}

add_action( 'save_post', 'add_product_field', 10, 2 );
function add_product_field( $product_id, $product ) {
    // Check post type for movie reviews
    if ( $product->post_type == 'products' ) {
        // Store data in post meta table if present in post data
        if ( isset( $_POST['_product_name'] ) && $_POST['_product_name'] != '' ) {
            update_post_meta( $product_id, '_product_name', $_POST['_product_name'] );
        }
        if ( isset( $_POST['_product_header'] ) && $_POST['_product_header'] != '' ) {
            update_post_meta( $product_id, '_product_header', $_POST['_product_header'] );
        }
    }
}
?>