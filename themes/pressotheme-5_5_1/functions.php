<?php
/*
Author: Benjamin Moody
URL: htp://www.BenjaminMoody.com
Version: 5.5.1
*/

/******************************************************************
 * 	Version
 *
 *****************************************************************/
 define( 'PRSOTHEMEFRAMEWORK__VERSION', '5.5.1' );

/******************************************************************
 * 	Text Domain
 *
 *****************************************************************/
 if( !defined('PRSOTHEMEFRAMEWORK__DOMAIN') ) {
	 define( 'PRSOTHEMEFRAMEWORK__DOMAIN', 'prso-theme-domain' );
	 load_theme_textdomain( PRSOTHEMEFRAMEWORK__DOMAIN, get_stylesheet_directory() . '/languages' );
 }

/**
* ADD CUSTOM THEME FUNCTIONS HERE -----
*
*/

//Easy Foundation Shortcode Plugin -- Remove any scripts/styles added by this plugin as theme has all this
remove_action( 'wp_enqueue_scripts', 'osc_add_frontend_efs_scripts', -100 );
remove_action( 'wp_enqueue_scripts', 'efs_osc_add_dynamic_css', 100 );
//remove_action( 'admin_menu', 'osc_efs_add_admin_menu' ); //Req for admin toolbar to work
//remove_action( 'admin_head', 'osc_efs_get_icons' ); //Req for admin toolbar to work

// unregister all default WP Widgets
function prso_unregister_default_wp_widgets() {
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Text');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('WP_Nav_Menu_Widget');
}
//add_action('widgets_init', 'prso_unregister_default_wp_widgets', 1);

/**
* prso_get_file_version
* 
* Helper to dynamically generate a file version for enqueued scripts/styles based
* on the filemtime()
* 
* NOTE that the param should be the path to the enequeud file from the theme root
* note it should start with a slash e.g. '/styles.css'
*
*
* @param	string	$file_path_from_theme_dir
* @access 	public
* @author	Ben Moody
*/
function prso_get_file_version( $file_path_from_theme_dir = NULL ) {
	
	return filemtime( get_stylesheet_directory() . $file_path_from_theme_dir );
	
}

add_filter('get_archives_link', 'archive_count_no_brackets');
add_filter('wp_list_categories', 'archive_count_no_brackets');
function archive_count_no_brackets($links) {
	
	//Wrap post count in span for styling
	$links = str_replace('(', '</a>&nbsp;<span class="prso-post-count">(', $links);
	$links = str_replace(')', ')</span>', $links);

	return $links;
	
}

function prso_get_theme_retina_images( $filename = NULL, $alt = NULL, $class = NULL ) {
	
	return PrsoThemeFunctions::get_theme_retina_images( $filename, $alt, $class );
	
}

function prso_wp_get_image( $image_data = NULL, $alt = NULL, $class = NULL ) {
	
	return PrsoThemeFunctions::get_wp_image_retina_html( $image_data , $alt , $class );
	
}

/**
* PRSO THEME FRAMEWORK -- DO NOT REMOVE!
* Call method to boot core framework
*
*/	

/**
* Include config file to set core definitions
*
*/
$config_path = get_template_directory() . '/prso_framework/config.php';

//Search for config in child theme
if( file_exists( get_stylesheet_directory() . '/prso_framework/config.php' ) ) {
	$config_path = get_stylesheet_directory() . '/prso_framework/config.php';
}

if( file_exists($config_path) ) {
	
	include( $config_path );
	
	if( class_exists('PrsoThemeConfig') ) {
		
		new PrsoThemeConfig();
		
		//Core loaded, load rest of plugin core
		include( get_template_directory() . '/prso_framework/bootstrap.php' );

		//Instantiate bootstrap class
		if( class_exists('PrsoThemeBootstrap') ) {
			new PrsoThemeBootstrap();
		}
		
	}
	
}

/**
* prso_theme_localize
* 
* Add all localized script vars here.
* 
* @access 	public
* @author	Ben Moody
*/
function prso_parent_theme_localize() {
	
	//Init vars
	global $post;
	$handle 	= 'prso-theme-app';
	$obj_name	= 'prsoParentThemeLocalVars';
	$data_array = array();
	
	if( !is_admin() ) {
		/** Cache data for localization **/
		
		if( isset($post->ID) ) {
			$data_array['currentPostID'] = $post->ID;
		}
			
		wp_localize_script( $handle, $obj_name, $data_array );
	}
	
}
add_action( 'wp_print_scripts', 'prso_parent_theme_localize', 100 );

// Comment Layout
if( !function_exists('prso_theme_comments') ) {

	function prso_theme_comments($comment, $args, $depth) {
	   $GLOBALS['comment'] = $comment; ?>
		<li <?php comment_class(); ?>>
			<article id="comment-<?php comment_ID(); ?>" class="clearfix">
				<div class="comment-author vcard clearfix">
                    <div class="
                        <?php
                        $authID = get_the_author_meta('ID');
                                                    
                        if($authID == $comment->user_id)
                            echo "callout";
                        ?>
                    ">
                        <div class="row">
            				<div class="avatar large-2 columns">
            					<?php echo get_avatar($comment,$size='75',$default='' ); ?>
            				</div>
            				<div class="large-10 columns">
            					<?php printf(__('<h4 class="span8">%s</h4>'), get_comment_author_link()) ?>
            					<time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time('F jS, Y'); ?> </a></time>
            					
            					<?php edit_comment_link(__('Edit'),'<span class="edit-comment">', '</span>'); ?>
                                
                                <?php if ($comment->comment_approved == '0') : ?>
                   					<div class="alert-box success">
                      					<?php _e('Your comment is awaiting moderation.') ?>
                      				</div>
            					<?php endif; ?>
                                
                                <?php comment_text() ?>
                                
                                <!-- removing reply link on each comment since we're not nesting them -->
            					<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                            </div>
                        </div>
                    </div>
				</div>
			</article>
	    <!-- </li> is added by wordpress automatically -->
	<?php
	} // don't remove this bracket!
	
}