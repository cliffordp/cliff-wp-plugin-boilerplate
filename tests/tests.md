# Notes for Tests

## About `tric`

The [tric](https://github.com/moderntribe/tric) (Modern **Tri**be **C**ontainers) CLI command provides a containerized and consistent environment for running automated tests.

### Quick example how to run tests via `tric` on your localhost

1. Make sure [`tric` is setup](https://github.com/moderntribe/tric/blob/main/docs/setup.md) and ready to run.
1. `cd` to `plugins`, then `tric here`
1. `cd cliff-wp-plugin-boilerplate`, then `tric use`
1. `tric run tests/wpunit/WpPluginName/Post/DemoJsonArrayTest.php`
    1. Or: `tric shell`
    1. Then run a test, e.g. `cr tests/wpunit/WpPluginName/Post/DemoJsonArrayTest.php`

### tric notes

* **tric does not currently (and may never) support parallel processing.** In other words, if you're working on 2 different plugins at once, you'll need to wait for #1's tests to finish running before switching tric to use #2 and then firing off #2's tests.
* At this time, tric is a functional, reliable, and valuable tool, but it's also in active development.
* Feel free to contribute back by logging detailed Issues or even submitting Pull Requests for code or documentation.
* **Every so often, run `tric upgrade` to get its latest fixes and features.**

## Export meta to JSON with WP-CLI

The `<slug>.json` must match the slug (*post_name*) passed to the Factory.

Find the Post ID from your real (not testing) site's example post (`277` in this example).

Then, from WP's root folder (`/Users/YOU/Local Sites/YOUR-SITE/app/public`) because that's where Local Flywheel puts you for "Open Site Shell":

```
wp post meta list 277 --fields=meta_key,meta_value --format=json > wp-content/plugins/cliff-wp-plugin-boilerplate/tests/_data/remap/post_meta/demo.json;
```

Then format them to be more human-readable (PhpStorm Reformat Code).

Then manually remove unnecessary ones like `_edit_last`, `_edit_lock`, and others that don't start with `wpcf-` (if using Toolset Types) or `{YOUR_PLUGIN_TEXT_DOMAIN}__` if you created custom fields via this boilerplate.

### WP-CLI References:

* [Post Meta List](https://developer.wordpress.org/cli/commands/post/meta/list/)
* [Combine Commands and other Tips](https://make.wordpress.org/cli/handbook/references/shell-friends/)

## CI/CD via GitHub Actions

* The boilerplate's default is to run tests upon Pull Requests to GitHub. If you host on BitBucket, GitLab, et. al. or otherwise don't want to use GitHub Actions (e.g. Travis CI or CircleCI), just delete the top-level `.github` directory. (But if you don't, it won't hurt anything.)
* To get started customizing the action(s) to your own needs, reference [GitHub Actions' documentation](https://docs.github.com/free-pro-team@latest/actions) and get your hands dirty in the `.github/workflows` directory.
* Many other actions exist in their marketplace, [some even specific to WordPress](https://github.com/marketplace?utf8=%E2%9C%93&type=actions&query=wordpress). Examples: publish a new plugin or update an existing plugin on WordPress.org, without touching SVN on your own computer.
* Learn more [about GitHub Actions' pricing](https://docs.github.com/free-pro-team@latest/github/setting-up-and-managing-billing-and-payments-on-github/about-billing-for-github-actions) and [view your current usage](https://docs.github.com/free-pro-team@latest/github/setting-up-and-managing-billing-and-payments-on-github/viewing-your-github-actions-usage).
* Feel free to PR to this boilerplate's repo to add additional actions that are generically helpful.

### External / Third-Party Plugins

* If your plugin requires a third-party plugin (see `src/Bootstrap.php`) and you want to test your plugin's code, you'll need that external plugin's code available to the test.
* For example, if your plugin requires Gravity Forms, but you don't have permissions toGravity Forms' GitHub organization (or is otherwise not available via URL), your code calling the `GF_Field` class would fatal.
* Therefore, the setup with GitHub Actions and tric built in the ability to bundle an external plugin .zip in the `tests/_zips` directory.
* If such functionality is not needed by your plugin's tests, delete this directory and the part of the GitHub Action that references it.

## Database

A database dump is only used for Acceptance tests, which most tests are not. Should you need a dump...

### Steps

This should be all you need, but do see the Flywheel tips below if not: `wp db export wp-content/plugins/cliff-wp-plugin-boilerplate/tests/_data/dump.sql`

### References

[WP-CLI Database Export](https://developer.wordpress.org/cli/commands/db/export/)

FYI for Local Flywheel:

* [Enable TCP/IP database connection on Mac](https://localwp.com/community/t/how-can-i-connect-to-mysql-using-tcp-ip-rather-than-a-socket-on-macos-linux/21220)
* [MySQL Socket](https://localwp.com/community/t/local-5-2-4-cant-connect-to-local-mysql-server-through-socket-macos/17420/14)
* [WP-CLI session](https://localwp.com/community/t/open-site-shell-no-longer-works/22984)

## Testing information and examples

* [WPBrowser](https://wpbrowser.wptestkit.dev/) uses [Codeception](https://codeception.com/for/wordpress), which uses [PHPUnit](https://phpunit.de/) and other tools.
* Using `tric` simplifies the _running_ of such tests in isolation without the overhead or worry about altering or destroying your in-use site, plus is used by the GitHub Actions workflow. You'll need [Docker](https://www.docker.com/products/docker-desktop) installed and running but that's all, no additional Docker tinkering required.
* [The Events Calendar](https://github.com/moderntribe/the-events-calendar/tree/master/tests) and [Event Tickets](https://github.com/moderntribe/event-tickets/tree/master/tests) plugins use a very similar setup and have many tests. They're part of a full plugin suite with other interdependent plugins, so they are likely more complex than you might need, but that also makes them a valuable reference.
