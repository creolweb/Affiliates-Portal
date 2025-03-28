<?php if ( ! empty( $error ) ) : ?>
    <div class="error"><?php echo esc_html( $error ); ?></div>
<?php endif; ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <img src="https://creol.ucf.edu/wp-content/uploads/2019/07/CREOL-Logo-1.png" alt="CREOL Logo" class="img-fluid"/>
        </div>
        <div class="col-md-12">
            <h1>CREOL, The College of Optics and Photonics</h1>
            <p class="lead">Welcome to the CREOL Affiliates Portal. Please login to access your account.</p>
        </div>
    </div>
    <form method="post">
        <div class="row">
            <div class="col-md-6">
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
            </div>
            <div class="col-md-6">
                <label for="affiliates_password">Password:</label>
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
        <p>Having difficulties logging in? <strong><a href="emailto:creolweb@ucf.edu">Contact Us.</a></strong></p>
    </div>
    <div class="row">
        <p><a href="https://creol.ucf.edu">Back to CREOL</a></p>
    </div>
</div>