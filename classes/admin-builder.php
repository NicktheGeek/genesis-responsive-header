<?php
 
/**
 * NTG Admin Settings
 * Requires Genesis 1.8 or later
 *
 * This file registers all of this Settings, 
 * accessible from Genesis Submenu.
 * 
 * Built upon the CMB Meta Box class by Bill Erickson
 *
 * @package      NTG_Theme_Settings_Builder
 * @author       Nick the Geek <NicktheGeek@NickGeek.com>
 * @copyright    Copyright (c) 2012, Nick Croft
 * @license      <a href="http://opensource.org/licenses/gpl-2.0.php" rel="nofollow">http://opensource.org/licenses/gpl-2.0.php</a> GNU Public License
 * @since        1.0
 * @alter        1.11.2012
 *
 */

/**
 * Add the Theme Settings Page
 *
 * @since 1.0.0
 */
function grh_add_settings() {
    global $_child_theme_settings;
    
    $admin_pages = array();
    $admin_pages = apply_filters ( 'ntg_settings_builder' , $admin_pages );
    
    //print_r( $admin_pages );
    
    foreach ( $admin_pages as $admin_page ) {
        $settings   = isset( $admin_page['settings'] )   ? $admin_page['settings']   : array();
        $sanatize   = isset( $admin_page['sanatize'] )   ? $admin_page['sanatize']   : array();
        $help       = isset( $admin_page['help'] )       ? $admin_page['help']       : array();
        $meta_boxes = isset( $admin_page['meta_boxes'] ) ? $admin_page['meta_boxes'] : array();
        
        //print_r( $help );
        
	$_child_theme_settings = new GRH_Theme_Settings_Builder( $settings, $sanatize, $help, $meta_boxes );
    }
}
add_action( 'admin_menu', 'grh_add_settings', 5 );

/**
* Defines the url to which is used to load local resources.
* This may need to be filtered for local Window installations.
* If resources do not load, please check the wiki for details.
*/
define( 'GRH_AB_META_BOX_URL', plugins_url( '/genesis-responsive-header/classes/' , GRH_PLUGIN_DIR ) );
 
/**
 * Registers a new admin page, providing content and corresponding menu item
 * for the Child Theme Settings page.
 *
 * @package      WPS_Starter_Genesis_Child
 * @subpackage   Admin
 *
 * @since 1.0.0
 */
class GRH_Theme_Settings_Builder extends Genesis_Admin_Boxes {
 
    protected $_settings;
    protected $_sanatize;
    protected $_help;
    protected $_meta_box;
    
    /**
     * Create an admin menu item and settings page.
     *
     * @since 1.0.0
     */
    function __construct( $settings, $sanatize, $help, $meta_boxes ) {
        
        $this->_settings = $settings;
        $this->_sanatize = $sanatize;
        $this->_help     = $help;
        $this->_meta_box = $meta_boxes;
        
        //print_r( $settings );
        
        
        // Specify a unique page ID.
        $page_id = $settings['page_id'];
 
        // Set it as a child to genesis, and define the menu and page titles
        $menu_ops_defaults = array(
            'submenu' => array(
                'parent_slug' => 'genesis',
                'capability' => 'manage_options',
            )
        );
        
        $menu_ops = wp_parse_args( $settings['menu_ops'], $menu_ops_defaults );
 
        // Set up page options. These are optional, so only uncomment if you want to change the defaults
        $page_ops_defaults = array(
        //  'screen_icon'       => array( 'custom' => WPS_ADMIN_IMAGES . '/staff_32x32.png' ),
            'screen_icon'       => 'users',
        //  'save_button_text'  => 'Save Settings',
        //  'reset_button_text' => 'Reset Settings',
        //  'save_notice_text'  => 'Settings saved.',
        //  'reset_notice_text' => 'Settings reset.',
        );     
        
        $page_ops = wp_parse_args( $settings['page_ops'], $page_ops_defaults );
 
        // Give it a unique settings field.
        // You'll access them from genesis_get_option( 'option_name', CHILD_SETTINGS_FIELD );
        $settings_field = $settings['settings_field'];
 
        // Set the default values
        $default_settings = $settings['default_settings'];
        
        //print_r( $menu_ops );
 
        // Create the Admin Page
        $this->create( $page_id, $menu_ops, $page_ops, $settings_field, $default_settings );
        
		global $ntg_ab_page_hooks;
		
		if( !isset( $ntg_ab_page_hooks) )
			$ntg_ab_page_hooks = array();
		
		$ntg_ab_page_hooks[] = $this->pagehook;
        
        
        // Initialize the Sanitization Filter
        add_action( 'genesis_settings_sanitizer_init', array( $this, 'sanitization_filters' ) );
 
    }
 
