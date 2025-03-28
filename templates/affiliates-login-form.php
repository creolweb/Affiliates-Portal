<?php if ( ! empty( $error ) ) : ?>
    <div class="error"><?php echo esc_html( $error ); ?></div>
<?php endif; ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <img src="https://creol.ucf.edu/wp-content/uploads/sites/2/2024/10/CREOL-Logo-2024.png" alt="CREOL Logo" class="img-fluid"/>
        </div>
        <div class="col-md-12">
            <h1>CREOL, The College of Optics and Photonics</h1>
            <p class="px-2">Welcome to the CREOL Affiliates Portal. Please login to access your account.</p>
        </div>
    </div>
    <form method="post">
        <div class="row">
            <div class="col-md-12">
                <label for="affiliate_login">Company:</label>
                <select name="affiliate_login" id="affiliate_login" required>
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
                <label for="affiliates_password">Password:</label>
                <br/>
                <input placeholder="Password" type="password" name="affiliates_password" id="affiliates_password" required/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <input type="submit" value="Login" class="btn btn-primary"/>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12">
            <p>Having difficulties logging in? <a href="emailto:creolweb@ucf.edu">Contact Us.</a></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p><strong><a href="https://creol.ucf.edu">Back to CREOL</a></strong></p>
        </div>
    </div>
</div>