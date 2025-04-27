<!DOCTYPE html>
<?php
/**
 * Template Name: Archive
 *
 * The template for displaying archive pages
 *
 * @package Arboreal_Theme
 */

function add_class_archive($classes)
{
    $classes[] = 'archive';
    return $classes;
}

add_filter('body_class', 'add_class_archive');

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        $title = get_the_archive_title();
        if (is_page()):
            $wp_query = new WP_Query(array(
                'post_type' => 'post',
                'meta_query' => array(array('key' => '_thumbnail_id')),
                'orderby' => 'date',
                'order' => 'ASC',
                'posts_per_page' => 10,
                'paged' => $paged
            ));
            $title = 'Archive';
        endif;

        if (have_posts()): ?>

            <header class="page-header">
                <h1 class="page-title"><?php echo $title; ?></h1>
                <?php
                the_archive_description('<div class="archive-description">', '</div>');
                ?>
            </header><!-- .page-header -->

            <?php if ($wp_query->max_num_pages > 1): ?>
                <nav class="navigation post-navigation" aria-label="Posts">
                    <div class="archive-pagination nav-links">
                        <?php
                        $links = paginate_links(array(
                            'prev_text' => '&lt; Older',
                            'next_text' => 'Newer &gt;',
                            'show_all' => true,
                            'type' => 'array'
                        ));
                        if ($paged === 0) {
                            echo '<div class="button inactive"><span>&lt; Older</span></div>';
                        }
                        foreach ($links as $key => $link) {
                            $classes = 'button';
                            if ($key === $paged) {
                                $classes .= ' inactive';
                            }
                            // if (!str_contains($link, 'prev') and !str_contains($link, 'next')) {
                            //     $classes .= ' desktop-only';
                            // }
                            echo '<div class="' . $classes . '">' . $link . '</div>';

                        }
                        if ($paged === $wp_query->max_num_pages) {
                            echo '<div class="button inactive"><span>Newer &gt;</span></div>';
                        }
                        ?>
                    </div>
                </nav>
            <?php endif; ?>

            <div class="archive-posts-container">
                <?php
                /* Start the Loop */
                while (have_posts()):
                    ?>
                    <div class="archive-post">
                        <?php
                        the_post();

                        /*
                         * Include the Post-Type-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Type name) and that will be used instead.
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
