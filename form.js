
function validateForm() {
    var firstName = document.getElementById('first-name').value;
    var lastName = document.getElementById('last-name').value;
    var email = document.getElementById('email').value;
    var message = document.getElementById('message').value;

    if (firstName === '' || lastName === '' || email === '' || message === '') {
      alert('Please fill out all fields before submitting the form.');
      return false;
    }

    return true;
  }

  document.getElementById('submit-btn').addEventListener('click', function(event) {
    if (!validateForm()) {
      event.preventDefault();
    }
  });

