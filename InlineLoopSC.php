<?php defined('ABSPATH') OR die('No direct script access allowed.');
/*
Plugin Name: Inline Loop [Shortcode]
Description: Use shortcodes to loop through other posts/pages/whatever.
Plugin URI: http://github.com/nocash/wp-inline-loop-sc
Author: Beau Dacious <dacious.beau@gmail.com>
Author URI: http://cxzcxz.com
Version: 0.11.3
*/

class InlineLoopSC {

    public function __construct()
    {
        add_action('init', array($this, 'add_shortcodes'));
    }

    public function add_shortcodes()
    {
        add_shortcode('inlineloop', array($this, 'shortcode_callback'));
    }

    public function shortcode_callback($atts)
    {
        extract( shortcode_atts( array(
            'query' => NULL,
            'template' => NULL
        ), $atts));

        // decode HTML entities in the query string
        if ( ! empty($query) )
        {
            $query = html_entity_decode($query);
        }

        // run our custom query
        query_posts($query);

        // buffer output and call our custom loop template
        ob_start();
        get_template_part('inline-loop', $template);
        $content = ob_get_contents();
        ob_end_clean();

        // restore the original loop query
        wp_reset_query();

        return $content;
    }

}

new InlineLoopSC;
