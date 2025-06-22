<?php
if (!defined('ABSPATH')) exit;

class ActionHandler {

    /**
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_admin
	 */
	private $plugin_admin;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_admin = new Dog_Pages_Admin( $plugin_name, $version );

	}

    /**
     * Register hooks for the Dog Pages plugin.
     *
     * @since 1.0.0
     */
    public function register_hooks() {        
        add_action('admin_menu', [$this->plugin_admin, 'add_dogpages_menu']);
        add_action('admin_init', [$this->plugin_admin, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this->plugin_admin, 'enqueue_styles']);
        add_action('admin_enqueue_scripts', [$this->plugin_admin, 'enqueue_scripts']);

        // Admin notices...
        add_action('admin_notices', [$this->plugin_admin, 'check_if_dog_page_exists_notice']);
        add_action('admin_notices', [$this->plugin_admin, 'dogpages_license_key_notice']);

         // Cron handler for both single and multisite
        add_action('dogpages_multisite_license_check_cron', function () {
            if (is_multisite()) {
                $sites = get_sites(['deleted' => 0]);
                foreach ($sites as $site) {
                    switch_to_blog($site->blog_id);
                    error_log('[DogPages] [' . get_bloginfo('name') . '] Checked license key at ' . current_time('mysql'));
                    restore_current_blog();
                }
            } else {
                error_log('[DogPages] [Single Site] Checked license key at ' . current_time('mysql'));
            }
        });
    }
}
