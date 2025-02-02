// Function to delete the user
function deleteUser(userId) {
    // Confirm if the user really wants to delete
    if (!confirm("Are you sure you want to delete this user?")) {
        return; // If user cancels, do nothing
    }

    // Prepare data to send (user ID)
    const data = {
        user_id: userId
    };

    // Make the AJAX request to delete the user
    fetch('/delete-user', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json()) // Parse the response to JSON
        .then(result => {
            if (result.success) {
                // If successful, alert the user and reload the page to update the view
                alert("User deleted successfully.");
                location.reload(); // Reload the page to reflect the change
            } else {
                // If there was an error, show an alert with the error message
                alert("Error: " + result.message);
            }
        })
        .catch(error => {
            // Catch any network or other errors
            alert("Error: " + error.message);
        });
}

// Function that is triggered when the delete button is clicked
function confirmDelete(userId) {
    // Confirm the action
    if (confirm("Are you sure you want to delete this user?")) {
        // If confirmed, call deleteUser function to actually delete the user
        deleteUser(userId);
    }
}
