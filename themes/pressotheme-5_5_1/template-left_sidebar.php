<?php
/*
Template Name: Left Sidebar Page
*/
?>

<?php get_header(); ?>

<div class="row">
	
	<?php get_sidebar(); // sidebar 1 ?>
	
	<?php get_template_part( 'loop', 'page' ); ?>
	
</div>

<?php get_footer(); ?>