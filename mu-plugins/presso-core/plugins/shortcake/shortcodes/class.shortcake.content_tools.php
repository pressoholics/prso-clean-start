<?php
/**
 * Handles registration of shortcode as well as adds it to shortcake plugin
 */
class Cbi_Shortcake_Content_Tools {

	function __construct() {

		//Register Shortcodes
		add_action('init', array($this, 'register_shortcodes'), 10);

	}

	public function register_shortcodes() {

		//button cta shortcode
		add_shortcode('prso_button', array($this, 'button_shortcode'));

		//Register collections cta with shortcake
		add_action( 'register_shortcode_ui', array($this, 'shortcake__prso_button_shortcode') );

	}

	/**
	 * button_shortcode
	 *
	 * @CALLED BY add_shortcode('prso_button')
	 *
	 * Regsiter shortcode to generate custom gallery element
	 *
	 * @access 	public
	 * @author	Ben Moody
	 */
	public function button_shortcode( $attr, $content, $shortcode_tag ) {

		//vars
		$attr = shortcode_atts(array(
			'title'     => '',
			'url'       => '',
			'css_class' => '',
			'size'      => '',
            'style'     => '',
            'target'    => '_parent'
		), $attr, $shortcode_tag);

		// Shortcode callbacks must return content, hence, output buffering here.
		ob_start();
		?>
		<a href="<?php echo esc_url( $attr['url'] ); ?>" class="button <?php echo esc_html( $attr['css_class'] ); ?> <?php echo esc_html( $attr['size'] ); ?> <?php echo esc_html( $attr['style'] ); ?>" target="<?php echo esc_html( $attr['target'] ); ?>" >
            <?php echo esc_html( $attr['title'] ); ?>
        </a>
		<?php

		return ob_get_clean();
	}

	/**
	 * shortcake__prso_button_shortcode
	 *
	 * @CALLED BY ACTION 'register_shortcode_ui'
	 *
	 * Register prso_button with shortcake plugin and setup interface
	 *
	 * @access 	public
	 * @author	Ben Moody
	 */
	public function shortcake__prso_button_shortcode() {

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
				'label'       => 'Button URL',
				'attr'        => 'url',
				'type'        => 'text',
				'description' => 'URL to link button to',
			),
			array(
				'label'       => 'CSS Classes (optional)',
				'attr'        => 'css_class',
				'type'        => 'text',
				'description' => 'Any custom CSS classes you want to add to the button',
			),
			array(
				'label' => 'Link Target',
				'attr' => 'target',
				'type' => 'select',
				'options' => array(
					array(
						'value' => '_parent',
						'label' => 'Parent (default)'
					),
					array(
						'value' => '_blank',
						'label' => 'New Window'
					),
				),
			),
			array(
				'label' => 'Button Size',
				'attr' => 'size',
				'type' => 'select',
				'options' => array(
					array(
						'value' => '',
						'label' => 'Default',
					),
                    array(
						'value' => 'tiny',
						'label' => 'Tiny',
					),
                    array(
						'value' => 'small',
						'label' => 'Small',
					),
                    array(
						'value' => 'large',
						'label' => 'Large',
					),
                    array(
						'value' => 'expand',
						'label' => 'Full Width',
					),
                    array(
						'value' => 'disabled',
						'label' => 'Disabled',
					),
				),
			),
			array(
				'label' => 'Button Style',
				'attr' => 'style',
				'type' => 'select',
				'options' => array(
					array(
						'value' => '',
						'label' => 'Default',
					),
					array(
						'value' => 'round',
						'label' => 'Round',
					),
					array(
						'value' => 'radius',
						'label' => 'Rounded Corners',
					),
				),
			),
		);

		/*
		 * Define the Shortcode UI arguments.
		 */
		$shortcode_ui_args = array(
			/*
			 * How the shortcode should be labeled in the UI. Required argument.
			 */
			'label' => 'CTA Button',
			/*
			 * Include an icon with your shortcode. Optional.
			 * Use a dashicon, or full HTML (e.g. <img src="/path/to/your/icon" />).
			 */
			'listItemImage' => 'dashicons-megaphone',
			/*
			 * Limit this shortcode UI to specific posts. Optional.
			 */
			//'post_type' => array( 'post', 'team_blog' ),
			'attrs' => $fields,
		);
		shortcode_ui_register_for_shortcode( 'prso_button', $shortcode_ui_args );
	}

}
new Cbi_Shortcake_Content_Tools();