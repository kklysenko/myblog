<?php
/**
 * Template Name: Home
 * Template Post Type: page
 *
 * @package myblog
 */
get_header();
global $post; ?>


<section class="content">

    <div class="container">
        <div id="posts-block">
            <div class="container">
                <div class="navigation">
                    <a href="/"><?php esc_html_e( 'Home', 'myblog' ); ?></a>
                    / <a href="/blog"><?php esc_html_e( 'Blog', 'myblog' ); ?></a>
                    <?php if ( is_tag() ) : ?>
                        / <a href="/"><?php single_tag_title(); ?></a>
                    <?php endif; ?>
                </div>

                <?php if ( have_posts() ) :
                        $i = 0;
                        // var_dump( $wp_query );s
                    ?>
                    <div class="post-block__wrap" data-page="<?= get_query_var('paged') ? get_query_var('paged') : 1; ?>" data-max="<?= $wp_query->max_num_pages; ?>">
                    <?php while ( have_posts() ) :
                        the_post(); ?>

                        <div class="post-block__post <?= $i == 0 ? 'main' : ''; ?>">
                            <a href="<?php the_permalink(); ?>" class="post-block__post-img">
                                <img src="<?php the_post_thumbnail_url('full'); ?>" alt="image">
                            </a>
                            <div class="post-block__post__content">
                                <?= get_template_part( 'template-parts/likes' ); ?>
                                <a href="<?php the_permalink(); ?>">
                                    <h3><?php the_title(); ?></h3>
                                </a>
                                <div class="fszM lh15"><?php the_excerpt(); ?></div>
                            </div>
                        </div>

                    <?php $i++; ?>
                    <?php endwhile; ?>
                    </div>
                <?php endif; ?>

                <?php if ( get_query_var('paged') != $wp_query->max_num_pages && $wp_query->max_num_pages > 1 ) : ?>
                    <button class="post-block__show" id="loadmore"><?= __('Show more', 'myblog') ?></button>
                <?php endif; ?>

                <?php get_template_part( 'template-parts/pagination' ); ?>
            </div>
        </div>
        <!-- posts-block ends -->

        <?php get_template_part( 'template-parts/sidebar-blog' ); ?>
    </div>
</section>

<?php get_footer();