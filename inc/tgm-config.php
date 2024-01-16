<?php
	
	if( ! defined( 'ABSPATH' ) )
		return;
	
	require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';
	
	add_action( 'tgmpa_register', 'wpcf7_pdf_forms_register_required_plugins' );
	
	function wpcf7_pdf_forms_register_required_plugins()
	{
		$plugins = array(
			array(
				'name'     => 'Contact Form 7',  // The plugin name.
				'slug'     => 'contact-form-7',  // The plugin slug (typically the folder name).
				'required' => true,              // If false, the plugin is only 'recommended' instead of required.
				'version'  => '5.0',             // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
			)
		);
		
		$config = array(
			'id'           => 'pdf-forms-for-contact-form-7',  // Unique ID for hashing notices for multiple instances of TGMPA.
			'menu'         => 'tgmpa-install-plugins',         // Menu slug.
			'parent_slug'  => 'plugins.php',                   // Parent menu slug.
			'capability'   => 'manage_options',                // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                            // Show admin notices or not.
			'dismissable'  => true,                            // If false, a user cannot dismiss the nag message.
			'is_automatic' => false,                           // Automatically activate plugins after installation or not.
		);
		
		tgmpa( $plugins, $config );
	}
