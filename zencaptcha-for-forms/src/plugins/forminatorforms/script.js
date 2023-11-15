jQuery( document ).on( 'ajaxSuccess', function( event, xhr, settings ) {
	zencap.reset();
} );

jQuery( document ).on( 'ajaxError', function( event, xhr, settings ) {
	zencap.reset();
} );