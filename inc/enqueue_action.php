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

function brand_global_assets() {
	wp_enqueue_style( 'slickcss', plugin_dir_url( __FILE__ ) . '../assets/js/slick/slick.css' );
  wp_enqueue_style( 'slickcss-theme', plugin_dir_url( __FILE__ ) . '../assets/js/slick/slick-theme.css' );
  wp_enqueue_style( 'brands-style', plugin_dir_url( __FILE__ ) . '../assets/css/style.css' );

	wp_enqueue_script( 'slickjs', plugin_dir_url( __FILE__ ) . '../assets/js/slick/slick.js', array( 'jquery' ) );
}

add_action( 'wp_enqueue_scripts', 'brand_global_assets' );
?>