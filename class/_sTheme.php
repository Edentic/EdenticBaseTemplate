<?php
require_once 'BaseTheme.php';

/**
 * _s functions and definitions
 *
 * @package _s
 * @since _s 1.0
 */


class _sTheme extends BaseTheme {
    private $content_width;

    public function __construct() {
        $this->loadJetpackCompatibility();
        $this->setContentWith();
        $this->scripts();
        $this->widgetsInit();
        $this->hookUp();
        parent::__construct();
    }

    public function hookUp() {
        $this->addAction( 'after_setup_theme', 'setup');
        $this->addAction( 'after_setup_theme', 'registerCustomBackground');
    }

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which runs
     * before the init hook. The init hook is too late for some features, such as indicating
     * support post thumbnails.
     *
     * @since _s 1.0
     */
    public function setup() {
        /**
         * Custom template tags for this theme.
         */
        require( get_template_directory() . '/inc/template-tags.php' );

        /**
         * Custom functions that act independently of the theme templates
         */
        require( get_template_directory() . '/inc/extras.php' );

        /**
         * Customizer additions
         */
        require( get_template_directory() . '/inc/customizer.php' );

        /**
         * WordPress.com-specific functions and definitions
         */
        //require( get_template_directory() . '/inc/wpcom.php' );

        /**
         * Make theme available for translation
         * Translations can be filed in the /languages/ directory
         * If you're building a theme based on _s, use a find and replace
         * to change '_s' to the name of your theme in all the template files
         */
        load_theme_textdomain( '_s', get_template_directory() . '/languages' );

        /**
         * Add default posts and comments RSS feed links to head
         */
        add_theme_support( 'automatic-feed-links' );

        /**
         * Enable support for Post Thumbnails
         */
        add_theme_support( 'post-thumbnails' );

        /**
         * This theme uses wp_nav_menu() in one location.
         */
        $this->addMenu('primary', __( 'Primary Menu', '_s' ));

        /**
         * Enable support for Post Formats
         */
        add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
    }

    /*
     * Sets content with if not set by default
     */
    public function setContentWith() {
        if( !isset( $this->content_width ))
            $this->content_width = 640;
    }

    /*
     * Load Jetpack compatibility file.
     */
    public function loadJetpackCompatibility() {
        require( get_template_directory() . '/inc/jetpack.php' );
    }

    /**
     * Registers a custom background
     */
    public function registerCustomBackground() {
        $args = array(
            'default-color' => 'ffffff',
            'default-image' => '',
        );

        $args = apply_filters( '_s_custom_background_args', $args );

        if ( function_exists( 'wp_get_theme' ) ) {
            add_theme_support( 'custom-background', $args );
        } else {
            define( 'BACKGROUND_COLOR', $args['default-color'] );
            if ( ! empty( $args['default-image'] ) )
                define( 'BACKGROUND_IMAGE', $args['default-image'] );
            add_custom_background();
        }
    }

    /*
     * Register widgetized area and update sidebar with default widgets
     */
    public function widgetsInit() {
        $this->addSidbar(__('Sidebar', '_s'), 'sidebar-1', '', '', '<aside id="%1$s" class="widget %2$s">', '</aside>', '<h1 class="widget-title">', '</h1>');
    }

    /**
     * Enqueue scripts and styles
     */
    public function scripts() {
       $this->addStyle( 'style', get_stylesheet_uri() );

        $this->addScript( 'small-menu', '/js/small-menu.js', array( 'jquery' ), '20120206', true );

        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            $this->addScript( 'comment-reply' );
        }

        if ( is_singular() && wp_attachment_is_image() ) {
            $this->addScript( 'keyboard-image-navigation', '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
        }
    }

    public function enableCustomHeader() {
        require( get_template_directory() . '/inc/custom-header.php' );
    }
}
