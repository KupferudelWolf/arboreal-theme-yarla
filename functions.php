<?php
/**
 * Arboreal Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Arboreal_Theme
 */

if (!defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function arboreal_setup()
{
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Arboreal Theme, use a find and replace
	 * to change 'arboreal' to the name of your theme in all the template files.
	 */
	load_theme_textdomain('arboreal', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support('title-tag');

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'nav-menu' => esc_html__('Primary', 'arboreal'),
		)
	);

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'arboreal_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height' => 250,
			'width' => 250,
			'flex-width' => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'arboreal_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function arboreal_content_width()
{
	$GLOBALS['content_width'] = apply_filters('arboreal_content_width', 640);
}
add_action('after_setup_theme', 'arboreal_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function arboreal_widgets_init()
{
	register_sidebar(
		array(
			'name' => esc_html__('Sidebar', 'arboreal'),
			'id' => 'sidebar-1',
			'description' => esc_html__('Add widgets here.', 'arboreal'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		)
	);
}
add_action('widgets_init', 'arboreal_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function arboreal_scripts()
{
	wp_enqueue_style('arboreal-style', get_stylesheet_uri(), array(), _S_VERSION);
	wp_style_add_data('arboreal-style', 'rtl', 'replace');

	wp_enqueue_script('arboreal-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'arboreal_scripts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}



require get_template_directory() . '/inc/custom-comments.php';

/**
 * Localization.
 */
function getLocaleSlug()
{
	return get_locale();
}

/**
 * Automatic theme updates from the GitHub repository.
 */
add_filter('pre_set_site_transient_update_themes', 'automatic_GitHub_updates', 100, 1);
function automatic_GitHub_updates($data)
{
	// Theme information
	$theme = get_stylesheet(); // Folder name of the current theme
	$current = wp_get_theme()->get('Version'); // Get the version of the current theme
	// GitHub information
	$user = 'KupferudelWolf'; // The GitHub username hosting the repository
	$repo = 'arboreal-theme-yarla'; // Repository name as it appears in the URL
	// Get the latest release tag from the repository. The User-Agent header must be sent, as per
	// GitHub's API documentation: https://developer.github.com/v3/#user-agent-required
	$file = @json_decode(
		@file_get_contents(
			'https://api.github.com/repos/' . $user . '/' . $repo . '/releases/latest',
			false,
			stream_context_create(['http' => ['header' => "User-Agent: " . $user . "\r\n"]])
		)
	);
	if ($file) {
		$update = filter_var($file->tag_name, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		// Only return a response if the new version number is higher than the current version
		if ($update > $current) {
			$data->response[$theme] = array(
				'theme' => $theme,
				// Strip the version number of any non-alpha characters (excluding the period)
				// This way you can still use tags like v1.1 or ver1.1 if desired
				'new_version' => $update,
				'url' => 'https://github.com/' . $user . '/' . $repo,
				'package' => $file->assets[0]->browser_download_url,
			);
		}
	}
	return $data;
}