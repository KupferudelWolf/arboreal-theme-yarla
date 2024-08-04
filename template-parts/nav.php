<?php
/**
 * Post navigation.
 *
 * @package Arboreal_Theme
 */

$nav_first = null;
$nav_prev = null;
$nav_next = null;
$nav_last = null;

?>

<nav class="navigation post-navigation" aria-label="Posts">
    <h2 class="screen-reader-text">Post navigation</h2>
    <div class="nav-links">
        <?php if (get_previous_post_link()):
            $posts_array = get_posts(
                array(
                    'offset' => 0,
                    'orderby' => 'ASC',
                    'order' => 'ASC',
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'suppress_filters' => true
                )
            );
            ?>
            <div class="nav-first">
                <a href="<?php echo get_permalink($posts_array[0]); ?>" rel="first">&lt;&lt;</a>
            </div>
        <?php else: ?>
            <div class="nav-blank"></div>
        <?php endif; ?>

        <?php if (get_previous_post_link()): ?>
            <div class="nav-previous">
                <?php echo get_previous_post_link('%link', '&lt;'); ?>
            </div>
        <?php else: ?>
            <div class="nav-blank"></div>
        <?php endif; ?>

        <div class="nav-toggle" data-boxes_mode="<?php
        $mode = "0";
        if ($_COOKIE && $_COOKIE['boxes_mode']) {
            $mode = $_COOKIE['boxes_mode'];
        }
        echo $mode == "NaN" ? "0" : $mode;
        ?>">
            <a href="javascript:void(0);" rel="toggle"></a>
        </div>

        <?php if (get_next_post_link()): ?>
            <div class="nav-next">
                <?php echo get_next_post_link('%link', '&gt;'); ?>
            </div>
        <?php else: ?>
            <div class="nav-blank"></div>
        <?php endif; ?>

        <?php
        if (get_next_post_link()): ?>
            <div class="nav-newest">
                <a href="<?php
                // echo get_permalink($nav_last);
                echo get_home_url();
                ?>" rel="newest">
                    &gt;&gt;
                </a>
            </div>
        <?php else: ?>
            <div class="nav-blank"></div>
        <?php endif; ?>
    </div>
</nav>

<?php /// Swipe indicators.
if ($nav_prev): ?>
    <div class="swipe-nav-left">&lt;</div>
<?php endif;
if ($nav_next): ?>
    <div class="swipe-nav-right">&gt;</div>
<?php endif; ?>