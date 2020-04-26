<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package myblog
 */

const BLOG_PAGE_SLUG = 'page_for_posts';
const LIKES_USER     = 'likes_users';
const LIKES_AMOUNT   = 'likes_amount';

if ( ! function_exists( 'myblog_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function myblog_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'myblog' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'myblog_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function myblog_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'myblog' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'myblog_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
    function myblog_entry_footer() {
        // Hide category and tag text for pages.
        if ( 'post' === get_post_type() ) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list( esc_html__( ', ', 'myblog' ) );
            if ( $categories_list ) {
                /* translators: 1: list of categories. */
                printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'myblog' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'myblog' ) );
            if ( $tags_list ) {
                /* translators: 1: list of tags. */
                printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'myblog' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }

        if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
            echo '<span class="comments-link">';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: post title */
                        __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'myblog' ),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post( get_the_title() )
                )
            );
            echo '</span>';
        }

        edit_post_link(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __( 'Edit <span class="screen-reader-text">%s</span>', 'myblog' ),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post( get_the_title() )
            ),
            '<span class="edit-link">',
            '</span>'
        );
    }
endif;

if ( ! function_exists( 'myblog_post_thumbnail' ) ) :
    /**
     * Displays an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     */
    function myblog_post_thumbnail() {
        if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
            return;
        }

        if ( is_singular() ) :
            ?>

            <div class="post-thumbnail">
                <?php the_post_thumbnail(); ?>
            </div><!-- .post-thumbnail -->

        <?php else : ?>

            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                    the_post_thumbnail(
                        'post-thumbnail',
                        array(
                            'alt' => the_title_attribute(
                                array(
                                    'echo' => false,
                                )
                            ),
                        )
                    );
                ?>
            </a>

            <?php
        endif; // End is_singular().
    }
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
    /**
     * Shim for sites older than 5.2.
     *
     * @link https://core.trac.wordpress.org/ticket/12563
     */
    function wp_body_open() {
        do_action( 'wp_body_open' );
    }
endif;

if ( ! function_exists( 'the_acf_option' ) ) :
    /**
     * Displays an ACF option field
     *
    */
    function the_acf_option( $label )
    {
        if ( function_exists( 'the_field' ) ) :
            the_field( $label, 'option' );
        else :
            echo 'Please, activate ACF plugin.';
        endif;
    }
endif;

if ( ! function_exists( 'the_acf_blog' ) ) :
    /**
     * Displays an ACF blog field
     *
    */
    function the_acf_blog( $label )
    {
        if ( function_exists( 'the_field' ) ) :
            the_field( $label, get_option( BLOG_PAGE_SLUG ) );
        else :
            echo 'Please, activate ACF plugin.';
        endif;
    }
endif;

if ( ! function_exists( 'the_acf_blog_repeater' ) ) :
    /**
     * Displays an ACF repeater field
     *
    */
    function the_acf_blog_repeater( $label )
    {
        if ( function_exists( 'the_field' ) ) :
            the_repeater_field( $label, get_option( BLOG_PAGE_SLUG ) );
        else :
            echo 'Please, activate ACF plugin.';
        endif;
    }
endif;

if ( ! function_exists( 'get_post_likes' ) ) :
    /**
     * Returns post likes value.
     *
     * @param int $id
     *
    */
    function get_post_likes( int $id )
    {
        $res = get_post_meta( $id, 'likes_amount' );

        return $res == [] ? 0 : $res[0];
    }

endif;

function popular_post()
{
    ob_start();
    $query = new WP_Query( array(
        'orderby' => 'post_views_count',
        'posts_per_page' => 3
        ) );
    ?>
        <?php
        while ( $query->have_posts() ) {
            $query->the_post(); ?>

            <div class="sidebar-item__post">
                <a href="<?php the_permalink(); ?>" class="sidebar-item__post-img">
                    <img src="<?php the_post_thumbnail_url('full'); ?>" alt="image">
                </a>
                <div class="sidebar-item__post-content">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    <div class="sidebar-item__post-content__likes">
                    </div>
                </div>
            </div>
        <?php
        } ?>
    <?php

    $string = ob_get_contents();
    ob_clean();
    wp_reset_postdata();

    return $string;
}

add_shortcode('popularpost' , 'popular_post' );

function wpb_tags()
{
	$wpbtags =  get_tags();
	$string = '<ul class="sidebar-item__tags">';
	foreach ($wpbtags as $tag) {
		$string .= '<li><a href="'. get_tag_link($tag->term_id) .'" >'. $tag->name . '</a></li>' . "\n"   ;
	}
	$string .= '</ul>';
	return $string;
}