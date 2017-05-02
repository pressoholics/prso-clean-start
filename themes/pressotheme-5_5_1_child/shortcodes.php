<?php
/**
* Theme Shortcodes
* 
* Contains all shortcodes used in this theme
* 
* @access 	public
* @author	Ben Moody
*/

#-----------------------------------------------------------------
# Disable wpautop on shortcode content
#-----------------------------------------------------------------

remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'wpautop', 13 );

remove_filter( 'the_content', 'do_shortcode', 11 );
add_filter( 'the_content', 'do_shortcode', 12 );

//Be sure all empty p tags are removed from content
add_filter( 'the_content', 'prso_remove_empty_p', 14 );
add_filter( 'widget_text', 'prso_remove_empty_p', 5 );

//Be sure all empty p tags are removed from content
function prso_remove_empty_p( $content ){
    return apply_filters( 'prso_remove_p', $content );
}

/**
* Shortcode Example
* 
* DESCRIPTION
* 
* @param	array	$atts 		- Shortcode attributes
* @param	string	$content 	- Post content
* @var		type	name
* @return	string	$content
* @access 	public
* @author	Ben Moody
*/
/*
add_shortcode( 'tab', 'EXAMPLE' );
function EXAMPLE( $atts, $content ){
	
    extract(shortcode_atts(array(
	), $atts));
	
    if( !empty($content) ) {
	    
	    //Add shortcode actions here
	    
	    
	    //NOTE: You may need to manually apply wpautop to content in your shortcode
	    //		If you want the user to be able to add P and BR with tinymce
	    $content = wpautop( trim($content) );
	    
    }
    
    return $content;
}
*/

#-----------------------------------------------------------------
# Zurb Foundation Shortcodes
#-----------------------------------------------------------------

/**
* Gallery Shortcodes
*
* Uses Zurb Foundation Clearing Feature
* 
*/
//Add shorcodes for gallery
remove_shortcode('gallery', 'gallery_shortcode' );
add_shortcode('gallery', 'gallery_shortcode_tbs' );
function gallery_shortcode_tbs($attr) {

	$post = get_post();

	static $instance = 0;
	$instance++;

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) )
			$attr['orderby'] = 'post__in';
		$attr['include'] = $attr['ids'];
	}

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters('post_gallery', '', $attr);
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}
	
	$gallery_defaults = array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post ? $post->ID : 0,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 4,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
	);
	
	//Filter gallery deafults
	$gallery_defaults = apply_filters( 'prso_gallery_shortcode_args', $gallery_defaults );
	
	extract(shortcode_atts($gallery_defaults, $attr, 'gallery'));
	
	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$icontag = tag_escape($icontag);
	$valid_tags = wp_kses_allowed_html( 'post' );
	if ( ! isset( $valid_tags[ $itemtag ] ) )
		$itemtag = 'dl';
	if ( ! isset( $valid_tags[ $captiontag ] ) )
		$captiontag = 'dd';
	if ( ! isset( $valid_tags[ $icontag ] ) )
		$icontag = 'dt';

	$columns = intval($columns);
	
	//Set bloch grid class based on columns
	switch( $columns ) {
		case 1:
			$block_class = "large-block-grid-1 small-block-grid-3";
			break;
		case 2:
			$block_class = "large-block-grid-2 small-block-grid-3";
			break;
		case 3:
			$block_class = "large-block-grid-3 small-block-grid-3";
			break;
		case 4:
			$block_class = "large-block-grid-4 small-block-grid-3";
			break;
		case 5:
			$block_class = "large-block-grid-5 small-block-grid-3";
			break;
		case 6:
			$block_class = "large-block-grid-6 small-block-grid-3";
			break;
		default:
			$block_class = "large-block-grid-4 small-block-grid-3";
			break;
	}
	
	$gallery_container = "<div class='row'><div class='large-12 columns'><ul class='clearing-thumbs gallery galleryid-{$id} {$block_class}' data-clearing>";
	
	$output = apply_filters( 'gallery_style', $gallery_container );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		if ( ! empty( $attr['link'] ) && 'file' === $attr['link'] )
			$image_output = wp_get_attachment_link( $id, $size, false, false );
		elseif ( ! empty( $attr['link'] ) && 'none' === $attr['link'] )
			$image_output = wp_get_attachment_image( $id, $size, false );
		else
			$image_output = wp_get_attachment_link( $id, $size, true, false );
			
		$image_output = wp_get_attachment_link( $id, $size, false, false );	
		
		$image_meta  = wp_get_attachment_metadata( $id );
		
		//Cache image caption
		$caption_text = NULL;
		if ( trim($attachment->post_excerpt) ) {
			$caption_text = wptexturize($attachment->post_excerpt);
		}
		
		//Add caption to img tag
		$image_output = str_replace('<img', "<img data-caption='{$caption_text}'", $image_output);
		
		ob_start();
		?>
		<li>
			<?php echo $image_output; ?>
		</li>
		<?php
		$output.= ob_get_contents();
		ob_end_clean();
		
	}

	$output .= "</ul></div></div>";
	
	return $output;
}