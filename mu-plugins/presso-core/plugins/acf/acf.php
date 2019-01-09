<?php
class Prso_Acf {

	public function __construct() {

		//$this->load_blocks();

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
	function jam3_blocks_allowed_block_types( $allowed_blocks ) {

		$allowed_blocks = array(
			//Core blocks
			'core/paragraph',
			'core/heading',
			'core/list',
			'core/quote',
			'core/image',

			//Project blocks
			'gcc-blocks',
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

}
new Prso_Acf();
