<?php
class Prso_Acf {

	public function __construct() {

		//$this->load_blocks();

		//Add custom block category
		//add_filter( 'block_categories', array( $this, 'blocks_catergories' ), 10, 1 );

	}

	public function load_blocks() {

		//Vars
		$cpt_path = dirname( __FILE__ ) . '/blocks';

		//Include files
		prso_include_all_files( $cpt_path );

	}

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

}
new Prso_Acf();
