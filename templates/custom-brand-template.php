<?php get_header();
?>
<div id="page-wrap" class="wrap">
	<div id="content" class="fullwidth">
		<?php
		
      $name = get_term(get_queried_object()->term_id, 'brand')->name;
      $slugis = get_term(get_queried_object()->term_id, 'brand')->slug;
      $custom_terms = get_terms('brand');
      foreach($custom_terms as $custom_term) {
        wp_reset_query();
        $args = array('post_type' => 'products',
            'tax_query' => array(
                array(
                    'taxonomy' => 'brand',
                    'field' => 'slug',
                    'terms' => $slugis,
                ),
            ),
         );

         $loop = new WP_Query($args);
         if($loop->have_posts()) {
            // echo '<h2>'.$custom_term->name.'</h2>';

            while($loop->have_posts()) : $loop->the_post();
                echo '<a href="'.get_permalink().'">'.get_the_title().'</a><br>';
            endwhile;
         }
      }
			echo do_shortcode("[wp_custom_brand]");
    ?>
		
	</div> <!-- end content -->

</div> <!-- end page-wrap -->

<?php get_footer(); ?>
