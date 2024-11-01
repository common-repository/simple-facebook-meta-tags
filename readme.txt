=== Simple Social Meta Tags ===
Contributors: eemitch
Donate link: https://elementengage.com/donate/
Tags: Facebook, tags, meta tags, open graph
Requires at least: 4
Tested up to: 5.2.3
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 
Simple Social Meta Tags easily fixes the poor appearance of shared links to your web pages on social media websites.
 
== Description ==
 
Simple Social Meta Tags is an ultra-simple, lightweight Wordpress plugin that easily fixes poor appearance of shared links to your web pages on social media. It requires no setup or configuration, and there is no admin dashboard page. All the proper values are gathered automatically.

This plugin automatically places the most relevant social media Open Graph (OG) meta tags into the head section of your web pages.
 
1.   og:site_name = Your website's title
1.   og:url = The link to the current page
1.   og:title = The title of the current page
1.   og:type = The type of the website; website (Page) or blog (Post or Index)
1.   og:description = The first three sentences of the page's Post content
1.   og:image = a link to the most relevant image.

The image chosen is...

1. The Featured Image, or if none...
1. The first Content image, or if none...
1. A default image you upload to your server's home directory named "social_default_image" (.jpg or .png), or if none...
1. The site theme's custom logo, or if none...
1. The custom header image, or if none...
1. No image meta tag will be output.
 
== Installation ==
 
1. Upload the plugin folder to the '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in WordPress
 
== Frequently Asked Questions ==

= How can I preview what my page will look like when shared on social media? =

For Facebook, use the page debugger tool here...

<a href="https://developers.facebook.com/tools/debug/sharing/">https://developers.facebook.com/tools/debug/sharing/</a>

You can use this to adjust your page and then re-check it until it looks the way you want.

= I'm not impressed. Why doesn't the result look like it should? =

Make sure you don't have another plugin, like Jetpack, adding Open Graph tags too.

= How can I force the link preview and/or image to be different from the page content? =

Put a &#x3C;div&#x3E; tag at the top of the content area with your text and image within. Then add the CSS style display:none; to the &#x3C;div&#x3E;. Now the &#x3C;div&#x3E; won't display on the page, but the meta tags will use this content.

For example:

&#x3C;div style=&#x22;diplay:none;&#x22;&#x3E;
&#x9;Sentence one. Sentence two. Sentence three.
&#x9;&#x3C;img src=&#x22;myimage.png&#x22; /&#x3E;
&#x3C;/div&#x3E;

Begin page content...
 
= How can I ask a question? =
 
Please contact me at: mitch@elementengage.com
 
= Can I add Twitter tags and others too? =
 
No, but I can for you. Please contact me.
 
== Screenshots ==
 
1. screenshot-1
1. screenshot-2

== Upgrade Notice ==

na
 
== Changelog ==

= 1.5 =
* Rebranded the plugin.
 
= 1.3 =
* Initial Release to Public

= 1.4 =
* Improved image file validation