<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package _s
 * @since _s 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <title><?php wp_title( '|', true, 'right' ); ?></title>

    <!-- FaveIcon -->
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/favicon.png">

    <!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
    <![endif]-->

    <!-- W3TC-include-css -->
    <!-- W3TC-include-js-head -->

    <?php wp_head(); ?>
    <!-- Google Analytics -->
    <script>
        jQuery(document).on('cookillian_ready', function() { //COOKILLIAN READY EVENT
            if(typeof cookillian != "undefined"  && cookillian.blocked_cookies == false) {
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','ga');


                ga('create', 'XX-XXXXXXXX-X'); //GOOGLE ANALYTICS ID
                ga('send', 'pageview');
            }
        });
    </script>
    <!-- End Google Analytics -->

    <!--[if lt IE 9]>
        <link href="<?php echo get_template_directory_uri(); ?>/stylesheets/no-mq.css" rel="stylesheet"/>
    <![endif]-->
</head>

<body <?php body_class(); ?>>
<!-- W3TC-include-js-body-start -->
<div id="page" class="site">
	<?php do_action( 'before' ); ?>
	<header id="masthead" class="site-header" role="banner">
		<hgroup>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
		</hgroup>

		<nav role="navigation" class="site-navigation main-navigation">
			<h1 class="assistive-text"><?php _e( 'Menu', '_s' ); ?></h1>
			<div class="assistive-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', '_s' ); ?>"><?php _e( 'Skip to content', '_s' ); ?></a></div>

			<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
		</nav><!-- .site-navigation .main-navigation -->
	</header><!-- #masthead .site-header -->

	<div id="main" class="site-main">
