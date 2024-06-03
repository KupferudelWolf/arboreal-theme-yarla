<?php
/**
 * @package Arboreal_Theme
 */

get_template_part('template-parts/meta');
get_header();
get_sidebar();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        $auth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
        $wp_query = new WP_Query(
            array(
                'pagename' => 'author-' . strtolower($auth->display_name)
            )
        );
        if (have_posts()):
            while (have_posts()):
                the_post();

                get_template_part('template-parts/content', 'page');

                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()):
                    comments_template();
                endif;

            endwhile; // End of the loop.
        else: ?>
            <h1><?php echo $auth->display_name; ?></h1>
            <div>
                <?php echo $auth->description; ?>
            </div>
        <?php endif; ?>
    </div>
</main><!-- #main -->

<?php
get_footer();
