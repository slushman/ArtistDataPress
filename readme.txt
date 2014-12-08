=== ArtistDataPress ===
Contributors: slushman
Donate link: http://slushman.com/
Tags: artistdata, xml, musician, widget
Requires at least: 2.9.1
Tested up to: 4.0.1
Stable tag: 0.72
License: GPLv2

ArtistDataPress displays your ArtistData feed on a page, post, or as a sidebar widget.

== Description ==

ArtistDataPress allows you to display your ArtistData calendar on any WordPress site and inherits the theme's style.  The plugin and widget options allow you to chose what show information people see on your page, post, and/or sidebar.

Features
    
* Display your ArtistData calendar on a page or post using the [artistdatapress] shortcode
* Override plugin settings in the shortcode to change how many shows are displayed
* Override plugin settings in the shortcode to display data from a different feed URL 
* Add a widget to your sidebar and use the options to decide what show information is displayed
* Styling is inherited from your theme and customization is super simple using CSS
* Multiple options for choosing what gets displayed on the templates
* Time and date formats are customizable
* Multiple responsive layout options available for the show listings
* Create your own template for displaying shows using the provided functions and filters or just the raw feed data
* If no state is listed for the show, the country is shown instead
* If there are no shows in your feed, the "no shows scheduled" message is customizable
* Feed is now saved as a WordPress transient for faster page loading

== Installation ==

1. Upload the artistdatapress folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enter your ArtistData feed URL in the plugin settings
4. Add the [artistdatapress] shortcode on the page or post where you'd like to display your calendar
5. Optionally, drag the ArtistDataPress Widget to a sidebar on the 'Widgets' page under the 'Appearance' menu

== Frequently Asked Questions ==

= How do I display my ArtistData calendar on a page or in a post? =

Use the shortcode: [artistdatapress]. Just place that on the page or post and publish!

= Can I put another ArtistDataPress feed on a page with another feed? =

Yes! In the shortcode, put "feedurl=" followed by the other url. Don't put a space after the equal sign. This will override the URL in the plugin settings.

= Can I have multiple feeds displayed with different amounts of shows displayed? =

Yes! In the shortcode, put "maxshows=" followed by the the amount of shows you want displayed by that instance. This will override the plugin settings for this instance.

= Where do I find the "ArtistData XML feed URL"? =

Log into your ArtistData account, then go to Tools > Data Feeds. Copy one of the URLs and paste it into the ArtistData XML feed URL field on the plugin options page. I imagine most people will use the XML Feed URL for Future Shows. The example from my band is: http://feeds.artistdata.com/xml.shows/artist/AR-8FAD4948ACC579CB/xml/future

= What is the "ArtistData Profile URL"? =

This is the link to your ArtistData profile page. For example, the one for my band, The Vibe Dials, is: http://artistdata.sonicbids.com/the-vibe-dials/shows/. However, this really can be any URL, as long as its a valid URL, so you could make this a link to your shows page or wherever.

= Where is the venue's address? =

Instead of displaying the venue's address, the city and state become a Google Maps link (and are tagged with "- map") if there is a venue address, otherwise it just displays the city and state.

= Where is the venue's website URL? =

The name of the venue is a link to the venue's website. If there isn't a venue website, it just displays the venue's name.

= How do I customize the time and / or date formats? =

The time and date fields use the standard PHP format options. WordPress has an excellent page that explains how to use them. http://codex.wordpress.org/Formatting_Date_and_Time

= I've added new shows, but they aren't showing up on my site yet, what gives? =

In version 0.6, I added feed caching, which means ADP won't need to go ask ArtistData's servers for your data every time the page loads. The cache cleares every hour, so that also means you might need to wait about an hour for new items to appear. However, this caching radically speeds up the performance of ADP, so your shows page loads faster, the widgets load faster, and the settings page loads faster because ADP isn't waiting to get the data from ArtistData. I think the speed is well worth the trade-off of a one-hour delay. If you disagree, let me know, that time limit can be changed and I'll change it based on feedback from users.

= How do I build my own template? =

