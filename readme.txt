=== PDF Forms Filler for Contact Form 7 ===
Contributors: maximumsoftware
Tags: pdf, form, filler, contact form, attachment, email
Requires at least: 4.3
Tested up to: 4.9
Requires PHP: 5.2
Stable tag: trunk
Version: 1.0.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Create Contact Form 7 forms from PDF forms.  Get PDF forms filled automatically and attached to email messages upon form submission on your website.  Embed images in PDF files.

== Description ==

[youtube http://www.youtube.com/watch?v=jy84xqnj0Zk]

This plugin gives WordPress Admin Panel users the ability to add fillable PDF attachments to email messages of Contact Form 7.

If the PDF attachment has a PDF form, the plugin allows users to add fields to the CF7 form and/or link them to fields in the PDF.  The plugin also allows the attached PDF files to be embedded with images supplied by the CF7 form fields.

When your website visitor submits the CF7 form, the form in the PDF file is filled with CF7 form information, images are embedded and the resulting PDF file is attached to the CF7 email message.

An external web API (https://pdf.ninja) is used for filling PDF forms (free usage has limitations).  An Enterprise Extension, which enables performing all PDF operations locally on your web server (no external web API), is available upon request.

Special thanks to the following sponsors of this plugin,
* [BrowserStack](https://www.browserstack.com/)
* [Momentum3](http://momentum3.biz/)

== Installation ==

1. Install the [Contact Form 7](https://wordpress.org/plugins/contact-form-7) plugin.
2. Upload this plugin's folder to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
3. Activate the plugin through the 'Plugins' screen in WordPress
4. Start using the 'PDF Form' button in the CF7 form editor

== Changelog ==

= 1.0.0 =

* Release date: April 6, 2018

* Major plugin refactoring
* Added image embedding tool
* Added help boxes
* A large number of bug fixes, optimizations and UX improvements
* Added Enterprise Extension support checking

= 0.4.2 =

* Release date: February 17, 2018

* Crash fix

= 0.4.1 =

* Release date: February 13, 2018

* Added bulk tag insertion feature to field mapper tool, special thanks to Momentum3 (http://momentum3.biz/) for sponsoring this feature
* Bug fixes and improvements

= 0.4.0 =

* Release date: February 5, 2018

* Added flatten option
* Fixed a possible timeout issue with large PDF files
* Other minor fixes

= 0.3.3 =

* Release date: December 19, 2017

* Added a feature that allows changing Pdf.Ninja web API URL
* Added a feature that allows disabling Pdf.Ninja web API TLS certificate verification
* Bug fixes

= 0.3.2 =

* Release date: December 2, 2017

* Bug fixes

= 0.3.1 =

* Release date: November 26, 2017

* Bug fix

= 0.3.0 =

* Release date: November 24, 2017

* Added field mapper tool
* Added wildcard field mapping
* Many bug fixes and improvements

= 0.2.4 =

* Release date: November 15, 2017

* Added attachments to secondary CF7 email message
* Added options that allow user to control which email message filled PDFs get attached to
* Updated minimum WP version requirement
* Minor refactoring and other improvements

= 0.2.3 =

* Release date: November 13, 2017

* Added 'skip when empty' option
* Added support for PHP 5.2 and 5.3
* Added plugin action links
* Other minor fixes

= 0.2.2 =

* Release date: October 20, 2017

* Fixed a small issue

= 0.2.1 =

* Release date: October 9, 2017

* Added a help message to tag generator window

= 0.2.0 =

* Release date: September 12, 2017

* Added support for PDF field flags
* Improved tag generation

= 0.1.7 =

* Release date: August 17, 2017

* Minor refactoring and fixes

= 0.1.6 =

* Release date: August 7, 2017

* Improved tag generation code

= 0.1.5 =

* Release date: June 13, 2017

* Bug fixes

= 0.1.4 =

* Release date: June 12, 2017

* Bug fixes

= 0.1.3 =

* Release date: May 18, 2017

* Bug fixes and other minor improvements

= 0.1.2 =

* Release date: April 13, 2017

* Added i18n support

* Minor UX improvement

= 0.1.1 =

* Release date: March 28, 2017

* Removed unnecessary files to save disk space

== Frequently Asked Questions ==

= Does this plugin allow my website users to work with PDF files? =

No.  This plugin adds features to the [Contact Form 7](https://wordpress.org/plugins/contact-form-7) interface in the WordPress Admin Panel only.

= Does this plugin require special software installation on the web server? =

No.  The plugin uses core WordPress features only.  No special software or PHP extensions are needed.  Working with PDF files is done through [Pdf.Ninja API](https://pdf.ninja).  It is recommended to have a working SSL/TLS certificate verification with cURL.

= How are the CF7 form fields mapped to the PDF form fields? =

There are two ways to map fields with this plugin.  The field mapper tool allows you to map fields individually and, when needed, generate new CF7 fields on the fly.  The tag generator tool maps to the fields in the PDF form using the random looking code in the CF7 field name that it generates.  Here is the format: pdf-field-{attachment-id}-{human-readable-field-name}-{random-looking-code}.  The '{attachment-id}' can be 'all' to allow it to map to all PDFs attached to the CF7 form (in case you ever want to swap out the PDF file without needing to fix the generated tags).  If you remove the random looking code, the field will no longer be mapped to the field in the PDF.

= My fields are not getting filled, what is wrong? =

If you reuploaded the PDF file and your mapping was using the old file ID then your mapping will no longer work and you will need to recreate it.
If you are using the field mapper tool, make sure the mapping exists in the list of mappings and the field names match.  If you are using the tag generator tool, make sure the attachment ID matches (or is 'all') and the base64-encoded part of the tag name is unchanged.
If you renamed the PDF field, you will need to remove the old mapping and recreate the mapping with the new name.

= How do I remove the watermark in the filled PDF files? =

Please see the [Pdf.Ninja API website](https://pdf.ninja) and the [Enterprise Extension plugin](https://maximum.software/store/pdf-forms-for-contact-form-7-wordpress-plugin-enterprise-extension/).

= How do I set up PDF form filling on my local web server? =

Please see the [Enterprise Extension plugin](https://maximum.software/store/pdf-forms-for-contact-form-7-wordpress-plugin-enterprise-extension/).

== Screenshots ==

1. PDF Form button is available to access PDF attachments interface
2. Form-tag Generator interface that allows users to upload and attach PDF files and generate tags
3. Email message in Thunderbird with the attached PDF file
