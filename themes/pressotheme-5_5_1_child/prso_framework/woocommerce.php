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
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * vt_get_woo_cart
 *
 * Render the custom woocommerce cart icon and item counter
 *
 * @access public
 * @author Ben Moody
 */
function vt_get_woo_cart() {

	$css_classes = null;
	$item_output = null;

	$count = WC()->cart->cart_contents_count;

	if ( 0 === $count ) {
		$css_classes = 'empty-cart';
	}

	ob_start();
	?>
	<a class="cart-contents <?php echo sanitize_html_class( $css_classes ); ?>"
	   href="<?php echo esc_url( wc_get_cart_url() ); ?>"
	   title="<?php echo esc_html_x( 'View your shopping cart', 'text', PRSOTHEMEFRAMEWORK__DOMAIN ); ?>">

		<i class="fa fa-shopping-cart" aria-hidden="true"></i>
		<?php
		if ( $count > 0 ) {
			?>
			<span class="cart-contents-count">
				<?php echo esc_html( $count ); ?>

				<?php if ( 1 === $count ): ?>
					<?php echo esc_html_x( 'item', 'text', PRSOTHEMEFRAMEWORK__DOMAIN ); ?>
				<?php else: ?>
					<?php echo esc_html_x( 'items', 'text', PRSOTHEMEFRAMEWORK__DOMAIN ); ?>
				<?php endif; ?>

			</span>
			<?php
		}
		?>
	</a>
	<?php
	$item_output = ob_get_contents();
	ob_end_clean();

	return $item_output;
}

/**
 * vt_woo_cart_count_fragments
 *
 * @CALLED BY FILTER 'woocommerce_add_to_cart_fragments'
 *
 * Add custom cart icon and item counter markup to woocommerce add to cart
 *     fragment. This ensures that the markup for the custom cart icon is
 *     updated when a product is added to cart via ajax
 *
 * @param array $fragments
 *
 * @param array $fragments
 *
 * @access public
 * @author Ben Moody
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'vt_woo_cart_count_fragments', 10, 1 );
function vt_woo_cart_count_fragments( $fragments ) {

	$fragments['div#vt-woo-cart'] = '<div id="vt-woo-cart">' . vt_get_woo_cart() . '</div>';

	return $fragments;

}

add_action( 'init', 'vt_woo_remove_wc_breadcrumbs' );
function vt_woo_remove_wc_breadcrumbs() {

	//woocommerce_before_main_content
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

	//woocommerce_before_shop_loop
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

	//woocommerce_after_shop_loop
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
	add_action( 'woocommerce_after_shop_loop', 'vt_woocommerce_pagination', 10 );

}

/**
 * vt_woocommerce_pagination
 *
 * @CALLED BY /ACTION 'woocommerce_after_shop_loop'
 *
 * Render load more button on product shop index pages
 *
 * @access public
 * @author Ben Moody
 */
function vt_woocommerce_pagination() {

	?>
	<?php echo prso_render_load_more_button( 'products', 'ul.products' ); ?>
	<?php

}

/**
 * vt_woo_products_order
 *
 * @CALLED BY /ACTION 'pre_get_posts'
 *
 * Filter main query params for woo archives
 *
 * @access public
 * @author Ben Moody
 */
add_action( 'pre_get_posts', 'vt_woo_products_order' );
function vt_woo_products_order( $query ) {

	if ( ! vt_is_product_archive() ) {
		return;
	}

	$query->set( 'orderby', 'date' );
	$query->set( 'order', 'DESC' );
	$query->set( 'post_per_page', get_option( 'posts_per_page' ) );
	$query->set( 'post_status', 'publish' );

}

/**
 * vt_woo_rest_product_query
 *
 * @CALLED BY FILTER/ 'woocommerce_rest_product_query'
 *
 * Force woo posts per page to match WP deafult
 *
 * @access public
 * @author Ben Moody
 */
add_filter( 'woocommerce_rest_product_query', 'vt_woo_rest_product_query', 999, 2 );
function vt_woo_rest_product_query( $args, $request ) {

	$args['posts_per_page'] = get_option( 'posts_per_page' );
	$args['post_status']    = 'publish';

	//Detect category filter in request
	if ( isset( $request['filter']['cat'] ) ) {

		unset( $args['cat'] );

		$term_id = intval( $request['filter']['cat'] );

		$args['tax_query'] = array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => array( $term_id ),
			),
		);

	}

	return $args;
}

/**
 * vt_woo_loop_shop_per_page
 *
 * @CALLED BY FILTER/ 'loop_shop_per_page'
 *
 * Force woo posts per page to match WP deafult
 *
 * @access public
 * @author Ben Moody
 */
add_filter( 'loop_shop_per_page', 'vt_woo_loop_shop_per_page', 20, 1 );
function vt_woo_loop_shop_per_page( $cols ) {

	$cols = get_option( 'posts_per_page' );

	return $cols;
}

