<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Arboreal_Theme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	$parent = get_post_parent();
	$children = get_pages(array('parent' => $post->ID));
	if ($parent or $children): ?>
		<div class="page-hierarchy">
			<?php
			if ($parent): ?>
				<div class="page-parent">
					<p>Subpage of: </p>
					<p>
						<a href=<?php echo the_permalink($parent); ?>><?php echo $parent->post_title; ?></a>
					</p>
				</div>
			<?php endif;
			if ($children): ?>
				<div class="page-children">
					<p>Subpages: </p>
					<?php foreach ($children as $child) { ?>
						<p>
							<a href=<?php echo the_permalink($child); ?>><?php echo $child->post_title; ?></a>
						</p>
					<?php } ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<header class="entry-header">
		<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
	</header><!-- .entry-header -->

	<?php arboreal_post_thumbnail(); ?>

	<div class="entry-content">

		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__('Pages:', 'arboreal'),
				'after' => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php if (get_edit_post_link()): ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__('Edit <span class="screen-reader-text">%s</span>', 'arboreal'),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post(get_the_title())
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->