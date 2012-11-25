=== pingeb.org ===
Contributors: pingeborg
Donate link: 
Tags: NFC, QR, geofence, location, GPS, stickers, posters
Requires at least: 3.0
Tested up to: 3.4.2
Stable tag: 
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

The plugin distributes content via a WordPress site in the real world using either stickers with NFC tags or QR codes or requiring the user to be at a certain location (geofence).

== Description ==

pingeb.org is a plugin for WordPress that connects your content to specific locations in the real world. You can distribute stickers/posters with NFC tags and/or QR codes in your town or building and offer location based content.

= Many use cases =
The plugin manages URLs and refreshes content for NFC-tags, QR-codes on stickers and posters or geofences, making possible a wide range of time-dependent and location-based applications in public places. Here are just some of the things which can be done with it:

* You can **promote local artists** with samples of their work, as we did with [Project Ingeborg](http://pingeb.org).
* You can distribute **e-books or songs** that are out of copyright or to which you have rights.
* You can surprise people with a **daily YouTube video** or a song from Soundcloud.
* You can post **a photo-of-the-day** which people can access with their smartphones.
* You can offer a **daily insider tip** for restaurants or bars around town.
* You can **introduce developers and their apps** and offer links to their pages in app stores.
* You can **promote your own blog or website** around town and do all kinds of advertising for just about anything.
* **Stadiums, museums or exhibitions** can locate visitors indoor and deliver location-based information.

In other words, you can do a lot with this WordPress-plugin and simple NFC-tags, QR-codes or geofences. Without having to change the stickers or posters you can even provide fresh content every day either at all or at selected locations. 

For the end user it’s really simple to use:
1. Switch on your NFC-enabled smartphone and hold it against a sticker or photograph the QR-code.
1. A mobile website featuring the content will then be opened.
1. The song or e-book on offer is then just one tap away.

= Workflow =
The plugin works in conjunction with the “Leaflet MapsMarker” plugin, which is also available for free. Both extend the functionality of WordPress and are very easy to use. The workflow sequence is as follows:
1. Add locations in MapsMarker
1. Assign URLs to the locations
1. Assign content to each location either individually or in a batch

= Features =
* The plugin generates random **URLs for NFC-tags and QR-codes** that connect to the content. QR-codes generated within the plugin can be copied easily to any layout program for stickers or posters. Because the plugin supports **geofence**, it is not even necessary to use stickers or posters.
* **Changing the content** (pages in WordPress) for the mobile locations is done with just two mouse clicks.
* One option allows you to send a **tweet** anytime a user discovers a new artist or downloads content from a mobile website.
* A **maintenance section** helps you identify stickers that have been inactive for a while and that may even have been removed.
* Via shortcodes you can easily set up a **statistics** page that shows, for example, the NFC- vs. QR-ratio or the latest downloads on a heatmap. A counter on your site allows you to display today’s downloads total and the overall number of downloads.
* A **RESTful API** gives users like you and third-party developers a few tools to build with.
* The “Leaflet Maps Marker”-plugin enables you to automatically generate a **map with all your locations** on your site. It also permits you to use the locations in an **augmented reality** app like Wikitude.

= We are curious =
If you use the plugin, we are curious about your opinion and use case. Please, send us an e-mail: [mail@pingeb.org](mailto:mail@pingeb.org)

== Installation ==

1. Using the plugin requires another plugin: [Leaflet Maps Marker](http://wordpress.org/extend/plugins/leaflet-maps-marker/)
1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently asked questions ==

=Q: How much would it cost to do a project like yours in my city/town?=
A: If you roll out every location with a sticker and an NFC-tag, we estimate the material cost to be around 300 Euro/100,000 inhabitants for one year.

=Q: But what about really big cities?=
A: Just use geofences. The idea behind is simple and powerful: People need to be at a specific location (plus a configurable radius) in order to call the content. While it’s obvious what to do if you roll out stickers, you have to communicate more if you want to use geofences. Why? It is simply not obvious to the user. How should anyone know about it? Plus: You have to find obvious locations. An example would be: Tell the people to open http://yoursite.com anytime the see a subway station.

=Q: For my project, I want to write an iPhone/Android app too. Can I do this?=
A: Absolutely. After installing the plugin on your WordPress site, there’s a set of APIs than can be used for it.

=Q: I found bugs or want to add features. How can I do that?=
A: Just go to the git site of the project and start: http://pep.pingeb.org

=Q: Why should I use random Urls?=
A: You do not have to. This makes the content accessible (almost) exclusively via mobile devices.  Accessed in a conventional way, a project like pingeb.org would be no more than one of many websites, but because action on the part of the user is necessary, this raises the regard for the free piece of art featured and increases the fun of discovering new artists. If you want to, URLs can also be made more “speaking” and obvious.

== Screenshots ==

1. http://pingeb.org/pingeborg/wp-content/uploads/wordpress.org/plugin.png

2. http://pingeb.org/pingeborg/wp-content/uploads/wordpress.org/tags.png

3. http://pingeb.org/pingeborg/wp-content/uploads/wordpress.org/settings1.png

4. http://pingeb.org/pingeborg/wp-content/uploads/wordpress.org/settings2.png

5. http://pingeb.org/pingeborg/wp-content/uploads/wordpress.org/maintenance.png

== Changelog ==



== Upgrade notice ==



== Arbitrary section 1 ==
