<?php
/**
 * Creates options and outputs admin menu and options page
 */


// Include & setup custom metabox and fields
add_filter( 'ntg_settings_builder', 'grh_admin_settings' );
function grh_admin_settings( $admin_pages ) {
    
    $prefix = 'genesis_comment_form_args_'; // start with an underscore to hide fields from custom fields list
    
    $widths = apply_filters( 'grh_media_sizes', array( '768', '480', '320', '240' ) );
    
    $fields = array();
    
    foreach( $widths as $width ){
	    $fields[] = array(
                        'name' => sprintf( __("%spx  Logo Image:", 'grh'), $width ),
                        'desc' => '',
                        'id'   => $width . '-image',
                        'type' => 'file'
                    );
        $fields[] = array(
                        'name' => sprintf( __("%spx Header Background Color:", 'grh'), $width ),
                        'desc' => '',
                        'id'   => $width .'-color',
                        'type' => 'colorpicker'
                    );
    }
    
    
	$admin_pages[] = array(
            'settings' => array(
                'page_id'          => 'grh-settings',
                'menu_ops'         => array(
                    'submenu' => array(
                        'parent_slug' => 'genesis',
                        'page_title'  => __('Responsive Header', 'grh'),
                        'menu_title'  => __('Responsive Header', 'grh'),
                        'capability'  => 'manage_options',
                    )
                ),
                'page_ops'         => array(
                    
                ),
                'settings_field'   => GRH_SETTINGS_FIELD,
                'default_settings' => array(),
                
            ),
            'sanatize' => array(
                'no_html'   => array(),
                'safe_html' => array()
            ),
            'help'       => array(
                
            ),
            'meta_boxes' => array(
                
                'id'         => 'grh_settings',
                'title'      => __( 'Genesis Responsive Header', 'grh' ),
                'context'    => 'main',
                'priority'   => 'high',/**/
                'show_names' => true, // Show field names on the left
                'fields'     => $fields
                
                )
	);
	
	return $admin_pages;
}

function wptuts_options_setup() {
	global $pagenow;
	if ('media-upload.php' == $pagenow || 'async-upload.php' == $pagenow) {
		// Now we'll replace the 'Insert into Post Button inside Thickbox' 
		add_filter( 'gettext', 'replace_thickbox_text' , 1, 2 );
	}
}
add_action( 'admin_init', 'wptuts_options_setup' );

function replace_thickbox_text($translated_text, $text ) {	
	if ( 'Insert into Post' == $text ) {
		$referer = strpos( wp_get_referer(), 'wptuts-settings' );
		if ( $referer != '' ) {
			return __('I want this to be my logo!', 'wptuts' );
		}
	}

	return $translated_text;
}
