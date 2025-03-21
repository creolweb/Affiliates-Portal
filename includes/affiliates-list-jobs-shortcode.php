<?php
/**
 * Shortcode to display a list of jobs in the affiliates portal
 *
 * This widget fetches jobs via the REST API and displays them, optionally filtered by user IDs.
 * When the "self" attribute is true, each job will get Edit and Delete buttons.
 */
class Affiliates_List_Jobs_Shortcode {
    public function __construct() {
        add_shortcode( 'affiliates_portal_list_jobs', [ $this, 'render_jobs_list' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    public function enqueue_assets() {
        // Register and enqueue the JavaScript file
        wp_register_script(
            'affiliates-list-jobs',
            plugins_url( '../assets/js/affiliates-list-jobs.js', __FILE__ ),
            [],
            '1.0.0',
            true
        );

        // Pass the REST API URL to the JavaScript file
        $base_url = rest_url( 'affiliates/v1/jobs' );
        wp_localize_script( 'affiliates-list-jobs', 'affiliatesJobs', [
            'restUrl' => esc_url( $base_url ),
            'currentUserId' => get_current_user_id(),
        ] );
        wp_enqueue_script( 'affiliates-list-jobs' );
    }

    public function render_jobs_list( $atts ) {
        // Get shortcode attributes; for example, [affiliates_portal_list_jobs self="true"]
        // Use the data-is-self attribute to determine if the current user is the one viewing the jobs
        // Default to false if not set
        $atts = shortcode_atts( [ 'self' => false ], $atts, 'affiliates_portal_list_jobs' );
        $is_self = filter_var($atts['self'], FILTER_VALIDATE_BOOLEAN);
        ob_start();
        ?>
        <div id="affiliates-portal-widget" data-is-self="<?php echo ($is_self ? '1' : '0'); ?>">
            <h3>Job Listings</h3>
            <ul id="affiliates-job-list"></ul>
        </div>
        <?php
        return ob_get_clean();
    }
}

new Affiliates_List_Jobs_Shortcode();