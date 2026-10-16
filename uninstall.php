<?php
/**
 * Uninstall script for PDF Forms Filler for CF7
 *
 * This script removes all plugin data when the plugin is uninstalled.
 */

if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

/**
 * Clean up plugin data for a single site
 */
function wpcf7_pdf_forms_uninstall_site()
{
	global $wpdb;
	
	// remove options (stored via WPCF7::get_option/update_option)
	// CF7 stores all options in a single 'wpcf7' option as an array
	// not using WPCF7::update_option() because it leaves null entries instead of removing keys
	$wpcf7_options_to_remove = array(
		'wpcf7_pdf_forms_pdfninja_key',
		'wpcf7_pdf_forms_pdfninja_api_url',
		'wpcf7_pdf_forms_pdfninja_api_version',
		'wpcf7_pdf_forms_verify_ssl'
	);
	$wpcf7_options = get_option( 'wpcf7', array() );
	if( is_array( $wpcf7_options ) )
	{
		foreach( $wpcf7_options_to_remove as $option_key )
			unset( $wpcf7_options[ $option_key ] );

		update_option( 'wpcf7', $wpcf7_options );
	}
	
	// remove transients
	delete_transient( 'wpcf7_pdf_forms_pdfninja_key_failure' );
	delete_transient( 'wpcf7_pdf_forms_updated_old_version' );
	
	// remove options
	delete_option( 'wpcf7_pdf_forms_version' );
	
	// remove cron jobs
	wp_clear_scheduled_hook( 'wpcf7_pdf_forms_cron' );
	
	// remove TGMPA dismissed notice flags (current and legacy ids)
	delete_metadata( 'user', null, 'tgmpa_dismissed_notice_pdf-forms-for-contact-form-7', null, true );
	delete_metadata( 'user', null, 'tgmpa_dismissed_notice_wpcf7-pdf-forms', null, true );
	
	// remove page snapshot attachments before the catch-all meta deletion below
	$page_snapshots = get_posts( array(
		'post_type' => 'attachment',
		'post_status' => 'any',
		'meta_key' => 'wpcf7-pdf-forms-page',
		'posts_per_page' => -1,
		'fields' => 'ids'
	) );
	foreach( $page_snapshots as $attachment_id )
		wp_delete_post( $attachment_id, true );
	
	// detach PDF attachments from CF7 forms
	$attached_pdf_ids = $wpdb->get_col( $wpdb->prepare(
		"SELECT DISTINCT post_id FROM {$wpdb->postmeta} WHERE meta_key LIKE %s",
		$wpdb->esc_like( 'wpcf7-pdf-forms-options-' ) . '%'
	) );
	foreach( $attached_pdf_ids as $attachment_id )
		wp_update_post( array( 'ID' => (int) $attachment_id, 'post_parent' => 0 ) );
	
	// remove all remaining plugin post meta
	$wpdb->query( $wpdb->prepare(
		"DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE %s",
		$wpdb->esc_like( 'wpcf7-pdf-forms-' ) . '%'
	) );
	
	// clean up file system - downloads directory
	wpcf7_pdf_forms_cleanup_downloads_directory();
}

/**
 * Remove the downloads directory and its one-level-deep subdirectories
 */
function wpcf7_pdf_forms_cleanup_downloads_directory()
{
	$uploads = wp_upload_dir( null, false );
	$downloads_path = trailingslashit( $uploads['basedir'] ) . 'wpcf7_pdf_forms_downloads';
	
	if( ! is_dir( $downloads_path ) )
		return;
	
	if( ( $downloads_dir = @opendir( $downloads_path ) ) === FALSE )
		return;
	
	while( FALSE !== ( $item = @readdir( $downloads_dir ) ) )
	{
		if( '.' === $item || '..' === $item )
			continue;
		
		$item_path = trailingslashit( $downloads_path ) . $item;
		
		if( is_dir( $item_path ) )
		{
			if( ( $subdir = @opendir( $item_path ) ) !== FALSE )
			{
				while( FALSE !== ( $file = @readdir( $subdir ) ) )
				{
					if( '.' !== $file && '..' !== $file )
						@unlink( trailingslashit( $item_path ) . $file );
				}
				
				closedir( $subdir );
			}
			
			@rmdir( $item_path );
		}
		else
		{
			@unlink( $item_path );
		}
	}
	
	closedir( $downloads_dir );
	@rmdir( $downloads_path );
}

/**
 * Main uninstall function - handles both single site and multisite
 */
function wpcf7_pdf_forms_uninstall()
{
	// check if this is a multisite installation
	if( is_multisite() )
	{
		// get all sites in the network ('number' => 0 returns all sites, the default is 100)
		$sites = get_sites( array( 'fields' => 'ids', 'number' => 0 ) );
		
		foreach( $sites as $site_id )
		{
			switch_to_blog( $site_id );
			try
			{
				wpcf7_pdf_forms_uninstall_site();
			}
			finally
			{
				restore_current_blog();
			}
		}
	}
	else
	{
		// single site installation
		wpcf7_pdf_forms_uninstall_site();
	}
}

// Execute the uninstall
wpcf7_pdf_forms_uninstall();
