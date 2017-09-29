<?php get_header();

// Layout
$sidebar = get_post_meta( get_the_ID(), 'minti_layout', true );

if($sidebar == 'default'){
	$sidebarlayout = 'sixteen columns';
}
elseif($sidebar == 'fullwidth'){
	$sidebarlayout = 'page-section nopadding';
}
elseif($sidebar == 'sidebar-left'){
	$sidebarlayout = 'sidebar-left twelve alt columns';
}
elseif($sidebar == 'sidebar-right'){
	$sidebarlayout = 'sidebar-right twelve alt columns';
}
else{
	$sidebarlayout = 'sixteen columns';
} ?>

<div id="page-wrap" <?php if($sidebar != 'fullwidth'){ echo 'class="container"'; } ?> >

	<div id="content" class="sidebar-right">
    <?php
      $name = get_term(get_queried_object()->term_id, 'brand')->name;
      $slugis = get_term(get_queried_object()->term_id, 'brand')->slug;
      $custom_terms = get_terms('brand');
      echo $custom_term->slug;
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
            echo '<h2>'.$custom_term->name.'</h2>';

            while($loop->have_posts()) : $loop->the_post();
                echo '<a href="'.get_permalink().'">'.get_the_title().'</a><br>';
            endwhile;
         }
      }
    ?>
	</div> <!-- end content -->

	<?php if($sidebar == 'sidebar-left' || $sidebar == 'sidebar-right'){ ?>
	<div id="sidebar" class="<?php echo esc_attr($sidebar); ?> alt">
		<?php get_sidebar(); ?>
	</div>
	<?php } ?>

</div> <!-- end page-wrap -->

<?php get_footer(); ?>
