<!DOCTYPE html>
<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Arboreal_Theme
 */

$posts_array = get_posts(
	array(
		'offset' => 0,
		'orderby' => 'DESC',
		'order' => 'DESC',
		'post_type' => 'post',
		'post_status' => 'publish',
		'suppress_filters' => true
	)
);

wp_redirect(get_permalink($posts_array[0]));

get_template_part('template-parts/meta');
get_header();

?>
<main id="primary" class="site-main">
	<div class="container">
		<?php
		get_template_part('template-parts/content', 'none');
		?>
	</div>

</main><!-- #main -->

<?php
get_sidebar();
get_footer();
