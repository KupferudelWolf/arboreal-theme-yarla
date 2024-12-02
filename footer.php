<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Arboreal_Theme
 */

?>

<footer id="colophon" class="site-footer site-footer-mobile">
    <div class="site-info">
        <?php dynamic_sidebar('footer'); ?>
    </div><!-- .site-info -->
</footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>