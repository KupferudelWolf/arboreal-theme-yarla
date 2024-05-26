<?php
/**
 * Post navigation.
 *
 * @package Arboreal_Theme
 */

?>

<nav class="navigation post-navigation" aria-label="Posts">
    <h2 class="screen-reader-text">Post navigation</h2>
    <div class="nav-links">
        <?php
        $current_id = $post->ID;
        $current_time = get_post_timestamp();

        $q = new WP_Query(
            array(
                // 'category_name' => getLocaleSlug(),
                'orderby' => 'date',
                'order' => 'ASC',
                'post_type' => 'post'
            )
        );

        $nav_first = null;
        $nav_prev = null;
        $nav_next = null;
        $nav_last = null;
        $nav_earlier = true;
        foreach ($q->posts as $post) {
            $nav_last = $post;
            if ($current_time <= get_post_timestamp($post)) {
                $nav_earlier = false;
            }
            if ($post->ID !== $current_id) {
                if ($nav_earlier) {
                    $nav_prev = $post;
                    if (null === $nav_first) {
                        $nav_first = $post;
                    }
                } elseif (null === $nav_next) {
                    $nav_next = $post;
                }
            }
        }
        if ($nav_earlier)
            $nav_last = null;
        if ($nav_first && $nav_first->ID === $current_id)
            $nav_first = null;
        if ($nav_last && $nav_last->ID === $current_id)
            $nav_last = null;
        ?>

        <?php if ($nav_first): ?>
            <div class="nav-first">
                <a href="<?php echo get_permalink($nav_first); ?>" rel="first">&lt;&lt;</a>
            </div>
        <?php else: ?>
            <div class="nav-blank"></div>
        <?php endif; ?>

        <?php if ($nav_prev): ?>
            <div class="nav-previous">
                <a href="<?php echo get_permalink($nav_prev); ?>" rel="prev">
                    &lt; <?php //echo get_the_title($nav_prev); ?>
                </a>
            </div>
        <?php else: ?>
            <div class="nav-blank"></div>
        <?php endif; ?>

        <div class="nav-toggle">
            <a href="javascript:void(0);" rel="toggle"></a>
        </div>

        <?php if ($nav_next): ?>
            <div class="nav-next">
                <a href="<?php echo get_permalink($nav_next); ?>" rel="next">
                    <?php //echo get_the_title($nav_next); ?> &gt;
                </a>
            </div>
        <?php else: ?>
            <div class="nav-blank"></div>
        <?php endif; ?>

        <?php if ($nav_last): ?>
            <div class="nav-newest">
                <a href="<?php echo get_permalink($nav_last); ?>" rel="newest">
                    &gt;&gt;
                </a>
            </div>
        <?php else: ?>
            <div class="nav-blank"></div>
        <?php endif; ?>
    </div>
</nav>