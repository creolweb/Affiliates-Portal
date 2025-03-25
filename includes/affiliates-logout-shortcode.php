<?php
function custom_logout_button() {
    if ( is_user_logged_in() ) {
        wp_clear_auth_cookie();
        wp_destroy_current_session();
    }
    $logout_url = 'https://creol.ucf.edu';
    return '<a href="' . esc_url($logout_url) . '" class="btn btn-primary">Logout</a>';
}
add_shortcode('logout_button', 'custom_logout_button');