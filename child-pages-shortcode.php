<?php
/*
Plugin Name: Child Pages Shortcode
Author: Takayuki Miyauchi
Plugin URI: https://github.com/miya0001/child-pages-shortcode
Description: You can use shortcode for display child pages from the page.
Version: 1.9.3
Author URI: http://wpist.me/
Domain Path: /languages
Text Domain: child-pages-shortcode
*/

$child_pages_shortcode = new Child_Pages_Shortcode();
$child_pages_shortcode->register();

class Child_Pages_Shortcode {

private $ver = '1.1.4';

function register()
{
    add_action('plugins_loaded', array($this, 'plugins_loaded'));
}

function plugins_loaded()
{
    add_shortcode("child_pages", array($this, "shortcode"));
    add_action("init", array($this, "init"));
    add_action("wp_enqueue_scripts", array($this, "wp_enqueue_scripts"));
}

public function init()
{
    add_post_type_support('page', 'excerpt');
}

public function wp_enqueue_scripts()
{
    /*
     * Filter the stylesheet URI
     *
     * @since none
     * @param string $stylesheet_uri URI to the stylesheet.
     */
    $css = apply_filters(
        "child-pages-shortcode-stylesheet",
        plugins_url("css/child-pages-shortcode.min.css", __FILE__)
    );

    wp_enqueue_style(
        'child-pages-shortcode-css',
        $css,
        array(),
        $this->ver,
        'all'
    );

    /*
     * Filter the JavaScript URI
     *
     * @since none
     * @param string $javascript_uri URI to the JavaScript.
     */
    $js = apply_filters(
        "child-pages-shortcode-js",
        plugins_url("js/child-pages-shortcode.min.js", __FILE__)
    );

    wp_enqueue_script(
        'child-pages-shortcode',
        $js,
        array('jquery'),
        $this->ver,
        false
    );
}

public function shortcode($p, $template = null)
{
	if( !isset($p['id']) || !intval($p['id']) ){
		$p['id'] = get_the_ID();
	}

    if (!isset($p['size']) || !$p['size']) {
        $p['size'] = 'thumbnail';
    }

    if (!isset($p['width']) || !intval($p['width'])) {
        $p['width'] = "50%";
    }

    if (!isset($p['disable_shortcode']) || !$p['disable_shortcode']) {
        add_filter("child-pages-shortcode-output", "do_shortcode");
    }

    return $this->display($p, $template);
}

private function display($p, $block_template)
{
    global $post;

    $html = '';

    if ($block_template) {
        $template = $block_template;
        $template = str_replace('<p>', '', $template);
        $template = str_replace('</p>', '', $template);
        /*
         * Filter the temaplate
         *
         * @since none
         * @param string $template Template HTML.
         */
        $template = apply_filters(
            'child-pages-shortcode-template',
            $template,
            $p
        );
    } else {
        /*
         * Filter the temaplate
         *
         * @since none
         * @param string $template Template HTML.
         */
        $template = apply_filters(
            'child-pages-shortcode-template',
            $this->get_template(),
            $p
        );
        $html = sprintf(
            '<div class="child_pages child_pages-%s">',
            esc_attr($p['size'])
        );
    }

    $args = array(
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_parent' => $p['id'],
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'nopaging' => true,
    );

    /*
     * Filter the query args for the get_posts()
     *
     * @since none
     * @param array $args Query args. See http://codex.wordpress.org/Class_Reference/WP_Query#Parameters.
     */
    $args = apply_filters('child-pages-shortcode-query', $args, $p);

    $pages = get_posts($args);
    foreach ($pages as $post) {
        setup_postdata($post);
        /*
         * Filter the $post data.
         *
         * @since none
         * @param object $post Post data.
         */
        $post = apply_filters('child_pages_shortcode_post', $post);
        $url = get_permalink($post->ID);
        $img = get_the_post_thumbnail($post->ID, $p['size']);
        $img = preg_replace( '/(width|height)="\d*"\s/', "", $img);
        $tpl = $template;
        $tpl = str_replace('%width%', esc_attr($p['width']), $tpl);
        $tpl = str_replace('%post_id%', intval($post->ID), $tpl);
        $tpl = str_replace('%post_title%', $post->post_title, $tpl);
        $tpl = str_replace('%post_url%', esc_url($url), $tpl);
        $tpl = str_replace('%post_thumb%', $img, $tpl);
        if (isset($p['disabled_excerpt_filters']) && $p['disabled_excerpt_filters']) {
            $tpl = str_replace('%post_excerpt%', $post->post_excerpt, $tpl);
        } else {
            $tpl = str_replace('%post_excerpt%', get_the_excerpt(), $tpl);
        }
        $tpl = str_replace('%post_content%', get_the_content(), $tpl);
        $html .= $tpl;
    }

    wp_reset_postdata();

    if (!$block_template) {
        $html .= '</div>';
    }

    /*
     * Filter the output.
     *
     * @since none
     * @param string $html     Output of the child pages.
     * @param array  $pages    An array of child pages.
     * @param string $template Template HTML for output.
     */
    return apply_filters("child-pages-shortcode-output", $html, $pages, $template);
}

private function get_template()
{
    $html = "\n";
    $html .= '<div id="child_page-%post_id%" class="child_page" style="width:%width%;max-width:100%;">';
    $html .= '<div class="child_page-container">';
    $html .= '<div class="post_thumb"><a href="%post_url%">%post_thumb%</a></div>';
    $html .= '<div class="post_content">';
    $html .= '<h4><a href="%post_url%">%post_title%</a></h4>';
    $html .= '<div class="post_excerpt">%post_excerpt%</div>';
    $html .= '</div><!-- .post_content  -->';
    $html .= '</div><!-- .child_page-container -->';
    $html .= '</div><!-- #child_page-%post_id%" -->';
    $html .= "\n";

    if ($tpl = get_post_meta(get_the_ID(), 'child-pages-template', true)) {
        $html = $tpl;
    }

    return $html;
}

} // end class

// eof
