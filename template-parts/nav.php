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

        $q = new WP_Query(
            array(
                'category_name' => getLocaleSlug(),
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
        // foreach ($q->posts as $post) {
        //     if (null === $nav_first) {
        //         $nav_first = $post;
        //     }
        //     $nav_last = $post;
        //     $post_id = $post->ID;
        //     if ($post_id === $current_id) {
        //         $nav_prev = $nav_last;
        //     } elseif (null === $nav_next && null !== $nav_prev) {
        //         $nav_next = $post;
        //     }
        // }
        foreach ($q->posts as $post) {
            $nav_last = $post;
            if ($post->ID === $current_id) {
                $nav_earlier = false;
            } elseif ($nav_earlier) {
                $nav_prev = $post;
                if (null === $nav_first) {
                    $nav_first = $post;
                }
            } elseif (null === $nav_next) {
                $nav_next = $post;
                // $nav_last = null;
            }
        }
        if ($nav_first && $nav_first->ID === $current_id)
            $nav_first = null;
        if ($nav_last && $nav_last->ID === $current_id)
            $nav_last = null;
        ?>
        <div class="nav-first">
            <?php if ($nav_first): ?>
                <a href="<?php echo get_permalink($nav_first); ?>" rel="first">
                    << First </a>
                    <?php endif; ?>
        </div>
        <div class="nav-previous">
            <?php if ($nav_prev): ?>
                <a href="<?php echo get_permalink($nav_prev); ?>" rel="prev">
                    < <?php echo get_the_title($nav_prev); ?>
                </a>
            <?php endif; ?>
        </div>
        <div></div>
        <div class="nav-next">
            <?php if ($nav_next): ?>
                <a href="<?php echo get_permalink($nav_next); ?>" rel="next">
                    <?php echo get_the_title($nav_next); ?> >
                </a>
            <?php endif; ?>
        </div>
        <div class="nav-newest">
            <?php if ($nav_last): ?>
                <a href="<?php echo get_permalink($nav_last); ?>" rel="newest">
                    Latest >>
                </a>
            <?php endif; ?>
        </div>
        <!-- <div class="nav-previous">
            <a href="http://localhost/yar.la/pilot-01/" rel="prev">
                <span class="nav-subtitle">Previous:</span>
                <span class="nav-title">PILOT #01</span>
            </a>
        </div>
        <div class="nav-next">
            <a href="http://localhost/yar.la/pilot-03/" rel="next">
                <span class="nav-subtitle">Next:</span>
                <span class="nav-title">PILOT #03</span>
            </a>
        </div> -->
    </div>
</nav>