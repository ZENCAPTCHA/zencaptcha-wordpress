jQuery( document ).ready( function( $ ) {
    const divi_newsletter_class = '.et_pb_newsletter_form form';
    if ($(divi_newsletter_class).length) {
    $.ajaxPrefilter( function( options ) {
        const zencsolution_el = "zenc-captcha-solution";
		const $searchedclass = $(divi_newsletter_class);
		let solution = $searchedclass.find( '[name="'+zencsolution_el+'"]' ).val();
		solution = solution ? solution : '';
		options.data += '&' + zencsolution_el + '=' + encodeURIComponent(solution.trim());

	} );
    
    $(document).ajaxComplete(function(event, xhr, settings) {
        zencap.reset();
    if (xhr.responseJSON && xhr.responseJSON.success === false) {
        if ($('.error-message-zenc').length) {
            $('.error-message-zenc').remove();
        }

      var customErrorVariable = '';
      const captchaElement = document.querySelector('.zenc-captcha');
      if (captchaElement) {
          const viewportHeight = window.innerHeight;
          const elementHeight = captchaElement.getBoundingClientRect().height;
          const scrollPosition = captchaElement.getBoundingClientRect().top + window.scrollY - (viewportHeight / 2 - elementHeight / 2);
          window.scrollTo(0, scrollPosition);
      }
      if(xhr.responseJSON.data.error=='zencaptcha_click_again'){
        customErrorVariable = ZENCAPTCHAdiviFormObject.zencaptcha_click_again;
      }
      else if(xhr.responseJSON.data.error=='zencaptcha_use_valid_a_valid_email'){
        customErrorVariable = ZENCAPTCHAdiviFormObject.zencaptcha_use_valid_a_valid_email;
      }
      else if(xhr.responseJSON.data.error=='zencaptcha_access_denied'){
        customErrorVariable = ZENCAPTCHAdiviFormObject.zencaptcha_access_denied;
      }
      else {
        customErrorVariable = ZENCAPTCHAdiviFormObject.zencaptcha_failed_try_again;
      }
      
      $('.zenc-captcha').each(function() {
    
        var errorMessage = $('<div>', {
          class: 'error-message-zenc',
          style: 'color: red; background-color: white; font-size:16px; border-radius: 10px; padding: 10px; margin-bottom: 10px;',
          text: customErrorVariable
        });
    
        $(this).before(errorMessage);
      });
    }

  });
}

} );