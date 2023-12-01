(function ($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
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
        var info_timer;

        /**
         * Save Settings
         */
        $('.bb-settings-form').submit(function (e) {
            e.preventDefault();

            var settings_data = $(this).serialize();

            $.ajax({
                type: 'post',
                url: bb_admin_js_object.admin_ajax_url,
                data: {
                    action: 'bb_settings_save_action',
                    settings_data: settings_data,
                    _wpnonce: bb_admin_js_object.admin_ajax_nonce
                },
                beforeSend: function (xhr) {
                    clearTimeout(info_timer);
                    $('.bb-info-wrap').slideDown(500);
                    $('.bb-info').html(bb_admin_js_object.messages.wait);
                    $('.bb-loader').show();
                },
                success: function (res) {
                    $('.bb-loader').hide();
                    $('.bb-info').html(res);
                    info_timer = setTimeout(function () {
                        $('.bb-info-wrap').slideUp(500);
                    }, 5000);

                }
            });
        });
    });
})(jQuery);
