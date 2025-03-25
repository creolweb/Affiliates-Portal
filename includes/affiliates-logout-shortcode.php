<?php
function custom_logout_button() {
    if ( is_user_logged_in() ) {
        // Use wp_logout_url() to generate the logout URL with a redirect.
        $logout_url = wp_logout_url( 'https://creol.ucf.edu' );
        return '<a href="' . esc_url( $logout_url ) . '" class="btn btn-primary">Logout</a>';
    }
}
add_shortcode('logout_button', 'custom_logout_button');