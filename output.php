<?php
/* 
 * Adds the required CSS and JS to the front end.
 */
 

/**
* Checks the settings for the images and background colors for each size
* If any of these value are set the appropriate CSS is output
* The #header background is removed and the height set to automatic and width set to 100% to account for differences in the various themes
* The new logo is set to the background for #title-area so the widget area will not interfere with the logo on mobile devices
* #title-area is set to 100% width and set to the appropriate height based on the height of the background image if set
* We have to make use of !important through out to ensure the values are correctly set regardless of the theme CSS.
*
* @since 0.1
*/
function grh_css() {
	
	$opts = array( 
		'768',
		'480',
		'320',
		'240',
	);
	
	$settings = array();
	
	foreach( $opts as $opt ){
		$settings[$opt]['image'] = grh_get_option($opt .'-image');
		$settings[$opt]['height'] = grh_get_option($opt .'-image_height');
		$settings[$opt]['color'] = str_replace( '#', '', grh_get_option($opt .'-color') );
	}
	
	if( grh_is_multi_array_empty( $settings) ) 
		return;
		
	echo '<style type="text/css">';
	
		foreach ( $settings as $size => $value ) {
			
			if( grh_is_multi_array_empty( $value ) )
				continue;
			
			$color = $value['color'] ? sprintf( 'background-color:#%s !important;', $value['color'] ) : '';
			$background = $value['image'] ? sprintf( 'background:url(%s)no-repeat center !important;background-size:contain!important;width:100%%!important;', $value['image'] ) : '';
			//$image_size = $value['image'] ? @getimagesize($value['image']) : '';
			$height = $value['height'] ? sprintf( 'height:%spx!important;', $value['height'] ) : ''; 
			
			printf( '@media only screen and (max-width: %spx){ #header{ background-image:none!important; %s height:auto!important;min-height:0!important;width:100%%; } #title-area{ %s%s } }', $size+15, $color, $background, $height );
			
		}
		
	echo '</style>';
	
	
}
add_action( 'wp_head', 'grh_css' );


/**
* Checks to see if each part of a multidimensional array is empy
*
* @since 0.1
*/
function grh_is_multi_array_empty($multiarray) { 
    if(is_array($multiarray) and !empty($multiarray)){ 
        $tmp = array_shift($multiarray); 
            if(!grh_is_multi_array_empty($multiarray) or !grh_is_multi_array_empty($tmp)){ 
                return false; 
            } 
            return true; 
    } 
    if(empty($multiarray)){ 
        return true; 
    } 
    return false; 
} 

/**
* Loads javascript to resize the header if the option isn't disabled in the settings
*
* @since 0.1
*/
function grh_enqueue_scripts() {
	if( ! grh_get_option( 'disable-script' ) )
		wp_enqueue_script( 'grh-header-height', plugins_url( '/genesis-responsive-header/js/resize-header.js' , GRH_PLUGIN_DIR ), array('jquery'), '0.1', true );
}
add_action('wp_enqueue_scripts', 'grh_enqueue_scripts');