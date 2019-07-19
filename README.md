# PDF Forms Filler for Contact Form 7

Create Contact Form 7 forms from PDF forms.  Get PDF forms filled automatically and attached to email messages upon form submission on your website.  Embed images in PDF files.

## Description

[![PDF Forms Filler for Contact Form 7 Tutorial](https://img.youtube.com/vi/jy84xqnj0Zk/0.jpg)](https://www.youtube.com/watch?v=jy84xqnj0Zk "PDF Forms Filler for Contact Form 7 Tutorial")

This plugin gives WordPress Admin Panel users the ability to add fillable PDF attachments to email messages of Contact Form 7.

If the PDF attachment has a PDF form, the plugin allows users to add fields to the CF7 form and/or link them to fields in the PDF.  The plugin also allows the attached PDF files to be embedded with images supplied by the CF7 form fields.

When your website visitor submits the CF7 form, the form in the PDF file is filled with CF7 form information, images are embedded and the resulting PDF file is attached to the CF7 email message.

An external web API (https://pdf.ninja) is used for filling PDF forms (free usage has limitations).  An Enterprise Extension, which enables performing all PDF operations locally on your web server (no external web API), is available upon request.

Known problems,
* Many UTF-8 (non-latin) characters don't render properly after being filled.  Almost always the problem lies with the PDF viewers not rendering the text correctly.  There is a workaround in the works, however, currently it remains unimplemented.
* Some third party plugins break the functionality of this plugin (see a list below).  Try troubleshooting the problem by disabling likely plugins that may cause issues, such as plugins that modify WordPress or Contact Form 7 in radical ways.
* Possible issues with checkbox multiselect

Known incompatible plugins,
* [Contact Form 7 Live Preview](https://wordpress.org/plugins/cf7-live-preview/)
* [Smart Grid-Layout Design for Contact Form 7](https://wordpress.org/plugins/cf7-grid-layout/)
* [Open external links in a new window](https://wordpress.org/plugins/open-external-links-in-a-new-window/)
* [Contact Form 7 Multi-step Pro](https://codecanyon.net/item/contact-form-7-multistep-pro/19635969) (partial compatibility)
* [WordPress Multilingual Plugin](https://wpml.org/)

## Installation

1. Install the [Contact Form 7](https://wordpress.org/plugins/contact-form-7) plugin.
2. Upload this plugin's folder to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
3. Activate the plugin through the 'Plugins' screen in WordPress
4. Start using the 'PDF Form' button in the CF7 form editor

## Screenshots

![PDF Form button is available to access PDF attachments interface](assets/screenshot-1.png?raw=true)

![Form-tag Generator interface that allows users to upload and attach PDF files and generate tags](assets/screenshot-2.png?raw=true)

![Email message in Thunderbird with the attached PDF file](assets/screenshot-3.png?raw=true)

## Special Thanks

Special thanks to the following sponsors of this plugin,

[![BrowserStack](assets/BrowserStack.png)](https://www.browserstack.com/)
[BrowserStack](https://www.browserstack.com/)
[Momentum3](http://momentum3.biz/)
[G-FITTINGS GmbH](http://www.g-fittings.com/)
