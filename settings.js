function changePassword() {
    // Redirect user to the index1 file 
    window.location.href = "index1.html";
}
function contactSupport() {
    // Add logic to redirect to index8 file for support 
    window.location.href = "index8.html";
}
function deleteAccount() {
    const confirmation = confirm('Do you want to delete your account?');
    if (confirmation) {
        // Add your logic here to delete the account
        alert('Your account has been deleted.');
        // Redirect the user to the home page
        window.location.href = "index.html"; // Change 'home.html' to the actual URL of your home page
    } else {
        alert("Account deletion canceled.");
    }
}
function logout() {
    const confirmLogout = confirm('Do you want to log out?');

    if (confirmLogout) {
        // Redirect to the login page if needed
        window.location.href = 'index.html'; 
        // Clear local storage data if needed
        localStorage.removeItem('userData');
    } else {
        // User clicked "No", do nothing or perform additional actions as needed
    }
}
