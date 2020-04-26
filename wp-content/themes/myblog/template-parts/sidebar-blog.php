<div id="sidebar">
    <div class="sidebar-item">
        <h3><?php the_acf_blog('sidebar_title_1'); ?></h3>
        <?php echo do_shortcode('[tagslist]'); ?>
    </div>
    <div class="sidebar-item">
        <h3><?php the_acf_blog('sidebar_title_2'); ?></h3>
        <?php echo do_shortcode('[popularpost]'); ?>
    </div>
</div>