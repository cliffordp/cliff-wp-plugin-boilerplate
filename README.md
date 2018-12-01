# WordPress Plugin Boilerplate with Support for Composer and Namespaces


## Contents

The `wp-plugin-name` directory contains the source code - a fully executable WordPress plugin.

## Features

* The Boilerplate is based on the [Plugin API](http://codex.wordpress.org/Plugin_API), [Coding Standards](http://codex.wordpress.org/WordPress_Coding_Standards), and [Documentation Standards](https://make.wordpress.org/core/handbook/best-practices/inline-documentation-standards/php/).
* All classes, functions, and variables are documented so that you know what you need to change.
* The Boilerplate uses a strict file organization scheme that corresponds both to the WordPress Plugin Repository structure, and that makes it easy to organize the files that compose the plugin.
* The project includes a `.pot` file as a starting point for internationalization.

### Highlights

* Displays a wp-admin error notice to administrators if the required version of PHP is not met
* Displays a wp-admin error notice to administrators if a required third-party plugin (e.g. WooCommerce) is not active
* Easily add a new shortcode by having the shortcode named the same as a method in the `Common` class

## Installation

The Boilerplate can be installed directly into your plugins folder "as-is". You will want to rename it and the classes inside of it to fit your needs.

* Copy wp-plugin-name to your plugin's directory and rename it to your plugin's name
* Perform a find and replace at the project level as follows:
    1. Find the text `wp-plugin-name` and replace with `your-plugin-name` in all files. This **must** match your plugin directory and its text domain.
    1. Find the text `wp_plugin_name` and replace with `your_plugin_name` in all files
    1. Find the text `WordPress Plugin Boilerplate` and replace with `Your Plugin Name` in all files
    1. Find the text `WP_Plugin_Name` and replace with `Your_Plugin_Name` in all files (the *namespace*)
    1. Rename the `css` and `js` files under `src/admin/css`, `src/admin/js/`, `src/views/js`, `src/views/css` and replace the string `wp-plugin-name` with `your-plugin-name`
    1. Rename the `pot` file under `languages` and replace the string `wp-plugin-name` with `your-plugin-name`
    1. Find the text `https://www.example.com/` and replace with your URI in all files
    1. Find the text `Your Name or Your Company` and replace with your name in all files
    1. Find the text `your@email.address` and replace with your email address in `composer.json`
    1. Find the text `cliffpaulick` and replace with your WordPress.org username (or delete it) in `readme.txt`
    1. Find the text `yourname` and replace with whatever you want [as your vendor name](https://getcomposer.org/doc/04-schema.md#name)) in `composer.json` (such as your GitHub username)
* Make sure everything in `composer.json` is appropriate to your project.
* Run Composer `install`
* Activate the plugin

### Using Composer

#### Getting Started

Visit https://getcomposer.org/ to learn all about it.

Here are some quick notes about Composer, in general, and this project's use of it:
1. You need to [install Composer](https://getcomposer.org/download/) on your desktop/laptop, not your server. You can download it right into your `wp-plugin-name` directory.
1. The `composer.json` file is the *instructions* file that tells the `composer.phar` how to build your `vendor` directory (which includes the autoloader), and possibly do other things.
1. Run `php composer.phar install` to generate your `composer.lock` file.
1. Because `composer.json` has `"optimize-autoloader": true` inside the config key, *you will need to run Composer's `update` if you ever add a new PHP class*
    1. See https://getcomposer.org/doc/articles/autoloader-optimization.md for more details.
    1. It is set this way to lean toward distribution convenience more than development convenience.

#### Generating and Distributing the .zip

1. **Once ready to build the finalized .zip to distribute to your site or to others...**
    1. `php composer.phar archive --file wp-plugin-name` *(name yours correctly)*
    1. Because we did not set a `--dir` argument for the `archive` command, Composer will create the .zip right in the project's directory. *#Convenient!*
1. Unzip this newly-created `wp-plugin-name.zip` file to make sure it got built correctly (excluding files like .gitignore, composer.json, etc).
1. Upload this .zip to your production site or wherever you want to distribute it.
1. Delete this .zip file from your hard drive.

### Plugin Structure

Following is the pre-built plugin structure. You can add your own new class files (include `namespace` and `use` at the top) by naming them correctly and putting the files in the most appropriate location.

* `wp-plugin-name/src/admin` - admin-specific functionality
* `wp-plugin-name/src/core` - plugin core to register hooks, load files etc
* `wp-plugin-name/src/frontend` - public-facing functionality
* `wp-plugin-name/src/common` - functionality shared between the admin area and the public-facing parts
* `wp-plugin-name/src/libraries` - third-party libraries that the plugin uses (like a Composer `vendor` directory but for stuff that isn't able to be installed via Composer)

### PHP Version

This plugin requires PHP 5.6 or newer and will display a wp-admin error notice if activated in an environment that does not meet this or other requirements (such as required plugins or other dependencies you may code).

# Developer Notes

### Updates
For each new version, don't forget to:
* Add a changelog entry to `readme.txt`
* Update the version number:
  * In your `readme.txt` file's header
  * In your main plugin file's header
  * In your main plugin file's `PLUGIN_VERSION` constant
* [Generate a fresh POT file](https://developer.wordpress.org/plugins/internationalization/localization/#generating-pot-file)

### The BoilerPlate uses a variable for the Text Domain

The WordPress Plugin Boilerplate uses a **variable** (`$this->plugin_text_domain`) to store the text domain, used when internationalizing strings.

If you face problems translating the strings with an automated tool/process, replace `$this->plugin_text_domain` with the literal string of your plugin's text domain throughout the plugin.

### References:
* [Here's a discussion from the original project in favor of using variables](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate/issues/59)
* [The Plugin Handbook Recommended Way (i.e. not to use variables)](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#text-domains)

# License

This WordPress Plugin Boilerplate is licensed under *GPL version 3 or any later version*.

> This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 3 or any later version, as published by the Free Software Foundation.

> This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

> A copy of the GNU General Public License should be included in the root of this plugin's directory. The file is named `license.txt`; if not, obtain one before using this software by visiting https://www.gnu.org/licenses/gpl-3.0.html or writing to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA


If you opt to use third-party code that is not compatible with this software's license, then you may need to switch to using code that is compatible.

As an example, [here's a discussion](http://make.wordpress.org/themes/2013/03/04/licensing-note-apache-and-gpl/) that states GPLv2-only plugins could not bundle work licensed with Apache 2.0.


# Credits

The WordPress Plugin Boilerplate was started in 2011, by [Tom McFarlin](http://twitter.com/tommcfarlin/) and has since included a number of great contributions. In March of 2015, the project was handed over by Tom to Devin Vinson.

This plugin boilerplate was created by [Clifford Paulick](https://github.com/cliffordp/) in 2018, as a fork of [WordPress Plugin Boilerplate with Namespace and Autoloader Support](https://github.com/karannagupta/WordPress-Plugin-Boilerplate), which forked the [WordPress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate) project to add support for Composer (including autoloading) and namespaces.

# Contributing

**[Reporting issues](https://github.com/cliffordp/WordPress-Plugin-Boilerplate/issues) -- and especially submitting [Pull Requests](https://github.com/cliffordp/WordPress-Plugin-Boilerplate/pulls) -- are welcome!** Do not contribute if you do not agree to this software's license terms.

# Boilerplate's Changelog

Documenting this project's progress...

##### December 1, 2018
* Improve main plugin class' loading, removing static methods and singleton.
* `Common` class: Use a singleton instead of static methods.
* Removed all `@author` DocBlocks, [per WordPress' best practices](https://make.wordpress.org/core/handbook/best-practices/inline-documentation-standards/php/#other-tags):
  * > It is WordPress policy not to use the `@author` tag, except in the case of maintaining it in external libraries. We do not want to imply any sort of "ownership" over code that might discourage contribution.
* Fix `Common::post_id_helper()` to not return `0` when passed `0`. Instead, will go through to the logic to automatically determine the Post ID.

##### October 6, 2018
* Now requires Composer. [See instructions, above.](https://github.com/cliffordp/cliff-wp-plugin-boilerplate#using-composer)
* Fix `Common::tk_request()` and add new `$default` and `$escape` parameters.

##### September 13, 2018
* Added 'ABSPATH' checks at the beginning of all PHP files
* Added `wp_plugin_name_get_plugin_display_name()` to main plugin file
* Removed `PLUGIN_NAME` constant and replaced all usage with `PLUGIN_TEXT_DOMAIN` since they were duplicates (as they should have been). Kept it as "plugin_text_domain" in the name instead of something like "plugin_id" or "plugin_slug" to help IDE autocomplete suggestions when using translation functions.
* Added `plugin_text_domain_underscores()` to Common

##### September 12, 2018
* Added a few nice helper methods to Common
* Improved readme.txt

##### September 3, 2018
* Initial Release