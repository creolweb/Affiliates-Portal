document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('affiliates-login-form');
    if (!loginForm) return;
    
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(loginForm);
        
        fetch(affiliatesLogin.ajaxUrl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            const errorContainer = document.getElementById('login-error');
            errorContainer.innerHTML = '';
            
            if (result.success) {
                // Redirect on successful login.
                window.location.href = result.data.redirect_url;
            } else {
                // Display the error message in a Bootstrap alert.
                errorContainer.innerHTML = `<div class="alert alert-danger" role="alert">${result.data}</div>`;
                console.error(result.data);
            }
        })
        .catch(error => {
            console.error('AJAX login error:', error);
        });
    });
});