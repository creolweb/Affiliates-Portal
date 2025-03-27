<?php if ( ! empty( $error ) ) : ?>
    <div class="error"><?php echo esc_html( $error ); ?></div>
<?php endif; ?>
<h3>IA Portal Login</h3>
<form method="post">
    <label for="affiliate_login">Company:</label>
    <select name="affiliate_login" id="affiliate_login" required>
        <option value="">Select a Company</option>
        <?php if ( ! empty( $affiliates ) ) : ?>
            <?php foreach ( $affiliates as $affiliate ) : ?>
                <option value="<?php echo esc_attr( $affiliate->user_login ); ?>">
                    <?php echo esc_html( $affiliate->display_name ); ?>
                </option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
    <br/>
    <label for="affiliates_password">Password:</label>
    <input type="password" name="affiliates_password" id="affiliates_password" required/>
    <br/>
    <?php wp_nonce_field( 'affiliates_portal_login', 'affiliates_login_nonce' ); ?>
    <input type="submit" value="Login"/>
</form>
<p><a href="creol.ucf.edu">Back to CREOL</a></p>