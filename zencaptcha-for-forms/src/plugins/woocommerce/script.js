jQuery( document ).ready( function( $ ) {
    
$(document).ajaxComplete(function(event, xhr, settings) {
    zencap.reset();
});

});

const wc = function( $ ) {

	$( document.body ).on( 'checkout_error', function() {
		zencaptchaInit();
	} );

	$( document.body ).on( 'updated_checkout', function() {
		zencaptchaInit();
	} );
};

window.zencaptchaWC = wc;

jQuery( document ).ready( wc );