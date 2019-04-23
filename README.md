# WordPress Plugin Boilerplate with Support for Composer, PHP Namespaces, and WordPress Customizer


## Features

* The Boilerplate is based on the [Plugin API](http://codex.wordpress.org/Plugin_API), [Coding Standards](http://codex.wordpress.org/WordPress_Coding_Standards), and [Documentation Standards](https://make.wordpress.org/core/handbook/best-practices/inline-documentation-standards/php/).
* All classes, functions, and variables are documented so that you know what you need to change.
* The Boilerplate uses a strict file organization scheme that corresponds both to the WordPress Plugin Repository structure, and that makes it easy to organize the files that compose the plugin.
* The project includes a `.pot` file as a starting point for internationalization.

### Highlights

* Displays a wp-admin error notice to administrators if the required version of PHP is not met
* Displays a wp-admin error notice to administrators if a required third-party plugin (e.g. WooCommerce) is not active
* Easily add a new shortcode by having the shortcode named the same as a method in the `Common` class
* Adds a wp-admin Settings page with a link to the plugin's options in the WordPress Customizer
* Includes a custom *Sortable Checkboxes* control in the WordPress Customizer and examples how to use it
* Includes a number of generally-helpful utility functions, such as getting all public post types, flattening an array of unknown dimensions, and option getters

## Installation

The Boilerplate can be installed directly into your plugins folder "as-is". You will want to rename it and the classes inside of it to fit your needs.

* Go to your *wp-content/plugins* directory, copy this *cliff-wp-plugin-boilerplate* repository/directory here, and rename it to your new plugin's directory
* Perform a *case-sensitive* search and replace at the project level as follows:
    1. Rename the `cliff-wp-plugin-boilerplate` directory to `your-plugin-name`. **This is your new plugin directory and must match your text domain.**
    1. Find the text `cliff-wp-plugin-boilerplate` and replace with `your-plugin-name` in all files
    1. Find the text `wp_plugin_name` and replace with `your_plugin_name` in all files
    1. Find the text `WordPress Plugin Boilerplate` and replace with `Your Plugin Name` in all files
    1. Find the text `WP_Plugin_Name` and replace with `Your_Plugin_Name` in all files (the *namespace*)
    1. Rename the `pot` file under `languages` and replace the string `cliff-wp-plugin-boilerplate` with `your-plugin-name`
    1. Find the text `https://www.example.com/` and replace with your URI in all files
    1. Find the text `Your Name or Your Company` and replace with your name in all files
    1. Find the text `your@email.address` and replace with your email address in `composer.json`
    1. Find the text `cliffpaulick` and replace with your WordPress.org username (or delete it) in `readme.txt`
    1. Find the text `yourname` and replace with whatever you want [as your vendor name](https://getcomposer.org/doc/04-schema.md#name)) in `composer.json` (such as your GitHub username)
    1. Make other edits to `readme.txt` as appropriate for your own plugin
* Make sure everything in `composer.json` is appropriate to your project.
    1. You do not need `tgmpa/tgm-plugin-activation` if your plugin does not require or recommend any other plugins or themes.
    1. Make sure to update the main plugin file's logic accordingly if you fully remove this library.
    1. Make sure to update the main plugin file's class properties:
        1. `$min_php`
        1. `$required_theme`
        1. `$required_plugins`
* Run Composer `install`
* Activate the plugin
* If it works (as it should), ***delete THIS README.md FILE***

### Using Composer

#### Getting Started

Visit https://getcomposer.org/ to learn all about it.

Here are some quick notes about Composer, in general, and this project's use of it:
1. You need to [install Composer](https://getcomposer.org/download/) on your desktop/laptop, not your server. You can download it right into your `cliff-wp-plugin-boilerplate` directory.
1. The `composer.json` file is the *instructions* file that tells the `composer.phar` how to build your `vendor` directory (which includes the autoloader), and possibly do other things.
1. Run `php composer.phar install` to generate your `composer.lock` file.
1. Because `composer.json` has `"optimize-autoloader": true` inside the config key, *you will need to run Composer's `update` if you ever add a new PHP class*
    1. See https://getcomposer.org/doc/articles/autoloader-optimization.md for more details.
    1. It is set this way to lean toward distribution convenience more than development convenience.

#### Generating and Distributing the .zip

1. **Once ready to build the finalized .zip to distribute to your site or to others...**
    1. `php composer.phar archive --file cliff-wp-plugin-boilerplate` *(name yours correctly)*
    1. Because we did not set a `--dir` argument for the `archive` command, Composer will create the .zip right in the project's directory. *#Convenient!*
1. Unzip this newly-created `cliff-wp-plugin-boilerplate.zip` file to make sure it got built correctly (excluding files like .gitignore, composer.json, etc).
1. Upload this .zip to your production site or wherever you want to distribute it.
1. Delete this .zip file from your hard drive.

### Plugin Structure

Following is the pre-built plugin structure. You can add your own new class files (include `namespace` and `use` at the top) by naming them correctly and putting the files in the most appropriate location.

* `cliff-wp-plugin-boilerplate/src/admin` - admin-specific functionality
* `cliff-wp-plugin-boilerplate/src/common` - functionality shared between the admin area and the public-facing parts
* `cliff-wp-plugin-boilerplate/src/core` - plugin core to register hooks, load files etc
* `cliff-wp-plugin-boilerplate/src/customizer` - WordPress Customizer functionality
* `cliff-wp-plugin-boilerplate/src/frontend` - public-facing functionality

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

The WordPress Plugin Boilerplate uses a **variable** (e.g. `$this->plugin_text_domain`) to store the text domain, used when internationalizing strings.

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

##### April 23, 2019
* Remove unused `libraries` and `views` directories throughout
* Fix `class_exists()` checks to be namespace-aware

##### March 13, 2019
* Fix `tk_request()` in *Common* to better support array values

##### February 5, 2019
* Added `output_to_log()` utility function to *Common* to enable writing to `WP_DEBUG_LOG` and optionally send an email, such as to the site administrator.
* Renamed `wp_plugin_name_get_plugin_display_name()` to `get_plugin_display_name()` to remove prefix since we are within our own namespace.

##### February 1, 2019
* Add `string_ends_with()` and `get_string_between_two_strings()` utility functions.

##### January 31, 2019
* Simplify the CSS and JS file names to speed up initial setup by avoiding unnecessary file renaming.
* Simplify boilerplate's repository files so boilerplate can be ran as a plugin itself ("out of the box" as they say), which helps with testing things work before committing changes to the repo.
* Fix logic for Common's `get_option()` and `get_option_as_array()`.

##### January 30, 2019
* Add link to plugin options screen in the Plugins List admin screen.
* Add plugin options screen that links to WordPress Customizer panel.
* Add methods for getting all Customizer options, deleting all options, and getting a single option (as raw, string, or array).
* Add a custom Customizer Control for multiple checkboxes, optionally sortable. Big thanks to [Scott Fennell](http://scottfennell.org/) for the start to the code [and permission to use](https://twitter.com/TourKick/status/1089524933133303808). The version included here is heavily modified and follows this repository's license. *Still needs work if wanting to use `<select>` within each checkbox.*
* Add example Customizer options to help get a quick start.
* Add utility function to detect current URL. 
* Add utility function to get public post types, sorted by their labels.
* Tweak - `Common` as class constructor (dependency injection) instead of singleton instance. [These](https://akrabat.com/what-problem-does-dependency-injection-solve/) [articles](http://fabien.potencier.org/what-is-dependency-injection.html) provide simple examples and explanations if you are curious.
* Tweak - Add try/catch around DateTime(), although it shouldn't actually affect code.
* Tweak - Wrap each class within `class_exists()`.
* Tweak - Add `ABSPATH` check to top of all PHP files.
* Tweak - Remove all `@since` and `@access` tags. Remove all `@link` tags to the example link.

##### January 29, 2019
* Add `flatten_array()` utility method to Common.

##### January 23, 2019
* Fix loading logic regarding Admin and Frontend to allow both to run during Ajax.

##### January 22, 2019
* Fix to allow Admin hooks to run during Ajax.

##### December 2, 2018
* Add ability to require a parent and/or child theme.
* Implement [TGM Plugin Activation](http://tgmpluginactivation.com/) for required plugins (does not handle requiring a theme). At this time, it does not handle non-bundled premium plugins very well (adding incorrect download links to the TGMPA admin screen), but it does enhance some functionality:
  * displaying plugin nice name
  * requiring a minimum version number
  * adding the ability to mark a plugin recommended without being required
  * adding the ability to link to the plugin (the only way to tell people where to download the plugin manually)

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
