<?php

class Buildbox_Report_Shortcode
{
    private $loader;

    public function __construct($loader)
    {
        $this->loader = $loader;
        $this->register_admin_hooks();
    }

    public function register_admin_hooks()
    {
        $this->loader->add_action('init', $this, 'register_shortcodes');
    }

    function register_shortcodes()
    {
        $this->loader->add_shortcode('buildbox-top-liked', $this, 'buildbox_shortcode_function');
    }

    function buildbox_shortcode_function()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'postmeta';

        $report_row = $wpdb->get_results(
            "SELECT post_id, meta_value AS popularity, post_title FROM {$table} wpm 
                    JOIN wp_posts wp ON wp.ID = wpm.post_id 
                    WHERE meta_key = 'bb_like_count' AND wp.post_status NOT IN ('auto-draft', 'trash') ORDER BY cast(meta_value AS unsigned) DESC",
            ARRAY_A
        );

        if (count($report_row) >= 1) {
            return $this->buildbox_shortcode_html($report_row);
        } else {
            return "<div class='aligncenter'>Nenhum item encontrado!</div>";
        }
    }

    function buildbox_shortcode_html($data)
    {
        $html = "<table class='table table-striped table-bordered'>
                    <thead  class='thead-light'>
                        <tr>
                            <th scope='col'>ID</th>
                            <th scope='col'>Post Title</th>
                            <th scope='col'>Popularyty</th>
                        </tr>
                    </thead>
                <tbody>";

        foreach ($data as $item) {
            $html .= "<tr>
                        <th scope='row'>{$item['post_id']}</th>
                        <td>{$item['post_title']}</td>
                        <td>{$item['popularity']}</td>
                      </tr>";
        }

        $html .= "  </tbody></table>";

        return $html;
    }
}




