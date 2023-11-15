document.addEventListener( 'DOMContentLoaded', function() {
	const zencaptchaResetCF7 = function( event ) {
		zencap.reset();
	};

	[ ...document.querySelectorAll( '.wpcf7' ) ].map( ( form ) => {
		form.addEventListener( 'wpcf7invalid', zencaptchaResetCF7, false );
		form.addEventListener( 'wpcf7spam', zencaptchaResetCF7, false );
		form.addEventListener( 'wpcf7mailsent', zencaptchaResetCF7, false );
		form.addEventListener( 'wpcf7mailfailed', zencaptchaResetCF7, false );
		form.addEventListener( 'wpcf7submit', zencaptchaResetCF7, false );

		return form;
	} );
} );