<!DOCTYPE html>
<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Arboreal_Theme
 */

function add_class_archive($classes)
{
	$classes[] = 'archive';
	return $classes;
}

add_filter('body_class', 'add_class_archive');

get_header();
?>

<main id="primary" class="site-main">
	<div class="container">
		<?php

		if (have_posts()): ?>

			<header class="page-header">
				<h1 class="page-title"><?php echo $title; ?></h1>
				<?php
				the_archive_description('<div class="archive-description">', '</div>');
				?>
			</header><!-- .page-header -->

			<?php
			the_posts_navigation(array(
				'prev_text' => '&lt; Older',
				'next_text' => 'Newer &gt;'
			));
			?>

			<div class="archive-posts-container">
				<?php
				/* Start the Loop */
				while (have_posts()):
					?>
					<div class="archive-post">
						<?php
						the_post();

						/*
						 * Include the Post-Type-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
						 */
						get_template_part('template-parts/content', 'search');
						?>
					</div>
					<?php

				endwhile;
				?>
			</div>
			<?php
		else:

			get_template_part('template-parts/content', 'none');

		endif;
		?>
	</div>
</main><!-- #main -->

<?php
get_sidebar();
get_footer();
