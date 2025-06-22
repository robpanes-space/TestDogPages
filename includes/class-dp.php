<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Dog_Pages
 * @subpackage Dog_Pages/includes
 * @author     Rob Pnanes <robpanes.jr@gmail.com>
 */
class Dog_Pages {

  /**
   * The unique identifier of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  /**
   * The current version of the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $version    The current version of the plugin.
   */
  protected $version;

    /**
   * The property that handles actions for the plugin.
   *
   * @since    1.0.0
   * @access   protected
   */
  protected $action_handler;

    /**
   * The property that handles filters for the plugin.
   *
   * @since    1.0.0
   * @access   protected
   */
  protected $filter_handler;

    /**
   * The property that handles filters for the plugin.
   *
   * @since    1.0.0
   * @access   protected
   */
  protected $route_handler;

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, define the locale, and set the hooks for the admin area and
   * the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function __construct() {

		if ( defined( 'DOG_PAGES_VERSION' ) ) {
			$this->version = DOG_PAGES_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'dog-pages';
    $this->load_dependencies();
    $this->regsiter_hooks_and_filters();
  }

  	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
    /**
     * The class responsible for orchestrating the actions and filters of the plugin.
     */
    require_once plugin_dir_path(__FILE__) . '/class-dp-action-handler.php';
    require_once plugin_dir_path(__FILE__) . '/class-dp-filter-handler.php';
    require_once plugin_dir_path(__FILE__) . '/class-dp-route-handler.php';

    /**
     * The class for admin functionality.
     */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-dp-admin.php';
  }

    /**
	 * Register hooks and filters
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function regsiter_hooks_and_filters() {
    $this->action_handler = new ActionHandler($this->get_plugin_name(), $this->get_version());
    $this->filter_handler = new FilterHandler($this->get_plugin_name(), $this->get_version());
    $this->route_handler = new RouteHandler();

    // Register hooks
    $this->action_handler->register_hooks();
    $this->filter_handler->register_hooks();
    $this->route_handler->register_hooks();
  }

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

} // End class Dog_Pages.