/**
 * vt_woo_rest_prepare_product
 *
 * @CALLED BY FILTER/ 'woocommerce_rest_prepare_product'
 *
 * Filter woo prodcuts rest api product response, add node containing rendered
 *     contents of woo content-product template
 *
 * @access public
 * @author Ben Moody
 */
add_filter( 'woocommerce_rest_prepare_product', 'vt_woo_rest_prepare_product', 999, 3 );
function vt_woo_rest_prepare_product( $response, $post_object, $request ) {

	global $product, $post;

	$post = $post_object;

	$product = wc_get_product( $post->ID );

	ob_start();
	wc_get_template_part( 'content', 'product' );
	$response->data['item_html'] = ob_get_contents();
	ob_end_clean();

	return rest_ensure_response( $response );
}

add_action( 'init', 'vt_woo_product_archive_item_component_order' );
function vt_woo_product_archive_item_component_order() {

	//Move item rating above item title
	add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_rating', 11 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

	//Add rating count right after rating
	add_action( 'woocommerce_before_shop_loop_item_title', 'vt_woocommerce_get_star_rating_html', 12 );

	//Add product short desciption after price
	add_action( 'woocommerce_after_shop_loop_item_title', 'vt_woo_product_loop_short_description', 11 );

}

/**
 * vt_woocommerce_get_star_rating_html
 *
 * @CALLED BY FILTER/ 'woocommerce_before_shop_loop_item_title'
 *
 * Render a review counter element to star rating component
 *
 * @access public
 * @author Ben Moody
 */
function vt_woocommerce_get_star_rating_html() {

	global $product;

	$count = $product->get_rating_count();

	?>
	<div class="review-count">
		<?php
		printf(
			esc_html(
				_n( '%d Review', '%d Reviews', intval( $count ), PRSOTHEMEFRAMEWORK__DOMAIN )
			),
			intval( $count )
		);
		?>
	</div>
	<div class="clearfix"></div>
	<?php
}

/**
 * vt_woo_product_loop_short_description
 *
 * @CALLED BY /ACTION 'woocommerce_after_shop_loop_item_title'
 *
 * Output product short description (excerpt)
 *
 * @access public
 * @author Ben Moody
 */
function vt_woo_product_loop_short_description() {

	?>
	<p>
		<?php the_excerpt(); ?>
	</p>
	<?php

}

add_filter( 'the_excerpt', 'vt_woo_the_excerpt' );
function vt_woo_the_excerpt( $text ) {

	if ( ! vt_is_product_archive() ) {
		return $text;
	}

	$text = wp_trim_words( $text, 15 );

	return $text;
}

/**
 * vt_woo_archive__before_main_content
 *
 * @CALLED BY /ACTION 'woocommerce_before_main_content'
 *
 * Render some elements before the the woo main content for product archives
 *     ONLY
 *
 * @access public
 * @author Ben Moody
 */
add_action( 'woocommerce_before_main_content', 'vt_woo_archive__before_main_content' );
function vt_woo_archive__before_main_content() {

	if ( ! vt_is_product_archive() ) {
		return;
	}

	//Wrap shop contents in foundation markup
	echo '<div class="row">';

	//Render shop sidebar component
	get_sidebar( 'shop' );

	//Wrap shop contents in foundation markup
	echo '<div class="small-12 medium-9 columns">';

}

/**
 * vt_woo_archive__after_main_content
 *
 * @CALLED BY /ACTION 'woocommerce_after_main_content'
 *
 * Render some elements after the the woo main content for product archives
 *     ONLY
 *
 * @access public
 * @author Ben Moody
 */
add_action( 'woocommerce_after_main_content', 'vt_woo_archive__after_main_content' );
function vt_woo_archive__after_main_content() {

	if ( ! vt_is_product_archive() ) {
		return;
	}

	//close product col container
	echo '</div> <!-- Close Products Col !-->';

	//close row
	echo '</div> <!-- Close Row !-->';

}

/**
 * vt_is_product_archive
 *
 * Helper to detect if current view is a WooCommerce product archive view
 *
 * @access public
 * @author Ben Moody
 */
function vt_is_product_archive() {

	if ( is_shop() ) {
		return true;
	}

	if ( is_product_category() ) {
		return true;
	}

	return false;
}

/**
 * vt_woo_archive__before_main_content
 *
 * @CALLED BY /ACTION 'woocommerce_before_main_content'
 *
 * Render some elements before the the woo main content for product archives
 *     ONLY
 *
 * @access public
 * @author Ben Moody
 */
add_action( 'woocommerce_before_main_content', 'vt_woo_single_product__before_main_content' );
function vt_woo_single_product__before_main_content() {

	if ( ! is_product() ) {
		return;
	}

	//Wrap shop contents in foundation markup
	echo '<div class="row">';

	//Wrap shop contents in foundation markup
	echo '<div class="small-12 medium-9 columns">';

}

/**
 * vt_woo_archive__after_main_content
 *
 * @CALLED BY /ACTION 'woocommerce_after_main_content'
 *
 * Render some elements after the the woo main content for product archives
 *     ONLY
 *
 * @access public
 * @author Ben Moody
 */
