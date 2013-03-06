<?php
/**
 * ::: EDENTIC BASE THEME :::
 * Base theme class for using wordpress functions
 */

class BaseTheme
{
    protected $hookUps = array();
    protected $scripts = array();
    protected $styles = array();
    protected $sideBars = array();
    protected $menus = array();
    protected $bootStrap = false;

    public function __construct() {
        $this->addAction('wp_enqueue_scripts', 'hookUpScripts');
        $this->addAction('wp_enqueue_scripts', 'hookUpStyles');
        $this->addAction('widgets_init', 'hookUpSidebars');
        $this->addAction('after_setup_theme', 'hookUpMenus');
        $this->initialize();
    }

    /**
     * Initialize hooks
     */
    private function initialize() {
        //Hook up actions
        foreach($this->hookUps as $hook) {
            add_action($hook[0], array($this, $hook[1]));
        }
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
}
