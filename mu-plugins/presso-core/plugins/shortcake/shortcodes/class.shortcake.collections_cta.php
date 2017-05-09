<?php
/**
 * Handles registration of shortcode as well as adds it to shortcake plugin
 */
class Cbi_Shortcake_Collections_Cta {

	function __construct() {

		//Register Shortcodes
		add_action('init', array($this, 'register_shortcodes'), 10);

	}

	public function register_shortcodes() {

		//collections cta shortcode
		add_shortcode('cbi_collections_cta', array($this, 'collections_cta_shortcode'));

		//Register collections cta with shortcake
		add_action( 'register_shortcode_ui', array($this, 'shortcake__collections_cta_shortcode') );

	}

	/**
	 * collections_cta_shortcode
	 *
	 * @CALLED BY add_shortcode('cbi_collections_cta')
	 *
	 * Regsiter shortcode to generate custom gallery element
	 *
	 * @access 	public
	 * @author	Ben Moody
	 */
	public function collections_cta_shortcode( $attr, $content, $shortcode_tag ) {

		//vars
		$images             = array();
		$image_size_name    = 'fbm-flex-content-blocks';

		$attr = shortcode_atts(array(
			'title'             => '',
			'collection_name'   => '',
			'collection_items'  => '',
			'collection_image'  => '',
			'clone_url'         => '',
			'cta_title'         => '',
			'cta_url'           => '',
		), $attr, $shortcode_tag);

		//Loop attr and convert image attachment ID's into image urls
		if( !empty($attr['collection_image']) ) {

			$attr['collection_image'] = wp_get_attachment_url( intval($attr['collection_image']), 'full' );

		}

		// Shortcode callbacks must return content, hence, output buffering here.
		ob_start();
		?>
		<!-- BEGIN_CTA COLLECTION -->
		<div class="cbi-blog-cta-collection">

			<div class="row">

				<div class="col-sm-8">

					<h4>
						<?php echo sanitize_text_field( $attr['title'] ); ?>
					</h4>

					<p><?php echo esc_html( $content ); ?></p>

					<a class="btn btn-warning" href="<?php echo esc_url( $attr['cta_url'] ); ?>" target="_blank">
						<?php echo sanitize_text_field( $attr['cta_title'] ); ?>
					</a>

				</div>

				<div class="col-sm-4">

                    <a href="<?php echo esc_url( $attr['clone_url'] ); ?>" target="_blank">
                        <div class="tile" style="background-image: url('<?php echo esc_url( $attr['collection_image'] ); ?>');">

                            <div class="header">

                                <h4 class="collection-name"><?php echo sanitize_text_field( $attr['collection_name'] ); ?></h4>
                                <p class="nb-items"><?php echo sanitize_text_field( $attr['collection_items'] ); ?> items</p>

                            </div>

                            <div class="footer">
                                <span class="collection-action"><i class="icon icon-code-fork"></i> CLONE</span>
                            </div>
                        </div>
                    </a>

				</div>

			</div>

		</div>
		<!-- END_CTA COLLECTION -->
		<?php

		return ob_get_clean();
	}

	/**
	 * shortcake__collections_cta_shortcode
	 *
	 * @CALLED BY ACTION 'register_shortcode_ui'
	 *
	 * Register cbi_collections_cta with shortcake plugin and setup interface
	 *
	 * @access 	public
	 * @author	Ben Moody
	 */
	public function shortcake__collections_cta_shortcode() {

		// Available shortcode attributes and default values. Required. Array.
        // Attribute model expects 'attr', 'type' and 'label'
        // Supported field types: text, checkbox, textarea, radio, select, email, url, number, and date.
		$fields = array(
			array(
				'label'       => 'Title',
				'attr'        => 'title',
				'type'        => 'text',
				'description' => 'CTA Title',
			),
			array(
				'label'       => 'Collection Image',
				'attr'        => 'collection_image',
				'type'        => 'attachment',
				/*
				 * These arguments are passed to the instantiation of the media library:
				 * 'libraryType' - Type of media to make available.
				 * 'addButton'   - Text for the button to open media library.
				 * 'frameTitle'  - Title for the modal UI once the library is open.
				 */
				'libraryType' => array( 'image' ),
				'addButton'   => esc_html__( 'Select Image', 'text_domain' ),
				'frameTitle'  => esc_html__( 'Select Image', 'text_domain' ),
			),
			array(
				'label'       => 'Collection Name',
				'attr'        => 'collection_name',
				'type'        => 'text',
				'description' => 'Collection Name',
			),
			array(
				'label'       => 'Number of Items',
				'attr'        => 'collection_items',
				'type'        => 'text',
				'description' => 'Number of items in the collection',
			),
			array(
				'label'       => 'Clone URL',
				'attr'        => 'clone_url',
				'type'        => 'text',
				'description' => 'URL for clone button action',
				'placeholder'   => 'http://example-domain.com'
			),
			array(
				'label'       => 'CTA Title',
				'attr'        => 'cta_title',
				'type'        => 'text',
				'description' => 'CTA Button title',
			),
			array(
				'label'       => 'CTA URL',
				'attr'        => 'cta_url',
				'type'        => 'text',
				'description' => 'CTA Button URL',
				'placeholder'   => 'http://example-domain.com'
			),
		);

		/*
		 * Define the Shortcode UI arguments.
		 */
		$shortcode_ui_args = array(
			/*
			 * How the shortcode should be labeled in the UI. Required argument.
			 */
			'label' => 'Collections CTA',
			/*
			 * Include an icon with your shortcode. Optional.
			 * Use a dashicon, or full HTML (e.g. <img src="/path/to/your/icon" />).
			 */
			'listItemImage' => 'dashicons-megaphone',
			/*
			 * Register UI for the "inner content" of the shortcode. Optional.
			 * If no UI is registered for the inner content, then any inner content
			 * data present will be backed-up during editing.
			 */
			'inner_content' => array(
				'label'        => 'CTA Description',
				'description'  => 'Enter description here',
			),
			/*
			 * Limit this shortcode UI to specific posts. Optional.
			 */
			'post_type' => array( 'post', 'team_blog' ),
			'attrs' => $fields,
		);
		shortcode_ui_register_for_shortcode( 'cbi_collections_cta', $shortcode_ui_args );
	}

}
new Cbi_Shortcake_Collections_Cta();