<?php
if (!function_exists('custom_comments')):
    function custom_comments($comment, $args, $depth)
    { ?>
        <li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
            <div class="comment">
                <div class="comment-header">
                    <div class="comment-thumbnail d-none d-sm-block">
                        <?php echo get_avatar($comment, $size = '64', $default = 'http://0.gravatar.com/avatar/36c2a25e62935705c5565ec465c59a70?s=32&d=mm&r=g'); ?>
                    </div>
                    <div class="comment-meta">
                        <div class="comment-author"><?php echo get_comment_author() ?></div>
                        <div class="comment-date">
                            <?php printf(/* translators: 1: date and time(s). */ esc_html__('%1$s at %2$s', 'arboreal'), get_comment_date(), get_comment_time()) ?>
                        </div>
                    </div>
                </div>
                <div class="comment-block">
                    <div class="comment-arrow"></div>
                    <?php if ($comment->comment_approved == '0'): ?>
                        <em><?php esc_html_e('Your comment is awaiting moderation.', 'arboreal') ?></em>
                        <br />
                    <?php endif; ?>
                    <div class="comment-body"> <?php comment_text() ?></div>
                </div>
                <div class="comment-footer">
                    <a href="#"><i class="fa fa-reply"></i>
                        <?php
                        comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'])))
                            ?>
                    </a>
                </div>
            </div>

        <?php }
endif;
?>