<?php
/**
 * Shortcode to display a form for creating a job.
 * 
 * This widget provides a form to create a new job via the REST API.
 */
function affiliates_create_job_widget() {
    // Enqueue the JS file and localize the REST URL and nonce
    wp_enqueue_script(
        'affiliates-create-job',
        plugins_url( '../assets/js/affiliates-create-job.js', __FILE__ ),
        [],
        '1.0.0',
        true
    );

    wp_localize_script( 'affiliates-create-job', 'affiliatesCreateJob', [
        'restUrl' => esc_url( rest_url( 'affiliates/v1/jobs' ) ),
        'nonce' => wp_create_nonce( 'wp_rest' ),
    ]);

    ob_start();
    include plugin_dir_path( __FILE__ ) . '../templates/create-job-form.php';

    return ob_get_clean();
}
add_shortcode('affiliates_portal_create_job', 'affiliates_create_job_widget');