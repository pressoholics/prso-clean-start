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

/**
 * WooCommerce Support
 *
 * Include theme woocommerce file to use a framework for woo projects
 *
 * @access public
 * @author Ben Moody
 */
//prso_include_file( get_stylesheet_directory() . '/prso_framework/woocommerce.php' );

/**
* prso_allow_iframes_filter
* 
* @CALLED BY FILTER 'wp_kses_allowed_html'
*
* Allow iframe output when using wp_kses_post
*
* @access 	public
* @author	Ben Moody
*/
add_filter( 'wp_kses_allowed_html', 'prso_allow_iframes_filter' );
function prso_allow_iframes_filter( $allowedposttags ) {

	// Allow iframes and the following attributes
	$allowedposttags['iframe'] = array(
		'align' => true,
		'width' => true,
		'height' => true,
		'frameborder' => true,
		'name' => true,
		'src' => true,
		'id' => true,
		'class' => true,
		'style' => true,
		'scrolling' => true,
		'marginwidth' => true,
		'marginheight' => true,
	);

	return $allowedposttags;
}

//Add BugHerd script for admins only
//add_action( 'wp_footer', 'gcc_enqueue_bugherd_admin' );
function gcc_enqueue_bugherd_admin() {
	if( current_user_can('manage_options') ):
	?>
	<script type='text/javascript'>
	(function (d, t) {
	  var bh = d.createElement(t), s = d.getElementsByTagName(t)[0];
	  bh.type = 'text/javascript';
	  bh.src = 'https://www.bugherd.com/sidebarv2.js?apikey=';
	  s.parentNode.insertBefore(bh, s);
	  })(document, 'script');
	</script>
	<?php
	endif;
}

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
	
	/**
	$data_array['wp_api'] = array(
		'products'     => rest_url( 'wc/v1/products' ),
		'current_page' => get_query_var( 'paged' ),
		'nonce'        => wp_create_nonce( 'wp_rest' ),
		'filter'       => prso_get_queried_term_id(), //HEY!! make sure you hook into rest api get_items filter for any endpoint using filter['cat'] see woocommerce.php -> vt_woo_rest_product_query() for example
	);
	**/
	
	
	wp_localize_script( $handle, $obj_name, $data_array );
	
}

/**
 * prso_get_queried_term_name
 *
 * Helper to return current queried term id if set
 *
 * @access public
 * @author Ben Moody
 */
function prso_get_queried_term_id() {

	//vars
	$queried_obj = get_queried_object();

	if ( ! isset( $queried_obj->term_id ) ) {
		return false;
	}

	return $queried_obj->term_id;
}

/**
 * prso_have_more_pages
 *
 * Helper to find out if there are more pages of results in wp_query
 *
 * @access    public
 * @author    Ben Moody
 */
function prso_have_more_pages() {

	//vars
	global $wp_query;
	$max_pages = $wp_query->max_num_pages;

	if ( $max_pages > 1 ) {
		return true;
	}

	return false;
}

/**
 * prso_render_load_more_button
 *
 * Render a load more button complete with data element for rest api endpoint
 *
 * @param string $endpoint - wp rest api endpoint, should be in rest_api array
 *     in local object
 *
 * @return string load more button html
 * @access public
 * @author Ben Moody
 */
function prso_render_load_more_button( $endpoint = null ) {

	$output = null;

	ob_start();
	?>
	<?php if ( prso_have_more_pages() ): ?>
		<div class="load-more-container">
			<button class="load-more"
					data-destination="ul.products"
					data-rest-endpoint="<?php echo esc_html( $endpoint ); ?>">
				<?php _ex( 'View More', 'button text', PRSOTHEMEFRAMEWORK__DOMAIN ); ?>
				<i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>
			</button>
		</div>
	<?php endif; ?>
	<?php
	$output = ob_get_contents();
	ob_end_clean();

	return $output;
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