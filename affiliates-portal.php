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

add_filter( 'wp_nav_menu_items', 'add_logout_link_to_menu', 10, 2 );
function add_logout_link_to_menu( $items, $args ) {
    if ( is_user_logged_in() ) {
        // Append the query parameter to the home URL.
        $logout_url = add_query_arg( 'custom-logout', '1', home_url() );
        $logout_link = '<li class="menu-item"><a href="' . esc_url( $logout_url ) . '">Logout</a></li>';
        $items .= $logout_link;
    }
    return $items;
}

add_action( 'template_redirect', 'affiliates_custom_logout', 1 );
function affiliates_custom_logout() {
    if ( isset( $_GET['custom-logout'] ) && '1' === $_GET['custom-logout'] ) {
        if ( is_user_logged_in() ) {
            // Clear auth cookies and reset the current user.
            wp_clear_auth_cookie();
            wp_set_current_user( 0 );
            do_action( 'wp_logout' );
        }
        wp_safe_redirect( 'https://creol.ucf.edu' );
        exit;
    }
}