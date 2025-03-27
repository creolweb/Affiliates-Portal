document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('affiliates-create-job-form');
    if (!form) return;
    
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            title: formData.get('title'),
            job_description: formData.get('description'),
            contact: formData.get('contact')
        };

        fetch(affiliatesCreateJob.restUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': affiliatesCreateJob.nonce
            },
            credentials: 'include', // Ensure cookies are sent with the request
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
});