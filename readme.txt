=== PDF Forms Filler for Contact Form 7 ===
Contributors: maximumsoftware
Tags: pdf, form, filler, contact form, attachment, email
Requires at least: 4.6
Tested up to: 4.7.3
Stable tag: trunk
Version: 0.1.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Create Contact Form 7 forms from PDF forms.  Get PDF forms filled automatically and attached to email messages upon form submission on your website.

== Description ==

This plugin gives WordPress Admin Panel users the ability to add PDF attachments to email messages of Contact Form 7.  If the PDF attachment has a PDF form, the plugin allows users to add fields onto the CF7 form that are mapped to fields in the PDF form.  When a website visitor submits the CF7 form, the form in the PDF file is filled with CF7 form information and the resulting PDF file is attached to the CF7 email message.  External web API (https://pdf.ninja) is used for filling PDF forms (free usage has limitations).

== Installation ==

1. Install the [Contact Form 7](https://wordpress.org/plugins/contact-form-7) plugin.
2. Upload this plugin's folder to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
3. Activate the plugin through the 'Plugins' screen in WordPress
4. Start using the 'PDF Form' button in the CF7 form editor

== Changelog ==

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

= Does this plugin allow website visitors to work with PDF files? =

No.  This plugin adds features to the CF7 interface in the WordPress Admin Panel only.

= Does this plugin require special software installation on the web server? =

No.  The plugin uses core WordPress features only.  No special software or PHP extensions are needed.  Working with PDF files is done through a HTTP JSON REST API.

= How are the CF7 form fields mapped to the PDF form fields? =

The fields in the PDF form are mapped using the random looking code in the CF7 field name.  Here is the format: pdf-field-{attachment-id}-{human-readable-field-name}-{random-looking-code}.  If you remove the random looking code, the field will no longer be mapped to the field in the PDF.

= How do I remove the watermark in the filled PDF files? =

Please see the [Pdf.Ninja API website](https://pdf.ninja).

== Screenshots ==

1. PDF Form button is available to access PDF attachments interface
2. Form-tag Generator interface that allows users to upload and attach PDF files and generate tags
3. Email message in Thunderbird with the attached PDF file
