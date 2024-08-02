<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Arboreal_Theme
 */

?>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<script>
		const COOKIEPATH = '<?php echo COOKIEPATH; ?>';
		const COOKIE_DOMAIN = '<?php echo COOKIE_DOMAIN; ?>';
	</script>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'arboreal'); ?></a>

		<header id="masthead" class="site-header">
			<div class="site-branding">
				<?php
				the_custom_logo();
				if (is_front_page() && is_home()):
					?>
					<h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
							rel="home"><?php bloginfo('name'); ?></a></h1>
					<?php
				else:
					?>
					<p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
							rel="home"><?php bloginfo('name'); ?></a></p>
					<?php
				endif;
				$arboreal_description = get_bloginfo('description', 'display');
				if ($arboreal_description || is_customize_preview()):
					?>
					<p class="site-description">
						<?php echo $arboreal_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</p>
				<?php endif; ?>
			</div><!-- .site-branding -->

			<nav id="site-navigation" class="main-navigation">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<i class="fa fa-bars"></i>
				</button>
				<?php
				$nav_menu = wp_nav_menu(
					array(
						'items_wrap' => '%3$s',
						'theme_location' => 'nav-menu',
						'container' => '',
						'menu_id' => 'primary-menu'
					)
				);
				?>
			</nav><!-- #site-navigation -->
		</header><!-- #masthead -->