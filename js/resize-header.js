function grhHeaderAutoHeight(){
    
    var headerImage = new Image();
    headerImage.src = jQuery('#title-area').css('background-image').replace(/"/g,"").replace(/url\(|\)$/ig, "");
    
    
    
    if(headerImage) {

	    var width = headerImage.width,
	        currentWidth = jQuery( '#title-area' ).width(),
	        ratio = currentWidth/width,
	        height = headerImage.height*ratio,
	        body = jQuery( 'body' ).width();
        
    
	        //alert(height);
	
		if( body < 768 ){
	    
			if( ratio < 1 ){
				jQuery( '#title-area' ).attr( 'style', 'height: '.concat( height, 'px !important' ) );
			}
	
		}
	
	}
	
	
}

jQuery(document).ready(function($){

    $( 'body' ).attr( 'onload', 'grhHeaderAutoHeight()' );

});

var resizeTimer;
jQuery(window).resize(function() {
	clearTimeout(resizeTimer);
	resizeTimer = setTimeout(grhHeaderAutoHeight, 100);
});