document.addEventListener('DOMContentLoaded', function() {
    const widget = document.getElementById('affiliates-portal-widget');
    if (!widget) {
        return;
    }
    const isSelf = widget.getAttribute('data-is-self') === '1';
    const restUrl = affiliatesJobs.restUrl + (isSelf ? '?user_ids=' + encodeURIComponent(affiliatesJobs.currentUserId) : '');

    const jobList = document.getElementById('affiliates-job-list');
    const fragment = document.createDocumentFragment();

    fetch(restUrl, { cache: 'no-store' })
        .then(response => response.json())
        .then(data => {
            data.forEach(function(job) {
                // Use template literals to generate card markup.
                const cardHTML = `
                    <div class="card mb-3">
                        <div class="card-block">
                            <h5 class="card-title">${job.title}</h5>
                            <p class="card-text">By: ${job.author.name}</p>
                            <p class="card-text">${job.content}</p>
                            <p class="card-text"><small class="text-muted">Contact: ${job.contact ? job.contact : 'N/A'}</small></p>
                            ${ isSelf ? `
                                <button class="btn btn-primary edit-button" data-id="${job.id}">Edit</button>
                                <button class="btn btn-danger delete-button" data-id="${job.id}">Delete</button>
                            ` : '' }
                        </div>
                    </div>
                `;
                
                // Create a temporary container to convert string to element.
                const temp = document.createElement('div');
                temp.innerHTML = cardHTML.trim();
                fragment.appendChild(temp.firstElementChild);
            });
            // Append all at once.
            jobList.appendChild(fragment);
        })
        .catch(error => console.error('Error fetching jobs:', error));
});