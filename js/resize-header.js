
/*
 *  This scripts prevents sidebars from being cutoff if the content is not longer than the sidebars
 */

/*
 * finds the height of the #sidebar and #sidebar-alt the sets the #content to have a min-height of whichever value is greater
 */
function grhHeaderAutoHeight(){

	
	 /*   var imageSrc = document
                    .getElementById('#title-area')
                     .style
                      .backgroundImage
                       .replace(/url\((['"])(.*?)\1\)/gi, '$2')
                        .split(',')[0];

    // I just broke it up on newlines for readability        

    var image = new Image();
    image.src = imageSrc;*/
    
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