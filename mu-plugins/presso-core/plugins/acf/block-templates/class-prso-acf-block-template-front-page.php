<?php
/**
 * ACF Gutenberg Block Template
 */

class Prso_Acf_Block_Template_Front_Page {

	public function __construct() {

		//Register block template
//		add_action(
//			'init',
//			array(
//				$this,
//				'register_block_template'
//			)
//		);

		//Set template allowed block types
		//add_filter( 'prso_blocks__allowed_blocks', array( $this, 'allowed_block_types' ) );

	}

	/**
	* register_block_template
	*
	* @CALLED BY ACTION 'init'
	*
	* Register this block template with gutenberg
	*
	* @access public
	* @author Ben Moody
	*/
	public function register_block_template() {

		//vars
		$post_type_object = get_post_type_object( 'page' );

		//Should we apply this block template?
		if( false === $this->apply_block_template() ) {
			return;
		}

		$post_type_object->template = array(
			array(
				'core/paragraph',
				array(
					'placeholder' => 'Add Description...',
				)
			),
		);

		$post_type_object->template_lock = 'all'; //false, all or insert (allows moving but not new blocks)

	}

	/**
	* apply_block_template
	*
	* Helper to apply logic to descide if current post being editied should have this block template applied to it
	*
	* @access private
	* @author Ben Moody
	*/
	private function apply_block_template() {

		//vars
		$post_id = null;
		$result = false;

		if( !isset($_GET['action']) ) {
			return false;
		}

		if( 'edit' !== $_GET['action'] ) {
			return false;
		}

		if( !isset($_GET['post']) ) {
			return false;
		}

		//Home page only
		$front_page_id = get_option( 'page_on_front' );
		if( $_GET['post'] === $front_page_id ) {
			$result = true;
		}

		return $result;
	}

	/**
	 * allowed_block_types
	 *
	 * @CALLED BY FILTER 'prso_blocks__allowed_blocks'
	 *
	 * Filter the block types users are allowed to access
	 *
	 * @param array $allowed_blocks
	 * @return array $allowed_blocks
	 * @access public
	 * @author Ben Moody
	 */
	function allowed_block_types( $allowed_blocks ) {

		//Should we apply this block template?
		if( false === $this->apply_block_template() ) {
			return $allowed_blocks;
		}

		$allowed_blocks = array(
			//Core blocks
			'core/paragraph',
			'core/heading',
			'core/list',
			'core/quote',
			'core/image',

			//Project blocks
			'',
		);

		return $allowed_blocks;
	}

}
new Prso_Acf_Block_Template_Front_Page();
