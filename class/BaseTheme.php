<?php
/**
 * ::: EDENTIC BASE THEME :::
 * Base theme class for using wordpress functions
 */

class BaseTheme
{
    protected $hookUps = array();
    protected $filters = array();
    protected $scripts = array();
    protected $styles = array();
    protected $sideBars = array();
    protected $menus = array();
    protected $customPostTypes = array();
    protected $bootStrap = false;
    protected $cleanUpHead = true;

    /**
     * Sets if hAtom tags should be removed
     * @var bool
     */
    protected $removeHAtomTags = true;

    public function __construct() {
        $this->addAction('wp_enqueue_scripts', 'hookUpScripts');
        $this->addAction('wp_enqueue_scripts', 'hookUpStyles');
        $this->addAction('widgets_init', 'hookUpSidebars');
        $this->addAction('after_setup_theme', 'hookUpMenus');
        $this->addFilter('post_class', 'removeHAtomEntry');
        $this->registerPostTypes();
        $this->initBaseFiles();
        $this->initialize();
    }

    /**
     * Initialize hooks
     */
    private function initialize() {
        //Cleanup the head
        if($this->cleanUpHead) {
            remove_action('wp_head', 'rsd_link');
            remove_action('wp_head', 'wp_generator');
            remove_action('wp_head', 'feed_links', 2);
            remove_action('wp_head', 'index_rel_link');
            remove_action('wp_head', 'wlwmanifest_link');
            remove_action('wp_head', 'feed_links_extra', 3);
            remove_action('wp_head', 'start_post_rel_link', 10, 0);
            remove_action('wp_head', 'parent_post_rel_link', 10, 0);
            remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
        }

        //Hook up actions
        foreach($this->hookUps as $hook) {
            add_action($hook[0], array($this, $hook[1]));
        }

        //Hook up filters
        foreach($this->filters as $filter) {
            add_filter($filter[0], array($this, $filter[1]));
        }
    }

    /**
     * Initialize base files
     */
    protected function initBaseFiles() {
        $this->addScript('modernizr', '/js/modernizr-2.6.2.min.js');
        $this->addScript('pictureFill', '/js/jquery-picture-min.js', array('jquery'), '', true);
        $this->addStyle('main', '/stylesheets/main.css');
    }

    /**
     * Hook up a new action
     *
     * @param string $hook
     * @param string $action
     * @return bool
     * @throws Exception
     */
    protected function addAction($hook = "", $action = "") {
        if(!is_string($hook)) throw new Exception('Hook is not a string!');
        if(!is_string($action)) throw new Exception('Action is not a string!');

        if(strlen($hook) > 0 && strlen($action) > 0) {
            $this->hookUps[] = array($hook, $action);
        }

        return true;
    }

    /**
     * Add filter to theme method
     * @param $filter
     * @param $action
     * @return bool
     * @throws Exception
     */
    protected function addFilter($filter, $action) {
        if(!is_string($filter)) throw new Exception('Filter has to be string!');
        if(!is_string($action)) throw new Exception('Action has to be string');

        $this->filters[] = array($filter, $action);
        return true;
    }

    /**
     * Adds a new script to the page
     *
     * @param $name
     * @param string $path
     * @param array $dependencies
     * @param string $version
     * @param bool $inFooter
     * @return bool
     * @throws Exception
     */
    protected function addScript($name, $path = "", $dependencies = array(), $version = '', $inFooter = false) {
        if(!is_string($name)) throw new Exception('Name for script is not string!');
        if(!is_string($path)) throw new Exception('Path is not a string!');
        if(!is_array($dependencies)) throw new Exception('Dependencies given is not an array!');
        if(!is_string($version)) throw new Exception('Version given is not a string!');
        if(!is_bool($inFooter)) throw new Exception('InFooter is not a boolean value!');


        $this->scripts[] = array($name, $path, $dependencies, $version, $inFooter);
        return true;
    }

    /**
     * Hook up styles for theme
     *
     * @param $name
     * @param string $path
     * @param array $dependencies
     * @param string $version
     * @param string $media
     * @throws Exception
     */
    protected function addStyle($name, $path = "", $dependencies = array(), $version = '', $media = '') {
        if(!is_string($name)) throw new Exception('Name given for style is not a string!');
        if(!is_string($path)) throw new Exception('Path given for style is not a string!');
        if(!is_array($dependencies)) throw new Exception('Dependencies given for style is not an array!');
        if(!is_string($version)) throw new Exception('Version given for style is not a string!');
        if(!is_string($media)) throw new Exception('Media given for style is not a string!');

        $this->styles[] = array($name, $path, $dependencies, $version, $media);
    }

