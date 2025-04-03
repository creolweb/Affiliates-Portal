document.addEventListener('DOMContentLoaded', function() {
    const widget = document.getElementById('affiliates-portal-widget');
    if (!widget) {
        return;
    }
    const isSelf = widget.getAttribute('data-is-self') === '1';

    // Base URL for the job list (note: for self, user_ids is already appended)
    const originalRestUrl = affiliatesJobs.restUrl + (isSelf ? '?user_ids=' + encodeURIComponent(affiliatesJobs.currentUserId) : '');
    const jobList = document.getElementById('affiliates-job-list');

    // Utility function to append a query parameter (handles existing params)
    function buildUrl(url, key, value) {
        return url + (url.indexOf('?') === -1 ? '?' : '&') + key + '=' + encodeURIComponent(value);
    }

    // Function to load and display the list of jobs
    function loadJobList() {
        jobList.innerHTML = '';
        fetch(originalRestUrl, { cache: 'no-store' })
            .then(response => response.json())
            .then(data => {
                const fragment = document.createDocumentFragment();

                data.forEach(function(job) {
                    const maxChars = 300; // Maximum characters allowed for listing
                    const truncatedContent = job.content.length > maxChars ? job.content.substring(0, maxChars) + '...' : job.content;

                    // Note: "More Details" button is always added.
                    const cardHTML = `
                        <div class="card mb-3">
                            <div class="card-block">
                                <h5 class="card-title">${job.title}</h5>
                                <p class="card-text">By: ${job.author.name}</p>
                                <p class="card-text">${truncatedContent}</p>
                                <p class="card-text"><small class="text-muted">Contact: ${job.contact ? job.contact : 'N/A'}</small></p>
                                <button href="#" class="btn btn-secondary more-details-button" data-id="${job.id}">More Details</button>
                                ${ isSelf ? `
                                    <button class="btn btn-primary edit-button" data-id="${job.id}">Edit</button>
                                    <button class="btn btn-danger delete-button" data-id="${job.id}">Delete</button>
                                ` : '' }
                            </div>
                        </div>
                    `;

                    const temp = document.createElement('div');
                    temp.innerHTML = cardHTML.trim();
                    fragment.appendChild(temp.firstElementChild);
                });

                jobList.appendChild(fragment);
            })
            .catch(error => console.error('Error fetching jobs:', error));
    }

    // Function to fetch and display a single job's detailed view
    function fetchJobDetails(jobId) {
        const detailUrl = buildUrl(affiliatesJobs.restUrl, 'id', jobId);
        jobList.innerHTML = '';
        fetch(detailUrl, { cache: 'no-store' })
            .then(response => response.json())
            .then(result => {
                // If the API returns an array, find the job with the matching id.
                let job = Array.isArray(result) ? result.find(j => j.id == jobId) : result;
                if (!job) {
                    jobList.innerHTML = '<p>Job not found.</p>';
                    return;
                }
                const detailHTML = `
                    <div class="card mb-3">
                        <div class="card-block">
                            <h5 class="card-title">${job.title}</h5>
                            <p class="card-text">By: ${job.author.name}</p>
                            <p class="card-text">${job.content}</p>
                            <p class="card-text"><small class="text-muted">Contact: ${job.contact ? job.contact : 'N/A'}</small></p>
                            <button class="btn btn-info back-button">Back</button>
                        </div>
                    </div>
                `;
                jobList.innerHTML = detailHTML;
            })
            .catch(error => console.error('Error fetching job details:', error));
    }

    // Delegate click events for More Details and Back buttons.
    jobList.addEventListener('click', function(event) {
        if (event.target.matches('.more-details-button')) {
            const jobId = event.target.getAttribute('data-id');
            fetchJobDetails(jobId);
        } else if (event.target.matches('.back-button')) {
            loadJobList();
        }
    });

    // Initial load.
    loadJobList();
});