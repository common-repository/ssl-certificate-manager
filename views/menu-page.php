<?php 
defined( 'ABSPATH' ) || exit;

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( __( 'You need a higher level of permission!', 'ssl-certificate-manager' ), 403 );
}

$ssl_option = get_option( 'ssl_certificate_manager_option' );
$ssl_is_expired_text = '';
$is_expired = ssl_certificate_manager_object()->admin->wordpress_ssl_is_expired();
if( $is_expired ) {
	$ssl_is_expired_text = ' (' . __( 'Warning: One of WordPress default cURL SSL certificates has expired', 'ssl-certificate-manager' ) . ')';
}
?>

<div class="container">
	<div class="">
	<h5 class="">
			<span>&#10144;</span> <?php _e( 'WordPress SSL Certificate Manager', 'ssl-certificate-manager' ); ?>
			<div style="float:right;">
			</div>
		</h5>
		<div class="">
			<form id="ssl_certificate_manager_form" action="" method="post">
				<div>
					<input type="radio" class="form-check-input position-static" id="ssl_certificate_manager_option_curl" name="ssl_certificate_manager_option" value="curl" <?php if( isset( $ssl_option['ssl'] ) && 'curl' === $ssl_option['ssl'] ) { echo 'checked=checked'; } ?>> <label class="form-check-label" for="ssl_certificate_manager_option_curl"><?php _e( 'Use Newest cURL SSL Certificate. (Recommend!)', 'ssl-certificate-manager' ); ?></label>
				</div>
				<div>
					<input type="radio" class="form-check-input position-static" id="ssl_certificate_manager_option_default" name="ssl_certificate_manager_option" value="default" <?php if( isset( $ssl_option['ssl'] ) && 'default' === $ssl_option['ssl'] ) { echo 'checked=checked'; } ?>> <label class="form-check-label" for="ssl_certificate_manager_option_default"><?php echo __( 'Keep Default WordPress Certificate.', 'ssl-certificate-manager' ) . $ssl_is_expired_text; ?></label>
				</div>
				<div>
					<input type="radio" class="form-check-input position-static" id="ssl_certificate_manager_option_disable" name="ssl_certificate_manager_option" value="disable" <?php if( isset( $ssl_option['ssl'] ) && 'disable' === $ssl_option['ssl'] ) { echo 'checked=checked'; } ?>> <label class="form-check-label" for="ssl_certificate_manager_option_disable"><?php _e( 'Disable WordPress cURL SSL Certificate Verify. (Not recommend and not the best for security)', 'ssl-certificate-manager' ); ?></label>
				</div>
				<br>
				<div class="">
					<div class="">
						<button id="submit_button" class="btn btn-primary" type="button" style="cursor:pointer;"><?php _e( 'Submit', 'ssl-certificate-manager' ); ?></button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
