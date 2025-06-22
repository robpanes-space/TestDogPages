<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Dog_Pages
 * @subpackage Dog_Pages/includes
 * @author     Rob Panes <robpanes.jr@gmail.com>
 */
class Dog_Pages_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// Set a transient to trigger the license notice
    set_transient('dogpages_show_license_notice', true, 60);

		// Add cron job to check license status daily at midnight
		if (!wp_next_scheduled('dogpages_multisite_license_check_cron')) {
				wp_schedule_event(strtotime('tomorrow midnight'), 'daily_midnight', 'dogpages_multisite_license_check_cron');
		}
	}

}