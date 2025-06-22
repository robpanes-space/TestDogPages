<?php

/**
 * The plugin file
 *
 * @link              https://rpanes.info
 * @since             1.0.0
 * @package           Dog_Pages
 *
 * @wordpress-plugin
 * Plugin Name:       DogPages
 * Plugin URI:        https://localhost:3000
 * Description:       WordPress plugin named “DogPages” that allows the user to add a page to their website with a single picture of a dog.
 * Version:           1.0.0
 * Requires PHP:      7.2
 * Author:            Rob Pnanes
 * Author URI:        https://rpanes.info/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dog-pages
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define lincesnse key for validation
define('DOGPAGES_VALID_LICENSE_KEY', 'ZwZ5GDs3hxV0esCf3Bm0xXmSH');

/**
 * Current plugin version.
 */
define( 'DOG_PAGES_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dp-activator.php
 */
function activate_dog_pages() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dp-activator.php';
	Dog_Pages_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dp-deactivator.php
 */
function deactivate_dog_pages() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dp-deactivator.php';
	Dog_Pages_Deactivator::deactivate();
}

/**
 * The code that runs during plugin uninstall.
 * This action is documented in includes/class-dp-uninstall.php
 */
function uninstall_dog_pages() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dp-uninstall.php';
	Dog_Pages_Uninstall::uninstall();
}

register_activation_hook( __FILE__, 'activate_dog_pages' );
register_deactivation_hook( __FILE__, 'deactivate_dog_pages' );
register_uninstall_hook( __FILE__, 'uninstall_dog_pages' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dog_pages() {
	$plugin = new Dog_Pages();
}
run_dog_pages();