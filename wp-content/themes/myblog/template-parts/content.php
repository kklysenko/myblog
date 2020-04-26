<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package myblog
 */

get_header();

?>

<article id="posts-block">
    <div class="container post-container">
        <div class="post-block__wrap fszM lh17">
            <h1><?php the_title(); ?></h1>
            <img class="post-block__image" src="<?php the_post_thumbnail_url('full'); ?>" alt="image">
            <?= get_template_part( 'template-parts/likes' ); ?>
            <?php
                the_content();
            ?>
            <div class="post-block__post__content-likes end-of-post">
            </div>
        </div>

        <?php
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
        ?>
    </div>
</article>