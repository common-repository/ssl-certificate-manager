<?php
defined( 'ABSPATH' ) || exit;

if( ! defined( 'PHP_INT_MIN' ) ) {
	define( 'PHP_INT_MIN', intval(-PHP_INT_MAX - 1) );
}

final class SSLCertificateManagerMain {

	private $container = array();

	protected static $instance = null;

	public static function getObject() {
		if ( is_null( self::$instance ) && ! ( self::$instance instanceof SSLCertificateManagerMain ) ) {
			self::$instance = new SSLCertificateManagerMain();
			self::$instance->main();
		}
		return self::$instance;
	}

	public function __get( $prop ) {
		if ( array_key_exists( $prop, $this->container ) ) {
			return $this->container[ $prop ];
		}

		return $this->{$prop};
	}

	public function __set( $prop, $value ) {
		if ( property_exists( $this, $prop ) ) {
			$this->$prop = $value;
			return;
		}

		$this->container[ $prop ] = $value;
	}

	public function __call( $name, $arguments ) {
		$hash = array();
		if ( isset( $hash[ $name ] ) ) {
			return $hash[ $name ];
		}
		if ( isset( $this->container[ $name ] ) ) {
			return $this->container[ $name ];
		}
	}

	private function main() {

		require_once SSL_CERTIFICATE_MANAGER_PLUGIN_DIR . 'classes/admin.php';

		$this->container['admin'] = new \SSLCertificateManager\Admin;

	}
}
	
if( !function_exists( 'ssl_certificate_manager_object' ) ) {
	function ssl_certificate_manager_object() {
		return SSLCertificateManagerMain::getObject();
	}
}
ssl_certificate_manager_object();
