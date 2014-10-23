=== WPDBSpringClean ===
Contributors: wpsolutions
Donate link: http://wpsolutions-hq.com/
Tags: WordPress database, database clean, delete DB tables, cleanup database, DB table optimize, optimize database, db spring clean, wpdbspringclean 
unused plugin tables, database scan
Requires at least: 3.1
Tested up to: 3.3.1
Stable tag: trunk

Scans your WordPress system and identifies and deletes unused database tables from uninstalled plugins. This plugin also allows you to optimize DB Tables.

== Description ==
1) Database Table Clean:

Many plugins do not clean up their database tables after you uninstall them.

What happens most of the time is that the plugin files and folder is deleted but not the corresponding plugin database tables.

This where this plugin comes to the rescue. The WPDBSpringClean plugin identifies unused, WordPress DB tables which have 
been left over from old plugins you have uninstalled on your site and it then gives you the option of deleting these tables.


2) Database Table Optimization:

Due to regular insertions and deletions in the various DB tables on your system, these tables can quite often hold allocated but unused space.
Consequently this can make your DB tables inefficient, fragmented and unoptimized.
This plugin will identify unoptimized tables and will allow you to optimize them by deleting the allocated unused space within a particular table.
The plugin also optionally allows you to specify search criteria such as the minimum amount of overhead per table and minimum unused space for a table.

By default if no search criteria is specified, the plugin will identify all tables which have an overhead of greater than 10%.
(Note: "Overhead" in this plugin is defined as (Data_free/Data_length) expressed as a percentage and where Data_free and Data_length are MySQL table parameters)  

For more information on the WPDBSpringClean and other plugins, visit the <a href="http://wpsolutions-hq.com/" target="_blank">WPSolutions-HQ Blog</a>.
Post any questions or feedback regarding this plugin at our website here: <a href="http://wpsolutions-hq.com/wpdbspringclean/" target="_blank">WPDBSpringClean</a>.

== Installation ==

1. FTP the WPDBSpringClean folder to the /wp-content/plugins/ directory, 

OR, alternatively, 

upload the WPDBSpringClean.zip file from the Plugins->Add New page in the WordPress administration panel.

2. Activate the WPDBSpringClean plugin through the ‘Plugins’ menu in the WordPress administration panel.

If you encounter any bugs, or have comments or suggestions, please contact the WPSolutions-HQ team on support@wpsolutions-hq.com.


== Frequently Asked Questions ==

= Will this plugin prevent me from deleting the wrong table? =

WPDBSpringClean identifies only those database tables which are considered 
not part of any of your installed plugins (either ACTIVE or INACTIVE).
When WPDBSpringClean has finished its query it will list all of the tables it 
believes are not being used and it will give you the option of deleting some or all of these tables.

(As a matter of prudence it is recommended that you take a DB backup if you are unsure about deleting particular tables)

== Screenshots ==

1. Screen shot file screenshot-1.jpg shows the table delete admin menu.
2. Screen shot file screenshot-2.jpg shows the table optimize admin menu.

== Changelog ==

= 1.6 =
* Improved the way in which the "plugins" directories are retrieved when searching. The plugin will now also work correctly for instances where people may have moved the "wp-contents" directory.

= 1.5 =
* Corrected a mistake in the logic which was automatically performing an unused table search when plugin was installed for the first time
* fixed bug where deletion of tables using bulk delete was not working properly

= 1.4 =
* Fixed and tested any incompatibilities and errors for WP 3.5.1

= 1.3 =
* Added new functionality:
WPDBSpringClean now also offers the ability to search for unoptimized DB tables (ie, tables which have a high overhead whereby they have a high percentage of allocated but unused space)
WPDBSpringClean then gives you the option of optimizing these tables so that your database uses its space more efficiently. 

= 1.2 =
Bugs Fixed:
* This release has fixed a problem which some people were encountering which displayed the error: "Maximum execution time of 30 seconds exceeded".
With this fix the search time has been greatly reduced too.

= 1.1 =
* Improved page load performance when a delete is being performed
* Added spinner GIF to represent progress bar during search operation

= 1.0 =
* First Release

== Upgrade Notice ==
= 1.5 =
* Corrected a mistake in the logic which was automatically performing an unused table search when plugin was installed for the first time and when user traversed to the plugin settings page

= 1.3 =
* New Feature: WPDBSpringClean now also offers the ability to optimize your DB tables. 

= 1.2 =
* This release has fixed a problem which some people were encountering which displayed the error: "Maximum execution time of 30 seconds exceeded" 

= 1.1 =
Improved page load time when user performs a table delete

= 1.0 =
First Release

For more information on the WPDBSpringClean and other plugins, visit the <a href="http://wpsolutions-hq.com/" target="_blank">WPSolutions-HQ Blog</a>.
Post any questions or feedback regarding this plugin at our website here: <a href="http://wpsolutions-hq.com/wpdbspringclean/" target="_blank">WPDBSpringClean</a>.
