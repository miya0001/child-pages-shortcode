<?php
/*
Plugin Name: Child Pages Shortcode
Author: Takayuki Miyauchi
Plugin URI: http://firegoby.theta.ne.jp/wp/child-pages-shortcode
Description: Display child pages.
Version: 0.1.0
Author URI: http://firegoby.theta.ne.jp/
Domain Path: /languages
Text Domain: child-pages-shortcode
*/

new childPagesShortcode();

class childPagesShortcode {

function __construct()
{
    add_shortcode('child_pages', array(&$this, 'shortcode'));
    add_action("wp_head", array(&$this, "wp_head"));
    add_action("init", array(&$this, "init"));
}

public function init()
{
    $js = apply_filters(
        "child-pages-shortcode-js",
        WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/script.js'
    );
    wp_register_script(
        'child-pages-shortcode',
        $js,
        array('jquery'),
        null,
        true
    );
    wp_enqueue_script('child-pages-shortcode');
}

public function shortcode($p)
{
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
    $posts = $this->get_posts($p);
    $size = $p['size'];
    $html = sprintf(
        '<div class=" child_pages child_pages-%s">',
        esc_attr($size)
    );
    $template = $this->get_template();
    foreach ($posts as $post) {
        $img = null;
        if ($tid = get_post_thumbnail_id($post->ID)) {
            $src = wp_get_attachment_image_src($tid, $size);
            $img = sprintf(
                '<img src="%s" alt="" title="%s" />',
                esc_attr($src[0]),
                esc_attr($post->post_title)
            );
        }
        $url = get_permalink($post->ID);
        $tpl = $template;
        $tpl = str_replace('%width%', esc_attr($p['width']), $tpl);
        $tpl = str_replace('%post_id%', intval($post->ID), $tpl);
        $tpl = str_replace('%post_title%', esc_html($post->post_title), $tpl);
        $tpl = str_replace('%post_url%', esc_url($url), $tpl);
        $tpl = str_replace('%post_thumb%', $img, $tpl);
        $tpl = str_replace('%post_excerpt%', esc_html($post->post_excerpt), $tpl);
        $html .= $tpl;
    }
    $html .= '</div>';

    return $html;
}

private function get_template()
{
    $html = '<div id="child_page-%post_id%" class="child_page" style="width:%width%;">';
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
    $url = WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/style.css';
    printf(
        '<link rel="stylesheet" type="text/css" media="all" href="%s" />'."\n",
        apply_filters("child-pages-shortcode-stylesheet", $url)
    );
}

private function get_posts($p)
{
	global $post;
	if( !isset($p['id']) || !intval($p['id']) ){
		$p['id'] = $post->ID;
	}
    $args = array(
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_parent' => $p['id'],
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'numberposts' => '-1',
    ); 

    return get_posts($args);
}

}
?>
