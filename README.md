# PDF Forms Filler for CF7

Build Contact Form 7 forms from PDF forms. Get PDFs auto-filled and attached to email messages and/or website responses on form submission.

## Description

[![PDF Forms Filler Intro](https://img.youtube.com/vi/PhcPZwDXlh8/0.jpg)](https://www.youtube.com/watch?v=PhcPZwDXlh8 "PDF Forms Filler Intro")

This plugin allows Contact Form 7 users to add PDF attachments filled with form submission data to email messages and responses of Contact Form 7.

If the PDF attachment has a PDF form, the plugin allows users to add fields to the Contact Form 7 form and/or link them to fields in the PDF. The plugin also allows the attached PDF files to be embedded with images supplied by Contact Form 7 form fields. The filled PDF files can be saved on the web server.

When your website visitor submits your Contact Form 7 form, the form in the PDF file is filled with CF7 form data, images are embedded and the resulting PDF file is attached to the Contact Form 7 email message. The resulting PDF file can also be downloaded by your website visitors if this option is enabled in your form's options. It is possible to save the resulting PDF file to your server's wp-content/uploads directory.

An external web API (https://pdf.ninja) is used for filling PDF forms (free usage has limitations). The "Enterprise Extension" plugin is available for purchase that enables the processing all PDF operations locally on your web server and disables the use of the external web API.

Please see the [tutorial video](https://youtu.be/jy84xqnj0Zk) and the [documentation](https://pdfformsfiller.org/docs/cf7/) for detailed information.

Requirements:
* PHP 5.2 or newer
* WordPress 4.8 or newer
* Contact Form 7 5.0 or newer
* IE 11 (or equivalent) or newer

Known problems:
* Some third party plugins may break the functionality of this plugin (see a list below). Try troubleshooting the problem by disabling likely plugins that may cause issues, such as plugins that modify WordPress or Contact Form 7 in radical ways.
* Some image optimization plugins optimize PDFs and strip PDF forms from PDF files. This may cause your existing forms to break at a random point in the future (when PDF file cache times out at the API).
* If you are still using the old version of the API (v1) or the old version of Enterprise Extension (v1), please note that resulting PDFs may not render properly in some PDF readers and with some UTF-8 (non-latin) characters, checkboxes and radio buttons.

Known incompatible plugins:
* [Imagify](https://wordpress.org/plugins/imagify/) (strips forms from PDF files)
* [ShortPixel Image Optimizer](https://wordpress.org/plugins/shortpixel-image-optimiser/) (strips forms from PDF files)
* [Live Preview for Contact Form 7](https://wordpress.org/plugins/cf7-live-preview/)
* [Open external links in a new window](https://wordpress.org/plugins/open-external-links-in-a-new-window/)
* [WordPress Multilingual Plugin](https://wpml.org/)
* [Contact Form 7 Skins](https://wordpress.org/plugins/contact-form-7-skins/)

## Installation

1. Install the [Contact Form 7](https://wordpress.org/plugins/contact-form-7) plugin.
2. Upload this plugin's folder to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
3. Activate the plugin through the 'Plugins' screen in WordPress.
4. Start using the 'PDF Form' button in the CF7 form editor.

## Screenshots

![PDF Form button is available to access PDF attachments interface](assets/screenshot-1.png?raw=true)

![Form-tag Generator interface that allows users to attach PDF files and generate tags](assets/screenshot-2.png?raw=true)

![Filled PDF file](assets/screenshot-3.png?raw=true)

## Special Thanks

Special thanks to the following sponsors of this plugin,

[![BrowserStack](assets/BrowserStack.png)](https://www.browserstack.com/)

[BrowserStack](https://www.browserstack.com/)

[Momentum3](http://momentum3.biz/)

[G-FITTINGS GmbH](http://www.g-fittings.com/)
