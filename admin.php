<?php

add_action('customize_register', 'grh_admin_settings');
/** Create Customizer Settings */
function grh_admin_settings($wp_customize) {

	$widths = apply_filters( 'grh_media_sizes', array( '768', '480', '320', '240' ) );
	$show_height = get_option( 'grh_show_height_option' );
	
	$wp_customize->add_section( GRH_SETTINGS_FIELD, array(
        'title'    => __( 'Responsive Header', 'grh' ),
        'priority' => 35,
    ) );
    
    $wp_customize->add_setting( 'grh_update_sizes', array(
	        'default'  => '',
	        'type'     => 'option',
	    ) );
	    
	$wp_customize->add_control( 'grh_update_sizes', array(
		'settings' => 'grh_update_sizes',
		'label'    => __( 'Update Image Sizes', 'grh' ),
		'section'  => GRH_SETTINGS_FIELD,
		'type'     => 'checkbox',
	) );
	
	$wp_customize->add_setting( 'grh_show_height_option', array(
	        'default'  => '',
	        'type'     => 'option',
	    ) );
	    
	$wp_customize->add_control( 'grh_show_height_option', array(
		'settings' => 'grh_show_height_option',
		'label'    => __( 'Show Height Option Field', 'grh' ),
		'section'  => GRH_SETTINGS_FIELD,
		'type'     => 'checkbox',
	) );
	
	$wp_customize->add_setting( 'grh_disable_script', array(
	        'default'  => '',
	        'type'     => 'option',
	    ) );
	    
	$wp_customize->add_control( 'grh_disable_script', array(
		'settings' => 'grh_disable_script',
		'label'    => __( 'Disable Scripts', 'grh' ),
		'section'  => GRH_SETTINGS_FIELD,
		'type'     => 'checkbox',
	) );
    
    foreach( $widths as $width ){
    
    	$wp_customize->add_setting( $width . '-image', array(
	        'default'  => '',
	        'type'     => 'option',
	    ) );
	    
	    $wp_customize->add_setting( $width .'-color', array(
	        'default'  => '',
	        'type'     => 'option',
	    ) );
	 
	    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $width . '-image', array(
	        'label'    => sprintf( __("%spx  Logo Image:", 'grh'), $width ),
	        'section'  => GRH_SETTINGS_FIELD,
	        'settings' => $width . '-image',
	        'priority' => -$width,
	    ) ) );
	 
	    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $width .'-color', array(
	        'label'    => sprintf( __("%spx Header Background Color:", 'grh'), $width ),
	        'section'  => GRH_SETTINGS_FIELD,
	        'settings' => $width .'-color',
	        'priority' => -$width+1,
	    ) ) );
	    
	    if( $show_height ){
		    $wp_customize->add_setting( $width . '-image-height', array(
		        'default'  => '',
		        'type'     => 'option',
		    ) );
		    
			$wp_customize->add_control( $width . '-image-height', array(
				'settings' => $width . '-image-height',
				'label'    => sprintf( __("%spx Image Height:", 'grh'), $width ),
				'section'  => GRH_SETTINGS_FIELD,
				'type'     => 'text',
				'priority' => -$width+2,
			) );
		}
	    
    }
    
    if( get_option( 'grh_update_sizes' ) ){
	    foreach( $widths as $width ){
		    $image  = get_option( $width .'-image' );
		    $meta   = @getimagesize( $image ); 
		    $height = $meta ? $meta['1'] : '';
		    
		    update_option( $width . '-image-height', $height );
	    }
	    
	    update_option( 'grh_update_sizes', '');
    }
 
}
