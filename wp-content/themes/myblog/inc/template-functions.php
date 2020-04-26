<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package myblog
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */

const POST_PER_PAGE = 5;

function start_session() {
    if( ! session_id() ) {
        session_start();
    }
}
add_action('init', 'start_session', 1);

function end_session() {
    session_destroy ();
}

add_action('wp_logout','end_session');
add_action('wp_login','end_session');
add_action('end_session_action','end_session');

function myblog_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'myblog_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function myblog_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'myblog_pingback_header' );

/**
 * Add ACF theme option setting.
 */
if( function_exists('acf_add_options_page') ) {

	acf_add_options_page();

}

/**
 * Excerpt's max length.
 */
function my_excerpt_length( $length ) {
	return 24;
}
add_filter( 'excerpt_length', 'my_excerpt_length' );

/**
 * Edit excerpt format.
 */
function new_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');

if ( ! function_exists( 'update_post_likes' ) ) :
    /**
     * Loadmore posts action.
     */
    function more_post_ajax() {

        $ppp = (isset($_POST["postsPerPage"])) ? $_POST["postsPerPage"] : POST_PER_PAGE;
        $current_page = (isset($_POST["currentPage"])) ? $_POST["currentPage"] : 1;
        $max_pages = (isset($_POST["maxPages"])) ? $_POST["maxPages"] : 1;

        header("Content-Type: text/html");

        $offset = $ppp * $current_page;

        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => $ppp,
            'paged'          => $current_page,
            'offset'         => $offset,
        );

        $query = new WP_Query($args);
        $posts_count = \count( $query->posts );

        $out = array(
            'posts'        => '',
            'posts_amount' => $posts_count,
            'pagination'   => '',
        );

        if ($query -> have_posts()) :  while ($query -> have_posts()) : $query -> the_post();
            $id = get_the_ID();
            $icon_class = array_search( $id, $_SESSION['likes'] ) ? 'like-icon_active' : '';
            $out['posts'] .= '<div class="post-block__post">
                <a href="' . get_the_permalink() .'" class="post-block__post-img">
                    <img src="' . get_the_post_thumbnail_url($post->ID, 'full') . '" alt="image">
                </a>
                <div class="post-block__post__content">
                    <div class="like" data-post-id="' . $id . '">
                        <div class="like-row">
                            <i class="like-icon ' . $icon_class . '"></i>
                        </div>
                        <div class="like-row">
                            <div class="like-title">Likes:
                            <span class="like-value"> ' . get_post_likes( $id ) . '</span>
                        </div>
                        </div>
                    </div>
                    <a href="' . get_the_permalink() . '">
                        <h3>' . get_the_title() . '</h3>
                    </a>
                    <div class="fszM lh15">' . get_the_excerpt() . '</div>
                    <div class="post-block__post__content-likes">
                    </div>
                </div>
            </div>';

            endwhile;
            $out['status'] = 'success';
        else :
            $out['status'] = 'unsuccess';
        endif;

        wp_reset_postdata();
        die( json_encode( $out ) );
    }
endif;

add_action('wp_ajax_nopriv_more_post_ajax', 'more_post_ajax');
add_action('wp_ajax_more_post_ajax', 'more_post_ajax');

if ( ! function_exists( 'update_post_likes' ) ) :
    /**
     * Update post likes value.
     *
    */
    function update_post_likes()
    {
        $id = (isset($_POST["id"])) ? $_POST["id"] : null;
        $action = (isset($_POST["whatDo"])) ? $_POST["whatDo"] : 'add';

        $post_meta = get_post_meta( $id );

        $userLikes = $_SESSION['likes'];
        $postLikes = $post_meta[ LIKES_AMOUNT ] ? $post_meta[ LIKES_AMOUNT ][0] : 0;

        $likes = $action == 'add' ? $postLikes+1 : $postLikes-1;

        $res = [];
        $res['likes_amount'] = $likes;

        if
        (
            ( ! in_array( $id, $userLikes ) && $action == 'add')
            || ( in_array( $id, $userLikes ) && $action != 'add')
            )
        {
            if ( $action == 'add' ) {
                $_SESSION['likes'][] = $id;
            } else {
                $index = array_search( $id, $_SESSION['likes'] );
                $_SESSION['likes'][$index] = null;
            }

            $res['status'] = update_post_meta( $id, LIKES_AMOUNT, $likes );
        } else {
            $res['status'] = 'error';
        }

        die( json_encode( $res ) );

    }

endif;

add_action('wp_ajax_nopriv_update_post_likes', 'update_post_likes');
add_action('wp_ajax_update_post_likes', 'update_post_likes');