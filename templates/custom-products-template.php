<?php get_header();
?>
<div id="page-wrap" class="container">

	<div id="content" class="sidebar-right">
    <?php
		echo get_post_meta(get_the_ID(), '_product_name', true).'<br>';
      echo wp_get_attachment_url(get_post_meta(get_the_ID(), '_product_header', true));
    ?>
	</div> <!-- end content -->

</div> <!-- end page-wrap -->

<?php get_footer(); ?>
