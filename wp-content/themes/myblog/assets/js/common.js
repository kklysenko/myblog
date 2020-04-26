jQuery(function($) {
    var $loadmoreBtn = $('#loadmore'),
        $likeIcons = $('.like-icon');

    function load_posts(){

        var $postsContainer = $('.post-block__wrap'),
            currentPage = $postsContainer.data('page'),
            data = {
                postsPerPage: ajax_posts.posts_per_page * 1,
                currentPage: currentPage,
                maxPages: $postsContainer.data('max'),
                action: 'more_post_ajax'
            }

        $.ajax({
            type: "POST",
            dataType: "html",
            url: ajax_posts.ajaxurl,
            data: data,
            success: function(res){
                res = JSON.parse(res);
                var success = res.status == 'success';
                if ( success ) {

                    currentPage += 1;
                    $postsContainer.data('page', currentPage);
                    $postsContainer.append(res.posts);

                    history.pushState({pageId: currentPage}, '', '/blog/page/'+ currentPage +'/');

                    if ( res.posts_amount < data.postsPerPage ) {
                        $loadmoreBtn.hide();
                    }
                }
            }

        });

        $enable( $loadmoreBtn )

        return false;
    }

    function update_post_like( id, $icon ) {

        var whatDo = $icon.hasClass('like-icon_active') ? 'remove' : 'add',
            data = {
            id: id,
            whatDo: whatDo,
            action: 'update_post_likes'
        }

        $.ajax({
            type: "POST",
            dataType: "html",
            url: ajax_posts.ajaxurl,
            data: data,
            success: function(res){
                res = JSON.parse(res);
                if ( res.status != 'error' ) {
                    $icon.toggleClass('like-icon_active');
                    $icon.addClass('like-icon_animate');
                    $icon.on("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd", function(){
                        $icon.removeClass("like-icon_animate");
                    });
                    $value = $icon.closest('.like').find('.like-value');
                    $value.text(res.likes_amount);
                }
            }

        });
    }

    $('.post-block__wrap').on('click' , '.like-icon' ,function(e) {
        var id = $(e.target).closest('.like').data('post-id'),
        $icon = $(e.target);

        update_post_like( id, $icon );
    });

    $loadmoreBtn.on('click',function() {
        $disable( $loadmoreBtn );
        load_posts();
    });

    function $disable( elem ) {
        elem.attr( 'disabled', true );
    }

    function $enable( elem ) {
        elem.attr( 'disabled', false );
    }

});