    /**
     * Set up Sanitization Filters
     *
     * See /lib/classes/sanitization.php for all available filters.
     *
     * @since 1.0.0
     */
    function sanitization_filters() {
        
                    
            foreach( $this->_sanatize as $key => $values )
 
                genesis_add_option_filter( $key, $this->settings_field, $values );
        
        
    }
 
    /**
     * Register metaboxes on Child Theme Settings page
     *
     * @since 1.0.0
     *
     * @see Child_Theme_Settings::contact_information() Callback for contact information
     */
    function metaboxes() {
        
        $this->_meta_box['context']  = isset($this->_meta_box['context']) ?  $this->_meta_box['context'] : 'main';
	$this->_meta_box['priority'] = isset($this->_meta_box['priority']) ?  $this->_meta_box['priority'] : 'high';
		
        //print_r( $this->_meta_box );
        
        add_meta_box( $this->_meta_box['id'], $this->_meta_box['title'], array(&$this, 'show'), $this->pagehook, $this->_meta_box['context'], $this->_meta_box['priority']) ;
        
        
    }
 
    /**
     * Register contextual help on Child Theme Settings page
     *
     * @since 1.0.0
     *
     */
    function help( ) {
        global $my_admin_page;
        $screen = get_current_screen();
 
        if ( $screen->id != $this->pagehook )
            return;
        
        $tabs = isset( $this->_help['tab'] ) ? $this->_help['tab'] : array();
        
        //print_r( $tabs );
        
        foreach( $tabs as $tab ){
 
        $screen->add_help_tab(
            array(
                'id'        => $tab['id'],
                'title'     => $tab['title'],
                'content'   => $tab['content'],
            ) );
                
        }
 
        $sidebars = isset( $this->_help['sidebar'] ) ? $this->_help['sidebar'] : array();
        // Add Genesis Sidebar
        foreach( $sidebars as $sidebar )
        
            $screen->set_help_sidebar( $sidebar );
        
    }
 
