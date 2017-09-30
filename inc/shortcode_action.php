<?php
function custom_brand_shortcode( $atts ) {
    extract( shortcode_atts( array(
        'total' => 4
    ), $atts, 'multilink' ) );
  ?>
  <div class="slick-container">
    <?php
    echo "gaees";
    $brand_terms = get_terms([
	    'taxonomy' => 'brand',
	    'hide_empty' => false,
		]);
    foreach($brand_terms as $brand_term) {
      $term_url = get_term_link($brand_term);
      $term_name = $brand_term->name;
      $logo_image = wp_get_attachment_image_src(get_term_meta($brand_term->term_id, '__term_brand_logo', true), 'medium')[0];
      echo '<div class="slick-inner"><a href="'.$term_url.'" title="'.$term_name.'"><img src="'.$logo_image.'" style="width:100%;display:block;" /></a></div>';
    }
    ?>
  </div>
  <script>
  jQuery(document).ready(function(){
  	jQuery('.slick-container').slick({
  		infinite: true,
  		slidesToShow: 3,
  		slidesToScroll: 3
  	});
  });
  </script>
<?php
}
add_shortcode( 'wp_custom_brand', 'custom_brand_shortcode' );
?>