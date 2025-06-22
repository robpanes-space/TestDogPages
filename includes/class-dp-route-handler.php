<?php
if (!defined('ABSPATH')) exit;

class RouteHandler {

    public function register_hooks() {
        add_action('init', [$this, 'add_custom_rewrite']);
        add_action('template_redirect', [$this, 'render_dog_image']);
    }

    public function add_custom_rewrite() {
        add_rewrite_rule('^dog/?$', 'index.php?show_dog_image=1', 'top');
        add_rewrite_tag('%show_dog_image%', '1');
    }

    public function render_dog_image() {
        if (get_query_var('show_dog_image') != 1) return;

        $image_id = get_option('dogpages_image');
        $image_url = $image_id ? wp_get_attachment_url($image_id) : '';

        status_header(200);
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html><head><title>Dog Page</title></head><body>';
        if ($image_url) {
            echo '<img src="' . esc_url($image_url) . '" style="max-width:100%;height:auto;" alt="Dog Image">';
        } else {
            echo '<h2>No dog image uploaded.</h2>';
        }
        echo '</body></html>';
        exit;
    }
}
