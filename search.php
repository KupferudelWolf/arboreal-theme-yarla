<!DOCTYPE html>
<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Arboreal_Theme
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container">
		<?php if (have_posts()): ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
					/* translators: %s: search query. */
					printf(esc_html__('Search Results for: %s', 'arboreal'), '<span>' . get_search_query() . '</span>');
					?>
				</h1>
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

						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
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
