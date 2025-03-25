<?php
function custom_logout_button() {
    $logout_url = 'https://creol.ucf.edu';
    return '<a href="' . esc_url($logout_url) . '" class="btn btn-primary">Logout</a>';
}
add_shortcode('logout_button', 'custom_logout_button');