<!DOCTYPE html>
<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Arboreal_Theme
 */

get_header();
?>

<main id="primary" class="site-main">

	<section class="error-404 not-found">
		<div class="container">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'arboreal'); ?></h1>
			</header><!-- .page-header -->

			<div class="page-content">
				<p><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'arboreal'); ?>
				</p>

				<?php
				get_search_form();

				echo '</br>';

				the_widget('WP_Widget_Recent_Posts');
				?>

				<div class="widget widget_categories">
					<h2 class="widget-title"><?php esc_html_e('Most Used Categories', 'arboreal'); ?></h2>
					<ul>
						<?php
						wp_list_categories(
							array(
								'orderby' => 'count',
								'order' => 'DESC',
								'show_count' => 1,
								'title_li' => '',
								'number' => 10,
							)
						);
						?>
					</ul>
				</div><!-- .widget -->

				<?php
				$arboreal_archive_content = '<p>' . esc_html__('Try looking in the monthly archives.', 'arboreal') . '</p>';
				the_widget('WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$arboreal_archive_content");

				the_widget('WP_Widget_Tag_Cloud');
				?>

			</div><!-- .page-content -->
		</div>
	</section><!-- .error-404 -->

</main><!-- #main -->

<?php
get_footer();
