<?php
/**
 * Shortcode to display the Affiliates Widget.
 *
 * This widget fetches jobs via the REST API and displays them.
 */

function affiliates_list_jobs_widget() {
    ob_start();
    ?>
    <div id="affiliates-portal-widget">
        <h3>Job Listings</h3>
        <ul id="affiliates-job-list"></ul>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('<?php echo esc_url( rest_url( 'affiliates/v1/jobs' ) ); ?>', { cache: 'no-store' })
            .then(response => response.json())
            .then(data => {
                const jobList = document.getElementById('affiliates-job-list');
                data.forEach(function(job) {
                    const li = document.createElement('li');
                    li.textContent = job.title + ' by ' + job.author;
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