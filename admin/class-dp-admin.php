<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://rpanes.info
 * @since      1.0.0
 *
 * @package    Dog_Pages
 * @subpackage Dog_Pages/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dog_Pages
 * @subpackage Dog_Pages/admin
 * @author     Rob Pnanes <robpanes.jr@gmail.com>
 */
class Dog_Pages_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
      wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dp-admin.css', array(), $this->version, 'all' );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts($hook) {

      // Check if current admin page is your plugin page
      if ($hook !== 'toplevel_page_dogpages-settings') {
          return;
      }

      // Enqueue WP media uploader
       wp_enqueue_media();

      wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dp-admin.js', array( 'jquery' ), $this->version, false );
    }

    /**
     * Display a notice if the 'dog' page does not exist.
     *
     * This function checks if a page with the slug 'dog' exists and displays a notice
     * in the WordPress admin area if it does not.
     *
     * @since 1.0.0
     */
    public function check_if_dog_page_exists_notice() {
        // Check if a page with slug 'dog' exists
        $page = get_page_by_path('dog'); // slug of the page

        if (!$page) {
            echo '<div class="notice notice-warning is-dismissible">
                    <p><strong>DogPages Plugin:</strong> The page with slug <code>dog</code> does not exist. Please create one to enable public viewing.</p>
                  </div>';
        }
    }

    /**
     * Display a notice if the license key is not set.
     *
     * This function checks if the license key is set and displays a notice in the WordPress
     * admin area if it is not.
     *
     * @since 1.0.0
     */
    public function dogpages_license_key_notice() {
      if (!current_user_can('manage_options')) return;

      $license_key = is_multisite() 
        ? get_blog_option(get_current_blog_id(), 'dogpages_license_key') 
        : get_option('dogpages_license_key');
      $show_notice = false;

      // Show if plugin was just activated
      if (get_transient('dogpages_show_license_notice')) {
          $show_notice = true;
          delete_transient('dogpages_show_license_notice');
      }

      // Show if user is on settings page and key is missing
      $screen = get_current_screen();
      if (strpos($screen->id, 'dogpages-settings') !== false && empty($license_key)) {
          $show_notice = true;
      }

      if ($show_notice && empty($license_key)) {
          $settings_url = admin_url('admin.php?page=dogpages-settings');
          ?>
          <div class="notice notice-error is-dismissible">
              <p>
                  <strong>DogPages Plugin:</strong> Please enter your license key to enable all features.
                  <?php if (strpos($screen->id, 'dogpages-settings') === false): ?>
                      <a href="<?php echo esc_url($settings_url); ?>">Go to DogPages Settings</a>
                  <?php endif; ?>
              </p>
          </div>
          <?php
      } else if (!empty($license_key) && $license_key !== DOGPAGES_VALID_LICENSE_KEY) {
          ?>
          <div class="notice notice-warning is-dismissible">
              <p>
                  <strong>DogPages Plugin:</strong> The entered license key is invalid. Please check your key.
              </p>
          </div>
          <?php
      }


    }

    /**
     * Add a menu item for the Dog Pages settings in the WordPress admin.
     */
    public function add_dogpages_menu() {
      add_menu_page(
          'Dog Pages Settings',
          'DogPages',
          'manage_options',
          'dogpages-settings',
          ['Dog_Pages_Admin', 'render_settings_page'],
          'dashicons-pets',
          60
      );
  }

  /**
   * Register settings for the Dog Pages plugin.
   */
  public function register_settings() {
      register_setting('dogpages_options', 'dogpages_image');
      register_setting('dogpages_options', 'dogpages_license_key');

      add_settings_section(
          'dogpages_main_section',
          'Dog Image Settings',
          null,
          'dogpages-settings'
      );

      add_settings_field(
        'dogpages_license_key',
        'License Key',
        [$this, 'license_key_field'],
        'dogpages-settings',
        'dogpages_main_section'
      );

       add_settings_field(
        'dogpages_image',
        'Upload Dog Image',
        ['Dog_Pages_Admin', 'dog_image_field'],
        'dogpages-settings',
        'dogpages_main_section'
      );
  }

  /**
   * Add the uploaded dog image to the "dog" page content.
   *
   * This function checks if the current page is the "dog" page and adds the uploaded
   * dog image at the top of the content.
   *
   * @param string $content The original page content.
   * @return string Modified content with the dog image added.
   */
  public static function dogpages_add_image_to_dog_page($content) {
      if (!is_page('dog')) {
          return $content; // Only modify the "dog" page
      }

      // $image_id = get_option('dogpages_image');
      $image_id = is_multisite() 
      ? get_blog_option(get_current_blog_id(), 'dogpages_image') 
      : get_option('dogpages_image');

      if (!$image_id) {
          return $content; // No image uploaded
      }

      $image_url = wp_get_attachment_url($image_id);

      if (!$image_url) {
          return $content;
      }

      // Add the image HTML before the page content
      $image_html = '<div class="dog-image"><img src="' . esc_url($image_url) . '" style="max-width:100%;height:auto;"></div>';

      return $image_html . $content;
  }

  /**
   * Render the Dog Image upload field in the settings page.
   */
  public static function dog_image_field() { 
        $license_key = is_multisite() 
        ? get_blog_option(get_current_blog_id(), 'dogpages_license_key') 
        : get_option('dogpages_license_key');
        $is_enabled = !empty($license_key);

        if(!$is_enabled || $license_key !== DOGPAGES_VALID_LICENSE_KEY ) return; // If license key is not set, do not show the image upload field
    ?>    
    <div class="dogpages-image-container">
      <?php
        // Get the current image ID and URL
        $image_id = is_multisite() 
        ? get_blog_option(get_current_blog_id(), 'dogpages_image') 
        : get_option('dogpages_image');
        $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
      ?>
      <div class="dogpages-image-field">
          <input type="hidden" name="dogpages_image" id="dogpages_image" value="<?php echo esc_attr($image_id); ?>">
          <img id="dogpages_image_preview" src="<?php echo esc_url($image_url); ?>" style="max-width:200px;"><br>
          <button type="button" class="button" id="upload_dog_image">Upload Image</button>
      </div>
      </div>
      <?php
  }

  /**
   * Render the settings page for Dog Pages.
   *
   * @since 1.0.0
   */
  public static function render_settings_page() {
      ?>
      <div class="wrap">
          <h1>DogPages Settings</h1>
          <?php if (is_multisite()): ?>
              <p><strong>Multisite Enabled:</strong> This setting is saved per site (Site ID: <?php echo get_current_blog_id(); ?>).</p>
          <?php endif; ?>
          <?php if (!get_page_by_path('dog')) {
              echo '<div class="notice notice-warning is-dismissible">';
              echo '<p><strong>Dog Page Missing:</strong> The /dog page does not exist yet. <a href="' . esc_url(admin_url('admin.php?page=dogpages-settings&create_dog_page=1')) . '">Click here to create it</a>.</p>';
              echo '</div>';
            }
          ?>
          <form method="post" action="options.php">
              <?php
              settings_fields('dogpages_options');
              do_settings_sections('dogpages-settings');
              submit_button();
              ?>
          </form>
      </div>
      <?php
  }

  /**
   * Validate the license key.
   *
   * This function checks if the entered license key is valid. If it is, it allows saving;
   * otherwise, it prevents saving and optionally shows an admin error.
   *
   * @since 1.0.0
   * @param string $new_value The new value of the license key.
   * @param string $old_value The old value of the license key.
   * @param string $option_name The name of the option being saved.
   * @return string The validated license key or the old value if invalid.
   */
  public  function dogpages_validate_license_key($new_value, $old_value, $option_name) {

      if ($new_value === DOGPAGES_VALID_LICENSE_KEY) {
          return $new_value; // valid, allow saving
      }

      return $old_value; // prevent saving invalid key
  }


  /**
   * Render the license key field in the settings page.
   *
   * @since 1.0.0
   */
  public static function license_key_field() {
      $license_key = is_multisite() 
      ? get_blog_option(get_current_blog_id(), 'dogpages_license_key') 
      : get_option('dogpages_license_key');
    ?>
    <p class="description">Note: This only simulate token/key entry and check the equality  </p>
    <?php if(empty($license_key) || $license_key !== DOGPAGES_VALID_LICENSE_KEY ) {
      echo "<p>Use token: <span class='blue'>ZwZ5GDs3hxV0esCf3Bm0xXmSH</span></p>"; 
      } 
    ?>
    <input id="licenseKey" type="password" name="dogpages_license_key" value="<?php echo esc_attr($license_key); ?>" class="regular-text">    
    
    <?php if(empty($license_key) || $license_key !== DOGPAGES_VALID_LICENSE_KEY ) {
      echo "<p class='description'>Enter your license key to activate and upload an image.</p>"; 
      } 
    ?>

    <?php
}


  /**
   * Retrieve the name of the plugin.
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
}