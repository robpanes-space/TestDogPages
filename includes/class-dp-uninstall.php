<?php
if (!defined('ABSPATH')) exit;

class RouteHandler {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function uninstall() {
    if (is_multisite()) {
        $sites = get_sites(['deleted' => 0]);
        foreach ($sites as $site) {
            switch_to_blog($site->blog_id);
            delete_option('dogpages_image');
            delete_option('dogpages_license_key');
            restore_current_blog();
        }
    } else {
        delete_option('dogpages_image');
        delete_option('dogpages_license_key');
    }
  }
}