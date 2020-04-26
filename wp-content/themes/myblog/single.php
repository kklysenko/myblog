<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package myblog
 */

get_header();
?>

	<main id="primary" class="site-main">
        <section class="content">
            <div class="container">

                <div class="navigation">
                    <a href="/"><?= __('Home', 'myblog'); ?></a>
                    /  <a href="/blog"><?= __('Blog', 'myblog'); ?></a>
                    <p>  /  <?php the_title(); ?></p>
                </div>

                <?php
                while ( have_posts() ) :
                    the_post();


                    get_template_part( 'template-parts/content', get_post_type() );

                    get_template_part( 'template-parts/sidebar-blog' );

                endwhile; // End of the loop.

                ?>

            </div>
        </section>
	</main><!-- #main -->

<?php

get_footer();
