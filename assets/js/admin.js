(function ($) {
	$('#submit_button').on('click', function(e) {
		e.preventDefault();
		var ssl_certificate_manager_option = '';
		if( jQuery('#ssl_certificate_manager_option_curl').prop("checked") ) {
			ssl_certificate_manager_option = jQuery.trim(jQuery('#ssl_certificate_manager_option_curl').val());
		} else if( jQuery('#ssl_certificate_manager_option_default').prop("checked") ) {
			ssl_certificate_manager_option = jQuery.trim(jQuery('#ssl_certificate_manager_option_default').val());
		} else if( jQuery('#ssl_certificate_manager_option_disable').prop("checked") ) {
			ssl_certificate_manager_option = jQuery.trim(jQuery('#ssl_certificate_manager_option_disable').val());
		}
		jQuery.ajax({
			type 	: "post",
			url		: ssl_certificate_manager_ajax.ajax_url,
			cache	: false,
			data: {
				'action'													: 'ssl_certificate_manager_option',
				'ssl_certificate_manager_option'	:	ssl_certificate_manager_option,
				'nonce'														: ssl_certificate_manager_ajax.ajax_nonce
			},
			success: function(result) {
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert(jqXHR.responseText);
			},
			complete: function(XMLHttpRequest, status){
				window.location.reload();
			}
		});

	});
})(jQuery);