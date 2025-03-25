<?php
/**
 * Shortcode for a custom login page.
 * 
 * This shortcode outputs a custom login form with a dropdown for companies and a password input.
 * On submit, it maps the selected company to a username, authenticates using wp_signon(),
 * prevents login for disallowed roles (e.g. SSO subscribers), and redirects appropriately.
 */

/**
 * Process login on the init hook to ensure authentication cookies
 * are set before any output is sent.
 */
function affiliates_process_login() {
    if ( isset( $_POST['affiliates_login_nonce'] ) && wp_verify_nonce( $_POST['affiliates_login_nonce'], 'affiliates_portal_login' ) ) {

        $company  = sanitize_text_field( $_POST['affiliates_company'] );
        $password = $_POST['affiliates_password']; // Let wp_signon() do its own password checking.

        // Map company names to their usernames.
        $company_users = array(
            'Company A' => 'companya',
            'Company B' => 'companyb',
            'Company C' => 'companyc',
        );

        if ( empty( $company ) || ! isset( $company_users[ $company ] ) ) {
            // Redirect back with an error flag (could also add a message via query var)
            wp_safe_redirect( add_query_arg( 'login_error', 'invalid_company', home_url() ) );
            exit;
        }

        $user = get_user_by( 'login', $company_users[ $company ] );

        // Prevent login for disallowed roles (e.g. SSO subscribers)
        if ( $user && in_array( 'sso_subscriber', (array) $user->roles, true ) ) {
            wp_safe_redirect( add_query_arg( 'login_error', 'disallowed_role', home_url() ) );
            exit;
        } else {
            $creds = array(
                'user_login'    => $company_users[ $company ],
                'user_password' => $password,
                'remember'      => true,
            );
            $user = wp_signon( $creds, false );
            if ( is_wp_error( $user ) ) {
                wp_safe_redirect( add_query_arg( 'login_error', urlencode( $user->get_error_message() ), home_url() ) );
                exit;
            } else {
                wp_safe_redirect( home_url() );
                exit;
            }
        }
    }
}
add_action( 'init', 'affiliates_process_login' );

/**
 * Shortcode callback: displays the login form.
 */
function affiliates_portal_login_shortcode( $atts ) {
    // If already logged in, redirect.
    if ( is_user_logged_in() ) {
        wp_safe_redirect( home_url() );
        exit;
    }

    ob_start();
    ?>
    <form method="post">
        <label for="affiliates_company">Company:</label>
        <select name="affiliates_company" id="affiliates_company" required>
            <option value="">Select a Company</option>
            <option value="Company A">Company A</option>
            <option value="Company B">Company B</option>
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