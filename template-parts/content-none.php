<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Arboreal_Theme
 */

get_header();
?>

<section class="no-results not-found">
	<!-- <div class="container"> -->
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e('Nothing Found', 'arboreal'); ?></h1>
	</header><!-- .page-header -->

	<?php
	if (is_home() && current_user_can('publish_posts')):
		?>
		<div class="page-content"> <?php
		printf(
			'<p>' . wp_kses(
				/* translators: 1: link to WP admin new post page. */
				__('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'arboreal'),
				array(
					'a' => array(
						'href' => array(),
					),
				)
			) . '</p>',
			esc_url(admin_url('post-new.php'))
		);
		?>
		</div> <?php

	elseif (is_search()):
		?>
		<div class="page-content">
			<p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'arboreal'); ?>
			</p>
			<?php get_search_form(); ?>
		</div>
	<?php endif; ?>
	<!-- .page-content -->
	<!-- </div> -->
</section><!-- .no-results -->