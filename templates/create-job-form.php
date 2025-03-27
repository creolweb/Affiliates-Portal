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
<?php