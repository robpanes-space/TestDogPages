<?php
if (!defined('ABSPATH')) exit;

class FilterHandler {

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
        // Add Dog Page image upload functionality
        add_filter('the_content', [$this->plugin_admin, 'dogpages_add_image_to_dog_page']);
        // Handle Dog Page image upload added check mutisite support
        add_filter('pre_update_option_dogpages_image', function($new_value, $old_value) {
            if (is_multisite()) {
                // Save per site
                update_blog_option(get_current_blog_id(), 'dogpages_image', $new_value);

                // Return old value to prevent update_option from doing anything
                return $old_value;
            }

            // Single site fallback
            return $new_value;
        }, 10, 2);

        // Register the daily cron event
        add_filter('cron_schedules', function ($schedules) {
            $schedules['daily_midnight'] = [
                'interval' => 86400,
                'display'  => __('Daily at Midnight')
            ];
            return $schedules;
        });
    }
}
