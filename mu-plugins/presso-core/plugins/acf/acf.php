<?php
class Prso_Acf {

	public function __construct() {

		//$this->load_blocks();
		
		//$this->load_block_templates();

		//Add custom block category
		//add_filter( 'block_categories', array( $this, 'blocks_catergories' ), 10, 1 );

		//Set project allowed block types
		//add_filter( 'allowed_block_types', array( $this, 'blocks_allowed_block_types' ) );

	}

	public function load_blocks() {

		//Vars
		$cpt_path = dirname( __FILE__ ) . '/blocks';

		//Include files
		prso_include_all_files( $cpt_path );

	}
	
	public function load_block_templates() {

		//Vars
		$cpt_path = dirname( __FILE__ ) . '/block-templates';

		//Include files
		prso_include_all_files( $cpt_path );

	}

	/**
	 * blocks_catergories
	 *
	 * @CALLED BY FILTER 'block_categories'
	 *
	 * Add custom block category
	 *
	 * @param array $categories
	 *
	 * @param array $categories
	 *
	 * @access public
	 * @author Ben Moody
	 */
	function blocks_catergories( $categories ) {

		$categories = array_merge(
			$categories,
			array(
				array(
					'slug'  => 'gcc-blocks',
					'title' => 'GCC Blocks',
				),
			)
		);

		return $categories;
	}

	/**
	 * blocks_allowed_block_types
	 *
	 * @CALLED BY FILTER 'allowed_block_types'
	 *
	 * Filter the block types users are allowed to access
	 *
	 * @param array $allowed_blocks
	 * @return array $allowed_blocks
	 * @access public
	 * @author Ben Moody
	 */
	function blocks_allowed_block_types( $allowed_blocks ) {

		$allowed_blocks = array(
			//Core blocks
			'core/paragraph',
			'core/heading',
			'core/list',
			'core/quote',
			'core/image',

			//Project blocks
			'acf/project-block-name-here',
		);

		/**
		 * prso_blocks__allowed_blocks
		 *
		 * @since 1.0.0
		 *
		 * @param array $allowed_blocks
		 */
		$allowed_blocks = apply_filters( 'prso_blocks__allowed_blocks', $allowed_blocks );

		return $allowed_blocks;
	}
	
	/**
	 * is_gutenberg_request
	 *
	 * Helper to detect if current request is from the Gutenberg editor
	 *
	 * @access public static
	 * @author Ben Moody
	 */
	public static function is_gutenberg_request() {

		global $pagenow;

		if ( isset( $pagenow ) && ( 'post-new.php' === $pagenow ) ) {
			return true;
		}

		if ( isset( $_REQUEST['_locale'] ) ) {
			return true;
		}

		if ( isset( $_REQUEST['action'] ) && ( 'edit' === $_REQUEST['action'] ) ) {
			return true;
		}
		
		if ( isset( $_REQUEST['action'] ) && ( 'acf/ajax/render_block_preview' === $_REQUEST['action'] ) ) {
			return true;
		}

		if ( isset( $_REQUEST['context'] ) && ( 'edit' === $_REQUEST['context'] ) ) {
			return true;
		}

		return false;
	}

}
new Prso_Acf();