    /**
     * Registers a sidebar to the project
     *
     * @param string $name
     * @param string $id
     * @param string $description
     * @param string $class
     * @param string $before_widget
     * @param string $after_widget
     * @param string $before_title
     * @param string $after_title
     */
    protected function addSidbar($name = '', $id = '', $description = '', $class = '', $before_widget = '', $after_widget = '', $before_title = '', $after_title = '') {
        $this->sideBars[] = array(
            'name'          => $name,
            'id'            => $id,
            'description'   => $description,
            'class'         => $class,
            'before_widget' => $before_widget,
            'after_widget'  => $after_widget,
            'before_title'  => $before_title,
            'after_title'   => $after_title
        );
    }

    public function addMenu($location = 'primary', $description = '') {
        if(isset($this->menus[$location])) throw new Exception('Location already taken!');
        $this->menus[$location] = $description;
        return true;
    }

    /**
     * Registers a new custom post type
     * @param $name
     * @param array $labels
     * @param array $args
     * @throws Exception
     */
    public function registerPostType($name, $labels = array(), $args = array()) {
        if(!is_string($name)) throw new Exception('Name should be given as string!');

        $defaultLabels = array(
            'name' => $name,
            'singular_name' => $name,
            'add_new' => 'Add New',
            'add_new_item' => 'Add New '. $name,
            'edit_item' => 'Edit '. $name,
            'new_item' => 'New '. $name,
            'all_items' => 'All '. $name,
            'view_item' => 'View '. $name,
            'search_items' => 'Search '. $name,
            'not_found' =>  'No '. $name. ' found',
            'not_found_in_trash' => 'No '. $name. ' found in Trash',
            'parent_item_colon' => '',
            'menu_name' => $name
        );

        $labels = array_merge($defaultLabels, $labels);

        $defaultOptions = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => $name ),
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'supports' => array( 'title', 'editor')
        );

        $args = array_merge($defaultOptions, $args);
        $this->customPostTypes[] = array($name, $args);
    }

    /**
     * Hooks up script
     */
    public function hookUpScripts() {
        foreach($this->scripts as $script) {
            if(!filter_var($script[1], FILTER_VALIDATE_URL)) $script[1] = get_template_directory_uri(). $script[1];
            wp_enqueue_script($script[0], $script[1], $script[2], $script[3], $script[4]);
        }
    }

    public function hookUpStyles() {
        foreach($this->styles as $style) {
             if(!filter_var($style[1], FILTER_VALIDATE_URL)) $style[1] = get_template_directory_uri(). $style[1];
             wp_enqueue_style($style[0], $style[1], $style[2], $style[3], $style[4]);
        }
    }

    /**
     * Registers custom post types for project
     */
    public function registerPostTypes() {
        foreach($this->customPostTypes as $postType) {
            if(!is_string($postType[0]) || !is_array($postType[1])) continue;
            register_post_type($postType[0], $postType[1]);
        }
    }

    public function hookUpSidebars() {
        foreach($this->sideBars as $sidebar) {
            register_sidebar($sidebar);
        }
    }

    public function hookUpMenus() {
        register_nav_menus($this->menus);
    }

    /**
     * Enable bootstrap on theme
     */
    public function enableBootStrap() {
        $this->bootStrap = true;
        $this->addStyle('bootstrap', '/css/bootstrap.min.css');
        $this->addScript('bootstrap', '/js/bootstrap.min.js');
    }

    /**
     * Removes hAtom entry
     * @param $classes
     * @return mixed
     */
    public function removeHAtomEntry($classes) {
        if($this->removeHAtomTags === false) {
            return $classes;
        }

        $keys = array_keys($classes, 'hentry');
        foreach($keys as $key) {
            $classes[$key] = "contentEntry";
        }
        return $classes;
    }

    /**
     * Returns all posts in a given menu
     * @param string $menuName
     * @return array
     */
    public function getMenuList($menuName = '') {
        $output = array();
        if(!is_nav_menu($menuName)) return $output;

        $list = wp_get_nav_menu_items($menuName);
        if(count($list) <= 0) return $output;
        foreach($list as $item) {
            $output[] = get_post($item->object_id);
        }

        return $output;
    }

    /**
     * Inserts responsive image into page
     * @param $postID
     */
    public function loadResponsiveImage($postID) {
        if(!is_numeric($postID)) return;

        $responsiveFile = get_template_directory(). "/inc/responsive.php";

        if(file_exists($responsiveFile)) {
            include $responsiveFile;
        }
    }
}
