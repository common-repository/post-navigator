=== Post Navigator ===
Contributors: hello@lukerollans.me, Plugify
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=hello%40lukerollans%2eme&lc=GB&item_name=Plugin%20Development%20Donation&currency_code=USD
Tags: posts,navigation,admin,post navigation,next,previous,admin
Requires at least: 2.9
Tested up to: 3.8
Stable tag: 1.3.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds simple navigation tools to the admin area when editing or creating posts, allowing for quick and time saving navigation

== Description ==

= Have a feature request? =
Sweet! Feature requests on Github: https://github.com/plugify/post-navigator/issues?labels=enhancement

= Want to contribute? =
Post Navigator on Github: http://github.com/plugify/post-navigator

Adds a simple dropdown menu to the publish box which allows a user to select a useful action immediately upon saving or publishing any post. The plugin also adds two new buttons to the edit post page, "Previous" and "Next". These buttons allow you to immediately navigate to the previous or next post of any type in the admin area.

**Features**

* All features of Post Navigator support custom post types
* Upon save, navigate directly to the "Add New" page of the current post type
* Navigate to parent post upon save
* Navigate to child post upon save. When selected, a second dropdown will automatically populate with the children of the current post
* Navigate to a sibling post upon save. When selected, a second dropdown will automatically populate with the siblings of the current post
* Navigate to next or previous post upon save
* Instantly navigate to next or previous post by using the "Previous" and "Next" buttons. These will be displayed, if available, in the header of the current

This plugin was created mainly as a tool to speed up content entry given it allows you to bypass multiple page loads. For example, when adding several new pages, simply choose the "Add a new Page" option and upon saving the current page you will immediately be taken to the "Add a New Page" screen.

If you have found this plugin useful, consider taking a moment to rate it.

== Installation ==

1. Upload the `post-navigator` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently asked questions ==

= How do I use the plugin? =

Once activated, a dropdown menu will appear in the post submit box. This is the box which contains the "Publish" or "Update" button when editing or adding a post. Selecting an action from the dropdown menu will cause that action to occur when the post is saved. Please refer to screenshot 1

Furthermore, you will notice two new buttons in the header of your page when editing or creating a post of any type. Clicking these self describing buttons of "Previous" and "Next" will instantly navigate you to the Previous or Next post. Please refer to screenshot 2

= Does Post Navigator support custom post types? =
Post Navigator supports all custom post types. To configure which post types Post Navigator should work with, go to Settings -> Post Navigator

== Screenshots ==

1. Post Navigator dropdown menu as it appears in the post publish box
2. Instant "Previous" and "Next" navigation buttons as they appear when editing or creating a post of any type

== Changelog ==

= 1.3.4 =
* Maintenance release. No functionality change

= 1.3.3 =
* Added option to view post which is currently being edited in your theme after saving.
* Added option to view all posts of the type currently being edited upon save. Page 1 only at this release.
* Enqueued jQuery in back end only. Not currently used in the front end.

= 1.3.2 =
* Fixed UI display error in new WordPress 3.8 admin theme

= 1.3.1 =
* Fixed bug causing WordPress to halt when creating a post of any type in the backend of which Post Navigator is not enabled switched on for.

= 1.3 =
* Added "Previous" and "Next" options to the Post Navigation action drop down. These options allow you to navigate between the previous and next post of the post you are editing upon update or save.
* Added two new buttons when editing or creating a post which allow the user to immediately navigate to the Previous or Next post. If adding a new post, only "Previous" will be available.

= 1.2.5 =
* Small security updates

= 1.2.4 =
* Small security updates
* Fixed bug which could cause a warning to display on first use
* General code maintenance

= 1.2.3 =
* General code maintenance. Increased stability and compatibility with other plugins which may be using the same filters and actions.

= 1.2.2 =
* Added "action cache" feature which automatically selects the last action in the dropdown if a previous selection exists
* Fixed bug causing unexpected behavior if "No Results Available" is left selected in the child/sibling dropdown

= 1.2.1 =
* Fixed bug causing redirect to fail

= 1.2 =
* Added support for custom post types. To select which post types Post Navigator should work with, go to Settings -> Post Navigator

= 1.1.1 =
* Fixed minor bugs

= 1.0 =
* Initial release of plugin

== Upgrade notice ==

No upgrade notice necessary
