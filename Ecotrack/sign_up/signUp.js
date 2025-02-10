$(document).ready(function() {
    $('#signup-form').on('submit', function(e) {
        e.preventDefault();
        
        // Collect form data
        var username = $('#userName').val();
        var email = $('#email').val();
        var password = $('#password').val();
        
        // Basic client-side validation
        if (!username || !email || !password) {
            alert('Please fill in all fields');
            return;
        }
        
        // AJAX call to register
        $.ajax({
            url: 'register.php',
            method: 'POST',
            data: {
                username: username,
                email: email,
                password: password
            },
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                window.location.replace("login.html");
            },
            error: function(xhr) {
                // Handle errors
                var errorMessage = 'Registration failed';
                
                // Try to parse error message from server
                try {
                    errorMessage = JSON.parse(xhr.responseText).error;
                } catch(e) {
                    // If parsing fails, use default message
                    errorMessage = xhr.responseText || 'Registration failed';
                }
                
                // Show error to user
                alert('Error: ' + errorMessage);
            }
        });
    });
});