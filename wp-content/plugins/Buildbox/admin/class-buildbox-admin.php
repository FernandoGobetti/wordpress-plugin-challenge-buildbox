<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Buildbox
 * @subpackage Buildbox/admin
 * @author     Fernando Gobetti
 */
class Buildbox_Admin
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
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Plugin_Name_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'css/buildbox-admin.css',
            array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Plugin_Name_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/buildbox-admin.js',
            array('jquery'), $this->version, false);

        $ajax_nonce = wp_create_nonce('bb-backend-ajax-nonce');
        $messages = array(
            'wait' => 'Please wait',);
        $js_object = array(
            'admin_ajax_url' => admin_url('admin-ajax.php'),
            'admin_ajax_nonce' => $ajax_nonce,
            'messages' => $messages);
        wp_localize_script($this->plugin_name, 'bb_admin_js_object', $js_object);

    }


    public function admin_menu_page()
    {
        add_options_page(
            __('Buildbox Like', 'buildbox'),
            __('Buildbox Like', 'buildbox'),
            'manage_options',
            'buildbox-like',
            array($this, 'bb_settings'));
    }

    public function bb_settings()
    {
        include(BUILDBOX_PATH . 'admin/partials/buildbox-admin-settings.php');
    }

    function save_settings()
    {
        if (isset($_POST['_wpnonce']) && current_user_can('manage_options')) {

            $_POST = stripslashes_deep($_POST);
            parse_str($_POST['settings_data'], $settings_data);

            update_option('bb_settings', $settings_data);
            die('Settings saved successfully');
        } else {
            die('No script kiddies please!!');
        }
    }

    function no_permission()
    {
        die('No script kiddies please!!');
    }

    private function sanitize_array($array = array(), $sanitize_rule = array())
    {
        if (!is_array($array) || count($array) == 0) {
            return array();
        }

        foreach ($array as $k => $v) {
            if (!is_array($v)) {

                $default_sanitize_rule = (is_numeric($k)) ? 'html' : 'text';
                $sanitize_type = isset($sanitize_rule[$k]) ? $sanitize_rule[$k] : $default_sanitize_rule;
                $array[$k] = $this->sanitize_value($v, $sanitize_type);
            }
            if (is_array($v)) {
                $array[$k] = $this->sanitize_array($v, $sanitize_rule);
            }
        }

        return $array;
    }
}
