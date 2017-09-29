<?php 
add_filter( 'taxonomy_template', function( $template )
{
  $mytemplate = __DIR__ . '/templates/custom-brand-template.php';

  if( is_tax( 'brand' ) && is_readable( $mytemplate ) )
  $template =  $mytemplate;

  return $template;
} );

/* Filter the single_template with our custom function*/
add_filter('single_template', 'custom_post_template');

function custom_post_template($single) {

    global $wp_query, $post;

    /* Checks for single template by post type */
    if ( $post->post_type == 'products' ) {
        if ( file_exists( __DIR__ . '/templates/custom-products-template.php' ) ) {
            return __DIR__ . '/templates/custom-products-template.php';
        }
    }

    return $single;

}
?>