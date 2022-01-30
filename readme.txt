=== PDF Forms Filler for CF7 ===
Version: 2.0.0
Requires at least: 4.8
Tested up to: 5.9
Requires PHP: 5.2
Stable tag: trunk
Tags: pdf, form, filler, contact form, attachment, email
Plugin URI: https://pdfformsfiller.org/
Author: Maximum.Software
Author URI: https://maximum.software/
Contributors: maximumsoftware
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Create Contact Form 7 forms from PDF forms.  Get PDF forms filled automatically and attached to email messages upon form submission on your website.  Embed images in PDF files.

== Description ==

[youtube http://www.youtube.com/watch?v=jy84xqnj0Zk]

This plugin gives WordPress Admin Dashboard users the ability to add fillable PDF attachments to email messages and form submission responses of Contact Form 7.

If the PDF attachment has a PDF form, the plugin allows users to add fields to the Contact Form 7 form and/or link them to fields in the PDF.  The plugin also allows the attached PDF files to be embedded with images supplied by the Contact Form 7 form fields.  The filled PDF files can be saved on the web server.

When your website visitor submits your Contact Form 7 form, the form in the PDF file is filled with the form information, images are embedded and the resulting PDF file is attached to the Contact Form 7 email message. The resulting PDF file can also be downloaded by your website visitors if this option is enabled in your form's options.

An external web API (https://pdf.ninja) is used for filling PDF forms (free usage has limitations).  The "Enterprise Extension" plugin is available for purchase that enables the processing all PDF operations locally on your web server and disables the use of the external web API.

Requirements:
 * PHP 5.2 or newer
 * WordPress 4.8 or newer
 * Contact Form 7 5.0 or newer
 * IE 11 (or equivalent) or newer

Known problems:
 * Some third party plugins break the functionality of this plugin (see a list below). Try troubleshooting the problem by disabling likely plugins that may cause issues, such as plugins that modify WordPress or Contact Form 7 in radical ways.
 * Some image optimization plugins optimize PDFs and strip PDF forms from PDF files. This may cause your existing forms to break at a random point in the future (when PDF file cache times out at the API).
 * Multi-select checkbox fields are not currently supported. Support is planned in the future.
 * The old version of the API (v1) produces PDFs which may not render properly in some PDF readers and with some UTF-8 (non-latin) characters, checkboxes and radio buttons.

Known incompatible plugins:
 * [Imagify](https://wordpress.org/plugins/imagify/) (strips forms from PDF files)
 * [ShortPixel Image Optimizer](https://wordpress.org/plugins/shortpixel-image-optimiser/) (strips forms from PDF files)
 * [Live Preview for Contact Form 7](https://wordpress.org/plugins/cf7-live-preview/)
 * [Open external links in a new window](https://wordpress.org/plugins/open-external-links-in-a-new-window/)
 * [WordPress Multilingual Plugin](https://wpml.org/)
 * [Contact Form 7 Skins](https://wordpress.org/plugins/contact-form-7-skins/)

Special thanks to the following sponsors of this plugin:
 * [BrowserStack](https://www.browserstack.com/)
 * [Momentum3](http://momentum3.biz/)
 * [G-FITTINGS GmbH](http://www.g-fittings.com/)

== Installation ==

1. Install the [Contact Form 7](https://wordpress.org/plugins/contact-form-7) plugin.
2. Upload this plugin's folder to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
3. Activate the plugin through the 'Plugins' screen in WordPress
4. Start using the 'PDF Form' button in the CF7 form editor

== Changelog ==

= 2.0.0 =

* Release date: February 1, 2022

* Added multi-select field support
* Switched to select2 dropdowns
* Added value mapping feature
* Fixed the scroll effect when adding an image embed
* Switched to using WPCF7_Submission::add_extra_attachments() for CF7 v5.4.1+
* Other bug fixes and improvements

= 1.3.23 =

* Release date: January 25, 2022

* Ensured support for CF7 v5.5.4
* Ensured support for WordPress v5.9
* Switched to using a less problematic PDF field name sanitization when generating form-tags

= 1.3.22 =

* Release date: December 5, 2021

* Ensured support up to CF7 v5.5.3
* Added remote media support, refactored Pdf.Ninja API integration code, improved error handling
* Hid wp-admin notices from users that don't have capabilities to act on them
* Other minor improvements and fixes

= 1.3.21 =

* Release date: October 29, 2021

* Ensured support up to CF7 v5.5.2
* Fixed issues with tag generator code when unavailable tag names are used
* Other minor improvements

= 1.3.20 =

* Release date: October 14, 2021

* Ensured support up to CF7 v5.5.1
* Added dismissible notices
* Minor refactor of API communication code
* Added a confirmation box when attaching a PDF file with no fields
* Other minor improvements

= 1.3.19 =

* Release date: September 21, 2021

* Crash fix

= 1.3.18 =

* Release date: September 18, 2021

* Added a user-provided email address field for requesting a new key from the API
* Fixed a minor error reporting bug when requesting a new key from the API fails
* Fixed an issue caused by direct modification of fileId post meta in the database
* Fixed a bug introduced recently that was causing the (deprecated) tag generator to not work
* Fixed typo

= 1.3.17 =

* Release date: August 11, 2021

* Fixed a bug that caused cron schedules issues with other plugins
* Bumped tested up to WP version

= 1.3.16 =

* Release date: August 2, 2021

* Switched the Pdf.Ninja API version setting default from v1 to v2

= 1.3.15 =

* Release date: July 14, 2021

* Renamed plugin
* Added CF7 v5.4.2 support
* Improved API response decoding error checks
* Small improvement in tag generator for radio/select/checkbox fields

= 1.3.14 =

* Release date: July 3, 2021

* Added the default tag option to radio/select/checkbox tag generator
* Fixed an issue with radio/select/checkbox tag generation with v2
* Improved tag generator to better escape tag names and values
* Fixed an issue with CF7 fields lists in tag generator thickbox not getting refreshed when necessary
* Fixed padding issue in tag generator thickbox
* Added confirmation box for the delete all mappings button
* Fixed an issue with localization not working properly
* Improved Enterprise Extension support messages

= 1.3.13 =

* Release date: June 1, 2021

* Added API version configuration option
* Improved plugin activation and deactivation hooks
* Improved and enabled the database migration scripts
* Added 1.3.13 database migration script
* Other bug fixes and improvements

= 1.3.12 =

* Release date: May 5, 2021

* Certified CF7 v5.4.1 as a supported version
* Improved admin notices
* Improved frontend JS
* Improved Enterprise Extension support checking code

= 1.3.11 =

* Release date: April 12, 2021

* Fixed and improved cron code
* Changed the default download links timeout from 1 day to 1 hour
* Fixed a crash
* Improved frontend JS slightly
* Added minimum kernel version check to enterprise extension support checking code

= 1.3.10 =

* Release date: April 4, 2021

* Fixed a bug that broke CF7 JS response
* Minor filter correction
* Fixed PHP warning

= 1.3.9 =

* Release date: April 2, 2021

* Fixed an issue with the download link feature and the latest version of CF7

= 1.3.8 =

* Release date: April 2, 2021

* Fixed and improved download link feature support in CF7 v5.4
* Fixed old version support
* Hid CF7 insert box to prevent it from getting in the way of the tag generator UI
* Fixed other minor issues

= 1.3.7 =

* Release date: March 10, 2021

* Fixed old PHP version support
* Decreased order of execution for wpcf7_before_send_mail action to allow other plugins to finish first

= 1.3.6 =

* Release date: March 7, 2021

* Added CF7 v5.4 compatibility: WPCF7_Submission::add_uploaded_file()
* Enabled CF7 v5.4 support
* Readme update

= 1.3.5 =

* Release date: March 1, 2021

* Fixed an accidental bug that was causing PDFs not to be attached to email messages

= 1.3.4 =

* Release date: March 1, 2021

* CF7 v5.4 is still unsupported, however, error mitigation measures were added
* Added CF7 v5.4 compatibility: WPCF7_Submission::uploaded_files()
* Added CF7 plugin version support checking feature
* Added crash prevention check to CF7's add_uploaded_file call
* Updated readme
* Other minor fixes

= 1.3.3 =

* Release date: December 9, 2020

* Bug fix: Removed unnecessary front-end Font Awesome CSS left in by mistake
* Added WebP image format support
* Improved Enterprise Extension support checking code
* Other minor fixes

= 1.3.2 =

* Release date: September 4, 2020

* Bug fix: Skip when empty feature no longer works

= 1.3.1 =

* Release date: August 20, 2020

* Fixed image embed MIME type checking issue that occurs when PHP fileinfo functions are not working

= 1.3.0 =

* Release date: August 8, 2020

* WARNING: this update introduces some changes in plugin operation, these changes should not break anything for existing users, however, testing after an update is encouraged
* Added mail-tags feature
* Improved general error handling during PDF filling
* WARNING: should any errors occur with the PDF filling process, they will now be displayed to users when they submit forms on the front-end (instead of being attached along with user input in a .txt file)
* Added file MIME type validation for image embeds
* WARNING: image embedding is now limited to the following MIME types: image/jpeg, image/png, image/gif, image/tiff, image/bmp, image/x-ms-bmp, image/svg+xml
* Switched to using mail-tags replacement function `wpcf7_mail_replace_tags()` for filling CF7 fields input (to improve third party plugin support)
* Hidden tag generator tool by default
* Other minor bug fixes and improvements

= 1.2.4 =

* Release date: May 15, 2020

* Bug fixes
* Code optimizations and improvements
* Added CF7 form duplication support

= 1.2.3 =

* Release date: March 4, 2020

* Fixed an issue that causes the removal of attachments from other posts when attaching them to CF7 forms
* Fixed a bug that caused HTML code to show up in response messages for filled PDF download links in some cases
* Fixed a bug with filled PDF saving/downloading when handling errors
* Refactored file save/download handling code

= 1.2.2 =

* Release date: January 8, 2020

* Bug fixes
* Readme updates

= 1.2.1 =

* Release date: October 27, 2019

* Fixed an issue with ajax form submission not always receiving HTML download response message
* Fixed an issue which caused the plugin to deactivate when updating from pre-1.2 versions to 1.2.0 and later versions due to main plugin php file rename

= 1.2.0 =

* Release date: September 27, 2019

* Added a number of optimizations, bug fixes and improvements
* Updated the lists of conflicting plugins and sponsors
* Added an option for saving the filled PDF on the server
* Added an option for allowing users to download the filled PDFs
* Added integration with WP media library
* Added code to add pipe to CF7 tags to prevent user confusion with singular options
* Fixed CF7 tag generation code's field value escape issue
* Renamed text domain and plugin filename/slug to the published plugin slug (plugin needs to be reactivated after update due to a change in plugin filename)

= 1.0.2 =

* Release date: February 12, 2019

* Added filename option with mail-tags feature
* Minor fixes and improvements

= 1.0.1 =

* Release date: January 15, 2019

* Bug fixes and improvements

= 1.0.0 =

* Release date: April 6, 2018

* Major plugin refactoring
* Added image embedding tool
* Added help boxes
* A large number of bug fixes, optimizations and UX improvements
* Added Enterprise Extension support checking

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

= My checkboxes and/or radio buttons are not getting filled, what is wrong? =

Make sure your PDF checkbox/radio field's exported value matches the value of the CF7 form's checkbox tag.  Usually, it is "On" or "Yes".  If you need to display a different value in the CF7 form, use [pipes](https://contactform7.com/selectable-recipient-with-pipes/).

Some PDF viewers don't render checkboxes correctly in some PDF files due to incompatible PDF formatting.  You may be able to solve this issue by recreating the PDF in a different PDF editor.

= How do I remove the watermark in the filled PDF files? =

Please see the [Pdf.Ninja API website](https://pdf.ninja) and the [Enterprise Extension plugin](https://maximum.software/store/pdf-forms-for-contact-form-7-wordpress-plugin-enterprise-extension/).

= How do I set up PDF form filling on my local web server? =

Please see the [Enterprise Extension plugin](https://maximum.software/store/pdf-forms-for-contact-form-7-wordpress-plugin-enterprise-extension/).

== Screenshots ==

1. PDF Form button is available to access PDF attachments interface
2. Form-tag Generator interface that allows users to upload and attach PDF files and generate tags
3. Email message in Thunderbird with the attached PDF file
