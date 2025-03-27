<?php
/**
 * Affiliates Portal Plugin
 *
 * @wordpress-plugin
 * Plugin Name: Affiliates Portal Plugin
 * Description: Shortcode for user-facing CRUD operations on affiliate jobs and events using JWT authorized WP REST API calls.
 * Version:     1.0.0
 * Author:      Gage Notarigacomo
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/affiliates-cpt.php';
require_once plugin_dir_path( __FILE__ ) . 'api/affiliates-rest-controller.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/affiliates-login-shortcode.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/affiliates-list-jobs-shortcode.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/affiliates-create-job-shortcode.php';

add_action( 'rest_api_init', function() {
    $controller = new Affiliates_REST_Controller();
    $controller->register_routes();
} );

// Hide the admin bar for all non-admin users.
add_action( 'after_setup_theme', function() {
    if ( ! current_user_can( 'administrator' ) ) {
        show_admin_bar( false );
    }
});

// Block access to the WordPress dashboard for non-admin users.
add_action( 'admin_init', function() {
    if ( is_user_logged_in() && ! current_user_can( 'administrator' ) ) {
        wp_safe_redirect( home_url() );
        exit;
    }
});

add_action('init', 'register_my_menus');
function register_my_menus() { 
    register_nav_menu('affiliates-menu', __('Affiliates Menu')); 
} 

add_action('wp_logout', 'custom_logout_redirect');
function custom_logout_redirect() {
    wp_redirect('https://creol.ucf.edu'); // Change to your desired logout URL
    exit();
}

function add_logout_link_to_menu( $items, $args ) {
    if ( is_user_logged_in() && $args->theme_location === 'affiliates-menu' ) {
        $logout_url = wp_logout_url();
        $items .= '<li class="menu-item"><a href="' . esc_url( $logout_url ) . '">Logout</a></li>';
    }
    return $items;
}
add_filter( 'wp_nav_menu_items', 'add_logout_link_to_menu', 10, 2 );