<?php

if( ! defined( 'ABSPATH' ) )
	return;

// this file is needed for smooth upgrades from pre-1.2 versions to 1.2.0 and later versions
// it fixes a plugin deactivation on upgrade issue due to main plugin php file rename

if( ! function_exists( 'activate_plugin' ) )
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

activate_plugin( plugin_basename( trailingslashit( dirname( __FILE__ ) ) . 'pdf-forms-for-contact-form-7.php' ) );