See my [link] (http://slushman.com/plugins/artistdatapress/artistdatapress-template-codex "Template Codex page") for details, there's too much to put in this FAQ.

== Screenshots ==

1. ArtistDataPress General Settings page
2. ArtistDataPress Layout Settings page
3. ArtistDataPress Widget Options
4. ArtistData Classic layout on a page with all options on
5. ArtistData iCal layout on a page with all options on
6. Widget with the Classic layout and all options on
7. Widget with the iCal layout and all options on
8. Custom "no shows" message on page
9. Custom "no shows" message on widget

== Changelog ==

= 0.72 =
BUG FIX: Resolved the "undefined function get_example_data()" error
BUG FIX: Removed the transient caching - causing too many errors and complaints
* Removed "Custom" template option; added custom template explanation instead.
* Changed Twitter account info
* Added plugin icon to assets

= 0.71 =
BUG FIX: Removed a file, but didn't remove the inclusion of said file. Fixed.

= 0.7 =
* Added the option to use your own template
* Added the Make_Template class with functions for creating template parts easily
* Added filters for customizing the class attributes
* Added option to always abbreviate the state
* Added option to always abbreviate the country
* BUG FIX: Empty feeds were displaying the current date.

= 0.61 =
* BUG FIX: fixed bug stopping layouts from being included by making the name lowercase

= 0.6 =
* Added feed caching through WP transients to improve plugin performance
* Made all layouts responsive
* Added a widget field for displaying a different feed
* Changed CSS for the widgets to be simpler and more consistent
* Changed CSS selectors for the widgets to this format: adp_w_
* Changed plugin settings fields to Slushman Toolkit functions
* Changed support section to floated divs and using FontAwesome for icons
* BUG FIX: The "More Shows" link in the widget now displays the URL entered in the plugin settings.

= 0.52 =
* Removed the gibberish from files conflicts with Subversion

= 0.51 =
* Took out erroneous line in adp_widget.php

= 0.5 =
* Added shortcode option for limiting shows displayed
* Added shortcode option for displaying a different feed
* Added iCal widget layout
* Changed from cURL to wp_remote_get for fetching the XML feed
* Rebuilt plugin settings and renamed the options
* Moved layout functions to main file
* Moved all layouts to separate files in the layout folder
* Created CSS files for each layout
* Rebuilt layout.php to remove most logic
* Moved most logic to the layout files

= 0.412 =
* Fixed widget headers for proper formatting.

= 0.411 =
* Fixed widget "More Shows" link to display URL from the "ArtistData Profile URL" field in the plugin settings.

= 0.41 =
* Changed map link to exclude state if it's "No State"
* Changed map link and displayed address to use full state name instead of abbreviation
* Changed map link and displayed address to use full country name instead of abbreviation
* Added a check to see if the PHP settings on the server are compatible with cURL and ADP.

= 0.4 =
* Added Layout class to make building new layouts easier
* Added the "iCal" layout
* Converted "Classic" layout to Layout class
* Converted "Classic" widget to Layout class
* Changed option names to be consistent
* Changed CSS IDs and classes to allow for additional layouts
* Shows without a zip code now display
* Added General and Layout tabs to settings pages
* Layout options display either live feed data or built-in sample data
* BUG FIX: ISO time returns blank if there is no timezone

= 0.374 =
* Removed an empty if statement that was displaying on the layout.

= 0.373 =
* Worked with ArtistData on correcting the "System/LocalTime" / "Unknown or bad timezone" display error.  Created a backup method of getting the correct timestamp in case that does show up in the feed again.  Also added an option to customize the time and date outputs to any format supported by PHP via a "custom" box.

= 0.371 =
* Corrected the errors on the settings page as well as the "There seems to be a problem with your feed URL." error.  Laid the groundwork for adding additional layouts easily.  Changed the value of the 'Display how many shows?' field - if it's left empty, it will now display "All", if it's not empty, it will display the number.  Also, removed the layout CSS and put it into a separate file.  Corrected the CSS for the -map notification and rebuilt that entire section so it would display as originally intended.  Removed the "work-around" since it wasn't working anyway.

= 0.36 =
* Fixed the bugs regarding what links get shown and which don't.  Changed the empty checks to a version that works.  Also added a potential solution for those with PHP's safe_mode turned on.

= 0.35 =
* Changed the last is_array check to !is_array so it will display the data on the page.  Also added XML validation so a false URL in the ADP data feed field will output an error.

= 0.34 =
* Added better error outputting.  Fixed the error displayed if there are no shows scheduled.

= 0.33 =
* Fixed the redirect error by adding CURLOPT_AUTOREFERER, CURLOPT_FOLLOWLOCATION, and CURLOPT_MAXREDIRS to curl_setopt. cURL will now follow the redirect and fetch the XML info at the final URL.

= 0.32 =
* Error fixes.

= 0.31 =
* Corrected the error with the ADP widget - changed "WP_PLUGIN_URL" to "dirname( __FILE__ )"

= 0.3 =
* The feed is now fetched using cURL, which should be more reliable
* Dates and times are now customizable

= 0.2 =
* If there are no shows in the feed, a customizable message is displayed
* Changed output HTML to use DIVs and CSS styling instead of HTML tables
* Added the option to display the country abbreviation
* The country is displayed if there is no state

= 0.1 =
* Plugin created.

== Upgrade Notice ==

= 0.72 =
BUG FIX: get_example_data() and caching errors fixed.

= 0.71 =
BUG FIX: Removed a file, but didn't remove the inclusion of said file. Fixed.

= 0.7 =
Create your own templates!

= 0.61 =
BUG FIX: fixed bug stopping layouts from being included by making the name lowercase

= 0.6 =
Added feed caching, responsive layouts, and bug fixes.

= 0.52 =
Removed the gibberish from files conflicts with Subversion

= 0.51 =
Removed erroneous line in widget code

= 0.5 =
Solved the URL fetch issues, added the iCal widget layout, and many more improvements!

= 0.412 =
Fixed widget headers for proper formatting.

= 0.411 =
Fixed widget "More Shows" link to display URL from the "ArtistData Profile URL" field in the plugin settings.

= 0.41 =
Changed maps link to work better with international addresses & added compatibility checks and warnings

= 0.4 =
Added multiple show listing layouts, new options, and much more!

= 0.374 =
Removes the "if() {}" from the venue location.

= 0.373 =
Fixed the "Unknown or bad timezone" display error.  Added a "custom" option for time and date display.

= 0.371 =
Fixed the "XML feed" error and the settings page errors.

= 0.36 =
Any blank URLs will now display correctly.  Also, if your server has safe_mode turned on, this work-around may work for you.

= 0.35 =
Fixed the "blank shortcode" error and added XML validation.

= 0.34 =
Added better error outputting.  Fixed the error displayed if there are no shows scheduled.

= 0.33 =
Artistdata is redirecting the XML feed URLs, but this update follows that redirection and fetches the XML feed at the final URL.

= 0.32 =
Error fixes.

= 0.31 =
* Corrected the error with the ADP widget

= 0.3 =
Feeds are now fetched via cURL for better performance. Dates and times are now customizable too.

= 0.2 =
If you have no shows in your feed, this version displays a custom "no shows" message on the page and widget. It also adds the option to display the country abbreviation and will show the country name if no state is listed. The output now uses all divs instead of an HTML table.

= 0.1 =
Plugin released.