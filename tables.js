function showForm(formId) {
    // Hide all forms
    document.querySelectorAll('form').forEach(form => form.classList.add('hidden'));
    // Show the selected form
    document.getElementById(formId).classList.remove('hidden');
}

function addItemToList(category, data) {
    const list = document.getElementById(category + 'List');
    const listItem = document.createElement('li');
    listItem.textContent = data;
    list.appendChild(listItem);
}
function addMeals() {
    const mealsValue = document.getElementById('mealsValue').value.trim();

    // Check if mealsValue is not empty before proceeding
    if (mealsValue === "") {
        alert('enter a value.');
        return;
    }

    // Check if the meal already exists in the list
    const mealsList = document.getElementById('mealsList');
    if (mealsList.innerHTML.includes(`Meals: ${mealsValue}`)) {
        alert('add a different meal.');
        return;
    }

    // Check if the meal contains only text
    const regex = /^[a-zA-Z\s]+$/;
    if (!regex.test(mealsValue)) {
        alert('enter only text.');
        return;
    }

    addItemToList('meals', `Meals: ${mealsValue}`);
    
    // Save data to local storage
    const userData = getUserData();
    userData.meals = mealsValue;
    saveUserData(userData);

    // Clear the form
    document.getElementById('mealsForm').reset();
}
function addExercise() {
    const exerciseName = document.getElementById('exerciseName').value.trim();
    
    // Check if exerciseName is empty or contains numbers or icons
    if (exerciseName === "" || /[0-9!@#$%^&*()_+={}|[\]\\';:"<>?]/.test(exerciseName)) {
        alert('enter a valid exercise.');
        return;
    }

    // Check if the exercise name is already added
    const exerciseList = document.getElementById('exerciseList').innerText;
    if (exerciseList.includes(`Exercise: ${exerciseName}`)) {
        alert('add a different exercise.');
        return;
    }
    
    // Add exercise to the list
    addItemToList('exercise', `Exercise: ${exerciseName}`);
    
    // Save data to local storage
    const userData = getUserData();
    userData.exercise = exerciseName;
    saveUserData(userData);

    // Clear the form
    document.getElementById('exerciseForm').reset();
}
function addReps() {
    const repsInput = document.getElementById('reps');
    const reps = repsInput.value.trim();
    
    // Check if reps is not empty before proceeding
    if (reps === "") {
        alert('Please enter a value for Reps.');
        return;
    }

    // Check if the input is a valid number
    if (isNaN(reps)) {
        alert('Please enter a valid number for Reps.');
        return;
    }

    // Check if reps is within the range of 1 to 1000
    const numericReps = parseInt(reps, 10);
    if (numericReps < 1 || numericReps > 1000) {
        alert('Reps must be a number between 1 and 1000.');
        return;
    }

    // Retrieve previously entered reps
    const previousReps = JSON.parse(localStorage.getItem('previousReps')) || [];

    // Check if reps is in the list of previous reps
    if (previousReps.includes(reps)) {
        alert('Please enter a different Reps value.');
        return;
    }

    // Add the current reps to the list of previous reps
    previousReps.push(reps);
    localStorage.setItem('previousReps', JSON.stringify(previousReps));

    addItemToList('exercise', `Reps: ${reps}`);
    
    // Save data to local storage
    const userData = getUserData();
    userData.reps = reps;
    saveUserData(userData);

    // Clear the form
    document.getElementById('repsForm').reset();
}

// Define a set to store entered times
const enteredTimes = new Set();

function addTime() {
    const time = document.getElementById('time').value;

    // Check if time is not empty before proceeding
    if (time.trim() === "") {
        alert('Please enter a value for Time.');
        return;
    }

    // Check if the time is already entered
    if (enteredTimes.has(time)) {
        alert('Please enter a different time.');
        return;
    }

    // Check if the time is within the range of 1 to 1000
    const numericTime = parseInt(time, 10);
    if (isNaN(numericTime) || numericTime < 1 || numericTime > 1000) {
        alert('Time must be a number between 1 and 1000.');
        return;
    }

    // Add the time to the set of entered times
    enteredTimes.add(time);

    addItemToList('exercise', `Time: ${time}`);
    
    // Save data to local storage
    const userData = getUserData();
    userData.time = time;
    saveUserData(userData);

    // Clear the form
    document.getElementById('timeForm').reset();
}
function addSleep() {
    const sleepDuration = document.getElementById('sleepDuration').value;

    // Check if sleepDuration is not empty before proceeding
    if (sleepDuration.trim() === "") {
        alert('Please enter a value for Sleep Duration.');
        return;
    }

    // Check if the input contains only numbers
    if (!isValidInput(sleepDuration)) {
        alert('Please enter a valid number for Sleep Duration.');
        return;
    }

    // Check if sleepDuration is within the range of 1 to 1000
    const duration = parseInt(sleepDuration, 10);
    if (duration < 1 || duration > 1000) {
        alert('Sleep Duration must be a number between 1 and 1000.');
        return;
    }

    // Check if sleepDuration is not the same as the previously added sleep duration
    const previousSleepDuration = localStorage.getItem('previousSleepDuration');
    if (previousSleepDuration === sleepDuration) {
        alert('Please add a different sleep time.');
        return;
    }

    addItemToList('health', `Sleep: ${sleepDuration} hours`);

    // Save data to local storage
    const userData = getUserData();
    userData.sleep = sleepDuration;
    saveUserData(userData);

    // Save current sleep duration as previous for future comparison
    localStorage.setItem('previousSleepDuration', sleepDuration);

    // Clear the form
    document.getElementById('healthForm').reset();
}

// Function to check if the input contains only numbers
function isValidInput(input) {
    return /^\d+$/.test(input);
}
document.addEventListener('DOMContentLoaded', function () {
    const userData = getUserData();
    if (userData.weight) {
        addItemToList('health', `Weight: ${userData.weight} lbs`);
    }
    if (userData.height) {
        addItemToList('health', `Height: ${userData.height} inches`);
    }
    if (userData.option) {
        addItemToList('meal', `Option: ${userData.option}`);
    }
    if (userData.sleep) {
        addItemToList('health', `Sleep: ${userData.sleep} hours`);
    }
    if (userData.exercise) {
        addItemToList('exercise', `Exercise: ${userData.exercise}`);
    }
    if (userData.reps) {
        addItemToList('exercise', `Reps: ${userData.reps}`);
    }
    if (userData.time) {
        addItemToList('exercise', `Time: ${userData.time}`);
    }

    const metricTypeInput = document.getElementById('metricType');
    const metricValueInput = document.getElementById('metricValue');
    const addMetricBtn = document.getElementById('addMetricBtn');
    const metricList = document.getElementById('metricList');

    addMetricBtn.addEventListener('click', function () {
        const metricTypeValue = metricTypeInput.value;
        const metricValue = metricValueInput.value;

        if (metricTypeValue && metricValue) {
            const listItem = document.createElement('li');
            listItem.textContent = `${metricTypeValue}: ${metricValue}`;
            metricList.appendChild(listItem);

            metricTypeInput.value = 'calories'; // Reset metric type to 'Calories' after adding a metric
            metricValueInput.value = ''; // Clear metric value input field
        } else {
            alert('Please select a metric type and enter a value.');
        }
    });
});