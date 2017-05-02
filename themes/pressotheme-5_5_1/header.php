<!doctype html>  

<!--[if IEMobile 7 ]> <html <?php language_attributes(); ?>class="no-js iem7"> <![endif]-->
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if (gte IE 9)|(gt IEMobile 7)|!(IEMobile)|!(IE)]><!--><html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
	
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		
		<title><?php wp_title('', true, 'right'); ?></title>
		
		<!-- icons & favicons -->
		<link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_stylesheet_directory_uri(); ?>/apple-touch-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_stylesheet_directory_uri(); ?>/apple-touch-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_stylesheet_directory_uri(); ?>/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_stylesheet_directory_uri(); ?>/apple-touch-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_stylesheet_directory_uri(); ?>/apple-touch-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_stylesheet_directory_uri(); ?>/apple-touch-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_stylesheet_directory_uri(); ?>/apple-touch-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_stylesheet_directory_uri(); ?>/apple-touch-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_stylesheet_directory_uri(); ?>/apple-touch-icon-180x180.png">
		<link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon-192x192.png" sizes="192x192">
		<link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon-160x160.png" sizes="160x160">
		<link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon-96x96.png" sizes="96x96">
		<link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon-16x16.png" sizes="16x16">
		<link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon-32x32.png" sizes="32x32">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="<?php echo get_stylesheet_directory_uri(); ?>/mstile-144x144.png">
				
		<!-- media-queries.js (fallback) -->
		<!--[if lt IE 9]>
			<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>			
		<![endif]-->

		<!-- html5.js -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		
  		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		
		<!-- wordpress head functions -->
		<?php wp_head(); ?>
		<!-- end of wordpress head -->
				
	</head>
	
	<body <?php body_class(); ?>>
		
		<!-- OLD IE Warning Message !-->
		<!--[if IE 8]>
		<div id="old-browser-alert" data-alert class="alert-box alert text-center" style="padding:30px 0;font-weight:bold;">
		  <?php _ex( 'This site was designed for modern browsers. To view this site please update your browser: ', 'text', PRSOTHEMEFRAMEWORK__DOMAIN ); ?>
		  <a style="color:#ffffff;text-decoration:underline;" href="http://outdatedbrowser.com/en" target="_blank">http://outdatedbrowser.com/en</a>
		</div>
		<style>
			.off-canvas-wrap {
				display: none;
			}
		</style>
		<![endif]-->
		
		<!-- off canvas wrap !-->
		<div class="off-canvas-wrap" data-offcanvas>
			<div class="inner-wrap">
				
				<!-- Mobile nav activate button !-->
				<nav class="tab-bar show-for-small show-for-medium-portrait">
				  <a class="left-off-canvas-toggle menu-icon">
				    <span><?php bloginfo('name'); ?></span>
				  </a>
				</nav>
				
				<?php 
					wp_nav_menu( 
				    	array( 
				    		'menu' 				=> 'mobile_nav', /* menu name */
				    		'menu_class' 		=> 'off-canvas-list',
				    		'theme_location' 	=> 'mobile_nav', /* where in the theme it's assigned */
				    		'container_class' 	=> 'left-off-canvas-menu show-for-small show-for-medium-portrait', /* container tag */
				    		'depth' 			=> '2',
				    		'fallback_cb'		=> false
				    	)
				    );
				?>
				
				<!-- Main Container !-->
				<div id="main-container">
				
					<!-- Header Row !-->
					<div id="header-container" class="row">
							
						<div class="large-12 columns">
							<header role="banner" id="top-header">
								
								<div class="siteinfo">
									<h1><a class="brand" id="logo" href="<?php echo get_bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
									<h4 class="subhead"><?php echo get_bloginfo ( 'description' ); ?></h4>
								</div>
								
								<div class="contain-to-grid sticky">
									<nav class="top-bar nav-bar hide-for-small hide-for-medium-portrait" data-topbar>
									  <ul class="title-area">
									    <!-- Title Area -->
									    <li class="name">
									      <h1><a href="#">Top Bar Title </a></h1>
									    </li>
									    <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
									    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
									  </ul>
									
									  <section class="top-bar-section">
									    <!-- Left Nav Section -->
									    <?php 
											// Adjust using Menus in Wordpress Admin
											if( has_nav_menu('main_nav') ) {
												//Get cached nav menu
											    wp_nav_menu( 
											    	array( 
											    		'menu' 				=> 'main_nav', /* menu name */
											    		'menu_class' 		=> 'left',
											    		'theme_location' 	=> 'main_nav', /* where in the theme it's assigned */
											    		'container' 		=> 'false', /* container tag */
											    		'depth' 			=> '4',
											    		'walker' 			=> new main_nav_walker(),
											    		'fallback_cb'		=> false
											    	)
											    );
											}
										?>
										
										<!-- Rigth Nav Section !-->
										<ul class="right">
											<li class="has-form" >
												<form action="<?php echo home_url( '/' ); ?>" method="get">
											      <div class="large-12 columns">
											        <input type="text" id="search" placeholder="Search" name="s" value="<?php the_search_query(); ?>" />
											      </div>
										  		</form>
											</li>
										</ul>
										
									  </section>
									</nav>
								</div>
								
							</header> <!-- end header -->
						</div>
						
					</div>
					<!-- /Header Row !-->
					
						<!-- Body Container !-->
						<div id="body-container">
							
							<!-- Mobile nav for deep (Teriary) pages if page has any !-->
							<div class="row">
								<div id="mobile-deep-nav-container" class="large-12 columns show-for-small show-for-medium-portrait">
									<?php //do_action( 'prso_deep_mobile_nav' ); ?>
								</div>
							</div>