# WordPress Plugin Boilerplate with Support for Composer, PHP Namespaces, and WordPress Customizer
<!-- ALL-CONTRIBUTORS-BADGE:START - Do not remove or modify this section -->
[![All Contributors](https://img.shields.io/badge/all_contributors-5-orange.svg?style=flat-square)](#contributors-)
<!-- ALL-CONTRIBUTORS-BADGE:END -->


## Features

* The Boilerplate is based on the [Plugin API](http://codex.wordpress.org/Plugin_API), [Coding Standards](http://codex.wordpress.org/WordPress_Coding_Standards), and [Documentation Standards](https://make.wordpress.org/core/handbook/best-practices/inline-documentation-standards/php/).
* All classes, functions, and variables are documented so that you know what you need to change.
* The Boilerplate uses a strict file organization scheme that corresponds both to the WordPress Plugin Repository structure, and that makes it easy to organize the files that compose the plugin.
* The project includes a `.pot` file as a starting point for internationalization.

### Highlights

* Built in a way that isn't just a skeleton (blank slate) but, instead, a fully-functioning plugin out of the box. We're of the opinion that it's quicker to dev and easier to learn if you can just **delete what you don't need and rework existing code into what you do need.**
* Thorough code documentation with regular updates.
* Adds a wp-admin Settings page with **React** components and the Settings API (restricts to Administrators). Bonus: legacy code that direct-links to the plugin's options in the WordPress **Customizer** (so use one or both for your plugin's options).
* Plugin assets (CSS and JS) are served minified with external sourcemaps, but unminified files exist as well for when `SCRIPT_DEBUG` is `true` or you're running Parcel's watching / Hot Module Replacement (HMR).
* Displays a wp-admin error notice to administrators if the required version of PHP is not met, saving users from a fatal error.
* Easily add a new shortcode by extending the abstract `Shortcode` class and adding to the array of shortcodes in the `Manage_Shortcodes` class.
* Primarily relies upon [Composer](https://getcomposer.org/) and [Parcel](https://parceljs.org/getting_started.html) to build the plugin and make some complex stuff pretty simple to get up and running quickly.
* Displays a wp-admin error notice to administrators if a required third-party plugin (e.g. WooCommerce) is not active.
* Includes a number of generally-helpful utility functions, such as getting all public post types, flattening an array of unknown dimensions, and sane option setters and getters.
* Uses Composer to zip the files for public distribution as an installable plugin, making sure to exclude build files and directories.

## Installation

The Boilerplate can be installed directly into your plugins folder "as-is". You will want to rename it and the classes inside of it to fit your needs.

1. Copy this *cliff-wp-plugin-boilerplate* repository/directory to your *wp-content/plugins* directory and rename your new plugin's directory
1. Delete the `.github` directory
1. Delete the `.all-contributorsrc` file
1. Perform a ***case-sensitive*** *search and replace* at the project level, as follows:
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
1. Make sure everything in `composer.json` is appropriate to your project.
    1. You do not need `tgmpa/tgm-plugin-activation` if your plugin does not require or recommend any other plugins or themes.
    1. Make sure to update the main plugin file's logic accordingly if you fully remove this library.
    1. Make sure to update the main plugin file's class properties:
        1. `$min_php`
        1. `$required_theme`
        1. `$required_plugins`
1. Go through all your PHP and JSON files to make sure your plugin descriptions are set.
1. Make sure everything in `package.json` is also appropriate to your project.
1. Run `composer install`
1. Run `npm update` (we purposefully don't commit package or composer lock files in the boilerplate, but you should in your repo)
1. Run `npm install`
1. Run `npm run start` if actively developing in React or CSS (HMR), else `npm run build`
1. Activate the plugin
1. Check if everything's working as it should (that it can be activated and without any errors)
1. If it works (as it should), ***delete THIS README.md FILE***

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
    
    
### Using Parcel

#### Getting Started

Make sure to have _yarn_ or _npm_ installed on your computer. The following are obviously _npm_ commands:

1. Open your plugin folder in your Terminal.
1. Run `npm install` so your `package.json` files (*node_modules*) get installed.
1. Run `npm run dev`, which will fire the "dev" command from the package.json's "scripts" (so you know where to look to understand the _real_ command it's running, which is Parcel).
1. Activate your plugin and see your Admin area has goofy styles and JavaScript _alert()_ noise. This is to confirm Parcel is running successfully and to annoy you so you get started on your customizations. ;)
1. Once your CSS and JS customizations are complete, run `npm run build` or just run Composer's _archive_ command, which will do a production build for you.

#### Generating and Distributing the .zip

1. **Once ready to build the finalized .zip to distribute to your site or to others...**
    1. `php composer.phar archive --file cliff-wp-plugin-boilerplate` *(name yours correctly)*
    1. Because we did not set a `--dir` argument for the `archive` command, Composer will create the .zip right in the project's directory. *#Convenient!*
1. Unzip this newly-created `cliff-wp-plugin-boilerplate.zip` file to make sure it got built correctly (excluding files like `.gitignore`, `composer.json`, `package.json`, etc).
1. Upload this .zip to your production site or wherever you want to distribute it.
1. Delete this .zip file from your hard drive.

### Plugin Structure

Following is the pre-built plugin structure. You can add your own new class files (include `namespace` and `use` at the top) by naming them correctly and putting the files in the most appropriate location.

* `cliff-wp-plugin-boilerplate/src/Admin` - admin-specific functionality
* `cliff-wp-plugin-boilerplate/src/Common` - functionality shared between the admin area and the public-facing parts
* `cliff-wp-plugin-boilerplate/src/Core` - plugin core to register hooks, load files etc
* `cliff-wp-plugin-boilerplate/src/Customizer` - WordPress Customizer functionality
* `cliff-wp-plugin-boilerplate/src/Frontend` - public-facing functionality

### PHP Version

This plugin requires PHP 7.1.0 or newer and will display a wp-admin error notice if activated in an environment that does not meet this or other requirements (such as required plugins or other dependencies you may code).

You can see the current WordPress usage of each PHP version at https://wordpress.org/about/stats/. A requirement of 7.1+ meets 53.9% of all WordPress installs as of February 9, 2020. Most of those not using PHP 7.1+ are assumed to be inactive sites.

Your requiring a PHP version update for anyone who might want to use your plugin will actually benefit them long-term, as their site will be quicker, more secure, and ready for future version bumps. In fact, [WordPress already recommends using PHP 7.3+](https://wordpress.org/about/requirements/) and has an ["Update PHP" help article](https://wordpress.org/support/update-php/).

# Developer Notes

### Updates
For each new version, don't forget to:
* Add a changelog entry to `readme.txt`
* Update the version number:
  * In your `readme.txt` file's header
  * In your main plugin file's header
  * In your main plugin file's `PLUGIN_VERSION` constant
* [Generate a fresh POT file](https://developer.wordpress.org/plugins/internationalization/localization/#generating-pot-file)

### How to generate your .pot file

We do not use a variable for strings' text domain because it does not work when using the WP CLI command, nor WordPress.org. [Reference](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#text-domains)

Here's the WP CLI command you can customize to your needs:

`wp i18n make-pot . languages/cliff-wp-plugin-boilerplate.pot --headers='{"Report-Msgid-Bugs-To":"Your Name or Your Company <https://www.example.com/>"}'` 

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

#### March 23, 2020
* Admin Settings page: Add tabbed navigation with icons in the tab names and styling to support the wp-admin color schemes. [2 minute demo video](https://share.getcloudapp.com/YEuAzYGO)
* Change the _Strings_ utility class to implement the [voku/stringy](https://github.com/voku/Stringy) [library](https://packagist.org/packages/voku/stringy).
* Change the way Assets (CSS/JS) are handled, making them have to be registered before enqueued (best practice) and make it easier to do so for our internal assets, only needing the file name from the */dist* folder.
* Add more reliable detection of when is a frontend request--example: `Common::current_request_is( 'frontend' )`--to improve performance, additionally being able to detect more, like REST API, Ajax, or WP-Cron requests.

#### March 22, 2020
* Improve the JavaScript build for WordPress React, reducing the `admin-settings.js` file size:
  * Minified: from 265.91 KB to 35.16 KB **(87% reduction)**
  * Unminified: from 803.68 KB to 48.15 KB **(94% reduction)**
* Improve the PostCSS build process to disable *modules* (rewriting selectors), enable writing nested CSS, and enable variables.
* Admin Settings page:
  * Protect components that get disabled while saving from getting permanently disabled if the API response never comes back (such as if PHP terminates).
  * Force displaying an error notification even if the API response was technically successful but isn't really due to a `null` response.
  * Fix the example radio button's validation logic in `register_setting()` by adding the correct "show_in_rest" > "schema" > "enum" args, removing the "sanitize_callback" arg, and using the "rest_api_init" hook.
  * Added basic styling.
* Changed plugin text domains from variable to string to allow using WP CLI command and be compliant with WordPress.org out of the box.

#### March 18, 2020
* Rebuild admin Settings Page:
    * Rebuilt via React and JSX.
    * Requires React version 16.8+ to be able to use [Hooks](https://reactjs.org/docs/hooks-intro.html), which means we now require [WordPress version 5.2+](https://core.trac.wordpress.org/browser/tags/5.2/package.json) (from [May 7, 2019](https://wordpress.org/download/releases/))
    * Only loads CSS/JS for the Settings Page when we're on the Settings Page.
    * Added a "greet box" in the heading area for things like your logo and social links.
    * Added a few demo settings fields (like toggle) to help get up and running quickly.
* [Hot Module Replacement (HMR)](https://parceljs.org/hmr.html) is now working when the plugin is active on a localhost WordPress installation. This is awesome because, for example, you could edit your PCSS from `p { color: blue; }` to `p { color: green; }` and your text will be green-colored before you can even switch back from your code editor to your web browser! (Yes, it works for JavaScript, too.)
* Add easy asset handle maker to keep styles and scripts named consistently but uniquely. Example: `Plugin_Data::get_asset_handle( 'admin-settings' )`
* `Loader()` itself now fires on `'init'` priority `3` instead of the default `10` so that we can add our own `add_action( 'init', ... );` without needing to also pass a priority greater than `10`.
* Add `Http` utility class, becoming a helper to get `$_REQUEST` values without needing to use the `[tk_request]` example shortcode (so just delete it unless you need it).

#### February 19, 2020
* Rework the Post Utility class' `post_id_helper()` to be simplified as well as accepting a Post Type filter.
* Enhance the abstract shortcode class to automatically register each shortcode with [Toolset Views](https://toolset.com/?aid=5336&affiliate_key=Lsvk04DjJOhq) (enabled/disable per shortcode).
* Fix an array/string _type_ error in the abstract shortcode class.

#### February 13, 2020
* Running Composer's _archive_ command now does an _npm_ build for production to ensure we've got the latest-greatest.
* Fix the build process (JS and CSS) so unminified files get shipped so they can be loaded per the [SCRIPT_DEBUG](https://wordpress.org/support/article/debugging-in-wordpress/#script_debug) constant, according to WordPress best practices.
* Entirely change the build process (from Gulp+Sass to Parcel+PostCSS) for simplicity, many small gains (Hot Module Replacement + you can still use Sass instead of or in addition to PostCSS), and a more flexible foundation going forward.
* Note that Parcel's PostCSS does nothing more than concatenate Common's _.pcss_ files with Admin's and Frontend's. It's not currently running _autoprefixer_ or other PostCSS plugins. This is an issue with Parcel 1 that I wasn't able to resolve and shipped anyway because it's still an overall improvement to the boilerplate. Parcel 2 should eventually resolve this issue.

#### February 8, 2020
* Rename PHP class file names to match class names, including capitalization, according to PSR-4

#### January 30, 2020
* Editable JS and CSS (moved to SCSS) moved to `development` folder and NPM build process implemented

##### April 29, 2019
* Declutter main plugin file by creating new `Bootstrap` class
* Now requires PHP version 7.1.0 (up from 5.6.0)
* Added argument type and return type declarations (including scalar, which is why 7.1+ is needed, plus 7.0 was deprecated as of December 3, 2018)

##### April 27, 2019
* Refactor classes to be smaller and more intentional, including multiple _utilities_ classes and consolidating settings
* Moved *defines* and related to `Plugin_Data` class (has static methods because of hard-coded values)
* Created abstract `Shortcode()` class, which should be extended when creating your own new shortcodes (`[tk_request]` is still included as an example)
* Fix `class_exists()` checks to be namespace-aware
* Remove unused `libraries` and `views` directories throughout

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

## Contributors ✨

Thanks goes to these wonderful people ([emoji key](https://allcontributors.org/docs/en/emoji-key)):

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->
<table>
  <tr>
    <td align="center"><a href="https://github.com/Stefan2409"><img src="https://avatars2.githubusercontent.com/u/17899913?v=4" width="100px;" alt=""/><br /><sub><b>Stefan Jöbstl</b></sub></a><br /><a href="https://github.com/cliffordp/cliff-wp-plugin-boilerplate/commits?author=Stefan2409" title="Code">💻</a></td>
    <td align="center"><a href="https://neal.codes"><img src="https://avatars3.githubusercontent.com/u/5731551?v=4" width="100px;" alt=""/><br /><sub><b>Neal Fennimore</b></sub></a><br /><a href="https://github.com/cliffordp/cliff-wp-plugin-boilerplate/pulls?q=is%3Apr+reviewed-by%3Anealfennimore" title="Reviewed Pull Requests">👀</a></td>
    <td align="center"><a href="http://www.scottfennell.com/wp"><img src="https://avatars3.githubusercontent.com/u/1585637?v=4" width="100px;" alt=""/><br /><sub><b>Scott Fennell</b></sub></a><br /><a href="https://github.com/cliffordp/cliff-wp-plugin-boilerplate/commits?author=scofennell" title="Code">💻</a></td>
    <td align="center"><a href="http://www.hardeepasrani.com"><img src="https://avatars1.githubusercontent.com/u/2649903?v=4" width="100px;" alt=""/><br /><sub><b>Hardeep Asrani</b></sub></a><br /><a href="#example-HardeepAsrani" title="Examples">💡</a></td>
    <td align="center"><a href="https://florian-rappl.de"><img src="https://avatars3.githubusercontent.com/u/1766191?v=4" width="100px;" alt=""/><br /><sub><b>Florian Rappl</b></sub></a><br /><a href="#plugin-FlorianRappl" title="Plugin/utility libraries">🔌</a></td>
  </tr>
</table>

<!-- markdownlint-enable -->
<!-- prettier-ignore-end -->
<!-- ALL-CONTRIBUTORS-LIST:END -->

This project follows the [all-contributors](https://github.com/all-contributors/all-contributors) specification. Contributions of any kind welcome!
