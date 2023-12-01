<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Buildbox
 * @subpackage Buildbox/public
 * @author     Fernando Gobetti
 */
class Buildbox_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name . "-frontend";
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Buildbox_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Buildbox_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'css/buildbox-public.css',
            array(), $this->version, 'all');

        wp_enqueue_style(
            $this->plugin_name . '-bootstrap',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
            array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Buildbox_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Buildbox_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/buildbox-public.js',
            array('jquery'), $this->version, false);
        $ajax_nonce = wp_create_nonce('bb-ajax-nonce');

        $js_object = array(
            'admin_ajax_url' => admin_url('admin-ajax.php'),
            'admin_ajax_nonce' => $ajax_nonce);
        wp_localize_script($this->plugin_name, 'bb_js_object', $js_object);

        wp_enqueue_script(
            $this->plugin_name . '-bootstrap',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js',
            array('jquery'), $this->version, false);
    }


    public function buildbox_like_dislike($content)
    {
        $bb_settings = get_option('bb_settings');

        if (empty($bb_settings['buildbox_settings']['basic_settings']['allowed'])) {
            // if posts like dislike is disabled from backend
            return $content;
        }

        $post_id = get_the_ID();

        $like_count = get_post_meta($post_id, 'bb_like_count', true);
        $dislike_count = get_post_meta($post_id, 'bb_dislike_count', true);

        if (isset($_COOKIE['bb_' . $post_id])) {
            $already_liked = 1;
            $already_liked_type = ($_COOKIE['bb_' . $post_id] != 1) ? $_COOKIE['bb_' . $post_id] : 'na';
        } else {
            $already_liked = 0;
            $already_liked_type = 0;
        }

        return $content . "
        <div class='bb-like-dislike-wrap'>
            <div class='bb-like-wrap  bb-common-wrap'>
                <a href='#'
                   class='bb-like-trigger bb-like-dislike-trigger " . ($already_liked && $already_liked_type == 'like' ? 'bb-liked-disliked bb-undo-trigger' : '') . "'
                   title='like' 
                    data-post-id='" . $post_id . "' 
                   data-trigger-type='like'
                   data-already-liked='" . esc_attr($already_liked) . "'>
                    <span class='dashicons dashicons-thumbs-up'></span>
                </a>
                <span class='bb-like-count-wrap bb-count-wrap'>" . esc_html($like_count) . "</span>
            </div>
            <div class='bb-dislike-wrap  bb-common-wrap'>
                <a href='#' 
                    class='bb-dislike-trigger bb-like-dislike-trigger " . ($already_liked && $already_liked_type == 'dislike' ? 'bb-liked-disliked bb-undo-trigger' : '') . "'  
                    title='dislike' 
                    data-post-id='" . $post_id . "' 
                    data-trigger-type='dislike' 
                    data-already-liked='" . esc_attr($already_liked) . "'>
                <span class='dashicons dashicons-thumbs-down'></span>
                </a>
                <span class='bb-dislike-count-wrap bb-count-wrap'>" . esc_html($dislike_count) . "</span>
            </div>
        </div>";
    }

    public function buildbox_like_dislike_action()
    {
        if (isset($_POST['_wpnonce'])) {
            $post_id = sanitize_text_field($_POST['post_id']);

            if (isset($_COOKIE['bb_' . $post_id])) {
                $response_array = array('success' => false, 'message' => 'Invalid action');
                echo json_encode($response_array);
                die();
            }

            $type = sanitize_text_field($_POST['type']);

            if ($type == 'like') {
                $like_count = get_post_meta($post_id, 'bb_like_count', true);
                if (empty($like_count)) {
                    $like_count = 0;
                }
                $like_count = $like_count + 1;
                $check = update_post_meta($post_id, 'bb_like_count', $like_count);

                if ($check) {
                    $response_array = array('success' => true, 'latest_count' => $like_count);
                } else {
                    $response_array = array('success' => false, 'latest_count' => $like_count);
                }
            } else {
                $dislike_count = get_post_meta($post_id, 'bb_dislike_count', true);
                if (empty($dislike_count)) {
                    $dislike_count = 0;
                }
                $dislike_count = $dislike_count + 1;
                $check = update_post_meta($post_id, 'bb_dislike_count', $dislike_count);
                if ($check) {
                    $response_array = array('success' => true, 'latest_count' => $dislike_count);
                } else {
                    $response_array = array('success' => false, 'latest_count' => $dislike_count);
                }
            }
            setcookie('bb_' . $post_id, $type, time() + 365 * 24 * 60 * 60, '/');

            echo json_encode($response_array);
            die();
        } else {
            die('No script kiddies please!');
        }
    }

    public function buildbox_like_dislike_undo_action()
    {
        if (isset($_POST['_wpnonce'])) {
            $post_id = sanitize_text_field($_POST['post_id']);

            $type = sanitize_text_field($_POST['type']);

            if (!isset($_COOKIE['bb_' . $post_id])) {
                $response_array = array('success' => false, 'message' => 'Invalid action');
                echo json_encode($response_array);
                die();
            }

            if ($type == 'like') {
                $like_count = get_post_meta($post_id, 'bb_like_count', true);
                if (empty($like_count)) {
                    $like_count = 0;
                }
                $like_count = $like_count - 1;
                $check = update_post_meta($post_id, 'bb_like_count', $like_count);

                if ($check) {
                    $response_array = array('success' => true, 'latest_count' => $like_count);
                } else {
                    $response_array = array('success' => false, 'latest_count' => $like_count);
                }
            } else {
                $dislike_count = get_post_meta($post_id, 'bb_dislike_count', true);
                if (empty($dislike_count)) {
                    $dislike_count = 0;
                }
                $dislike_count = $dislike_count - 1;
                $check = update_post_meta($post_id, 'bb_dislike_count', $dislike_count);
                if ($check) {
                    $response_array = array('success' => true, 'latest_count' => $dislike_count);
                } else {
                    $response_array = array('success' => false, 'latest_count' => $dislike_count);
                }
            }

            setcookie('bb_' . $post_id, $type, time() - 3600 * 24 * 365, '/');

            echo json_encode($response_array);
            die();
        } else {
            die('No script kiddies please!');
        }
    }
}