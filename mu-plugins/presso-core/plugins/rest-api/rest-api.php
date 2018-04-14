<?php

/**
 * PrsoCustomRestApi
 *
 * Class contains any customisation to rest api
 *∂
 * @access 	public
 * @author	Ben Moody
 */
class PrsoCustomRestApi {

	function __construct() {

		//Prevent external access to ALL REST API endpoints
		add_filter( 'rest_authentication_errors', array(
			$this,
			'restrict_external_rest_access',
		) );

	}

	/**
	 * restrict_external_rest_access
	 *
	 * @CALLED BY FILTER 'rest_authentication_errors'
	 *
	 * Checks and validates HTTP_X_WP_NONCE in request, prevents reqeusts to
	 *     REST API without valid rest api nonce
	 *
	 * @param bool $result
	 *
	 * @return mixed WP_Error/bool
	 * @access public
	 * @author Ben Moody
	 */
	public function restrict_external_rest_access( $result ) {
		if ( ! empty( $result ) ) {
			return $result;
		}

		global $wp_rest_auth_cookie;

		/*
		 * Is cookie authentication being used? (If we get an auth
		 * error, but we're still logged in, another authentication
		 * must have been used).
		 */
		if ( true === $wp_rest_auth_cookie && is_user_logged_in() ) {
			return true;
		}

		// Determine if there is a nonce.
		$nonce = null;

		if ( isset( $_REQUEST['_wpnonce'] ) ) {
			$nonce = $_REQUEST['_wpnonce'];
		} elseif ( isset( $_SERVER['HTTP_X_WP_NONCE'] ) ) {
			$nonce = $_SERVER['HTTP_X_WP_NONCE'];
		}

		// Check the nonce.
		$result = wp_verify_nonce( $nonce, 'wp_rest' );

		if ( ! $result ) {
			return new WP_Error( 'rest_cookie_invalid_nonce', __( 'PRSO Framework Functions: Cookie nonce is invalid.' ), array( 'status' => 403 ) );
		}

		return true;
	}

}
new PrsoCustomRestApi();