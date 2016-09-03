/*
 *  This scripts prevents sidebars from being cutoff if the content is not longer than the sidebars
 */

/*
 * finds the height of the #sidebar and #sidebar-alt the sets the #content to have a min-height of whichever value is greater
 */
function grhHeaderAutoHeight()
{
    var headerImage = new Image(),
        imageSource = jQuery('.title-area').css('background-image').replace(/"/g,"").replace(/url\(|\)$/ig, "");

    // Make sure we're not trying to load any empty image source
    // This will prevent calls to /none 
    if (typeOf(imageSource) !== 'undefined' && imageSource !== '' && imageSource !== 'none') {
        headerImage.src = imageSource;
        if(headerImage) {
            var width = headerImage.width,
                currentWidth = jQuery( '.title-area' ).width(),
                ratio = currentWidth/width,
                height = headerImage.height*ratio,
                body = jQuery( 'body' ).width();

            if( body < 768 ){
                if( ratio < 1 ){
                    jQuery( '.title-area' ).attr( 'style', 'height: '.concat( height, 'px !important' ) );
                }
            }
        }
    }
}

jQuery(document).ready(function($)
{
    $( 'body' ).attr( 'onload', 'grhHeaderAutoHeight()' );
});

var resizeTimer;
jQuery(window).resize(function()
{
	clearTimeout(resizeTimer);
	resizeTimer = setTimeout(grhHeaderAutoHeight, 100);
});
