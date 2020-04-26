<?php
/**
 * Template part for displaying posts on home page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package myblog
 */

?>

<article class="post-block__post">
    <a href="<?php the_permalink(); ?>" class="post-block__post-img">
        <img src="<?php the_post_thumbnail_url('full'); ?>" alt="image">
    </a>
    <div class="post-block__post__content">
        <a href="<?php the_permalink(); ?>">
            <h3><?php the_title(); ?></h3>
        </a>
        <div class="fszM lh15"><?php the_excerpt(); ?></div>
        <div class="post-block__post__content-visits">
            <p class="number"><?php //echo getPostViews(get_the_ID()); ?></p>
            <p><?php the_acf_blog('views_name'); ?></p>
        </div>
    </div>
</article>