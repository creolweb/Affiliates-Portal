<?php
function affiliates_login_enqueue_assets() {
    wp_enqueue_script(
        'affiliates-login',
        plugins_url( '../assets/js/affiliates-login.js', __FILE__ ),
        [],
        '1.0.0',
        true
    );
    wp_localize_script( 'affiliates-login', 'affiliatesLogin', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'affiliates_portal_login' ),
    ]);
}
add_action( 'wp_enqueue_scripts', 'affiliates_login_enqueue_assets' );

/**
 * Shortcode for a custom login page.
 * 
 * This widget displays a custom login form with a dropdown for companies and a password input.
 * The dropdown is populated with display names of users with the custom role 'affiliate'.
 */
function affiliates_portal_login_shortcode( $atts ) {

    // Redirect if already logged in.
    if ( is_user_logged_in() && ! isset( $_GET['loggedout'] ) ) {
        if ( ! current_user_can( 'administrator' ) ) {
            wp_safe_redirect( home_url() );
            exit;
        }
    }
    
    // We no longer process form POST here – the AJAX handler will take care of it.
    
    // Fetch all users with the 'affiliate' role.
    $affiliates = get_users( array( 'role' => 'affiliate' ) );
    
    ob_start();
    include plugin_dir_path( __FILE__ ) . '../templates/affiliates-login-form.php';
    return ob_get_clean();
}
add_shortcode( 'affiliates_portal_login', 'affiliates_portal_login_shortcode' );