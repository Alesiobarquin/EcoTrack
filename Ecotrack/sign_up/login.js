$(document).ready(function() {
    $('#login-form').on('submit', function(e) {
        e.preventDefault();
        
        // Collect form data
        var email = $('#email').val();
        var password = $('#password').val();
        
        // Basic client-side validation
        if (!email || !password) {
            alert('Please enter both email and password');
            return;
        }
        
        // AJAX call to login
        $.ajax({
            url: 'login.php',
            method: 'POST',
            data: {
                email: email,
                password: password
            },
            dataType: 'json',
            success: function(response) {
                // Show success message
                alert(response.message);
                
                // Redirect to dashboard or main page
                // You might want to customize this based on your app structure
                window.location.href = "../profile/profile.php";
            },
            error: function(xhr) {
                // Handle errors
                var errorMessage = 'Login failed';
                
                // Try to parse error message from server
                try {
                    errorMessage = JSON.parse(xhr.responseText).error;
                } catch(e) {
                    // If parsing fails, use default message
                    errorMessage = xhr.responseText || 'Login failed';
                }
                
                // Show error to user
                alert('Error: ' + errorMessage);
            }
        });
    });
});