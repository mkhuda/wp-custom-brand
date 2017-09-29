<?php
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

    wp_enqueue_script( 'productuploadscript', plugin_dir_url( __FILE__ ) . '../assets/brands.js', array('jquery'), null, false );
}
?>