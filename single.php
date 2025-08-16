<!DOCTYPE html>
<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Arboreal_Theme
 */

get_template_part('template-parts/meta');
get_header();
get_sidebar();
?>

<main id="primary" class="site-main">

	<?php
	while (have_posts()):
		?>
		<div class="container">
			<?php
			get_template_part('template-parts/nav');

			the_post();

			get_template_part('template-parts/content', get_post_type());

			// the_post_navigation(
			// 	array(
			// 		'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'arboreal') . '</span> <span class="nav-title">%title</span>',
			// 		'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'arboreal') . '</span> <span class="nav-title">%title</span>',
			// 	)
			// );
		
			// get_template_part('template-parts/nav');
			?>
		</div>
		<?php
		// Mobile position of transcript.
		global $TRANSCRIPT;
		if (count($TRANSCRIPT) > 0): ?>
			<!-- Transcript (Mobile) -->
			<div class="entry-transcript mobile-only">
				<?php get_template_part('template-parts/transcript'); ?>
			</div>
		<?php endif;

		// If comments are open or we have at least one comment, load up the comment template.
		if (comments_open() || get_comments_number()):
			?>
			<!-- Comments -->
			<div class="container container-comments">
				<?php comments_template(); ?>
			</div>
			<?php
		endif;
	endwhile; ?>

</main><!-- #main -->

<?php
get_footer();
