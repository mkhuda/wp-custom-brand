<?php
add_action( 'admin_enqueue_scripts', 'brand_uploadscript' );
function brand_uploadscript( $hook_suffix ) {
  $custom_post_type = 'products';
  if ( ! did_action( 'wp_enqueue_media' ) ) {
      wp_enqueue_media();
  }
  
  if( in_array($hook_suffix, array('post.php', 'post-new.php', 'edit-tags.php', 'term.php') ) ){
      $screen = get_current_screen();

      if( is_object( $screen ) && $custom_post_type == $screen->post_type ){

          // Register, enqueue scripts and styles here
          wp_enqueue_script( 'productuploadscript', plugin_dir_url( __FILE__ ) . '../assets/js/brands.js', array('jquery'), null, false );
      }
  }

}
?>