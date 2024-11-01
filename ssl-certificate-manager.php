<?php
/**
 * Plugin Name:       SSL Certificate Manager
 * Description:       Newest cURL SSL certificate installer and manager. Install newest cURL SSL certificate and fix the cURL Error 60 SSL certificate problem that certificate has expired.
 * Version:           1.0.0
 * Author:            Akeysite.com
 * Author URI:        https://www.akeysite.com/
 * Text Domain:       ssl-certificate-manager
 */

defined( 'ABSPATH' ) || exit;

if( !defined( 'SSL_CERTIFICATE_MANAGER_VERSION' ) ) {
		
	define( 'SSL_CERTIFICATE_MANAGER_VERSION', '1.0.0' );
	
	define( 'SSL_CERTIFICATE_MANAGER_DB_VERSION', '1.0.0' );
	
	define( 'SSL_CERTIFICATE_MANAGER_PLUGIN_FILE', __FILE__ );

	define( 'SSL_CERTIFICATE_MANAGER_PLUGIN_URL', trailingslashit( plugins_url( '', __FILE__ ) ) );

	define( 'SSL_CERTIFICATE_MANAGER_PLUGIN_DIR', trailingslashit( __DIR__ ) );

	require_once( SSL_CERTIFICATE_MANAGER_PLUGIN_DIR . 'main.php' );

}
