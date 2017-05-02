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

add_action( 'wp_enqueue_scripts', 'prso_init_wp_api' );
function prso_init_wp_api() {
	
	//Backbone
	//wp_enqueue_script( 'backbone' );
	
	//Underscore
	//wp_enqueue_script( 'underscore' );
	
	//WP API - loads all dependents
	wp_enqueue_script( 'wp-api' );
	
}

add_filter( 'rest_url_prefix', 'prso_wp_api_prefix' );
function prso_wp_api_prefix() {
	
	return 'wp-json/wp/v2';
	
}

add_filter( 'rest_url', 'prso_wp_api_rest_url', 10, 4 );
function prso_wp_api_rest_url( $url, $path, $blog_id, $scheme ) {
	
	return rtrim( $url, '/' );
	
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

/**
* prso_theme_localize
* 
* Add all localized script vars here.
* 
* @access 	public
* @author	Ben Moody
*/
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
//add_action( 'wp_print_scripts', 'prso_theme_localize', 100 );

add_filter( 'prso_gform_pluploader_entry_attachment_links', 'download_entry_attachments', 10, 3 );
function download_entry_attachments( $attachment_url, $file_id, $post ) {

	//Get url to attachment
	$attachment_url = wp_get_attachment_url( $file_id );
	
	return $attachment_url;
}