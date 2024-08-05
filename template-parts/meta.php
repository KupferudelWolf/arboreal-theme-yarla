<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo get_permalink(); ?>">
<meta property="og:title" content="Arboreal | <?php echo get_the_title(); ?>">
<meta property="og:description"
    content="Arboreal: A Comic From Another World.</br>&nbsp;(Translations available on website.)">
<meta property="og:image" content="<?php echo get_the_post_thumbnail_url(); ?>">
<meta property="og:image:type" content="<?php
if (get_the_post_thumbnail_url()) {
    echo wp_get_image_mime(get_the_post_thumbnail_url());
}
?>">
<meta property="og:site_name" content="Arboreal" />
<meta property="og:locale" content="en_US">
<meta name="theme-color" content="#313f40">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Arboreal | <?php echo get_the_title(); ?>">
<meta name="twitter:description"
    content="Arboreal: A Comic From Another World.</br>&nbsp;(Translations available on website.)">
<meta name="twitter:image" content="<?php echo get_the_post_thumbnail_url(); ?>">
<link rel="preload" as="image" href="<?php echo get_the_post_thumbnail_url(); ?>" type="<?php
   if (get_the_post_thumbnail_url()) {
       echo wp_get_image_mime(get_the_post_thumbnail_url());
   }
   ?>" />