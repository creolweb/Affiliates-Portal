<?php if ( ! empty( $error ) ) : ?>
    <div class="error"><?php echo esc_html( $error ); ?></div>
<?php endif; ?>
<div class="container w-50">
<div class="row justify-content-center">
        <div class="col-md-12 ">
            <img class="w-50" src="https://creol.ucf.edu/wp-content/uploads/sites/2/2024/10/CREOL-Logo-2024.png" alt="CREOL Logo" class="img-fluid"/>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
            <h3>CREOL, The College of Optics and Photonics</h1>
            <p class="w-50">Welcome to the CREOL Affiliates Portal. Please login to access your account.</p>
        </div>
    </div>
    <form method="post">
        <div class="row">
            <div class="col-md-12">
                <label class="mb-2 w-100" for="affiliate_login">Company:</label>
                <br/>
                <select class="mb-2" w-100 name="affiliate_login" id="affiliate_login" required>
                    <option value="">Select a Company</option>
                    <br/>
                    <?php if ( ! empty( $affiliates ) ) : ?>
                        <?php foreach ( $affiliates as $affiliate ) : ?>
                            <option value="<?php echo esc_attr( $affiliate->user_login ); ?>">
                                <?php echo esc_html( $affiliate->display_name ); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-12">
                <label class="mb-2 w-100" for="affiliates_password">Password:</label>
                <br/>
                <input class="mb-2 w-100" placeholder="Password" type="password" name="affiliates_password" id="affiliates_password" required/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php wp_nonce_field( 'affiliates_portal_login', 'affiliates_login_nonce' ); ?>
                <input href="#" class="mb-2 w-100" type="submit" value="Login" class="btn btn-primary"/>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12">
            <p>Having difficulties logging in? <a href="mailto:creolweb@ucf.edu">Contact Us.</a></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p><strong><a href="https://creol.ucf.edu">Back to CREOL</a></strong></p>
        </div>
    </div>
</div>