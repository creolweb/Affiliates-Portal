<?php
/**
 * Shortcode for a custom login page.
 * 
 * This shortcode outputs a custom login form with a dropdown for companies and a password input.
 * On submit, it maps the selected company to a username, authenticates using wp_signon(),
 * prevents login for disallowed roles (e.g. SSO subscribers), and redirects appropriately.
 */

function affiliates_portal_login_shortcode( $atts ) {
    // If already logged in, redirect (adjust destination as needed)
    if ( is_user_logged_in() ) {
        wp_safe_redirect( home_url() );
        exit;
    }

    ob_start();

    // Process form submission.
    if ( isset( $_POST['affiliates_login_nonce'] ) && 
         wp_verify_nonce( $_POST['affiliates_login_nonce'], 'affiliates_portal_login' ) ) {

        $company  = sanitize_text_field( $_POST['affiliates_company'] );
        $password = $_POST['affiliates_password']; // Do not trim/password-sanitize here so that wp_signon() can check it exactly.

        // Map company names to their usernames.
        // In a real site this mapping can be stored in the DB.
        $company_users = array(
            'Company A' => 'company_a_user',
            'Company B' => 'company_b_user',
            'Company C' => 'company_c_user',
        );

        if ( empty( $company ) || ! isset( $company_users[ $company ] ) ) {
            echo '<div class="error">Invalid company selected.</div>';
        } else {
            // Get the user by login.
            $user = get_user_by( 'login', $company_users[ $company ] );

            // Prevent login for non‑affiliate users (for example, SSO subscribers).
            if ( $user && in_array( 'sso_subscriber', (array) $user->roles, true ) ) {
                echo '<div class="error">Login not allowed for this account.</div>';
            } else {
                $creds = array(
                    'user_login'    => $company_users[ $company ],
                    'user_password' => $password,
                    'remember'      => true,
                );
                $user = wp_signon( $creds, false );
                if ( is_wp_error( $user ) ) {
                    echo '<div class="error">Login failed: ' . esc_html( $user->get_error_message() ) . '</div>';
                } else {
                    wp_safe_redirect( home_url() );
                    exit;
                }
            }
        }
    }
    ?>

    <form method="post">
        <label for="affiliates_company">Company:</label>
        <select name="affiliates_company" id="affiliates_company" required>
            <option value="">Select a Company</option>
            <option value="Company A">Company A</option>
            <option value="Company B">Company B</option>
            <option value="Company C">Company C</option>
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