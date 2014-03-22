=== CPT on Front Page ===
Contributors: fabrizim
Donate link: http://owlwatch.com/
Tags: front page, custom post types
Requires at least: 3.5
Tested up to: 3.8.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows post types other than "page" to be displayed as the static front page.

== Description ==

Choose the post types that you would like to choose from in addition to Pages. The
"Front Page:" dropdown will then display each different post type in their own groups.

This will not result in a redirect on the front page as has been seen in other attempts
to provide this functionality. In addition, the canonical url will be properly reflected
as the home url.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('plugin_name_hook'); ?>` in your templates

== Frequently Asked Questions ==

= Where to do I add the post types? =

You can select them in the "Reading" section of the settings.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets 
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png` 
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0 =
* Initial commit.