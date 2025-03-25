<?php
function custom_logout_button() {
    if ( is_user_logged_in() ) {
        // Append a query parameter to trigger logout processing.
        $logout_url = add_query_arg( 'custom-logout', '1', home_url() );
        return '<a href="' . esc_url( $logout_url ) . '" class="btn btn-primary">Logout</a>';
    }
}
add_shortcode('logout_button', 'custom_logout_button');