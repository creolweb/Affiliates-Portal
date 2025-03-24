<?php
/**
 * Shortcode to display a form for creating a job.
 * 
 * This widget provides a form to create a new job via the REST API.
 */

function affiliates_create_job_widget() {
    ob_start();
    ?>
    <div id="affiliates-create-job-widget">
        <h3>Create a Job</h3>
        <form id="affiliates-create-job-form">
            <label for="job-title">Job Title:</label>
            <input type="text" id="job-title" name="title" required/>
            
            <label for="job-description">Job Description:</label>
            <textarea id="job-description" name="description" required></textarea>
            
            <label for="job-contact">Contact:</label>
            <input type="email" id="job-contact" name="contact" required/>
            
            <input type="submit" value="Create Job"/>
        </form>
        <div id="job-response"></div>
    </div>
    <script>
    document.getElementById('affiliates-create-job-form').addEventListener('submit', function(event) {
        event.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            title: formData.get('title'),
            job_description: formData.get('description'),
            contact: formData.get('contact')
        };

        fetch('<?php echo esc_url( rest_url( 'affiliates/v1/jobs' ) ); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': '<?php echo wp_create_nonce( 'wp_rest' ); ?>'
            },
            credentials: 'same-origin', // Ensure cookies are sent with the request
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('job-response').textContent = 'Job created successfully!';
            console.log('Success:', data);
        })
        .catch(error => {
            document.getElementById('job-response').textContent = 'Error creating job.';
            console.error('Error:', error);
        });
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('affiliates_portal_create_job', 'affiliates_create_job_widget');