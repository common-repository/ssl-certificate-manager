<?php
namespace SSLCertificateManager;

defined( 'ABSPATH' ) || exit;

class Admin {

	public $options;

	public function __construct() {

		$this->options = get_option( 'ssl_certificate_manager_option', $this->get_default_options() );

		add_action( 'init', array( $this, 'init_action' ) );
		
		if( isset( $this->options['ssl'] ) && 'curl' === $this->options['ssl'] ) {
			add_filter( 'http_request_args', function( $parsed_args, $url ) {
				$parsed_args['sslcertificates'] = SSL_CERTIFICATE_MANAGER_PLUGIN_DIR . 'certificates/curl/cacert.pem';
				return $parsed_args;
			}, 10, 2 );
		}
		
		if( isset( $this->options['ssl'] ) && 'disable' === $this->options['ssl'] ) {
			add_filter( 'https_ssl_verify', '__return_false' );
			add_filter( 'https_local_ssl_verify', '__return_false' );
		}
	}

	public function __destruct() {

	}

	public function init_action() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		load_plugin_textdomain( 'ssl-certificate-manager', false, dirname( plugin_basename( SSL_CERTIFICATE_MANAGER_PLUGIN_FILE ) ) . '/languages' );

		global $pagenow;
		if( $pagenow === 'plugins.php' ) {
			$this->show_expired_message();
		}

		add_action( 'admin_menu', function() {

			$page_hook = add_submenu_page( 'options-general.php', __( 'SSL Certificate', 'ssl-certificate-manager' ), __( 'SSL Certificate', 'ssl-certificate-manager' ), 'manage_options', 'ssl-certificate-manager', function() {
				require_once SSL_CERTIFICATE_MANAGER_PLUGIN_DIR . 'views/menu-page.php';
			} );

			add_action( 'load-' . $page_hook, function() {

				$this->show_expired_message();

				add_action('admin_enqueue_scripts', function() {
					wp_register_script( 'ssl_certificate_manager', SSL_CERTIFICATE_MANAGER_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), SSL_CERTIFICATE_MANAGER_VERSION, true );
					wp_enqueue_script( 'ssl_certificate_manager' );
					wp_localize_script( 'ssl_certificate_manager', 'ssl_certificate_manager_ajax', array(
						'ajax_url' 		=> admin_url( 'admin-ajax.php' ),
						'ajax_nonce'	=> wp_create_nonce( 'aks_nonce' ),
					) );
				} );
			} );
		} );

		add_action( 'admin_init', function() {
		} );

		add_action( 'wp_ajax_ssl_certificate_manager_option', array( $this, 'ssl_certificate_manager_option_action' ) );

	}

	public function ssl_certificate_manager_option_action() {
		check_ajax_referer( 'aks_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You need a higher level of permission!', 'ssl-certificate-manager' ), 403 );
		}

		$ssl_option = sanitize_text_field( $_POST['ssl_certificate_manager_option'] );
		if( 'curl' === $ssl_option ) {
			$this->options['ssl'] = 'curl';
		} else if( 'default' === $ssl_option ) {
			$this->options['ssl'] = 'default';
		} else if( 'disable' === $ssl_option ) {
			$this->options['ssl'] = 'disable';
		} else {
			wp_die( __( 'invalid option value!', 'ssl-certificate-manager' ), 403 );
		}
		
		update_option( 'ssl_certificate_manager_option', $this->options );
		$ret = __( 'cURL SSL certificate option has changed!', 'ssl-certificate-manager' );
		wp_send_json_success( $ret );
	}

	public function wordpress_ssl_is_expired() {
		$default_cert = ABSPATH . WPINC . '/certificates/ca-bundle.crt';
		if( is_readable( $default_cert ) ) {
			$cert_file =  file_get_contents( $default_cert );
			if( false !== $cert_file ) {
				$pos = strpos( $cert_file, "\nDST Root CA X3\n" );
				if( false !== $pos ) {
					return true;
				}
			}
		}
		return false;
	}

	public function show_expired_message() {
		if( $this->wordpress_ssl_is_expired() ) {
			add_action( 'in_admin_header', function() {
				add_action( 'admin_notices', function() {
					echo '
					<div class="notice notice-warning is-dismissible">
						<p>
						'. __( 'Warning: One of WordPress default cURL SSL certificates has expired. Please go to SSL Certificate Manager Setup page and fix the cURL error 60 problem.', 'ssl-certificate-manager' ) . ' <button onclick="javascript:window.location.href=\'' . admin_url( 'options-general.php' ) . '?page=ssl-certificate-manager\';" style="cursor:pointer"> ' . __( 'Setup cURL SSL Certificate', 'ssl-certificate-manager' ) . ' </button>' . '
						</p>
					</div>
					';
				} );
			} );
		}
	}


	public function get_default_options() {
		return array(
			'ssl'	=> 'default',
		);
	}

}