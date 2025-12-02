<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Arboreal_Theme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<a href="<?php the_permalink(); ?>" rel="bookmark">
		<?php if (has_post_thumbnail()):
			arboreal_post_thumbnail();
			?>
			<header class="entry-header">
				<?php the_title(sprintf('<h2 class="entry-title">', esc_url(get_permalink())), '</h2>'); ?>
			</header>
			<!-- .entry-header -->
		</a>
	<?php else: ?>
		<header class="entry-header">
			<?php the_title(sprintf('<h2 class="entry-title">', esc_url(get_permalink())), '</h2>'); ?>
		</header>
		<!-- .entry-header -->
		</a>

		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div>
		<!-- .entry-summary -->

		<footer class="entry-footer">
			<?php arboreal_entry_footer(); ?>
		</footer>
		<!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->