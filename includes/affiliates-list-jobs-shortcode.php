<?php
/**
 * Shortcode to display the Affiliates Widget.
 *
 * This widget fetches jobs via the REST API and displays them optionally filtered by user IDs
 */
function affiliates_list_jobs_widget( $atts ) {
    $atts = shortcode_atts( array(
        'self'      => '',
        'companies' => '',
    ), $atts, 'affiliates_portal_list_jobs' );

    // Prepare the REST API URL:
    $base_url = esc_url( rest_url( 'affiliates/v1/jobs' ) );
    $query    = '';

    if ( filter_var( $atts['self'], FILTER_VALIDATE_BOOLEAN ) ) {
        $user_id = get_current_user_id();
        if ( $user_id ) {
            $query = '?user_ids=' . urlencode( $user_id );
        } else {
            // User not logged in, return no jobs for safety accordingly:
            $query = '?user_ids=0';
        }
    } elseif ( ! empty( $atts['companies'] ) ) {
        // Remove spaces and ensure proper query format
        $companies = preg_replace( '/\s+/', '', $atts['companies'] );
        $query = '?user_ids=' . urlencode( $companies );
    }

    $rest_url = $base_url . $query;
    ob_start();
    ?>
    <div id="affiliates-portal-widget">
        <h3>Job Listings</h3>
        <ul id="affiliates-job-list"></ul>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('<?php echo $rest_url; ?>', { cache: 'no-store' })
            .then(response => response.json())
            .then(data => {
                const jobList = document.getElementById('affiliates-job-list');
                data.forEach(function(job) {
                    const li = document.createElement('li');
                    li.textContent = job.title + ' by ' + job.author.name;
                    jobList.appendChild(li);
                });
            })
            .catch(error => console.error('Error fetching jobs:', error));
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('affiliates_portal_list_jobs', 'affiliates_list_jobs_widget');