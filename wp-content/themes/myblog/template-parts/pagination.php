<?php $args = array(
    'end_size'     => 2,
    'mid_size'     => 2,
    'prev_next'    => false,
    'type' 				 => 'array',
); ?>
<?php $pagination = paginate_links( $args ); ?>

<?php if( !empty( $pagination ) ) : ?>
    <ul class="post-block__numbers">
        <?php foreach( $pagination as $link ) : ?>
            <li><?php echo $link; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>