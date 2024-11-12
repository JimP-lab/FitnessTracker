function getQueryParams() {
    let params = {};
    window.location.search.replace(/^\?/, '').split('&').forEach(param => {
        let parts = param.split('=');
        params[parts[0]] = parts[1];
    });
    return params;
}
// Function to display confirmation message
function displayConfirmationMessage() {
    const params = getQueryParams();
    if (params.registered === 'success') {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'confirmation-container';

        const loadingCircle = document.createElement('div');
        loadingCircle.className = 'loading-circle';

        const messageText = document.createElement('p');
        messageText.innerText = 'Loading...';

        messageDiv.appendChild(loadingCircle);
        messageDiv.appendChild(messageText);

        document.body.prepend(messageDiv);

        setTimeout(() => {
            loadingCircle.remove();
            const checkMark = document.createElement('i');
            checkMark.className = 'fas fa-check-circle fa-3x';
            messageText.innerText = 'You Are Registered!';
            messageDiv.insertBefore(checkMark, messageText);

            // Make the loading bar disappear
            setTimeout(() => {
                messageDiv.remove();
            }, 1000); // Remove the confirmation message after 1 seconds

        }, 2000); // Simulate loading for 2 seconds
    }
}

document.addEventListener('DOMContentLoaded', displayConfirmationMessage);