    /**
     * Callback for Contact Information metabox
     *
     * @since 1.0.0
     *
     * @see Child_Theme_Settings::metaboxes()
     */
    // Show fields
	function show() {

		// Use nonce for verification
		echo '<input type="hidden" name="wp_meta_box_nonce" value="', wp_create_nonce( basename(__FILE__) ), '" />';
		echo '<table class="form-table ntg_ab_metabox">';

		foreach ( $this->_meta_box['fields'] as $field ) {
			// Set up blank or default values for empty ones
			if ( !isset( $field['name'] ) ) $field['name'] = '';
			if ( !isset( $field['desc'] ) ) $field['desc'] = '';
			if ( !isset( $field['std'] ) ) $field['std'] = '';
			if ( 'file' == $field['type'] && !isset( $field['allow'] ) ) $field['allow'] = array( 'url', 'attachment' );
			if ( 'file' == $field['type'] && !isset( $field['save_id'] ) )  $field['save_id']  = false;
			if ( 'multicheck' == $field['type'] ) $field['multiple'] = true;  
						
			if ( $field['type'] != "title" )
                            $meta = esc_html( $this->get_field_value( $field['id'] ) );

			echo '<tr>';
	
			if ( $field['type'] == "title" ) {
				echo '<td colspan="2">';
			} else {
				if( $this->_meta_box['show_names'] == true ) {
					echo '<th style="width:18%"><label for="', $field['id'], '">', $field['name'], '</label></th>';
				}			
				echo '<td>';
			}		
						
			switch ( $field['type'] ) {

				case 'text':
					echo '<input type="text" name="', $this->get_field_name( $field['id'] ), '" id="', $this->get_field_id( $field['id'] ), '" value="', '' !== $meta ? $meta : $field['std'], '" />','<p class="cmb_metabox_description">', $field['desc'], '</p>';
					break;
				case 'text_small':
					echo '<input class="cmb_text_small" type="text" name="', $this->get_field_name( $field['id'] ), '" id="', $this->get_field_id( $field['id'] ), '" value="', '' !== $meta ? $meta : $field['std'], '" /><span class="cmb_metabox_description">', $field['desc'], '</span>';
					break;
				case 'checkbox':
					echo '<input type="checkbox" name="', $this->get_field_name( $field['id'] ), '" id="', $this->get_field_id( $field['id'] ), '"', $meta ? ' checked="checked"' : '', ' />';
					echo '<span class="cmb_metabox_description">', $field['desc'], '</span>';
					break;
				case 'colorpicker':
					if( '' !== $meta ) {
						if( preg_match('/^(([a-fA-F0-9]){3}){1,2}$/i', $meta ) ){
							$meta = '#' . $meta;
						} elseif( preg_match('/^#(([a-fA-F0-9]){3}){1,2}$/i', $meta ) ) {
						
						} else {
							$meta = "#";
						}
					} else {
						if( preg_match('/^(([a-fA-F0-9]){3}){1,2}$/i', $field['std'] ) ){
							$meta = '#' . $field['std'];
						} elseif( preg_match('/^#(([a-fA-F0-9]){3}){1,2}$/i', $field['std'] ) ) {
							$meta = $field['std'];
						} else {
							$meta = "#";
						}
					}
					echo '<input class="cmb_colorpicker ntg_ab_text_small" type="text" name="', $this->get_field_name( $field['id'] ), '" id="', $this->get_field_id( $field['id'] ), '" value="', $meta, '" /><span class="cmb_metabox_description">', $field['desc'], '</span>';
					break;				
				case 'file':
					$input_type_url = "hidden";
					if ( 'url' == $field['allow'] || ( is_array( $field['allow'] ) && in_array( 'url', $field['allow'] ) ) )
						$input_type_url="text";
						
						/*?>
						<input type="hidden" id="logo_url" name="theme_wptuts_options[logo]" value="<?php echo esc_url( $wptuts_options['logo'] ); ?>" />
						<input id="upload_logo_button" type="button" class="button" value="<?php _e( 'Upload Logo', 'wptuts' ); ?>" />
						<?php if ( '' != $wptuts_options['logo'] ): ?>
							<input id="delete_logo_button" name="theme_wptuts_options[delete_logo]" type="submit" class="button" value="<?php _e( 'Delete Logo', 'wptuts' ); ?>" />
						<?php endif; ?>
						<span class="description"><?php _e('Upload an image for the banner.', 'wptuts' ); ?></span> 
						<?php*/
		
						$id = str_replace( '[', '-', str_replace( ']', '', $this->get_field_id( $field['id'] ) ) );
						
					echo '<input class="cmb_upload_file" type="' . $input_type_url . '" size="45" id="file-', $id, '" name="', $this->get_field_name( $field['id'] ), '" value="', $meta, '" />';
					echo '<input class="ntg_upload_button button" type="button" id="', $id ,'" value="Upload File" />';
					echo '<input class="cmb_upload_file_id" type="hidden" id="', $this->get_field_id( $field['id'].'_id' ), '" name="', $this->get_field_name( $field['id'].'_id' ) ,'" value="', esc_html( $this->get_field_value( $field['id'].'_id' ) ), '" />';	
					echo '<input class="ntg_height" type="hidden" id="height-', $id, '" name="', $this->get_field_name( $field['id'].'_height' ) ,'" value="', esc_html( $this->get_field_value( $field['id'].'_height' ) ), '" />';
									
					echo '<p class="cmb_metabox_description">', $field['desc'], '</p>';
					echo '<div id="', $this->get_field_id( $field['id'] ), '_status" class="cmb_upload_status">';	
						if ( $meta != '' ) { 
							$check_image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $meta );
							if ( $check_image ) {
								echo '<div class="img_status">';
								echo '<div id="preview-'. $id .'" style="min-height: 100px;">
										<img style="max-width:100%; height: auto;" src="'. esc_url( $meta ) .'" />
									</div>';
								echo '<a href="#" class="cmb_remove_file_button" rel="'. $id  .'">Remove Image</a>';
								echo '</div>';
							} 	
						}
						else {
							echo '<div id="preview-'. $id .'"><img style="max-width:100%; height: auto;" src="" alt="" /></div>';
						}
					echo '</div>'; 
				break;
				default:
					do_action('ntg_ab_render_' . $field['type'] , $field, $meta);
			}
			
			echo '</td>','</tr>';
		}
		echo '</table>';
	}
 
}

/**
 * Adding scripts and styles
 */
function GRH_ab_scripts( $hook ) {
	global $ntg_ab_page_hooks;
	
	if ( 'genesis_page_grh-settings' == $hook || ( isset( $_REQUEST['referer'] ) && 'wptuts-settings' == $_REQUEST['referer'] ) ) {
		wp_register_script( 'ntg-scripts', GRH_AB_META_BOX_URL . 'js/cmb.js', array( 'jquery', 'jquery-ui-core', 'media-upload', 'thickbox', 'farbtastic' ) );
		wp_enqueue_script( 'ntg-scripts' );
		wp_register_style( 'ntg-styles', GRH_AB_META_BOX_URL . 'style.css', array( 'thickbox', 'farbtastic' ) );
		wp_enqueue_style( 'ntg-styles' );
	}
}

add_action( 'admin_enqueue_scripts', 'grh_ab_scripts', 10 );

// End. That's it, folks! //