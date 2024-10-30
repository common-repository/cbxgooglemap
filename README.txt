=== CBX Map for Google Map & OpenStreetMap ===
Contributors: codeboxr, manchumahara
Donate link: https://codeboxr.com
Tags: google map, openstreetmap, openstreet, gutenberg block, elementor addons
Requires at least: 3.5
Tested up to: 6.4.3
Stable tag: 1.1.12
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easy google map and open streetmap embed using shortcode, Responsive.

== Description ==

CBX Map is a WordPress plugin that helps to display Google map and OpenStreetMap inside worpress. It’s easy to use using shortcode and map loads responsive. From the plugin’s seeing create map, find adress and configure easily with just mouse click.

### CBX Map for Google Map & OpenStreetMap by [Codeboxr](https://codeboxr.com/product/cbx-google-map-for-wordpress/)

>📺 [Live Demo](https://codeboxr.net/wordpress/cbxgooglemap/codeboxr/) | 🌟 [Upgrade to PRO](https://codeboxr.com/product/cbx-google-map-for-wordpress/#downloadarea) | 📋 [Documentation](https://codeboxr.com/doc/cbxmap-doc/) | 👨‍💻 [Free Support](https://wordpress.org/support/plugin/cbxgooglemap/) | 🤴 [Pro Support](https://codeboxr.com/contact-us) | 📱 [Contact](https://codeboxr.com/contact-us/)

## 🛄 Core Plugin Features ##

*   Google MAP or Openstreep map(no api key needed)
*   Custom post type for map
*   Easy Shortcode
*   Works without custom post type using the same shortcode [cbxgooglemap]
*   Responsive with browser width and resize
*   Info window
*   Default global Setting
*   Meta field for custom post type
*   Easy geo complete feature while finding proper marker position in custom post type edit.
*   Easy copy shortcode with mouse click

**▶️ Watch Video**
[youtube https://www.youtube.com/watch?v=pxeGCNc9Be0]

### 🀄 Widgets ###

*   Classic Wedget (From v1.1.7)
*   Elementor page builder element/widget support
*   Gutenberg support (From v1.1.2)
*   WPBackery(VC) Support (From v1.1.6)


### 🧮 Shortcodes ###

The most short form of the shortcode is `[cbxgooglemap id="google map post id here"]` where id is post id of custom google map post type

We can use shortcode to display saved map (this plugin creates custom post type CBX Maps(cbxgooglemap) in admin to create maps as need) or can display map using custom attributes. For save map we need only one param `[cbxgooglemap id="google map post id here"]`

	id      = post id, can be empty
	--------------------------------
	We can also display map using custom attributes
	maptype = default 'roadmap', possible values, 'roadmap', 'satellite', 'hybrid',  'terrain'
	width   = numeric value, '%' accepted, no 'px', if only numeric value then px will be added automatically
	height  = nemeric value, no 'px'
	zoom    = default 8
	lat     = lattitude value, required
	lng     = longitude value, required
	heading = used for info window title
	website = website url that is linked to place name in popup info window, leave empty to ignore
	address = used for info window content
	scrollwheel = 1 enable , 0 disable, default 1 or comes from default config
	showinfo = 1 enable , 0 disable, default 1 or comes from default config, show popup window or not
	infow_open = 1 enable , 0 disable, default 1 or comes from default config, show popup as opened or on click
	mapicon = map icon url, leave empty to ignore


Let us know which new feature you except.

For pro addon features and shortcode see documentation.

## 💎 CBX Map for Google Map & OpenStreetMap Pro Features ##
👉 Get the [pro addon](https://codeboxr.com/product/cbx-google-map-for-wordpress/#downloadarea)

*  	Distance Search shortcode , map and list display
* 	Displays multiple markers from the maps post types in single map
* 	Make map public or not so that single map can be browse like post
*	Map Categories


**▶️ Watch Video**
[youtube https://www.youtube.com/watch?v=bTuysIg-mho]

### 🔩 Installation ###

This section describes how to install the plugin and get it working.
e.g.
1. Upload folder  `cbxgooglemap` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to CBX Google map setting, put google map api key (in google project console you need, js map api, geo coding api, e)
4. Place shortcode any where as need

== Frequently Asked Questions ==

= Is there any custom function to call directly ? =

Not at this moment

== Screenshots ==

1. CBX Map global setting
2. CBX Map global setting-2
3. CBX Map admin post listing
4. CBX Map admin single map edit
5. CBX Map admin single map edit -2
6. CBX Map frontend

== Changelog ==

= 1.1.12 =
* [improvement] Backend UI improved.
* [fixed] Sanitization and escaping improved
* [improvement] PHP 8.2 compatible
* [improvement] WordPress 6.4.3 compatible
* [improvement] Frontend & Backend style improvement for settings and map
* [improvement] Translation fixed


= 1.1.11 =

* [improvement] Backend UI improved.
* [New] Hide leaflet branding yes/no globally from setting - default show as was before

= 1.1.10 =

* [fixed] Backend UI improved.
* [fixed] Add new menu Helps & Update.

= 1.1.9 =

* [fixed] Gutenberg block register compatibility fix for old sites
* [fixed] WPBakery Latest version compatibility fix

= 1.1.8 =

* [New] Openstreep map now has location search for quick lat, lng

= 1.1.7 =

* [New] Classic widget
* [improvement] More backend Style improved

= 1.1.6 =

* [New] WPBakery widget
* [improvement] Backend Style improved
* [improvement] Backend Shortcode click to copy improved

= 1.1.5 =

* [improvement] JS and CSS loading improved, js and css only added to page or post where needed.
* [improvement] Elementor widget improved
* [New] Testing with upcoming wordpress 5.4 latest release candidate

= 1.1.4 =

* [Buf fix] fixed warning about sub menu wrong param

= 1.1.3 =

* [Bug fix] Elementor widget loading conflict issue solved. Pro version has a new update

= 1.1.2 =

* New shortcode param, default setting, post meta 'infow_open' to control info window default popup open or not.
* Added new shortcode params maptype for google map types(roadmap, satelite, hybrid, terrain)
* Elementor now has custom attributes feature , that means no need saved map to display
* Added gutenberg block support

= 1.1.1 =

* [improvement] Elementor widget dynamic render and lots of other improvement
* Template system and template override from theme, same feature in pro addon

= 1.1.0 =

* [improvement] Add same type html markup for opensteetmap info window for heading and description. lots of improvement.
* [New] Pro addon released for distance search search features with new shortcode.

= 1.0.10 =

* [Bug Fix] Map save error fix

= 1.0.9 =

* [New] Added openstreet map as an option with existing google map which need api key

= 1.0.8 =

* [Bug Fix] Backend map address input field flickering issue

= 1.0.7 =

* [improvement] CSS and JS minified for best performance

= 1.0.6 =
* [Bug Fix] Minor bug fix and improvement

= 1.0.5 =

* [Bug Fix] Google api key missing error handled