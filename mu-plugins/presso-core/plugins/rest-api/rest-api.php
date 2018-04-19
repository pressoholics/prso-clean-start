<?php

/**
 * PrsoCustomRestApi
 *
 * Class contains any customisation to rest api
 *∂
 *
 * @access    public
 * @author    Ben Moody
 */
class PrsoCustomRestApi {

	function __construct() {

		//Prevent external access to ALL REST API endpoints
		add_filter( 'rest_authentication_errors', array(
			$this,
			'restrict_external_rest_access',
		) );

		add_filter( 'rest_prepare_post', array(
			$this,
			'rest_prepare_post',
		), 10, 3 );

		add_filter( 'rest_post_query', array(
			$this,
			'rest_post_query',
		), 999, 2 );

	}

	/**
	 * rest_prepare_post
	 *
	 * @CALLED BY FILTER/ 'rest_prepare_post'
	 *
	 * Filter rest api response for indivdual posts
	 *
	 * @access public
	 * @author Ben Moody
	 */
	function rest_prepare_post( $response, $post_object, $request ) {

		global $post;

		$post = $post_object;

		setup_postdata( $post );

		ob_start();
			get_template_part('/template_parts/part', 'posts_grid_item');
		$response->data['item_html'] = ob_get_contents();
		ob_end_clean();

		wp_reset_postdata();

		return rest_ensure_response( $response );
	}

	/**
	 * rest_post_query
	 *
	 * @CALLED BY FILTER/ 'rest_post_query'
	 *
	 * Force posts per page to match WP deafult
	 *
	 * @access public
	 * @author Ben Moody
	 */
	function rest_post_query( $args, $request ) {

		$args['posts_per_page'] = get_option( 'posts_per_page' );
		$args['post_status']    = 'publish';

		//Detect category filter in request
		if ( isset( $request['filter']['cat'] ) ) {

			unset( $args['cat'] );

			$term_id = intval( $request['filter']['cat'] );

			$args['tax_query'] = array(
				array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => array( $term_id ),
				),
			);

		}

		return $args;
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