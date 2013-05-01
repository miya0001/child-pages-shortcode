=== Child Pages Shortcode ===
Contributors: miyauchi
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=FR7RD5SGEU69Y
Tags: shortcode
Requires at least: 3.4
Tested up to: 3.5
Stable tag: 1.5.1

You can use shortcode for display child pages from the page.

== Description ==

You can use shortcode for display child pages from the page.

[This plugin maintained on GitHub.](https://github.com/miya0001/child-pages-shortcode)

= Some features =

* This plugin will add shortcode `[child_pages]` display child pages.
* You can customize default HTML template on your plugin.
* This plugin will be able to "excerpt" to the pages.

= Example =

Display child pages of the current page.
`[child_pages width="33%"]`


= Args =

* id - ID of page (Optional)
* size - Post thumbnail size. e.g. 'thumbnail' or 'large'
* width - width of block for child pages.
* disable_shortcode - Shortcode not work in the template if set true.
* disable_excerpt_filters - filters not work for the excerpt if set true.

= filter hooks example =

Filter for query_posts() query.

`<?php
    // default args
    $args = array(
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_parent' => $id_for_the_post,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'nopaging' => true,
    );

    add_filters('child-pages-shortcode-query', "my_query");
    function my_query($args) {
        //
        // some code here
        //
        return $args;
    }
?>`

Filter for default template.

`<?php
    add_filter("child-pages-shortcode-template", "my_template");
    function my_template($template) {
        return '<div class="%class%"><a href="%post_url%">%post_thumb%</a></div>';
    }
?>`

Filter for stylesheet URI.

`<?php
    add_filter("child-pages-shortcode-stylesheet", "my_style");
    function my_style($url) {
        return 'http://example.com/path/to/style.css';
    }
?>`

Default Template

`<div id="child_page-%post_id%" class="child_page" style="width:%width%;">
    <div class="child_page-container">
        <div class="post_thumb"><a href="%post_url%">%post_thumb%</a></div>
        <div class="post_content">
            <h4><a href="%post_url%">%post_title%</a></h4>
            <div class="post_excerpt">%post_excerpt%</div>
        </div>
    </div>
</div>`

Template valiables

* %post_id% - ID of the Page
* %width% - Width of block for single page
* %post_url% - Page permalink
* %post_thumb% - <img> for Post thubmail
* %post_title% - Page title
* %post_excerpt% - Page excerpt

= Support =

* http://wpist.me/wp/child-pages-shortcode/ (en)
* http://firegoby.theta.ne.jp/wp/child-pages-shortcode (ja)

= Contributors =

* [Takayuki Miyauchi](http://wpist.me/)

== Installation ==

* A plug-in installation screen is displayed on the WordPress admin panel.
* It installs it in `wp-content/plugins`.
* The plug-in is made effective.

== Changelog ==

= 1.3.0 =
* setup_postdata() added.

= 1.2.0 =
* bug fix

= 1.1.4 =
* bug fix on non-responsive theme

= 1.1.3 =
* Bug fix

= 1.1.2 =
* Bug fix

= 1.0.1 =
* Add filter hook "child-pages-shortcode-output"

= 0.9.0 =
* Add filter hook "child-pages-shortcode-query" 
* Load stylesheet by wp_enqueue_style()

= 0.8.0 =
* Add style "max-width:100%".

= 0.4.0 =
* add `add_post_type_support("page", "excerpt");`

= 0.3.0 =
* Adapt to no-image.

= 0.1.0 =
* The first release.

== Credits ==

This plug-in is not guaranteed though the user of WordPress can freely use this plug-in free of charge regardless of the purpose.
The author must acknowledge the thing that the operation guarantee and the support in this plug-in use are not done at all beforehand.

== Contact ==

* http://wpist.me/
* [@wpist_me](https://twitter.com/#!/wpist_me)

