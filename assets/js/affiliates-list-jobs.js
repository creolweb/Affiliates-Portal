document.addEventListener('DOMContentLoaded', function() {
    const widget = document.getElementById('affiliates-portal-widget');
    if (!widget) {
        return;
    }
    const isSelf = widget.getAttribute('data-is-self') === '1';
    const restUrl = affiliatesJobs.restUrl + (isSelf ? '?user_ids=' + encodeURIComponent( affiliatesJobs.currentUserId ) : '');

    fetch(restUrl, { cache: 'no-store' })
        .then(response => response.json())
        .then(data => {
            const jobList = document.getElementById('affiliates-job-list');
            data.forEach(function(job) {
                const li = document.createElement('li');
                li.textContent = job.title + ' by ' + job.author;
                if (isSelf) {
                    const editBtn = document.createElement('button');
                    editBtn.textContent = 'Edit';
                    editBtn.classList.add('edit-button');
                    editBtn.addEventListener('click', function() {
                        console.log('Edit job', job.id);
                    });
                    
                    const deleteBtn = document.createElement('button');
                    deleteBtn.textContent = 'Delete';
                    deleteBtn.classList.add('delete-button');
                    deleteBtn.addEventListener('click', function() {
                        console.log('Delete job', job.id);
                    });
                    
                    li.appendChild(document.createTextNode(' '));
                    li.appendChild(editBtn);
                    li.appendChild(document.createTextNode(' '));
                    li.appendChild(deleteBtn);
                }
                jobList.appendChild(li);
            });
        })
        .catch(error => console.error('Error fetching jobs:', error));
});