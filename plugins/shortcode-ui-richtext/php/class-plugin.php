<?php
/**
 * Bootstraps the Shortcake Richtext plugin.
 *
 * @package ShortcodeUiRichtext
 */

namespace ShortcodeUiRichtext;

/**
 * Main plugin bootstrap file.
 */
class Plugin extends Plugin_Base {

	/**
	 * Integration object
	 *
	 * @var Integration
	 */
	public $integration;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		parent::__construct();

		$priority = 9; // Because WP_Customize_Widgets::register_settings() happens at after_setup_theme priority 10.
		add_action( 'after_setup_theme', array( $this, 'init' ), $priority );

		$this->integration = new Integration( $this );
	}

	/**
	 * Initiate the plugin resources.
	 *
	 * @action after_setup_theme
	 */
	public function init() {
		add_action( 'wp_default_scripts', array( $this, 'register_scripts' ), 11 );
		add_action( 'wp_default_styles', array( $this, 'register_styles' ), 11 );
	}

	/**
	 * Register scripts.
	 *
	 * @param \WP_Scripts $wp_scripts Instance of \WP_Scripts.
	 * @action wp_default_scripts
	 */
	public function register_scripts( \WP_Scripts $wp_scripts ) {}

	/**
	 * Register styles.
	 *
	 * @param \WP_Styles $wp_styles Instance of \WP_Styles.
	 * @action wp_default_styles
	 */
	public function register_styles( \WP_Styles $wp_styles ) {}
}
