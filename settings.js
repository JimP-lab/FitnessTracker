function changeProfilePicture() {
    const pictureInput = document.getElementById('pictureInput');
    const profilePicture = document.getElementById('profilePicture');

    if (pictureInput.files && pictureInput.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            profilePicture.src = e.target.result;
        };

        reader.readAsDataURL(pictureInput.files[0]);

        // Add your logic here to handle the added picture, e.g., save it to the server
        // For demonstration, let's show an alert
        alert('Profile picture changed successfully!');
    }
}
function changePassword() {
    // Redirect user to the change password page
    window.location.href = "change.password.html";
}
function contactSupport() {
    // Add logic to redirect to email for support
    window.location.href = "https://mail.google.com/mail/?view=cm&fs=1&to=sfitnesstracker116@gmail.com";
}
function deleteAccount() {
    const confirmation = confirm("Are you sure you want to delete your account?.");
    if (confirmation) {
        // Add your logic here to delete the account
        alert("Your account has been successfully deleted.");
        // Redirect the user to the home page
        window.location.href = "fit.html"; // Change 'home.html' to the actual URL of your home page
    } else {
        alert("Account deletion canceled.");
    }
}
function logout() {
    const confirmLogout = confirm('Do you want to log out?');

    if (confirmLogout) {
        // Redirect to the login page if needed
        window.location.href = 'fit.html'; 
        // Clear local storage data if needed
        localStorage.removeItem('userData');
    } else {
        // User clicked "No", do nothing or perform additional actions as needed
    }
}