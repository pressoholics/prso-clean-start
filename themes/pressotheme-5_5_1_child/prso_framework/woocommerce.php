<?php
/**
 * WooCommerce
 */

//Woocommerce support
add_action( 'after_setup_theme', 'vt_woocommerce_support' );
function vt_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

//Disable woocomerce styles
//add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * vt_get_woo_cart
 *
 * Render the custom woocommerce cart icon and item counter
 * SEE template part woocommerce/part-the_cart_icon to customise the output
 *
 * @access public
 * @author Ben Moody
 */
function vt_get_woo_cart() {

	if( method_exists('Prso_Woocom', 'get_woo_cart') ) {
		return Prso_Woocom::get_woo_cart();
	}

}

