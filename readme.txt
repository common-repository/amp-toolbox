=== Plugin Name ===
Contributors: deano1987
Tags: amp, amp schema, amp header, amp link, amp original, schema, header, header logo, link
Requires at least: 3.0
Tested up to: 5.4
Stable tag: 2.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin provides extra features and fixes for use with the AMP Wordpress plugin. You can change the header and it fixes schema as well as allowing you to give users a link to your amp/original versions.

== Description ==

This plugin provides extra features and fixes for use with the AMP Wordpress plugin, which must also be installed. https://wordpress.org/plugins/amp

- You can change the header CSS, specify a header image, change the size and colours of the header.
- You can add a link to normal posts page "there is a AMP version of this page" - so users can opt to view the AMP version.
- You can add a link to AMP versions of posts "this is an AMP version of this page" - so users can opt to view the normal version.
- You can fix the schema problems with publisher logo by setting your publisher logo and dimensions.
- You can setup Google Analytics to track visitors to your AMP pages.

Fixes new Schema rules by Google for Width and Height.

== Frequently Asked Questions ==

= Will there ever be more options added? =

Yes, we are in early development so expect lots of features! Please let us know if you have a feature suggestion.


== Installation ==

1. Upload the folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==
= 2.1.1 =
* removed handheld meta tag - deprecated

= 2.1.0 =
* plugin now automatically removes the wordpress and AMP generator meta tags from amp pages. see https://webdesires.co.uk/knowledge-base/wordpress-remove-generator-meta-tags-for-amp-plugin/

= 2.0.0 =
* removed amphtml tag looks like the amp plugin now does this!

= 1.9.9 =
* broken tag fix

= 1.9.8 =
* amp urls for pages are now correct since they use ?amp instead of /amp/ - dont know why??

= 1.9.7 =
* fixed the amphtml meta tags, no longer appear in category listings

= 1.9.6 =
* added new amphtml rel tag to pages

= 1.9.2 =
* the header CSS is now different, if your updating copy and paste the below into your header CSS box and edit the image url and width/height as needed.
.amp-wp-header {
background:#e8e8e8;
padding:12px 0;
}

.amp-wp-header a {
background-image:url(/path/to/image);
background-repeat:no-repeat;
background-size:contain;
display:block;
height:85px;
width:320px;
text-indent:-9999px;
margin:0 auto;
}

= 1.9.1 =
* Fixed small problem with links to and from AMP version

= 1.9 =
* The mobile optimized version text no longer appears in excerpts.
* Adjusted optimized / unoptimized version text boxes.

= 1.8 =
* Added <link rel="alternate" media="handheld" linked to amp url, as mentioned in a google blog post: https://webmasters.googleblog.com/2016/11/an-update-on-googles-feature-phone.html
* Fixed shortcode issue

= 1.7 =
* Added Google Analytics tracking for AMP pages

= 1.6 =
* Pikto invalid tag handling added
* Latest wordpress version supported

= 1.5 =
* Fixed a bug where AMP version links were not being removed if you turned the option off - Thanks Robert.

= 1.4 =
* Now stripping illegal tag <font> from any content being served on an AMP page.

= 1.3 =
* Google have updated schema testing, and AMP plugin breaks image width and height, this is now automagically fixed by AMP Toolbox.

= 1.2 =
* Now stripping illegal tags such as <embed> and replacing <quote> with <em>

= 1.0 =
* Inital release