add_action( 'woocommerce_after_main_content', 'vt_woo_single_product__after_main_content' );
function vt_woo_single_product__after_main_content() {

	if ( ! is_product() ) {
		return;
	}

	//close product col container
	echo '</div> <!-- Close Products Col !-->';

	//close row
	echo '</div> <!-- Close Row !-->';

}

/**
 * vt_woocommerce_filter_product_categories_widget_items
 *
 * @CALLED BY FILTER/ 'wp_list_categories'
 *
 * Filter woocommerce products cateogyr list and add link to products master
 *     page at the top
 *
 * @access public
 * @author Ben Moody
 */
add_filter( 'wp_list_categories', 'vt_woocommerce_filter_product_categories_widget_items', 10, 2 );
function vt_woocommerce_filter_product_categories_widget_items( $output, $args ) {

	//vars
	$new_item     = null;
	$shop_page_id = get_option( 'woocommerce_shop_page_id' );

	if ( isset( $args['taxonomy'] ) && ( 'product_cat' !== $args['taxonomy'] ) ) {
		return $output;
	}

	ob_start();
	?>
	<li class="cat-item all-products">
		<a href="<?php echo esc_url_raw( get_permalink( $shop_page_id ) ); ?>">
			<?php echo esc_html_x( 'All Products', 'text', PRSOTHEMEFRAMEWORK__DOMAIN ); ?>
		</a>
	</li>
	<?php
	$new_item = ob_get_contents();
	ob_end_clean();

	//Prepend new item
	$output = $new_item . $output;

	return $output;
}

/**
 * vt_get_price_html
 *
 * Copy of WooCommerce product->get_price_html() Needed to clone this out to
 * remove subscription plugin override of price on product archive views
 *
 * @access public
 * @author Ben Moody
 */
add_filter( 'woocommerce_variable_subscription_price_html', 'vt_woocommerce_variable_subscription_price_html', 999, 2 );
function vt_woocommerce_variable_subscription_price_html( $price, $product_class ) {
	global $product;

	if ( ! method_exists( $product, 'get_variation_prices' ) ) {
		return $price;
	}

	$prices = $product->get_variation_prices( true );

	if ( empty( $prices['price'] ) ) {
		$price = apply_filters( 'woocommerce_variable_empty_price_html', '', $this );
	} else {
		$min_price     = current( $prices['price'] );
		$max_price     = end( $prices['price'] );
		$min_reg_price = current( $prices['regular_price'] );
		$max_reg_price = end( $prices['regular_price'] );

		if ( $product->is_on_sale() && $min_reg_price === $max_reg_price ) {
			$price = wc_format_sale_price( wc_price( $max_reg_price ), wc_price( $min_price ) );
		} else {
			$price = wc_price( $min_price );
		}
	}

	return $price;
}

/**
 * vt_woocommerce_format_sale_price
 *
 * @CALLED BY FILTER/ 'woocommerce_format_sale_price'
 *
 * Filter sale price output for woocommerce product
 *
 * @access public
 * @author Ben Moody
 */
add_filter( 'woocommerce_format_sale_price', 'vt_woocommerce_format_sale_price', 999, 3 );
function vt_woocommerce_format_sale_price( $price, $regular_price, $sale_price ) {

	//vars
	$output         = null;
	$saving_percent = null;

	//Calc % price delta
	$saving_percent = ( ( $regular_price - $sale_price ) / $regular_price ) * 100;

	ob_start();
	?>
	<ul class="sale-price">

		<li>
			<del>
				<?php
				if ( is_numeric( $regular_price ) ) {
					echo wc_price( $regular_price );
				} else {
					echo esc_html( $regular_price );
				}
				?>
			</del>
			<?php echo esc_html_x( 'Regular Price', 'text', PRSOTHEMEFRAMEWORK__DOMAIN ); ?>
		</li>

		<li>
			<ins>
				<?php
				if ( is_numeric( $sale_price ) ) {
					echo wc_price( $sale_price );
				} else {
					echo esc_html( $sale_price );
				}
				?>
			</ins>
			(<?php
			printf(
				esc_html__( 'You save %d', PRSOTHEMEFRAMEWORK__DOMAIN ),
				intval( $saving_percent )
			);
			?>%)
		</li>

	</ul>
	<?php
	$output = ob_get_contents();
	ob_end_clean();

	return $output;
}

/**
 * vt_woocommerce_product_add_to_cart_text
 *
 * @CALLED BY FILTER/ 'woocommerce_product_add_to_cart_text'
 *
 * Filter product add to cart button text
 *
 * @access public
 * @author Ben Moody
 */
add_filter( 'woocommerce_product_add_to_cart_text', 'vt_woocommerce_product_add_to_cart_text', 10, 2 );
function vt_woocommerce_product_add_to_cart_text( $text, $product ) {

	return esc_html_x( 'Add to cart', 'text', PRSOTHEMEFRAMEWORK__DOMAIN );

}
