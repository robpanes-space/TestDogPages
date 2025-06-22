<?php
/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Dog_Pages
 * @subpackage Dog_Pages/includes
 * @author     Rob Panes <robpanes.jr@gmail.com>
 */
class Dog_Pages_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// clear cron
		wp_clear_scheduled_hook('dogpages_multisite_license_check_cron');
	}

}
