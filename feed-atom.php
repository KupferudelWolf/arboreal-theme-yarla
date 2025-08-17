<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:thr="http://purl.org/syndication/thread/1.0" xml:lang="<?php
echo preg_replace('/_/', '-', get_locale()); ?>">

    <?php
    $query = new WP_Query(
        array(
            'is_single' => true,
            'orderby' => 'publish_date',
            'order' => 'DESC',
            'posts_per_page' => '4'
        )
    );
    ?>

    <title type="text"><?php echo get_bloginfo('name'); ?></title>
    <subtitle type="text"><?php echo get_bloginfo('description'); ?></subtitle>

    <updated><?php
    $first = $query->posts[3];
    echo get_post_time('Y-m-d', true, $first); ?>T<?php echo get_post_time('H:m:s', true, $first);
        ?>Z</updated>

    <link rel="alternate" type="text/html" href="<?php echo get_site_url(); ?>" />
    <id><?php echo home_url($wp->request); ?></id>
    <link rel="self" type="application/atom+xml" href="<?php echo home_url($wp->request); ?>" />

    <icon><?php echo get_site_icon_url(); ?></icon>

    <?php foreach ($query->posts as $post): ?>
        <entry>
            <title type="html">
                <![CDATA[<?php echo $post->post_title; ?>]]>
            </title>
            <link rel="alternate" type="text/html" href="<?php echo get_permalink($post); ?>" />
            <id><?php echo get_permalink($post); ?></id>
            <updated><?php echo get_post_time('Y-m-d', true, $post); ?>T<?php echo get_post_time('H:m:s', true, $post);
                 ?>Z</updated>
            <published><?php echo get_post_time('Y-m-d', true, $post); ?>T<?php echo get_post_time('H:m:s', true, $post);
                 ?>Z</published>
            <category scheme="<?php echo get_site_url(); ?>" term="webcomic" />
            <summary type="html">
                <![CDATA[<?php echo $post->post_content; ?>]]>
            </summary>
            <content type="html" xml:base="<?php echo get_permalink($post); ?>">
                <![CDATA[<?php echo $post->post_content; ?><a href="<?php
                   echo get_permalink($post); ?>" target="_blank"><img src="<?php
                     echo get_the_post_thumbnail_url($post, 'small'); ?>" style="display: block; margin: 1em auto; width: auto !important;"></a><?php
                        global $TRANSCRIPT;
                        get_transcript($post->ID);
                        $desc = "";
                        foreach ($TRANSCRIPT as $item) {
                            $content = $item['content'];
                            if ($content) {
                                $speaker = $item['speaker'];
                                if ($speaker) {
                                    $desc .= "<b>";
                                    $desc .= $speaker;
                                    $desc .= ":</b> ";
                                }
                                $content = preg_replace(['/\<[ \/]*br[ \/]*\>/', '/\\[nr]/'], ' | ', $content);
                                $desc .= $content;
                                $desc .= "<br \>";
                            }
                        }
                        echo $desc;
                        echo $desc;
                        ?>]]>
            </content>
        </entry>
    <?php endforeach; ?>
</feed>