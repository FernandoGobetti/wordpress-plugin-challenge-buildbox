(function ($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */
    $(document).ready(function () {
        var ajax_flag = 0;
        $('body').on('click', '.bb-like-dislike-trigger', function () {
            if (ajax_flag == 0) {
                var selector = $(this);
                var already_liked = selector.data('already-liked');
                var trigger_type = $(this).data('trigger-type');
                var post_id = $(this).data('post-id');

                if (already_liked == 0) {
                    $.ajax({
                        type: 'post',
                        url: bb_js_object.admin_ajax_url,
                        data: {
                            post_id: post_id,
                            action: 'bb_post_ajax_action',
                            type: trigger_type,
                            _wpnonce: bb_js_object.admin_ajax_nonce
                        },
                        beforeSend: function (xhr) {
                            ajax_flag = 1;
                        },
                        success: function (res) {
                            ajax_flag = 0;
                            res = $.parseJSON(res);
                            if (res.success) {
                                var latest_count = res.latest_count;
                                selector.closest('.bb-common-wrap').find('.bb-count-wrap').html(latest_count);
                                selector.addClass('bb-undo-trigger');
                                selector.attr('data-already-liked', '1')
                            }
                        }

                    });
                }
            }
        });

        $('body').on('click', '.bb-undo-trigger', function () {

            var selector = $(this);
            var post_id = $(this).data('post-id');
            var trigger_type = $(this).data('trigger-type');
            var already_liked = $(this).attr('data-already-liked');

            if (already_liked == 1) {
                $.ajax({
                    type: 'post',
                    url: bb_js_object.admin_ajax_url,
                    data: {
                        post_id: post_id,
                        action: 'bb_post_undo_ajax_action',
                        type: trigger_type,
                        _wpnonce: bb_js_object.admin_ajax_nonce
                    },
                    beforeSend: function (xhr) {
                        ajax_flag = 1;
                    },
                    success: function (res) {
                        ajax_flag = 0;
                        res = $.parseJSON(res);
                        if (res.success) {
                            var latest_count = res.latest_count;
                            selector.closest('.bb-common-wrap').find('.bb-count-wrap').html(latest_count);
                            selector.closest('.bb-like-dislike-wrap').find('.bb-like-dislike-trigger').data('already-liked', 0);
                            selector.removeClass('bb-undo-trigger');
                            selector.closest('.bb-like-dislike-wrap').find('.bb-like-dislike-trigger').removeClass('bb-prevent');
                            selector.attr('data-already-liked', '0')
                        }
                    }
                });
            }
        });
    });
})(jQuery);
