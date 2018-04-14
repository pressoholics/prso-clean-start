jQuery.noConflict();
(function($) {

	/*** Init Zurb Foundation Orbit **/
	$(document).foundation({
		orbit: {
		  animation: 'slide', // Sets the type of animation used for transitioning between slides, can also be 'fade'
	      timer_speed: 10000, // Sets the amount of time in milliseconds before transitioning a slide
	      pause_on_hover: true, // Pauses on the current slide while hovering
	      resume_on_mouseout: false, // If pause on hover is set to true, this setting resumes playback after mousing out of slide
	      next_on_click: true, // Advance to next slide on click
	      animation_speed: 500, // Sets the amount of time in milliseconds the transition between slides will last
	      stack_on_small: false,
	      navigation_arrows: true,
	      slide_number: true,
	      slide_number_text: 'of',
	      container_class: 'orbit-container',
	      stack_on_small_class: 'orbit-stack-on-small',
	      next_class: 'orbit-next', // Class name given to the next button
	      prev_class: 'orbit-prev', // Class name given to the previous button
	      timer_container_class: 'orbit-timer', // Class name given to the timer
	      timer_paused_class: 'paused', // Class name given to the paused button
	      timer_progress_class: 'orbit-progress', // Class name given to the progress bar
	      slides_container_class: 'orbit-slides-container', // Class name given to the slide container
	      preloader_class: 'preloader', // Class given to the perloader
	      slide_selector: 'li', // Default is '*' which selects all children under the container
	      bullets_container_class: 'orbit-bullets',
	      bullets_active_class: 'active', // Class name given to the active bullet
	      slide_number_class: 'orbit-slide-number', // Class name given to the slide number
	      caption_class: 'orbit-caption', // Class name given to the caption
	      active_slide_class: 'active', // Class name given to the active slide
	      orbit_transition_class: 'orbit-transitioning',
	      bullets: true, // Does the slider have bullets visible?
	      circular: true, // Does the slider should go to the first slide after showing the last?
	      timer: true, // Does the slider have a timer active? Setting to false disables the timer.
	      variable_height: false, // Does the slider have variable height content?
	      swipe: true,
	      //before_slide_change: noop, // Execute a function before the slide changes
	      //after_slide_change: noop // Execute a function after the slide changes
		}
	});	
	
	//Interchange -- Once orbit banner image has been loaded trigger resize to recalc orbit container
	/*
	$(document).on('replace', '.slideshow-wrapper img', function (e, new_path, original_path) {
  		setTimeout(function(){
			
			$(window).trigger('resize');
			$('.slideshow-wrapper').css( 'min-height', 0 );
			
		},500);
	});
	*/
	
	/*** Init Zurb Foundation **/
	$(document).ready(function(){
		
		//Load More
        //$('body').on('click', '.load-more', prso_load_more);
		
		/*** Skrollr -- Parallax Effects ***/
		/**
		function init_skrollr() {
			
			var s = skrollr.init({
		        render: function(data) {
		            //Debugging - Log the current scroll position.
		            console.log(data);
		        }
		    });
			
		}
		init_skrollr();
		**/
		
		/**
		var post_template	= $('#content-template');
		var posts 			= new wp.api.collections.Posts();
		
		posts.fetch().done( function() {
		    
		    //console.log(posts.models);
		    
			jQuery( '#json-output' ).html( _.template(post_template.html(), {posts: posts.models}) );
		    
		});
		**/
		
	});
	
	/**
     * prso_load_more
     *
     * Helper to load more content via ajax on a contextual basis
     * Function uses the context data param on the load more link
     * This context param let's us know where on the site the load more is located
     * and thus we can call the appropriate helper functions to load the correct data
     *
     * @access    public
     * @author    Ben Moody
     */
    var load_more_page = 2;
    function prso_load_more(event) {

        //vars
        var rest_endpoint = $(this).data('rest-endpoint');
        var destination = $(this).data('destination');
        var destination_element = $('body').find( destination );
        var endpoint = null;
        var moreButton = $(this);
        var results = null;

        event.preventDefault();

        if( destination_element.length < 1 ) {
            return;
        }

        if ( prsoThemeLocalVars.wp_api[rest_endpoint] === undefined) {
            return;
        }

        endpoint = prsoThemeLocalVars.wp_api[rest_endpoint];

        if ( prsoThemeLocalVars.wp_api.current_page === undefined) {
            return;
        } else if( prsoThemeLocalVars.wp_api.current_page > 0 ) {

            //Set page to that provded by wp_query as user is not on the 1st page of results
            load_more_page = prsoThemeLocalVars.wp_api.current_page;

        }

        //Try and get data from rest api
        $.ajax({
            url: endpoint + '?page=' + load_more_page,
            method: 'GET',
            beforeSend: function ( xhr ) {
                xhr.setRequestHeader( 'X-WP-Nonce', prsoThemeLocalVars.wp_api.nonce );

                moreButton.addClass('loading');

            },
        }).done(function (posts, status, xhr) {

            var total_pages = xhr.getResponseHeader('X-WP-TotalPages');

            $.each(posts, function (i, post) {

                destination_element.append( post.item_html );

            });

            //Are we on the last page?
            if( load_more_page >= total_pages ) {
                moreButton.hide();
            }

            load_more_page++;

        }).always(function () {

            moreButton.removeClass('loading');

        });

    }
	
})(jQuery);