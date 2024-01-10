

jQuery(document).ready(function() {
    jQuery( document ).on( 'ajaxSuccess', function( event, xhr, settings ) {
        const params = new URLSearchParams( settings.data );
    
        if ( params.get( 'action' ) !== 'elementor_pro_forms_send_form' ) {
            return;
        }
        zencap.reset();
    } );

    /*jQuery(document).on('ajaxComplete', function(event, xhr, settings) {
        reset?
        if (xhr.status === 200) {
            reset?
        } else {
           reset?
        }
    });*/
});