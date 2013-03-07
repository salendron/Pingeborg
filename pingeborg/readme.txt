=== pingeb.org ===
Contributors:      pingeborg
Plugin URI:        http://pep.pingeb.org
Tags:              NFC, QR, geofence, location, GPS, stickers, posters, locationbased, timedependent
Author URI:        http://pingeb.org
Author:            Projekt Ingeborg
Requires at least: 3.0
Tested up to:      3.5beta2
Stable tag:        2.0.0.0
License:           GPLv3 or later
License URI:       http://www.gnu.org/licenses/gpl-3.0.html

The plugin distributes content from a WordPress site to specific locations using NFC-tags, QR-codes or geofences.

== Description ==

pingeb.org is a plugin for WordPress that connects your content to specific locations in the real world. You can distribute stickers/posters with NFC tags and/or QR codes in your town or building and offer location based content. It also supports geofences.

= Many different use cases =
The plugin manages URLs and refreshes content for NFC-tags, QR-codes on stickers and posters or geofences, making possible a wide variety of time-dependent and location-based applications in public places. Here are just some of the things which can be done:

* You can **promote local artists** with samples of their work, as we on [Project Ingeborg](http://pingeb.org).
* You can distribute **e-books or songs** that are out of copyright or to which you have rights.
* You can surprise people with a **daily YouTube video** or a song from Soundcloud.
* You can post **a photo-of-the-day** which people can access with their smartphones.
* You can offer a **daily insider tip** for restaurants or bars around town.
* You can **introduce developers and their apps** and offer links to their pages in app stores.
* You can **promote your own blog or website** around town and do all kinds of advertising for just about anything.
* **Stadiums, museums or exhibitions** can locate visitors indoor and deliver location-based information.

In other words, you can do a lot with this WordPress-plugin and simple NFC-tags, QR-codes or geofences. Without having to change the stickers or posters you can provide fresh content either at all or at selected locations.

For the end user it’s really simple to use:
1. Switch on your NFC-enabled smartphone and hold it against a sticker or photograph the QR-code.
2. A mobile website featuring the content will then be opened.
3. The song or e-book on offer is then just one tap away.

= Workflow =
The plugin works in conjunction with the “Leaflet MapsMarker” plugin, which is also available for free. Both extend the functionality of WordPress and are very easy to use. The workflow sequence is as follows:
1. Add locations in MapsMarker
2. Assign URLs to the locations
3. Assign content to each location either individually or in a batch

= Twitter integration =
You can tweet anytime a user downloads an item from your stickers or the geofence-location. Setup is easy, you only have to register as a [Twitter devloper] (https://dev.twitter.com/).

= Maintenance section =
A handy tool lets you check for possibly removed or dead stickers or posters.

= API =
* A **RESTful API** gives third-party developers easy access to the location data and download-counts to build on them. A documentation of the API can be found at [pep.pingeb.org](http://pep.pingeb.org).

= Other Features =
* The plugin generates random **URLs for NFC-tags and QR-codes** that connect to the content. QR-codes generated within the plugin can be copied easily to any layout program for stickers or posters. Because the plugin supports **geofence**, it is not even necessary to use stickers or posters.
* **Changing the mobile content** (pages in WordPress) for all locations or just one is done with only two mouse clicks.
* A **maintenance section** helps you identify stickers that have been inactive for a while and that may even have been removed.
* Via shortcodes you can easily set up a **statistics** page that shows, for example, the NFC- vs. QR-ratio or the latest downloads on a heatmap. A counter on your site allows you to display today’s downloads, the total number of downloads and the number of active locations.
* The “Leaflet Maps Marker”-plugin enables you to automatically generate a **map with all your locations** on your site. It also permits you to use the locations in an **augmented reality** app like Wikitude.

= Documentation =
A detailed documentation can be found on Github at [pep.pingeb.org](http://pep.pingeb.org).

= We are curious =
If you use the plugin, we are curious about your opinion and use case. Please, send us an e-mail: [mail@pingeb.org](mailto:mail@pingeb.org)

== Installation ==

1. Login on your WordPress site with your user account (needs to have admin rights!)
2. Select "Add New" from the "Plugins" menu
3. Search for **pingeb.org**
4. Click on "Install now"
5. Click on "OK" on the popup "Are you sure you want to install this plugin?"
6. Click "Activate Plugin"

**Note:** Using the plugin requires another one: [Leaflet Maps Marker](http://wordpress.org/extend/plugins/leaflet-maps-marker/)

== Frequently asked questions ==

= Q: How much would it cost to distribute stickers in your town/city? =
A: If you roll out every location with a sticker and an NFC-tag, we estimate the material cost to be around 300 Euro/100,000 inhabitants for one year.

= Q: But what about really big cities? =
A: Just use geofences. The idea behind is simple and powerful: People need to be at a specific location (plus a configurable radius) in order to call the content. While it’s obvious what to do if you roll out stickers, you have to communicate more if you want to use geofences. Why? It is simply not obvious to the user. How should anyone know about it? Plus: You have to find obvious locations. An example would be: Tell the people to open http://yoursite.com anytime the see a subway station.

= Q: For my project, I want to write an iPhone/Android app too. Can I do this? =
A: Absolutely. After installing the plugin on your WordPress site, there’s a set of APIs than can be used for it.

= Q: I found bugs or want to add features. How can I do that? =
A: Just go to the git site of the project and start: [pep.pingeb.org](http://pep.pingeb.org)

= Q: Why should I use random Urls? =
A: You do not have to. This makes the content accessible (almost) exclusively via mobile devices.  Accessed in a conventional way, a project like pingeb.org would be no more than one of many websites, but because action on the part of the user is necessary, this raises the regard for the free piece of art featured and increases the fun of discovering new artists. If you want to, URLs can also be made more “speaking” and obvious.

== Screenshots ==

1. /assets/screenshot-1.png
2. /assets/screenshot-2.png
3. /assets/screenshot-3.png
4. /assets/screenshot-4.png
5. /assets/screenshot-5.png

== Changelog ==
2.0.0.0
New Feature: pingeb.org content can now be autogenerated for each content page, by setting custom fields in WYSIWYG editor.
New Feature: API call /tags now also contains geofence enabled flag, geofence radius and unique content identiefier for each tag.

1.0.1.0
New Feature: Tags can now be exported as csv. This is useful for creating new stickers. (This is no backup!) 
New Feature: You can now autogenerate missing urls for tags. 
New Feature: pingeb.org now has an adminbar menu.
Change: Maps Marker added to info page. 
Change: Plugin author is now pingeb.org. 
Change: Twitter settings now automatically limit tweet texts to 140 characters and do not allow ' and " in texts.

1.0.0.91
Change: API call /api/tags now also shows tags without downloads.
Change: Google Maps API Key removed from settings page. 
Bug fix: Geofences work now even with Wordpress 3.5 and do not require a Google API Key anymore. 

1.0.0.8
Bug fix: Fixed warning at wpdb prepare (Wordpress 3.5)

1.0.0.7
Bug fix: Bigger data type for page ids on db.

1.0.0.6
Just minor bug fixes.

1.0.0.5
New Feature: The plugin now detects if Leaflet Maps Marker is installed. If it isn´t the plugin shows a warning.
New Feature: Info page with license info of  3rd party libraries and infos about supporters.
Bug Fix: NFC vs QR chart now works a bit better.  (still not perfect yet).
Fix: Plugin text 

1.0.0.4 
New Feature: Number of deployed locations in widget.
If no tags there are no tags yet a link to maps marker is shown.
Tags are now also available as GeoJSON via API.

== Other notes ==
= Thank You =
The project was supported by the Internet Foundation Austria (IPA). More at netidee.at.
