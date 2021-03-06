<?php
/**
 * Created by PhpStorm.
 * User: ben
 * Date: 2017-05-04
 * Time: 2:49 PM
 */

class PrsoShortcakeShortcodes {

	/**
	 * Reports constructor.
	 */
	public function __construct() {

		//Register post types
		$this->init_shortcodes();

	}

	/**
	 * init_shortcodes
	 *
	 * @CALLED BY ACTION 'init'
	 *
	 * Includes all class files for each shortcake shortcode group
	 *
	 * @access 	public
	 * @author	Ben Moody
	 */
	public function init_shortcodes() {

		//Vars
		$cpt_path = dirname( __FILE__ ) . '/shortcodes';

		//Include shortcake shortcode for posts
		prso_include_all_files( $cpt_path );

	}


}
new PrsoShortcakeShortcodes();