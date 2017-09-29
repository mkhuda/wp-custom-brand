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
      echo 'HALOOOOOOOO';
    ?>
	</div> <!-- end content -->

	<?php if($sidebar == 'sidebar-left' || $sidebar == 'sidebar-right'){ ?>
	<div id="sidebar" class="<?php echo esc_attr($sidebar); ?> alt">
		<?php get_sidebar(); ?>
	</div>
	<?php } ?>

</div> <!-- end page-wrap -->

<?php get_footer(); ?>
