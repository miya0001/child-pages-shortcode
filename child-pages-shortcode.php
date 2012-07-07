<?php
/*
Plugin Name: Child Pages Shortcode
Author: Takayuki Miyauchi
Plugin URI: http://wpist.me/wp/child-pages-shortcode/
Description: You can use shortcode for display child pages from the page.
Version: 0.8.0
Author URI: http://wpist.me/
Domain Path: /languages
Text Domain: child-pages-shortcode
*/

new childPagesShortcode();

class childPagesShortcode {

private $ver = '0.4.0';

function __construct()
{
    add_shortcode('child_pages', array(&$this, 'shortcode'));
    add_action("wp_head", array(&$this, "wp_head"));
    add_action("init", array(&$this, "init"));
    add_filter('plugin_row_meta', array(&$this, 'plugin_row_meta'), 10, 2);
}

public function init()
{
    add_post_type_support('page', 'excerpt');
    $js = apply_filters(
        "child-pages-shortcode-js",
        plugins_url("", __FILE__).'/script.js'
    );
    wp_register_script(
        'child-pages-shortcode',
        $js,
        array('jquery'),
        $this->ver,
        true
    );
    wp_enqueue_script('child-pages-shortcode');
}

public function shortcode($p)
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
    return $this->display($p);
}

private function display($p)
{
    $html = sprintf(
        '<div class="child_pages child_pages-%s">',
        esc_attr($p['size'])
    );
    $template = $this->get_template();

    $args = array(
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_parent' => $p['id'],
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'nopaging' => true,
    );

    query_posts($args);
    if (have_posts()):
    while (have_posts()) {
        the_post();
        $img = null;
        if ($tid = get_post_thumbnail_id()) {
            $src = wp_get_attachment_image_src($tid, $p['size']);
            $img = sprintf(
                '<img src="%s" alt="%s" title="%s" />',
                esc_attr($src[0]),
                esc_attr(get_the_title()),
                esc_attr(get_the_title())
            );
        }
        $url = get_permalink(get_the_ID());
        $tpl = $template;
        $tpl = str_replace('%width%', esc_attr($p['width']), $tpl);
        $tpl = str_replace('%post_id%', intval(get_the_ID()), $tpl);
        $tpl = str_replace('%post_title%', esc_html(get_the_title()), $tpl);
        $tpl = str_replace('%post_url%', esc_url($url), $tpl);
        $tpl = str_replace('%post_thumb%', $img, $tpl);
        $tpl = str_replace('%post_excerpt%', get_the_excerpt(), $tpl);
        $html .= $tpl;
    }
    wp_reset_query();
    endif; // end have_posts()

    $html .= '</div>';

    return $html;
}

private function get_template()
{
    $html = '<div id="child_page-%post_id%" class="child_page" style="width:%width%;max-width:100%;">';
    $html .= '<div class="child_page-container">';
    $html .= '<div class="post_thumb"><a href="%post_url%">%post_thumb%</a></div>';
    $html .= '<div class="post_content">';
    $html .= '<h4><a href="%post_url%">%post_title%</a></h4>';
    $html .= '<div class="post_excerpt">%post_excerpt%</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    return apply_filters("child-pages-shortcode-template", $html);
}

public function wp_head()
{
    $url = plugins_url("", __FILE__).'/style.css?ver='.$this->ver;
    printf(
        '<link rel="stylesheet" type="text/css" media="all" href="%s" />'."\n",
        apply_filters("child-pages-shortcode-stylesheet", $url)
    );
}

public function plugin_row_meta($links, $file)
{
    $pname = plugin_basename(__FILE__);
    if ($pname === $file) {
        $links[] = sprintf(
            '<a href="%s">Donate</a>',
            'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8RADH554RPKDU'
        );
    }
    return $links;
}

} // end childPagesShortcode()

// eof
