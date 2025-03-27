<?php
/**
 * Shortcode for a custom login page.
 * 
 * This shortcode outputs a custom login form with a dropdown for companies and a password input.
 * The dropdown is populated with display names of users with the custom role 'affiliate'.
 */

function affiliates_portal_login_shortcode( $atts ) {

    // Redirect if already logged in.
    if ( is_user_logged_in() ) {
        wp_safe_redirect( home_url() );
        exit;
    }
    
    $error = '';
        
    // Process the form submission using a nonce to verify and secure the request
    if ( isset( $_POST['affiliates_login_nonce'] ) && 
         wp_verify_nonce( $_POST['affiliates_login_nonce'], 'affiliates_portal_login' ) ) {
        
        $affiliate_login = sanitize_text_field( $_POST['affiliate_login'] );
        $password = $_POST['affiliates_password'];  // let wp_signon() handle password sanitization
        
        if ( empty( $affiliate_login ) ) {
            $error = 'Please select a company from the dropdown.';
        } else {
            $user = get_user_by( 'login', $affiliate_login );
            if ( ! $user ) {
                $error = 'User not found.';
            } else {
                $creds = array(
                    'user_login'    => $user->user_login,
                    'user_password' => $password,
                    'remember'      => true,
                );
                $user = wp_signon( $creds, false );
                
                if ( is_wp_error( $user ) ) {
                    $error = $user->get_error_message();
                } else {
                    // Ensure cookies and current user are set.
                    wp_set_current_user( $user->ID );
                    wp_set_auth_cookie( $user->ID, true );
                    do_action( 'wp_login', $user->user_login, $user );
                    wp_safe_redirect( home_url() );
                    exit;
                }
            }
        }
    }
    ob_start();

    // Display any error messages
    if ( ! empty( $error ) ) {
    echo '<div class="error">' . esc_html( $error ) . '</div>';
    }

    // Fetch all users with the 'affiliate' role.
    $affiliates = get_users( array( 'role' => 'affiliate' ) );
    ?>
    <form method="post">
        <label for="affiliate_login">Company:</label>
        <select name="affiliate_login" id="affiliate_login" required>
            <option value="">Select a Company</option>
            <?php
            // Loop through each affiliate user.
            if ( ! empty( $affiliates ) ) {
                foreach ( $affiliates as $affiliate ) {
                    echo '<option value="' . esc_attr( $affiliate->user_login ) . '">' . esc_html( $affiliate->display_name ) . '</option>';
                }
            }
            ?>
        </select>
        <br/>
        <label for="affiliates_password">Password:</label>
        <input type="password" name="affiliates_password" id="affiliates_password" required/>
        <br/>
        <?php wp_nonce_field( 'affiliates_portal_login', 'affiliates_login_nonce' ); ?>
        <input type="submit" value="Login"/>
    </form>
    <?php

    return ob_get_clean();

}
add_shortcode( 'affiliates_portal_login', 'affiliates_portal_login_shortcode' );