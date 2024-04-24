function Display() {
    document.getElementById("hiddenElem").style.display = "block";
}

function DisplayLoginForm() {
    document.getElementById("loginForm").style.display = "block";
}

function showForm(formId) {
    // Hide all forms
    document.querySelectorAll('form').forEach(form => form.classList.add('hidden'));
    // Show the selected form
    document.getElementById(formId).classList.remove('hidden');
}