=== WordPress Plugin Boilerplate ===

Contributors: cliffpaulick
Donate link: https://www.example.com/
Tags:
Requires at least: 5.2
Tested up to: 5.3.2
Requires PHP: 7.1.0
Stable tag: 1.0.0
License: GPL version 3 or any later version
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Here is a short description of the plugin's purpose & functionality. It should be no more than 150 characters or fewer. No markup is allowed in here.

== Description ==

This is the long description. No limit, and you can use Markdown (as well as in the following sections).

For backwards compatibility, if this section is missing, the full length of the short description will be used, and
Markdown parsed.

A few notes about the sections above:

* "Contributors" is a comma separated list of wp.org/wp-plugins.org usernames
* "Tags" is a comma separated list of tags that apply to the plugin (maximum of 3-5 tags)
* "Requires at least" is the lowest version of WordPress that the plugin will work on
* "Tested up to" is the highest version that you've *successfully used to test the plugin*. Note that it might work on higher versions... this is just the highest one you've verified.
* Stable tag should indicate the Subversion "tag" of the latest stable version, or "trunk," if you use `/trunk/` for
stable.

Note that the `readme.txt` of the stable tag is the one that is considered the defining one for the plugin, so
if the `/trunk/readme.txt` file says that the stable tag is `4.3`, then it is `/tags/4.3/readme.txt` that'll be used
for displaying information about the plugin. In this situation, the only thing considered from the trunk `readme.txt`
is the stable tag pointer. Thus, if you develop in trunk, you can update the trunk `readme.txt` to reflect changes in
your in-development version, without having that information incorrectly disclosed about the current stable version
that lacks those changes -- as long as the trunk's `readme.txt` points to the correct stable tag.

If no stable tag is provided, it is assumed that trunk is stable, but you should specify "trunk" if that's where
you put the stable version, in order to eliminate any doubt.

== Installation ==

This section describes how to install the plugin and get it working.

1. Install the plugin:
    1. Upload the `cliff-wp-plugin-boilerplate.zip` file at wp-admin > Plugins > Add New menu
    1. Or, via SFTP, upload the `cliff-wp-plugin-boilerplate` directory to the `/wp-content/plugins/` directory
1. Activate the plugin:
    1. Visit wp-admin > Plugins
    1. Find the `WordPress Plugin Boilerplate` plugin in the list
    1. Click its "Activate" link
1. Use the plugin:
    1. Visit the wp-admin "Users" screen to be able to do new things

== Frequently Asked Questions ==

= Where's the first question? =

An answer to that question.

= What about foo bar? =

Answer to foo bar dilemma.

== Changelog ==

= 1.0.1 =
* December 1, 2018
* A change since the previous version.
* Another change.

= 1.0.0 =
* October 1, 2018
* List versions from most recent at top to oldest at bottom.
* Reference https://semver.org/ for determine your version numbers.
* Make sure to update the readme.txt header, the cliff-wp-plugin-boilerplate.php header, and the `PLUGIN_VERSION` constant each time you release a new version.

== Upgrade Notice ==

= 1.0.1 =
Upgrade notices describe the reason a user should upgrade. No more than 300 characters.

== Screenshots ==

See https://developer.wordpress.org/plugins/wordpress-org/plugin-assets/ for image dimensions

Screenshot images should be in your SVN directory (not this plugin's directory) and named `screenshot-1.png`, `screenshot-2.png`, etc. to match these captions:

1. A caption for `screenshot-1.png`

2. A caption for `screenshot-2.png`


== Arbitrary section ==

You may provide arbitrary sections, in the same format as the ones above. This may be of use for extremely complicated
plugins where more information needs to be conveyed that doesn't fit into the categories of "description" or
"installation." Arbitrary sections will be shown below the built-in sections outlined above.

== A brief Markdown Example ==

Ordered list:

1. Some feature
1. Another feature
1. Something else about the plugin

Unordered list:

* something
* something else
* third thing

Here's a link to [WordPress](http://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax].
Titles are optional, naturally.

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
Markdown is what the parser uses to process much of the readme file

Markdown uses email style notation for blockquotes and I've been told:
> Asterisks for *emphasis*. Double it up for **strong**.

`<?php code(); // goes in backticks ?>`