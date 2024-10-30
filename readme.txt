==== LH Log sql queries to file ===
Contributors: shawfactor
Author: shawfactor
Donate link: https://lhero.org/portfolio/lh-log-sql-queries-to-file/
Tags: query, log, queries, developer, log-file
Requires at least: 5.0
Tested up to: 6.0
Stable tag: 1.00
License: GPLv2 or later

Log all queries to a text file for development and debugging purposes

== Description ==

This plugin logs all wordpress queries to a log file so you can review them later

Read the faq to learn more


**Like this plugin? Please consider [leaving a 5-star review](https://wordpress.org/support/view/plugin-reviews/lh-log-sql-queries-to-file/).**

**Love this plugin or want to help the LocalHero Project? Please consider [making a donation](https://lhero.org/portfolio/lh-log-sql-queries-to-file/).**

== Frequently Asked Questions ==

= Why did you write this plugin? =

I wrote it as I wanted a simple, developer orientated plugin that would log queries to a file so I could review them later.

= What is something does not work?  =

LH Log sql queries to file, and all [https://lhero.org](LocalHero) plugins are made to WordPress standards. Therefore they should work with all well coded plugins and themes. However not all plugins and themes are well coded (and this includes many popular ones). 

If something does not work properly, firstly deactivate ALL other plugins and switch to one of the themes that come with core, e.g. twentyfirteen, twentysixteen etc.

If the problem persists pleasse leave a post in the support forum: [https://wordpress.org/support/plugin/lh-log-sql-queries-to-file/](https://wordpress.org/support/plugin/lh-log-sql-queries-to-file/) . I look there regularly and resolve most queries.

= Will it slow my site down? =

Maybe slightly, but the effect should be minimal.

= Where can I find the log file? =

The log file can be found in your wp-content directory

= Can I change the location of the log file? =

There is a filter in the plugin called lh_log_sql_queries_to_file_get_log_file_path which can be used to chage the file location

= Will this file grow huge? =

No by default the file will be deleted once it grows beyond 4 MBs. This size treshold can be filtered by using lh_log_sql_queries_to_file_size_threshold

= Does this pugin contain a log file viewer? =

No it is deliberately simple

= What if I need a feature that is not in the plugin?  =

Please contact me for custom work and enhancements here: [https://shawfactor.com/contact/](https://shawfactor.com/contact/)

== Installation ==


1. Upload the entire `lh-log-sql-queries-to-file` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.

== Changelog ==

**1.00 May 25, 2021**  
Initial release