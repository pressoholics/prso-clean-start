<?php
/******************************************************************
 * 	Text Domain
 *
 *****************************************************************/
 define( 'PRSOTHEMEFRAMEWORK__DOMAIN', 'prso-child-theme-domain' );
 load_theme_textdomain( PRSOTHEMEFRAMEWORK__DOMAIN, get_stylesheet_directory() . '/languages' );

/**
* ADD CUSTOM THEME FUNCTIONS HERE -----
*
*/

//add_action( 'wp_enqueue_scripts', 'prso_init_wp_api' );
function prso_init_wp_api() {
	
	//Backbone
	//wp_enqueue_script( 'backbone' );
	
	//Underscore
	//wp_enqueue_script( 'underscore' );
	
	//WP API - loads all dependents
	wp_enqueue_script( 'wp-api' );
	
}

/**
* prso_custom_login_view
* 
* @CAlled by: 'login_enqueue_scripts'
* 
* Customize the wp login view
*
* @access 	public
* @author	Ben Moody
*/
function prso_custom_login_view() { ?>
    <style type="text/css">
    	body {
	    	
    	}
        .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/admin/site_login_logo.png);
             background-size: cover;
			 display: block;
			 width: 324px;
        }
    </style>
<?php }
//add_action( 'login_enqueue_scripts', 'prso_custom_login_view' );

/******************************************************************
 *    Theme Scripts / Styles
 *****************************************************************/

/**
 * prso_child_enqueue_scripts
 *
 * @CALLED BY ACTION 'wp_enqueue_scripts'
 *
 * Enqueue any theme SCRIPTS here
 *
 * @access    public
 * @author    Ben Moody
 */
add_action( 'wp_enqueue_scripts', 'prso_child_enqueue_scripts' );
function prso_child_enqueue_scripts() {

	if( is_admin() ) {
		return;
	}

	/** example
	wp_enqueue_script('fbm-vendor',
		get_stylesheet_directory_uri() . '/' . FRONTEND_FOLDER . '/' . SCRIPT_VENDOR_DESKTOP_BUNDLE,
		array(),
		$wp_version,
		true
	);
    **/

}

/**
* prso_theme_localize
* 
* Add all localized script vars here.
* 
* @access 	public
* @author	Ben Moody
*/
//add_action( 'wp_print_scripts', 'prso_theme_localize', 100 );
function prso_theme_localize() {
	
	//Init vars
	$handle 	= 'prso-theme-app';
	$obj_name	= 'prsoThemeLocalVars';
	$data_array = array();
	
	//Cache ajax_url
	$data_array['admin_ajax_url'] = admin_url( 'admin-ajax.php' );
	
	//Cache theme nonce
	$data_array['_ajax_nonce'] = wp_create_nonce( PRSOTHEMEFRAMEWORK__DOMAIN );
	
	/** Cache data for localization **/
	
	
	wp_localize_script( $handle, $obj_name, $data_array );
	
}

/**
 * prso_tiny_mce_editor_styles
 *
 * @CALLED BY ACTION 'init'
 *
 * Enqueue custom visual editor stylesheet
 *
 * @access 	public
 * @author	Ben Moody
 */
add_action( 'init', 'prso_tiny_mce_editor_styles', 10 );
function prso_tiny_mce_editor_styles() {

	add_editor_style();

}

add_action('init', 'disable_embeds_init', 9999);
function disable_embeds_init() {

	// Remove the REST API endpoint.
	remove_action('rest_api_init', 'wp_oembed_register_route');

	// Turn off oEmbed auto discovery.
	// Don't filter oEmbed results.
	remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

	// Remove oEmbed discovery links.
	remove_action('wp_head', 'wp_oembed_add_discovery_links');

	// Remove oEmbed-specific JavaScript from the front-end and back-end.
	remove_action('wp_head', 'wp_oembed_add_host_js');

	// REMOVE WP EMOJI
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('wp_print_styles', 'print_emoji_styles');

	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );

}