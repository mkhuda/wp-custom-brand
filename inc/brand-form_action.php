<?php
add_action( 'brand_add_form_fields', '___edit_form_field_term_meta_text' );
function ___edit_form_field_term_meta_text( $term ) {
    $brand_title  = ___get_term_meta_text( $term->term_id );
    $brand_image = ___get_term_brand_image( $term->term_id );
    $brand_logo = ___get_term_brand_logo( $term->term_id );
    $brand_tagline = ___get_term_brand_tagline( $term->term_id );
    $button_brand_header = '<a href="#" class="brand_upload_image_button button">Upload Brand Header</a>';
    $display_brand_header= "none";
    if( $image_attributes = wp_get_attachment_image_src( $brand_image, 'medium' ) ) {

        $button_brand_header = '<a href="#" class="product_upload_image_button"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" /></a>';
        $display_brand_header = 'inline-block';

    }
    ?>

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
        <?php echo $button_brand_header; ?>
        <input type="hidden" name="term_brand_header" id="brand_upload_image_button" value="<?php echo $brand_image; ?>" />
        <a href="#" class="product_remove_image_button" style="display: <?php echo $display_brand_header; ?>;">Remove Brand Header</a>
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
    $brand_image = ___get_term_brand_image( $term->term_id );
    $brand_logo = ___get_term_brand_logo( $term->term_id );
    $brand_tagline = ___get_term_brand_tagline( $term->term_id );
    $button_brand_header = '<a href="#" class="brand_upload_image_button button">Upload Brand Header</a>';
    $display_brand_header= "none";
    if( $image_attributes = wp_get_attachment_image_src( $brand_image, 'medium' ) ) {

        $button_brand_header = '<a href="#" class="product_upload_image_button"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" /></a>';
        $display_brand_header = 'inline-block';

    }
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
        <?php echo $button_brand_header; ?>
        <input type="hidden" name="term_brand_header" id="brand_upload_image_button" value="<?php echo $brand_image; ?>" />
        <a href="#" class="product_remove_image_button" style="display: <?php echo $display_brand_header; ?>;">Remove Brand Header</a>
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
?>