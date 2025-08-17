<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:slash="http://purl.org/rss/1.0/modules/slash/">

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

    <channel>
        <title><?php echo get_bloginfo('name'); ?></title>
        <atom:link href="<?php echo home_url($wp->request); ?>" rel="self" type="application/rss+xml" />
        <link><?php echo get_site_url(); ?></link>
        <description><?php echo get_bloginfo('description'); ?></description>
        <lastBuildDate><?php
        $first = $query->posts[0];
        echo get_post_time('D, d M Y H:m:s', false, $first);
        ?> <?php echo preg_replace('/:/', '', get_post_time('P', false, $first)); ?></lastBuildDate>
        <language><?php echo preg_replace('/_/', '-', get_locale()); ?></language>
        <sy:updatePeriod>weekly</sy:updatePeriod>
        <sy:updateFrequency>1</sy:updateFrequency>
        <sy:updateBase>2024-06-09T00:00:00-04:00</sy:updateBase>

        <image>
            <url><?php echo get_site_icon_url(); ?></url>
            <title><?php echo get_bloginfo('name'); ?></title>
            <link><?php echo get_site_url(); ?></link>
            <width>32</width>
            <height>32</height>
        </image>

        <?php foreach ($query->posts as $post): ?>
            <item>
                <title><?php echo $post->post_title; ?></title>
                <link><?php echo get_permalink($post); ?></link>
                <pubDate><?php
                echo get_post_time('D, d M Y H:i:s', false, $post) . ' ' . preg_replace('/:/', '', get_post_time('P', false, $post));
                ?></pubDate>
                <category>webcomic</category>
                <guid isPermaLink="true"><?php echo get_permalink($post); ?></guid>
                <description>
                    <![CDATA[<?php echo $post->post_content; ?><a href="<?php
                       echo get_permalink($post); ?>" target="_blank"><img src="<?php
                         echo get_the_post_thumbnail_url($post, 'width'); ?>" style="display: block; margin: 1em auto; width: auto !important;"></a><?php
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
                            ?>]]>
                </description>
            </item>
        <?php endforeach; ?>
    </channel>
</rss>