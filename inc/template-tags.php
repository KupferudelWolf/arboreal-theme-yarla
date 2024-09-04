<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Arboreal_Theme
 */

if (!function_exists('arboreal_posted_on')):
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function arboreal_posted_on()
	{
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if (get_the_time('U') !== get_the_modified_time('U')) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr(get_the_date(DATE_W3C)),
			esc_html(get_the_date()),
			esc_attr(get_the_modified_date(DATE_W3C)),
			esc_html(get_the_modified_date())
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x('Posted on %s', 'post date', 'arboreal'),
			'<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if (!function_exists('arboreal_posted_by')):
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function arboreal_posted_by()
	{
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x('by %s', 'post author', 'arboreal'),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if (!function_exists('arboreal_entry_footer')):
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function arboreal_entry_footer()
	{
		// Hide category and tag text for pages.
		if ('post' === get_post_type()) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list(esc_html__(', ', 'arboreal'));
			if ($categories_list) {
				/* translators: 1: list of categories. */
				printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'arboreal') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'arboreal'));
			if ($tags_list) {
				/* translators: 1: list of tags. */
				printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'arboreal') . '</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'arboreal'),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post(get_the_title())
				)
			);
			echo '</span>';
		}

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
	}
endif;

if (!function_exists('arboreal_post_thumbnail')):
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function arboreal_post_thumbnail()
	{
		if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
			return;
		}

		if (is_singular()):
			?>

			<div class="post-thumbnail">
				<?php
				// Append version number to force replaced images to reload.
				$reg = '#(https?:\/\/[^\/\s]+\/\S+\.(jpg|jpeg|png|webp|gif))#i';
				$tag = '?v' . do_shortcode('[file_modified id=' . get_post_thumbnail_id() . ' format=U]', true);
				echo preg_replace($reg, "$1" . $tag, get_the_post_thumbnail(null, 'full'));
				?>
				<?php if (current_user_can('edit_posts')): ?>
					<div class="hoverbox adminbox"></div>
				<?php endif; ?>
				<?php
				$arr = get_field('hoverbox_table');
				if (is_array($arr)) {
					foreach ($arr as &$row) {
						if (is_array($row)) {
							foreach ($row as &$cell) {
								$celldata = json_decode($cell[2]['c'], true);
								if (
									$celldata
									and array_key_exists('x1', $celldata)
									and array_key_exists('y1', $celldata)
									and array_key_exists('x2', $celldata)
									and array_key_exists('y2', $celldata)
								):
									$x1 = (float) $celldata['x1'];
									$y1 = (float) $celldata['y1'];
									$x2 = (float) $celldata['x2'];
									$y2 = (float) $celldata['y2'];
									$left = min($x1, $x2);
									$top = min($y1, $y2);
									$right = max($x1, $x2);
									$bottom = max($y1, $y2);
									$width = $right - $left;
									$height = $bottom - $top;
									if (
										min($left, $top) >= 0 &&
										max($right, $bottom) <= 100 &&
										min($width, $height) > 0
									):
										?>
										<div class="areamap<?php if ($cell[1]['c']) {
											$speaker = strtolower($cell[1]['c']);
											echo ' ' . preg_replace('/[^a-z]+/', '_', $speaker);
											if ($left < 50) {
												echo ' left';
											} else {
												echo ' right';
											}
										} ?>"
											style="top: <?php echo $top; ?>%; left: <?php echo $left; ?>%; width: <?php echo $width; ?>%; height: <?php echo $height; ?>%;">
											<div class="hoverbox">
												<?php echo $cell[0]['c']; ?>
											</div>
										</div>
									<?php endif;
								endif;
							}
						}
					}
				}
				?>
			</div><!-- .post-thumbnail -->

		<?php else: ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
				the_post_thumbnail(
					'post-thumbnail',
					array(
						'alt' => the_title_attribute(
							array(
								'echo' => false,
							)
						),
					)
				);
				?>
			</a>

			<?php
		endif; // End is_singular().
	}
endif;

if (!function_exists('wp_body_open')):
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open()
	{
		do_action('wp_body_open');
	}
endif;
