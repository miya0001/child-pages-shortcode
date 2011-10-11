=== Child Pages Shortcode ===
Contributors: miyauchi
Donate link: http://firegoby.theta.ne.jp/
Tags: shortcode 
Requires at least: 3.2
Tested up to: 3.2
Stable tag: 0.3.0

Shortcode display child pages.

== Description ==

Shortcode display child pages.

[This plugin maintained on GitHub.](https://github.com/miya0001/child-pages-shortcode)

= Some features =

* Shortcode display child pages.
* You can customize default HTML template on your plugin.

= Example =

Display child pages of the current page.
`[child_pages]`

= Args =

* id - ID of page (Optional)
* size - Post thumbnail size. e.g. 'thumbnail' or 'large'
* width - width of block for child pages.

= filter hooks example =

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

= Support =

* @miya0001 on twitter.
* http://www.facebook.com/firegoby
* https://github.com/miya0001/child-pages-shortcode

= Contributors =

* [Takayuki Miyauchi](http://firegoby.theta.ne.jp/)

== Installation ==

* A plug-in installation screen is displayed on the WordPress admin panel.
* It installs it in `wp-content/plugins`.
* The plug-in is made effective.

== Changelog ==

= 0.3.0 =
* Adapt to no-image.

= 0.1.0 =
* The first release.

== Credits ==

This plug-in is not guaranteed though the user of WordPress can freely use this plug-in free of charge regardless of the purpose.
The author must acknowledge the thing that the operation guarantee and the support in this plug-in use are not done at all beforehand.

== Contact ==

twitter @miya0001
