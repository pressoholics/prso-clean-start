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
		
		//Geolocation
		//geolocation();
		
		/*** Skrollr -- Parallax Effects ***/
		/**
		function init_skrollr() {
			
			const s = skrollr.init({
		        render: function(data) {
		            //Debugging - Log the current scroll position.
		            console.log(data);
		        }
		    });
			
		}
		init_skrollr();
		**/
		
		/**
		const post_template	= $('#content-template');
		const posts 			= new wp.api.collections.Posts();
		
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
    let load_more_page = 2;

    function prso_load_more(event) {

        //lets
        const rest_endpoint = $(this).data('rest-endpoint');
        const destination = $(this).data('destination');
        const destination_element = $('body').find(destination);
        const posts_per_page = $(this).data('posts-per-page');
        const template_part = $(this).data('template-part');
        let endpoint = null;
        let moreButton = $(this);
        let filters = '';
        let search = '';
        let per_page = '';
        let template_part_name = '';
        let results = null;

        event.preventDefault();

        if (destination_element.length < 1) {
            return;
        }

        if (typeof prsoThemeLocalVars.wp_api[rest_endpoint] === 'undefined') {
            return;
        }

        endpoint = prsoThemeLocalVars.wp_api[rest_endpoint];

        if (typeof prsoThemeLocalVars.wp_api.current_page === 'undefined') {
            return;
        } else if (prsoThemeLocalVars.wp_api.current_page > 0) {

            //Set page to that provded by wp_query as user is not on the 1st page of results
            load_more_page = prsoThemeLocalVars.wp_api.current_page;

        }

        //Detect if current page has search query
        if (typeof prsoThemeLocalVars.wp_api.search !== 'undefined') {

            if (prsoThemeLocalVars.wp_api.search !== false) {

                let search_query = prsoThemeLocalVars.wp_api.search;

                search = `&search=${search_query}`;

            }

        }

        //Detect if current page is filtered
        if (typeof prsoThemeLocalVars.wp_api.filter !== 'undefined') {

            if (prsoThemeLocalVars.wp_api.filter !== false) {

                let object_filter = prsoThemeLocalVars.wp_api.filter;

                filters = `&filter[cat]=${object_filter}`;

            }

        }

        //Detect posts per page override
        if (typeof posts_per_page !== 'undefined') {

            if (0 !== posts_per_page) {

                per_page = `&per_page=${posts_per_page}`;

            } else {

                per_page = '';

            }

        }

        //Detect page template part override
        if (typeof template_part !== 'undefined') {

            if( template_part !== false ) {
                template_part_name = `&template_part=${template_part}`;
            }

        }

        //Try and get data from rest api
        let args = {
            endpoint: endpoint,
            filters: filters,
            search: search,
            destination_element: destination_element,
            moreButton: moreButton,
            posts_per_page: per_page,
            template_part: template_part_name,
        };

        //Make call to get api results, set callback functions to deal with results
        getRestApiResults(
            args,
            prso_load_more_click__before_callback,
            prso_load_more_click__successCallback,
            prso_load_more_click__alwaysCallback,
        );

    }

    /**
     * prso_load_more_click__before_callback
     *
     * @CALLED BY Callback function for prso_load_more->getRestApiResults()
     *
     * Actions before call to rest api is made
     *
     * @param object args
     * @access public
     * @author Ben Moody
     */
    function prso_load_more_click__before_callback(args) {

        if (typeof args.moreButton === 'undefined') {
            return;
        }

        args.moreButton.addClass('loading');

    }

    /**
     * prso_load_more_click__successCallback
     *
     * @CALLED BY Callback function for prso_load_more->getRestApiResults()
     *
     * Actions on succesfull rest api request
     *
     * @param object args
     * @access public
     * @author Ben Moody
     */
    function prso_load_more_click__successCallback(args) {

        if (
            (typeof args.destination_element === 'undefined') ||
            (typeof args.moreButton === 'undefined') ||
            (typeof args.posts === 'undefined')
        ) {
            return;
        }

        $.each(args.posts, function (i, post) {

            args.destination_element.append(post.item_html);

        });

        //Are we on the last page?
        if (load_more_page >= args.total_pages) {
            args.moreButton.hide();
        }

        //Global variable
        load_more_page++;

    }

    /**
     * prso_load_more_click__alwaysCallback
     *
     * @CALLED BY Callback function for prso_load_more->getRestApiResults()
     *
     * Actions to always be carried out when api request is made
     *
     * @param object args
     * @access public
     * @author Ben Moody
     */
    function prso_load_more_click__alwaysCallback(args) {

        if (typeof args.moreButton === 'undefined') {
            return;
        }

        args.moreButton.removeClass('loading');

    }

    /**
     * getRestApiResults
     *
     * Function to make a data request to rest api
     *
     * @param object args - filters, search
     * @param beforeSendCallback - callback function when request is sent
     * @param successCallback - callback function when request is succesfully completed
     * @param alwaysCallback - callback function always made for request
     * @access public
     * @author Ben Moody
     */
    function getRestApiResults(args, beforeSendCallback, successCallback, alwaysCallback) {

        //vars
        let filters = '';
        let search = '';
        let posts_per_page = '';
        let template_part = '';

        if (typeof args.filters !== 'undefined') {
            filters = args.filters;
        }

        if (typeof args.search !== 'undefined') {
            search = args.search;
        }

        if (typeof args.posts_per_page !== 'undefined') {
            posts_per_page = args.posts_per_page;
        }

        if (typeof args.template_part !== 'undefined') {
            template_part = args.template_part;
        }

        //Try and get data from rest api
        $.ajax({
            url: args.endpoint + '?page=' + load_more_page + filters + search + posts_per_page + template_part,
            method: 'GET',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', prsoThemeLocalVars.wp_api.nonce);

                if (typeof beforeSendCallback !== 'undefined') {

                    beforeSendCallback(args);

                }

            },
        }).done(function (posts, status, xhr) {

            let total_pages = xhr.getResponseHeader('X-WP-TotalPages');

            if (typeof successCallback !== 'undefined') {

                args.posts = posts;

                args.total_pages = total_pages;

                successCallback(args);

            }

        }).always(function () {

            if (typeof alwaysCallback !== 'undefined') {

                alwaysCallback(args);

            }

        });

    }
    
    /**
    * geolocation
    *
    * Initates any geolocation actions, gets and stores user's IP address by default
    *
    * @access public
    * @author Ben Moody
    */
    function geolocation() {


        if (typeof prsoThemeLocalVars.wp_api['geo'] === 'undefined') {
            return;
        }

        //Get user IP -- callback function is optional, will store IP in global user_ip with or without callback
        user_ip = getUserIP( getUserIP_callback );

    }

    /**
    * getUserIP
    *
    * Gets user IP address from 3rd party (api.ipify.org) and caches it in a cookie
     * Calling this fundtion wll set the IP address in the global var user_ip
    *
    * @access public
    * @author Ben Moody
    */
    let user_ip = null;
    function getUserIP( getUserIP_callback ) {

        //const
        const cookie_name = 'prso_user_ip';

        //vars
        let user_ip_cache = null;

        //first try and get user ip from cookie
        user_ip_cache = $.cookie( cookie_name );

        if (typeof user_ip_cache === 'undefined') {

            //get user IP
            $.ajax({
                url: 'https://api.ipify.org/?format=json',
                method: 'GET',
            }).done(function (response, status, xhr) {

                if (typeof response.ip === 'undefined') {
                    return;
                }

                //Make user IP availble to all functions
                user_ip = response.ip;

                //Cache IP
                $.cookie( cookie_name, user_ip, { expires: 365, path: '/' } );

                //Trigger callback function
                if (typeof getUserIP_callback !== 'undefined') {

                    getUserIP_callback();

                }

            });

        } else {

            //Make user IP availble to all functions
            user_ip = user_ip_cache;

            //Trigger callback function
            if (typeof getUserIP_callback !== 'undefined') {

                getUserIP_callback();

            }

        }

    }

    /**
    * getUserIP_callback
    *
    * @CALLED BY getUserIP()
    *
    * This is the optional callback for getUserIP, makes a rest api request to custom endpoint to return data on users location
    *
    * @access public
    * @author Ben Moody
    */
    function getUserIP_callback() {

		//vars
        const cookie_name = 'prso_user_locale';
		let user_locale = '';
        let user_locale_cache = '';

		//Shoud we pass users locale cached in cookie?
        user_locale_cache = $.cookie( cookie_name );

        if (typeof user_locale_cache !== 'undefined') {
            user_locale = '&location=' + user_locale_cache;
        }

        //Try and get data from rest api
        $.ajax({
            url: prsoThemeLocalVars.wp_api['geo'] + '?ip=' + user_ip + user_locale,
            method: 'GET',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', prsoThemeLocalVars.wp_api.nonce);
            },
        }).done(function (response, status, xhr) {

            if (typeof response === 'undefined') {
                return;
            }
            
            //Cache user location in cookie
            if (typeof response.geo_data.locale !== 'undefined') {

                //Cache IP
                $.cookie( cookie_name, JSON.stringify( response.geo_data.locale ), { expires: 365, path: '/' } );

            }

        }).always(function () {



        });

    }
	
})(jQuery);