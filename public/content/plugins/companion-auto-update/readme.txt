=== Companion Auto Update ===
Contributors: Papin, qweb
Donate link: https://www.paypal.me/dakel/2
Tags: auto, automatic, background, update, updates, updating, automatic updates, automatic background updates, easy update, wordpress update, theme update, plugin update, up-to-date, security, update latest version, update core, update wp, update wp core, major updates, minor updates, update to new version, update core, update plugin, update plugins, update plugins automatically, update theme, plugin, theme, advance, control, mail, notifations, enable
Requires at least: 3.5.0
Tested up to: 4.9
Stable tag: 3.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin automatically updates all plugins, all themes and the wordpress core in the background.

== Description ==

= Keep your website safe! =
We understand that you might not always be able to check if your wordpress site has any updates that need to be installed, especially when you maintain multiple websites keeping them up-to-date can be a lot of work.
This plugin enables background auto-updating for all plugins, all themes and the wordpress core (both major and minor updates). 
We give you full control over what is updated and what isn't, via the settings page you can easily disallow auto-updating for either plugins, themes or wordpress core.

= Available settings =
Full control, that's what this plugin is all about. We offer settings to enable or disable automatic updating for plugins, themes, wordpress core updates (both minor and major can be changed separately) and for translation files.

= Know what's happening =
We want you to know what's happening on your website. This plugin offers settings for various email notifications. We can send you an email when an update is available, when a plugin has been updated or when wordpress has been updated.
But if you don't want to recieve emails about this you can still log in and view the changelog to see what happened.

= Advanced Controls =
You can control auto-updating per plugin via the plugin filter. 
For example: If you have Woocommerce installed but you do not wan't to have it auto-updated you can now disable auto-updating for Woocommerce only, so your other plugins will continue to be updated.

= Scheduling =
By default the updater will run twice a day, but you can change this to every hour or to daily. When set to daily you can even set the time at which it should run, this way you can make sure that it will not overload your server by letting it run at times with less activity. The same settings can be set for notifications.

== Installation ==

= Manual install =
1. Download Companion Auto Update.
1. Upload the 'Companion Auto Update' directory to your '/wp-content/plugins/' directory.
1. Activate Companion Auto Update from your Plugins page.

= Via WordPress =
1. Search for 'Companion Auto Update'.
1. Click install.
1. Activate.

= Settings =
Settings can be found trough Tools > Auto Updater

== Frequently Asked Questions ==

= Where can I find the settings? =

You can find the settings under Tools > Auto updating

= How often does this plugin check for updates?  =

By default the updater will run twice a day, but you can change this to every hour or to daily. When set to daily you can even set the time at which it should run.

= Can I change how often it checks and/or updates?  =

Yes you can. Go to the dashboard > Scheduling

= Can I disable auto updating for certain plugins? =

Yes. You can control auto-updating per plugin via the plugin filter. 

= I'm using cPanel and auto-updating doens't work =

If you launched your website a few years ago using cPanel it could be the case that auto-updating is broken. We've contacted cPanel and they said to remove the "AUTOMATIC_UPDATER_DISABLED" line from your wp-config file.

== Screenshots ==

1. Full control over what to update and when to recieve notifications
2. Disable auto-updating for certain plugins
3. Advanced scheduling options for updating and notifcations
4. Keep track of updates with the update log

== Changelog ==

= 3.1.2 (May 14, 2018) =
* Fix error: Notice: Undefined index: cau_page

= 3.1.1 (May 12, 2018) =
* Reorganized the dashboard to cleaner

= 3.1.0 (May 11, 2018) =
* New: Status page. We've introduced a status page to be able to provide better help when an error occurs. This page will be updated with even more info in coming updates.

= 3.0.9 (April 17, 2018) =
* Fix: Successful update emails to multiple adresses. We took our time to really test this one but it works now! Promise!
* Update available emails might still be broken.
* Improvement: Email notifications no long show updates done in the last 2 days but instead changed depending on the interval of the emails.

= 3.0.8 (March 22, 2018) =
* Fix: Error "Notice: Only variables should be passed by reference"

= 3.0.7 (March 15, 2018) =
* We've recieved a bunch of feedback since the last few updates and we've listened!
* If the schedule is NOT daily - hide the hours object. show it only when daily is selected.
* Disable Notifications: Many requested a "Never" option for email notifications, this was already there, just on the dashboard. We want to keep this plugin clean so we're not going to add 2 settings for this, instead we now show a message stating "To disable email notifications go to the dashboard and uncheck everything under Email Notifications".
* Some people reported settings on the schedule page not saving, they were saved but the page required a reload to display the changes. We get how this can be confusing so we've fixed this.

= 3.0.6 (March 14, 2018) =
* Fix: Support & Feedback tab not working

= 3.0.5 (March 14, 2018) =
* Fix: Time schedule scheduling an hour before set time
* New: Support & Feedback page

= 3.0.4 (March 12, 2018) =
* Fix: Schedule Time not able to set! [Read support topic here](https://wordpress.org/support/topic/schedule-time-not-able-to-set/)

= 3.0.3 (February 28, 2018) =
* Added update time to changelog
* Minor tweaks to mobile design

= 3.0.2 (February 10, 2018) =
* Security improvements

= 3.0 (February 10, 2018) =
* New: Set the update time, many requested this feature so here it is :)
* New: Update log
* Fixed issue where multiple emailaddresses wouldn't work.

[View full changelog](https://codeermeneer.nl/stuffs/auto-updater-changelog/)