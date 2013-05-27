<?php

/*
  Plugin Name: Genesis Responsive Header
  Plugin URI: http://DesignsByNicktheGeek.com
  Version: 0.9
  Author: Nick_theGeek
  Author URI: http://DesignsByNicktheGeek.com
  Description: Allows user to add custom header images for 768px, 480px, 320px, and 240px wide browsers. Requires Genesis 1.8+
  Text Domain: grh
  Domain Path: /languages/
 */

/*
 * To Do:
 *      Create and setup screen shots
 */

/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    wp_die( __( "Sorry, you are not allowed to access this page directly.", 'grh' ) );
}

define( 'GRH_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'GRH_SETTINGS_FIELD', 'grh-settings' );


/**
 * This registers the settings field
 */
function register_grh_settings() {
	register_setting(GRH_SETTINGS_FIELD, GRH_SETTINGS_FIELD);
	add_action('admin_init', 'register_grh_settings');
}

register_activation_hook( __FILE__, 'grh_activation_check' );
/**
 * Checks for minimum Genesis Theme version before allowing plugin to activate
 *
 * @author Nathan Rice
 * @uses grh_truncate()
 * @since 0.1
 * @version 0.2
 */
function grh_activation_check() {

    $latest = '1.9';

    $theme_info = get_theme_data( TEMPLATEPATH . '/style.css' );

    if ( basename( TEMPLATEPATH ) != 'genesis' ) {
        deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate ourself
        wp_die( sprintf( __( 'Sorry, you can\'t activate unless you have installed %1$sGenesis%2$s', 'grh' ), '<a href="http://designsbynickthegeek.com/go/genesis">', '</a>' ) );
    }

    $version = grh_truncate( $theme_info['Version'], 3 );

    if ( version_compare( $version, $latest, '<' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate ourself
        wp_die( sprintf( __( 'Sorry, you can\'t activate without %1$sGenesis %2$s%3$s or greater', 'grh' ), '<a href="http://designsbynickthegeek.com/go/genesis">', $latest, '</a>' ) );
    }
}

/**
 *
 * Used to cutoff a string to a set length if it exceeds the specified length
 *
 * @author Nick Croft
 * @since 0.1
 * @version 0.2
 * @param string $str Any string that might need to be shortened
 * @param string $length Any whole integer
 * @return string
 */
function grh_truncate( $str, $length=10 ) {

    if ( strlen( $str ) > $length ) {
        return substr( $str, 0, $length );
    } else {
        $res = $str;
    }

    return $res;
}


/** Loads required files when needed */
function grh_init() {

    /** Load textdomain for translation */
    load_plugin_textdomain( 'grh', false, basename( dirname( __FILE__ ) ) . '/languages/' );

    if ( is_admin ( ) )
        require_once(GRH_PLUGIN_DIR . '/admin.php');
    else
        require_once(GRH_PLUGIN_DIR . '/output.php');

            
}
add_action( 'genesis_init', 'grh_init', 14 );
