<?php
if ( ! session_id() ) {
    session_start();
}

function affiliates_portal_ajax_login() {
    // Verify nonce.
    if ( empty( $_POST['affiliates_login_nonce'] ) || ! wp_verify_nonce( $_POST['affiliates_login_nonce'], 'affiliates_portal_login' ) ) {
        wp_send_json_error( 'Security check failed.' );
    }
    
    $affiliate_login = sanitize_text_field( $_POST['affiliate_login'] );
    $password        = $_POST['affiliates_password'];
    
    if ( empty( $affiliate_login ) ) {
        wp_send_json_error( 'Please select a company from the dropdown.' );
    }
    
    $user = get_user_by( 'login', $affiliate_login );
    if ( ! $user ) {
        wp_send_json_error( 'User not found.' );
    }
    
    $creds = array(
        'user_login'    => $user->user_login,
        'user_password' => $password,
        'remember'      => true,
    );
    
    $user = wp_signon( $creds, false );
    if ( is_wp_error( $user ) ) {
        wp_send_json_error( wp_strip_all_tags( $user->get_error_message() ) );
    }
    
    wp_set_current_user( $user->ID );
    wp_set_auth_cookie( $user->ID, true );
    do_action( 'wp_login', $user->user_login, $user );
    
    wp_send_json_success( array( 'redirect_url' => home_url() ) );
}
add_action( 'wp_ajax_nopriv_affiliates_portal_ajax_login', 'affiliates_portal_ajax_login' );