<?php if ( function_exists( 'get_post_likes' ) ) : ?>
    <?php $id = get_the_ID(); ?>
    <div class="like" data-post-id="<?= $id; ?>">
        <div class="like-row">
            <i class="like-icon <?= array_search( $id, $_SESSION['likes'] ) ? 'like-icon_active' : ''; ?>"></i>
        </div>
        <div class="like-row">
            <div class="like-title">Likes:
            <span class="like-value"> <?= get_post_likes( $id ); ?></span>
        </div>
        </div>
    </div>
<?php endif; ?>